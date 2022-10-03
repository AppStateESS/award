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
use award\Resource\Participant;

class NominationView extends AbstractView
{

    public static function deadlinePassed(Award $award, Cycle $cycle)
    {
        return 'The deadline for this cycle has passed. No more nominations are accepted';
    }

    /**
     * Error screen for judges trying to nominate.
     * @return string
     */
    public static function noJudges($award, $cycle)
    {
        $title = self::getFullAwardTitle($award, $cycle);
        return self::participantMenu('nomination') . self::centerCard("Nomination not allowed", self::getTemplate('Participant/NoJudges', ['awardTitle' => $title]), 'danger');
    }

    public static function nominate(Award $award, Cycle $cycle)
    {
        $menu = self::participantMenu('nomination');
        $content = self::scriptView('Nominate', ['cycle' => $cycle->getValues(), 'award' => $award->getParticipantValues()]);
        return $menu . $content;
    }

    public static function nominateParticipant(Participant $participant, Award $award, Cycle $cycle)
    {
        $menu = self::participantMenu('nomination');
        $maxsize = \award\Factory\DocumentFactory::maximumUploadSize();
        $content = self::scriptView('NominationCompletion', ['maxsize' => $maxsize, 'award' => $award->getParticipantValues(), 'cycle' => $cycle->getParticipantValues(), 'participant' => $participant->getParticipantValues()]);
        return $menu . $content;
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

    /**
     * Gives participants a message that only certain accounts can nominate.
     */
    public static function onlyTrusted()
    {
        return self::getTemplate('Participant/OnlyTrusted');
    }

}
