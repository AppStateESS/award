<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matt McNaney
 * @license https://opensource.org/licenses/MIT
 *
 */

namespace award\Resource;

use award\Traits\TrackedTrait;
use award\Traits\ReminderTrait;
use award\AbstractClass\AbstractResource;

/**
 * An invitation is sent to a participant or soon to be a participant when they
 * have been asked to be a judge, reference, or, if applicable, the recipient of
 * an award.
 *
 * Once a confirmed judge, reference, or recipient, no more invitations for
 * the same cycleId may occur.
 *
 * Once refused, no more invitations for the same cycle position may occur.
 *
 * Once a participant signs up, their participantId must be updated on all
 * invitations.
 *
 * See config/system.php for defines used here.
 *
 * @table award_invitation
 */
class Invitation extends AbstractResource
{

    use TrackedTrait;
    use ReminderTrait;

    /**
     * Award id
     * @var int
     */
    private int $awardId = 0;

    /**
     * Determines the response to the invitation.
     * AWARD_INVITATION_WAITING - waiting for response
     * AWARD_INVITATION_CONFIRMED - confirmed
     * AWARD_INVITATION_REFUSED - refused
     * @var int
     */
    private int $confirm = AWARD_INVITATION_WAITING;

    /**
     * Id invitation cycle.
     * @var int
     */
    private int $cycleId = 0;

    /**
     * @var string
     */
    private string $email;

    /**
     * Id of participant sent the invite. Will be zero on general invitations.
     *
     * @var int
     */
    private int $invitedId = 0;

    /**
     * The invitation type
     * AWARD_INVITE_TYPE_NEW
     * AWARD_INVITE_TYPE_JUDGE
     * AWARD_INVITE_TYPE_REFERENCE
     * AWARD_INVITE_TYPE_NOMINATED
     * @var int
     */
    private int $inviteType = AWARD_INVITE_TYPE_NEW;

    /**
     * If a reference invite, id of the nominated participant;
     * @var int
     */
    private int $nominatedId = 0;

    /**
     * If a reference invite, id of the nomination.
     * @var int
     */
    private int $nominationId = 0;

    /**
     * Id of participant who sent the invitation. Will be zero if sent by an admin.
     * @var int
     */
    private int $senderId = 0;

    public function __construct()
    {
        parent::__construct('award_invitation');
        self::constructDates();
    }

    public function getAwardId(): int
    {
        return $this->awardId;
    }

    public function getConfirm(): int
    {
        return $this->confirm;
    }

    /**
     * @return int
     */
    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the participant id sent the invite.
     * @return int
     */
    public function getInvitedId(): int
    {
        return $this->invitedId;
    }

    /**
     * @return int
     */
    public function getInviteType(): int
    {
        return $this->inviteType;
    }

    public function getNominatedId(): int
    {
        return $this->nominatedId;
    }

    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function isJudge(): bool
    {
        return $this->inviteType === AWARD_INVITE_TYPE_JUDGE;
    }

    public function isReference(): bool
    {
        return $this->inviteType === AWARD_INVITE_TYPE_REFERENCE;
    }

    public function setAwardId(int $awardId): self
    {
        $this->awardId = $awardId;
        return $this;
    }

    public function setConfirm(int $confirm): self
    {
        $this->confirm = $confirm;
        return $this;
    }

    /**
     * @param int $cycleId
     * @return self
     */
    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);
        return $this;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param int $participantId
     * @return self
     */
    public function setInvitedId(int $participantId): self
    {
        $this->invitedId = $participantId;
        return $this;
    }

    /**
     *
     * @param int $inviteType
     * @return self
     */
    public function setInviteType(int $inviteType): self
    {
        $this->inviteType = $inviteType;
        return $this;
    }

    public function setNominatedId(int $nominatedId): self
    {
        $this->nominatedId = $nominatedId;
        return $this;
    }

    public function setNominationId(int $nominationId): self
    {
        $this->nominationId = $nominationId;
        return $this;
    }

    /**
     *
     * @param int $senderId
     * @return self
     */
    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;
        return $this;
    }

}
