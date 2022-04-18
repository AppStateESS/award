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

namespace award\Factory;

use award\Resource\Participant;
use phpws2\Database;

class ParticipantFactory extends \award\AbstractFactory
{

    /**
     * Creates a new participant using email and password parameter.
     * Password is hashed by setPassword.
     *
     * @param string $email
     * @param string $password
     * @return Participant
     */
    public static function create(string $email, string $password)
    {
        $participant = new Participant;
        $participant->setEmail($email)->setPassword($password)->createHash();
        self::save($participant);
        return $participant;
    }

    /**
     * Returns a Participant object if exists, FALSE bool otherwise.
     *
     * @param string $email
     * @return boolean|Participant
     */
    public static function getByEmail(string $email)
    {
        $db = Database::getDB();
        $tbl = $db->addTable('award_participant');
        $tbl->addFieldConditional('email', $email);
        $result = $db->selectOneRow();

        if (!$result) {
            return false;
        } else {
            $participant = new Participant;
            $result['id'] = (int) $result['id'];
            $participant->setValues($result);
            return $participant;
        }
    }

    public static function isSignedIn()
    {
        return isset($_SESSION['Award_Participant']);
    }

}
