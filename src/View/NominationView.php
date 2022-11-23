<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\View;

use award\AbstractClass\AbstractView;
use award\Factory\NominationFactory;
use award\Factory\AwardFactory;
use award\Factory\CycleFactory;
use award\Factory\CycleLogFactory;
use award\Factory\SettingFactory;
use award\Factory\ParticipantFactory;
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Nomination;
use award\Resource\Participant;

class NominationView extends AbstractView
{

    public static function adminView(Nomination $nomination)
    {
        $template = NominationFactory::getAssociated($nomination);
        extract($template);
        $template['nomination'] = $nomination;
        $template['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        if ($award->getReferencesRequired()) {
            $template['referenceSummary'] = self::scriptView('ReferenceSummary', ['nominationId' => $nomination->id]);
        } else {
            $template['referenceSummary'] = false;
        }

        return self::getTemplate('Admin/NominationView', $template);
    }

    public static function cannotNominateParticipant()
    {
        return self::getTemplate('Error/CannotNominateParticipant');
    }

    public static function deadlinePassed($award, $cycle, $nomination)
    {
        $awardTitle = self::getFullAwardTitle($award, $cycle);
        return self::getTemplate('Error/CycleDeadline', ['awardTitle' => $awardTitle, 'award' => $award, 'cycle' => $cycle, 'nomination' => $nomination]);
    }

    /**
     * Returns an error page view determined by the exception type.
     * @param \Exception $exception
     */
    public static function errorByException(\Exception $exception, $award, $cycle, $nomination)
    {
        $awardTitle = CycleView::getFullAwardTitle($award, $cycle);
        switch (get_class($exception)) {
            case 'award\Exception\CycleComplete':
                $title = 'Cycle complete';
                $content = CycleView::complete($awardTitle);
                break;

            case 'award\Exception\CycleEndDatePassed':
                $title = 'Cycle deadline has passed';
                $content = NominationView::deadlinePassed($award, $cycle, $nomination);
                break;

            case 'award\Exception\CannotNominateJudge':
                $title = 'Nomination not allowed';
                $content = NominationView::noJudges($awardTitle);
                break;

            case 'award\Exception\ParticipantPrivilegeMissing':
                $title = 'Permission denied';
                $content = NominationView::notPermitted($awardTitle);
                break;

            case 'award\Exception\NotTrusted':
                $title = 'Nomination not allowed';
                $content = NominationView::onlyTrusted();
                break;

            case 'award\Exception\ParticipantAlreadyNominated':
            case 'award\Exception\InactiveParticipant':
            case 'award\Exception\BannedParticipant':
                $title = 'Cannot nominate this participant';
                $content = NominationView::cannotNominateParticipant();

                break;

            default:
                $title = 'Unknown error';
                $content = 'Error: ' . $exception->getMessage();
                break;
        }

        return self::centerCard($title, $content, 'danger');
    }

    /**
     * Returns the admin script for nominations that require approval.
     */
    public static function needsApproval()
    {
        return self::scriptView('NominationApproval');
    }

    /**
     * Error screen for judges trying to nominate.
     * @return string
     */
    public static function noJudges(string $awardTitle)
    {
        return self::getTemplate('Participant/NoJudges', ['awardTitle' => $awardTitle]);
    }

    /**
     * View that allows the participant choose who they want to nominate.
     * @param Award $award
     * @param Cycle $cycle
     * @return string
     */
    public static function nominate(Award $award, Cycle $cycle)
    {
        return self::scriptView('Nominate', ['cycle' => $cycle->getValues(), 'award' => $award->getParticipantValues()]);
    }

    /**
     * Error page for mismatched nominator id
     * @param string $awardTitle
     */
    public static function notPermitted(string $awardTitle)
    {
        return self::getTemplate('Error/NominatorNotPermitted');
    }

    /**
     * Gives participants a message that only certain accounts can nominate.
     */
    public static function onlyTrusted()
    {
        return self::getTemplate('Participant/OnlyTrusted');
    }

