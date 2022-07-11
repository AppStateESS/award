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
use award\AbstractClass\AbstractFactory;
use phpws2\Database;

class ParticipantFactory extends AbstractFactory
{

    static Participant $currentParticipant;

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
                return ['success' => false, 'message' => 'Could not sign in this account with current email and password'];
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
    public static function authorize(string $email, string $hash): bool
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
     * Creates a new internal participant using email and password parameter.
     * This participant does not use use SSO to sign in.
     * Password is hashed by setPassword.
     *
     * @param string $email
     * @param string $password
     * @return Participant
     */
    public static function createInternal(string $email, string $password): Participant
    {
        $participant = new Participant;
        $participant->setActive(false)
            ->setEmail($email)
            ->setPassword($password)
            ->createHash();

        self::save($participant);
        return $participant;
    }

    public static function createSSO(string $email)
    {

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

    /**
     * Returns an the token session made on a new account request.
     * FALSE is returned if the session was not set.
     * @return string | bool
     */
    public static function getCreateToken()
    {
        return $_SESSION['Award_Create_Token'] ?? false;
    }

    /**
     * Returns Participant array if
     * @return boolean | array
     */
    public static function getCurrentParticipant()
    {
        if (!self::isSignedIn()) {
            return false;
        } else {
            if (!isset(self::$currentParticipant)) {
                self::loadCurrentParticipant();
            }
            return self::$currentParticipant;
        }
    }

    /**
     * Returns status of signed in participant.
     * @return bool
     */
    public static function isSignedIn()
    {
        return isset($_SESSION['AWARD_PARTICIPANT']);
    }

    public static function listing(array $options = [])
    {
        $db = parent::getDB();
        $tbl = $db->addTable('award_participant');
        return $db->select();
    }

    /**
     * Creates and returns a random sessioned token. This token
     * is used to authenticate a new participant creation from an
     * SSO user.
     * @return string
     */
    public static function loadCreateToken()
    {
        $token = md5(time() . rand());
        $_SESSION['Award_Create_Token'] = $token;
        return $token;
    }

    /**
     * Loads a participant object into the static based on the AWARD_PARTICIPANT
     * session.
     */
    private static function loadCurrentParticipant()
    {
        self::$currentParticipant = new Participant;
        self::$currentParticipant->setValues($_SESSION['AWARD_PARTICIPANT'], ['password']);
    }

    /**
     * Puts participant values into session.
     * @param Participant $participant
     */
    public static function signIn(Participant $participant)
    {
        $_SESSION['AWARD_PARTICIPANT'] = $participant->getValues(['password']);
    }

    /**
     * Clears participant session.
     */
    public static function signOff()
    {
        unset($_SESSION['AWARD_PARTICIPANT']);
    }

}
