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
     * Attempts to authenticate participant using email and password params. Will
     * fail if account is not active.
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function authenticate(string $email, string $password)
    {
        $participant = self::getByEmail($email);
        if ($participant === false) {
            return ['success' => false, 'message' => 'Could not sign in this account with current email and password'];
        } else {
            if (!$participant->getActive()) {
                return ['success' => false, 'message' => 'Your account has not been activated. Check your email.'];
            } elseif ($participant->isPassword($password)) {
                self::signIn($participant);
                return ['success' => true];
            } else {
                return ['success' => false, 'Could not sign in this account with current email and password'];
            }
        }
    }

    /**
     * Flips the participant account to active if their hash and email are correct.
     *
     * @param string $email
     * @param string $hash
     * @return boolean
     */
    public static function authorize(string $email, string $hash)
    {
        $participant = self::getByEmail($email);
        if ($participant && $participant->getHash() === $hash) {
            $participant->setActive(true);
            self::save($participant);
            return true;
        } else {
            return false;
        }
    }

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
        $participant->setActive(false)
            ->setEmail($email)
            ->setPassword($password)
            ->createHash();

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
        $db = self::getDB();
        $tbl = $db->addTable('award_participant');
        $tbl->addFieldConditional('email', filter_var($email, FILTER_SANITIZE_EMAIL));
        $result = $db->selectOneRow();

        if (!$result) {
            return false;
        } else {
            $participant = new Participant;
            $participant->setValues($result);
            return $participant;
        }
    }

    public static function isSignedIn()
    {
        return isset($_SESSION['AWARD_PARTICIPANT']);
    }

    public static function signIn(Participant $participant)
    {
        $_SESSION['AWARD_PARTICIPANT'] = $participant->getValues(['password']);
    }

    public static function signOff()
    {
        unset($_SESSION['AWARD_PARTICIPANT']);
    }

}