    /**
     * A full view of all the nominations submitted by the participant. Unlike the
     * dashboard view which only contains nomination needing attention.
     * @return string
     */
    public static function participantView()
    {
        $participant = ParticipantFactory::getCurrentParticipant();
        $tpl['now'] = time();
        $tpl['nominations'] = NominationFactory::listing(['nominatorId' => $participant->id, 'includeNominated' => true, 'includeAward' => true, 'includeCycle' => true]);
        return self::getTemplate('Participant/NominationList', $tpl);
    }

    public static function reasonForm(Award $award, Cycle $cycle, Nomination $nomination, Participant $nominee)
    {
        $maxsize = \award\Factory\DocumentFactory::maximumUploadSize();

        $js = [
            'maxsize' => $maxsize,
            'award' => $award->getParticipantValues(),
            'cycle' => $cycle->getParticipantValues(),
            'nomination' => $nomination->getValues(),
            'participant' => $nominee->getParticipantValues()
        ];
        return self::scriptView('NominationReason', $js);
    }

    public static function selectReferences(Nomination $nomination, Participant $nominated, Award $award, Cycle $cycle)
    {
        $values['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $values['nominated'] = $nominated;
        $values['deadline'] = $cycle->formatEndDate();

        $referencesRequired = $award->getReferencesRequired();
        if ($referencesRequired === 0) {
            return ReferenceView::referencesNotRequired();
        }
        $values['referencesRequired'] = $referencesRequired;
        $values['chooseReference'] = self::chooseReferenceScript($award, $cycle, $nomination->id, $referencesRequired);

        return self::getTemplate('Participant/SelectReferences', $values);
    }

    /**
     * An admin menu of a cycle's current nominations.
     * @param int $cycleId
     */
    public static function summaryByCycle(int $cycleId)
    {
        $nominationList = NominationFactory::listing(['cycleId' => $cycleId,
                'includeNominated' => true, 'includeNominator' => true]);
        $values['nominations'] = $nominationList;
        return self::getTemplate('Admin/NominationSummary', $values);
    }

    /**
     * Determines the proper view for the nomination process based on its current status.
     * @param Participant $nominator The person nominating someone for an award
     * @param Nomination $nomination The current nomination object.
     * @return string
     */
    public static function view(Nomination $nomination)
    {
        $nominator = ParticipantFactory::build($nomination->nominatedId);
        $award = AwardFactory::build($nomination->awardId);
        $cycle = CycleFactory::build($nomination->cycleId);
        $tpl['approvalRequired'] = (bool) $award->approvalRequired;
        $tpl['reasonRequired'] = (bool) $award->nominationReasonRequired;
        $tpl['referencesRequired'] = $award->referencesRequired;

        $tpl['nominationId'] = $nomination->id;
        $tpl['reasonComplete'] = (bool) $nomination->reasonComplete;
        $tpl['referencesSelected'] = (bool) $nomination->referencesSelected;
        $tpl['nominationComplete'] = (bool) $nomination->completed;
        $tpl['referencesComplete'] = (bool) $nomination->referencesComplete;

        $tpl['firstName'] = $nominator->firstName;
        $tpl['lastName'] = $nominator->lastName;
        $tpl['awardTitle'] = self::getFullAwardTitle($award, $cycle);

        $tpl['canComplete'] = NominationFactory::canComplete($nomination);

        return self::getTemplate('Participant/NominationStatus', $tpl);
    }

    private static function chooseReferenceScript(Award $award, Cycle $cycle, int $nominationId, int $referencesRequired)
    {
        $js['cycleId'] = $cycle->id;
        $js['nominationId'] = $nominationId;
        $js['reminderGrace'] = AWARD_REFERENCE_REMINDER_GRACE;
        $js['referencesRequired'] = $referencesRequired;
        $js['referenceReasonRequired'] = $award->getReferenceReasonRequired();
        return self::scriptView('ChooseReference', $js);
    }

}
