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
 *
 * @table award_invitation
 */
define('AWARD_INVITATION_WAITING', 0);
define('AWARD_INVITATION_CONFIRMED', 1);
define('AWARD_INVITATION_REFUSED', 2);

class Invitation extends AbstractResource
{

    /**
     * Determines the response to the invitation.
     * 0 - waiting for response
     * 1 - confirmed
     * 2 - refused
     * @var int
     */
    private int $confirm;

    /**
     * Id invitation cycle.
     * @var int
     */
    private int $cycleId;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var int
     */
    private int $id;

    /**
     * @var bool
     */
    private bool $judge;

    /**
     * @var bool
     */
    private bool $nominated;

    /**
     * @var int
     */
    private int $participantId;

    /**
     * @var bool
     */
    private bool $reference;

    public function __construct()
    {
        parent::__construct('award_invitation');
    }

    public function getConfirm(): bool
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
     * @return bool
     */
    public function getJudge(): bool
    {
        return $this->judge;
    }

    /**
     * @return bool
     */
    public function getNominated(): bool
    {
        return $this->nominated;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @return bool
     */
    public function getReference(): bool
    {
        return $this->reference;
    }

    public function setConfirm(bool $confirm): self
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
        $this->email = $email;
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
     * @param bool $judge
     * @return self
     */
    public function setJudge(bool $judge): self
    {
        $this->judge = $judge;
        return $this;
    }

    /**
     * @param bool $nominated
     * @return self
     */
    public function setNominated(bool $nominated): self
    {
        $this->nominated = $nominated;
        return $this;
    }

    /**
     * @param int $participantId
     * @return self
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

    /**
     * @param bool $reference
     * @return self
     */
    public function setReference(bool $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

}
