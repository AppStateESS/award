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
use award\Factory\ParticipantHashFactory;
use award\View\EmailView;
use phpws2\Database;

class ParticipantFactory extends AbstractFactory
{

    static Participant $currentParticipant;
    protected static string $table = 'award_participant';
    protected static string $resourceClassName = 'award\Resource\Participant';

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
        $checkHash = ParticipantHashFactory::get($participant->id);
        if ($participant && $checkHash === $hash) {
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
            ->hashPassword($password);

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
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable('award_participant'));

        $table->addFieldConditional('email', filter_var($email, FILTER_SANITIZE_EMAIL));
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
        extract(self::getDBWithTable());
        $table->addField('id');
        $table->addField('active');
        $table->addField('authType');
        $table->addField('banned');
        $table->addField('created');
        $table->addField('email');
        $table->addField('firstName');
        $table->addField('lastName');
        $table->addField('updated');
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
     * Updates the participant password.
     * @param int $participantId
     * @param string $password
     */
    public static function resetPassword(int $participantId, string $password)
    {
        $participant = self::build($participantId);
        if ($participant) {
            $participant->hashPassword($password);
            self::save($participant);
        }
    }

    /**
     * Creates an update hash and sends an email allowing users to
     * change their password.
     * If the account is not found or their account is not yet active,
     * it does nothing.
     *
     * @param string $email
     */
    public static function sendForgotEmail(string $email)
    {
        $participant = self::getByEmail($email);
        if ($participant === false) {
            return;
        }
        $hash = ParticipantHashFactory::create($participant->id);
        // if the participant is not active, allow them to activate their account
        if (!$participant->active) {
            EmailFactory::sendActivationReminder($participant, $hash);
        } else {
            EmailFactory::sendForgotPassword($participant, $hash);
        }
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
     * To clear any authentication source, AuthenticateFactory::signOut must
     * also be called.
     */
    public static function signOut()
    {
        unset($_SESSION['AWARD_PARTICIPANT']);
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

}
