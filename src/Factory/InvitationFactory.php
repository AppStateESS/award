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

use award\Resource\Invitation;
use award\Factory\ParticipantFactory;
use award\AbstractClass\AbstractFactory;
use phpws2\Database;

class InvitationFactory extends AbstractFactory
{

    static string $table = 'award_invitation';

    /**
     * Initiates a Invitation Resource. If the $id is passed, a retrieval
     * from the database is attempted.
     * @param int $id
     * @return award\Resource\Invitation
     */
    public static function build(int $id = 0): Invitation
    {
        $invitation = new Invitation;
        if ($id) {
            $result = self::load($invitation, $id);
            if (!$result) {
                throw new ResourceNotFound($id);
            }
        }
        return $invitation;
    }

    public static function createGeneral(string $email): Invitation
    {
        $invitation = self::build();
        $invitation->setEmail($email);
        $invitation->setInviteType(AWARD_INVITE_TYPE_NEW);
        return self::save($invitation);
    }

    /**
     * Returns the results of the award_invitation table.
     * Can be filtered in options with:
     * - awardId
     * - cycleId
     * - email address
     * - invitedId (participant who was invited)
     * - inviteType (defines for new, judge, reference, nominated)
     * - senderId (participant who invited someone)
     *
     * Additional options for sorting are:
     * - sortByConfirm: if true, sort by waiting, confirmed, and refused
     * - sortByInvite: if true, sort by new, judge, reference, nominated
     * @param array $options
     * @return type
     */
    public static function getList(array $options = [])
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $idChecks = ['awardId', 'cycleId', 'email', 'invitedId', 'inviteType', 'senderId'];

        self::addIdOptions($table, $idChecks, $options);
        self::addIssetOptions($table, ['inviteType', 'confirm'], $options);
        self::addOrderOptions($table, $options, 'email');

        $result = $db->select();

        if (empty($result)) {
            return [];
        }
        if (!empty($options['sortByConfirm'])) {
            return self::sortByConfirm($result);
        } elseif (!empty($options['sortByInvite'])) {
            return self::sortByInvite($result);
        } else {
            return $result;
        }
    }

    /**
     * Sends a general invitation to sign up as a participant.
     * Checks 3 conditions
     * 1) already a participant
     * 2) previously refused
     * 3) previously invited but did not respond.
     *
     * Returns true if email sent or false if previously invited.
     * @param string $email
     * @return bool
     */
    public static function sendGeneral(string $email): bool
    {
        $displayName = \Current_User::getDisplayName();
        $previousInvite = self::getPreviousInvite($email, AWARD_INVITE_TYPE_NEW);

        if (ParticipantFactory::getByEmail($email)) {
            throw \Exception('Participant already exists');
        } elseif (self::userRefusedGeneral($email)) {
            throw \Exception('Previously refused invitation may not be sent.');
        } elseif (!empty($previousInvite)) {
            return false;
        } else {
            EmailFactory::inviteNewParticipant($email, $displayName);
            return true;
        }
    }

    public static function getPreviousInvite(string $email, int $inviteType, int $cycleId = 0)
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());

        $table->addFieldConditional('email', $email);
        $table->addFieldConditional('inviteType', $inviteType);
        $table->addFieldConditional('cycleId', $cycleId);

        return $db->selectOneRow();
    }

    /**
     * Tests if a person can be invited with a general invitation. This is
     * their initial request to join without a purpose (judge, reference, etc.).
     *
     * Returns FALSE if the user can be invited to site.
     * Returns TRUE if the user has been previously invited and refused
     * The invitation record will not exist if they have already created an
     * account so a ParticipantFactory::getByEmail() test should be used instead.
     * @param string $email
     * @return boolean
     */
    public static function userRefusedGeneral(string $email)
    {
        /**
         * @var \phpws2\Database\DB $db
         * @var \phpws2\Database\Table $table
         */
        extract(self::getDBWithTable());
        $table->addFieldConditional('email', $email);
        $table->addFieldConditional('inviteType', AWARD_INVITE_TYPE_NEW);
        $table->addFieldConditional('confirm', AWARD_INVITATION_REFUSED);
        return (bool) $db->selectOneRow();
    }

    /**
     * Sorts the results from list() into their confirmation status.
     * @param array $list
     * @return array
     */
    private static function sortByConfirm(array $list)
    {
        $sorted = ['waiting' => [], 'confirmed' => [], 'refused' => []];
        foreach ($list as $row) {
            switch ($row['confirm']) {
                case AWARD_INVITATION_WAITING:
                    $sorted['waiting'][] = $row;
                    break;
                case AWARD_INVITATION_CONFIRMED:
                    $sorted['confirmed'][] = $row;
                    break;
                case AWARD_INVITATION_REFUSED:
                    $sorted['refused'][] = $row;
                    break;
            }
        }
        return $sorted;
    }

    /**
     * Sorts the results from list() into their invite type.
     * @param array $list
     * @return array
     */
    private static function sortByInvite(array $list)
    {
        $sorted = ['new' => [], 'judge' => [], 'reference' => [], 'nominated' => []];
        foreach ($list as $row) {
            switch ($row['inviteType']) {
                case AWARD_INVITE_TYPE_NEW:
                    $sorted['new'][] = $row;
                    break;
                case AWARD_INVITE_TYPE_JUDGE:
                    $sorted['judge'][] = $row;
                    break;
                case AWARD_INVITE_TYPE_REFERENCE:
                    $sorted['reference'][] = $row;
                    break;
                case AWARD_INVITE_TYPE_NOMINATED:
                    $sorted['nominated'][] = $row;
                    break;
            }
        }
        return $sorted;
    }

}
