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
use award\Resource\Participant;
use award\Factory\ParticipantFactory;
use award\Factory\CycleFactory;
use award\AbstractClass\AbstractFactory;
use phpws2\Database;

class InvitationFactory extends AbstractFactory
{

    protected static string $table = 'award_invitation';
    protected static string $resourceClassName = 'award\Resource\Invitation';

    /**
     * Searches the invitation table by email to see if recipient requested
     * to never be bothered.
     * This would be an invitation with a new invite type, zero cycleId (only new invites
     * have this) and a no contact confirm.
     * One someone becomes a participant, they need to become inactive to prevent
     * further contact.
     *
     * @param string $email
     * @return bool True if participant chooses never to be contacted.
     */
    public static function checkNoContact(string $email): bool
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('email', strtolower($email));
        $table->addFieldConditional('inviteType', AWARD_INVITE_TYPE_NEW);
        $table->addFieldConditional('cycleId', 0);
        $table->addFieldConditional('confirm', AWARD_INVITATION_NO_CONTACT);
        return !empty($db->selectOneRow());
    }

    public static function confirmReason(int $confirm)
    {
        switch ($confirm) {
            case AWARD_INVITATION_WAITING:
                return 'participant has not responded to a previous invitation.';
            case AWARD_INVITATION_CONFIRMED:
                return 'participant previously confirmed.';
            case AWARD_INVITATION_REFUSED:
                return 'participant refused invitation.';
        }
    }

    /**
     * Creates an invitation for a judge request. Must be a participant.
     *
     * @param int $cycleId
     * @param int $invitedId
     * @throws ResourceNotFound
     */
    public static function createJudgeInvitation(Participant $invited, int $cycleId)
    {
        $invitation = self::build();
        $invitation->email = $invited->email;
        $invitation->cycleId = $cycleId;
        $invitation->invitedId = $invited->id;
        $invitation->inviteType = AWARD_INVITE_TYPE_JUDGE;
        $invitation->awardId = CycleFactory::getAwardId($cycleId);
        self::save($invitation);
        return $invitation;
    }

    public static function createNewAccountInvite(string $email): Invitation
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
     * Checks for previous invitation to help prevent a repeated request.
     * @param string $email
     * @param int $inviteType
     * @param int $cycleId
     * @return boolean
     */
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

        $result = $db->selectOneRow();
        if (empty($result)) {
            return false;
        } else {
            $invitation = self::build();
            $invitation->setValues($result);
            return $invitation;
        }
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
