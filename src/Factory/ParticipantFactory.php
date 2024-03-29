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

use award\AbstractClass\AbstractFactory;
use award\Exception\BannedParticipant;
use award\Exception\InactiveParticipant;
use award\Exception\JudgeMayNotNominate;
use award\Exception\ParticipantAlreadyNominated;
use award\Exception\NotTrusted;
use award\Factory\ParticipantHashFactory;
use award\Factory\JudgeFactory;
use award\Factory\CycleFactory;
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Document;
use award\Resource\Participant;
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
     * Flips the participant account to active and sets their trusted status.
     *
     * @param Participant
     * @return void
     */
    public static function authorize(Participant $participant)
    {
        $participant->setActive(true);
        $participant->setTrusted(SettingFactory::getTrustedDefault());
        self::save($participant);
    }

    public static function canNominate(Participant $nominator, Participant $participant, $cycleId)
    {
        if (!$nominator->getTrusted()) {
            throw new NotTrusted;
        }

        if (JudgeFactory::isJudge($nominator->id, $cycleId)) {
            throw new JudgeMayNotNominate;
        }

        self::isViable($participant);

        $nomination = NominationFactory::getByParticipant($participant->id, $cycleId);
        if ($nomination->nominatorId !== $nominator->id) {
            throw new ParticipantAlreadyNominated;
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
    public static function createInternal(string $email, string $password, string $firstName, string $lastName): Participant
    {
        $participant = new Participant;
        $participant->setActive(false)->setEmail($email)->setFirstName($firstName)
            ->setLastName($lastName)->hashPassword($password);
        $participant->setTrusted(SettingFactory::getTrustedDefault());
        self::save($participant);
        return $participant;
    }

    public static function createSSO(string $email)
    {

    }

    /**
     * Checks if current logged participant is a cycle judge.
     * @param int $cycleId
     * @return bool
     */
    public static function currentIsJudge(int $cycleId)
    {
        return JudgeFactory::isJudge(self::getCurrentParticipant()->id, $cycleId);
    }

    public static function currentIsTrusted()
    {
        return self::getCurrentParticipant()->getTrusted();
    }

    /**
     * Returns TRUE is the associated reference is the current participant.
     * @param int $referenceId
     * @return boolean
     */
    public static function currentIsReference(int $referenceId)
    {
        return ReferenceFactory::build($referenceId)->getParticipantId() === self::getCurrentParticipant()->getId();
    }

    /**
     * Returns TRUE is the associated document resource is owned by the current participant.
     * @param award\Resource\Document $document
     * @return boolean
     */
    public static function currentOwnsDocument(Document $document)
    {
        $nominationId = $document->getNominationId();
        $referenceId = $document->getReferenceId();
        return ($nominationId && self::currentOwnsNomination($nominationId)) ||
            ($referenceId && self::currentIsReference($referenceId));
    }

    /**
     * Returns TRUE is the associated nomination id was created by the current participant.
     * @param int $referenceId
     * @return boolean
     */
    public static function currentOwnsNomination(int $nominationId)
    {
        return NominationFactory::build($nominationId)->getNominatorId() === self::getCurrentParticipant()->getId();
    }

    /**
     * Returns TRUE is the associated reference was suggested by the current participant.
     * @param int $referenceId
     * @return boolean
     */
    public static function currentOwnsReference(int $referenceId)
    {
        $reference = ReferenceFactory::build($referenceId);
        return NominationFactory::build($reference->nominationId)->getNominatorId() === self::getCurrentParticipant()->getId();
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
        $email = strtolower($email);
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

    /**
     * Throws exceptions if participant is not usable.
     * @param Participant $participant
     * @throws BannedParticipant
     */
    public static function isViable(Participant $participant)
    {
        if ($participant->banned) {
            throw new BannedParticipant($participant->email);
        }
        if (!$participant->active) {
            throw new InactiveParticipant($participant->email);
        }
    }

    /**
     * Options:
     * - search        (string): search email plus first and last name for string
     * - cycleId       (integer): only return participants available for nomination
     * - asSelect      (bool): return results with only id and participant full name.
     * - allowBanned   (bool): if true, include banned participants in results.
     * - allowInactive (bool): if true, include inactive participants in results.
     * - notIn (array) Array of ids to not include.
     *
     * @param array $options
     * @return array
     */
    public static function listing(array $options = [])
    {
        extract(self::getDBWithTable());

        if (!empty($options['search'])) {
            $search = $options['search'];
            $firstCond = new Database\Conditional($db, $table->getField('firstName'), "%$search%", 'like');
            $lastCond = new Database\Conditional($db, $table->getField('lastName'), "%$search%", 'like');
            $emailCond = new Database\Conditional($db, $table->getField('email'), "%$search%", 'like');
            $meldCond = new Database\Conditional($db, $firstCond, $lastCond, 'or');
            $meldCond2 = new Database\Conditional($db, $meldCond, $emailCond, 'or');

            $db->addConditional($meldCond2);
        }

        if (!empty($options['asSelect'])) {
            $table->addField('id', 'value');
            $expString = 'concat(firstName, " ", lastName, " ", " ", email)';
            $dbExpression = new \phpws2\Database\Expression($expString, 'label');
            $table->addField($dbExpression);
        } else {
            $table->addField('id');
            $table->addField('active');
            $table->addField('authType');
            $table->addField('banned');
            $table->addField('created');
            $table->addField('email');
            $table->addField('firstName');
            $table->addField('lastName');
            $table->addField('updated');
            $table->addField('trusted');
        }

        if (!empty($options['cycleId'])) {
            $judgeIds = JudgeFactory::listing(['cycleId' => $options['cycleId'], 'participantIdOnly' => true]);

            if (!empty($judgeIds)) {
                $table->addFieldConditional('id', $judgeIds, 'not in');
            }
        }

        if (!empty($options['notIn'])) {
            $table->addFieldConditional('id', $options['notIn'], 'not in');
        }

        if (empty($options['allowBanned'])) {
            $table->addFieldConditional('banned', 0);
        }

        /**
         * If false or not set, only return active participants
         */
        if (empty($options['allowInactive'])) {
            $table->addFieldConditional('active', 1);
        }

        if (empty($options['sortBy'])) {
            $sortBy = 'email';
        }
        if (empty($options['sortDir'])) {
            $sortDir = 'asc';
        }
        $table->addOrderBy($sortBy, $sortDir);
        // TODO an option needs to exist for this in the case of reports.
        $db->setLimit(50);
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
