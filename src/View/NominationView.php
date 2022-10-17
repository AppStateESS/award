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
use award\Factory\CycleFactory;
use award\Factory\AwardFactory;
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

    public static function deadlinePassed(string $awardTitle)
    {
        return self::getTemplate('Error/CycleDeadline', ['awardTitle' => $awardTitle]);
    }

    /**
     * Returns an error page view determined by the exception type.
     * @param \Exception $exception
     */
    public static function errorByException(\Exception $exception, $award, $cycle)
    {
        $awardTitle = CycleView::getFullAwardTitle($award, $cycle);
        switch (get_class($exception)) {
            case 'award\Exception\CycleComplete':
                $title = 'Cycle complete';
                $content = CycleView::complete($awardTitle);
                break;

            case 'award\Exception\CycleEndDatePassed':
                $title = 'Cycle deadline has passed';
                $content = NominationView::deadlinePassed($awardTitle);
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
        $participant = ParticipantFactory::build($nomination->participantId);
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

}
