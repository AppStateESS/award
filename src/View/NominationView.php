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
use award\Resource\Cycle;
use award\Resource\Award;
use award\Resource\Nomination;
use award\Resource\Participant;

class NominationView extends AbstractView
{

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
     * #TODO
     * @return type
     */
    public static function participantView()
    {
        $menu = self::participantMenu('nominations');
        return $menu . '<p>List of all people participant has nominated.</p>';
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
        $values['chooseReference'] = self::chooseReferenceScript($cycle, $nomination->id, $referencesRequired);

        return self::getTemplate('Participant/SelectReferences', $values);
    }

    /**
     * Gives participants a message that only certain accounts can nominate.
     */
    public static function onlyTrusted()
    {
        return self::getTemplate('Participant/OnlyTrusted');
    }

    /**
     * Determines the proper view for the nomination process based on its current status.
     * @param Participant $nominator The person nominating someone for an award
     * @param Nomination $nomination The current nomination object.
     * @return string
     */
    public static function view(Participant $nominator, Nomination $nomination)
    {
        $participant = ParticipantFactory::build($nomination->nominatedId);
        $award = AwardFactory::build($nomination->awardId);
        $cycle = CycleFactory::build($nomination->cycleId);
        $tpl['approvalRequired'] = (bool) $award->approvalRequired;
        $tpl['reasonRequired'] = (bool) $award->nominationReasonRequired;
        $tpl['reasonComplete'] = (bool) $nomination->reasonComplete;
        $tpl['referencesRequired'] = $award->referencesRequired;
        $tpl['referencesSelected'] = (bool) $nomination->referencesSelected;
        $tpl['nominationComplete'] = (bool) $nomination->completed;
        $tpl['firstName'] = $participant->firstName;
        $tpl['lastName'] = $participant->lastName;
        $tpl['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $tpl['nominationId'] = $nomination->id;

        $tpl['canComplete'] = NominationFactory::canComplete($award, $nomination);

        return self::getTemplate('Participant/NominationStatus', $tpl);
    }

    private static function chooseReferenceScript(Cycle $cycle, int $nominationId, int $referencesRequired)
    {

        $lastReminder = CycleLogFactory::getLastReferenceRemind($cycle->id);
        $now = time();
        $sendReason = null;
        if ($lastReminder === false) {
            $canSend = true;
        } else {
            $stamped = strtotime($lastReminder['stamped']);

            if ($stamped + (AWARD_JUDGE_REMINDER_GRACE * 86400) < $now) {
                $canSend = true;
            } else {
                $canSend = false;
                $sendReason = 'too_soon';
            }
        }

        $js['canSend'] = $canSend;
        $js['sendReason'] = $sendReason;
        $js['lastSent'] = $lastReminder === false ? 'Never' : $lastReminder['stamped'];
        $js['cycleId'] = $cycle->id;
        $js['nominationId'] = $nominationId;
        $js['referencesRequired'] = $referencesRequired;
        return self::scriptView('ChooseReference', $js);
    }

}
