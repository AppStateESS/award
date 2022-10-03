<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Resource;

/**
 * @table nomination
 */
class Nomination extends award\AbstractResource
{

    /**
     * Determines if judges are allowed to vote for this nomination.
     * Could be set to false for a "sudden death" vote in a tie.
     * @var bool
     */
    private bool $allowVote = true;

    /**
     * Determines if this nomination is allowed to pushed forward in
     * the process.
     * @var bool
     */
    private bool $approved = false;

    /**
     * @var int
     */
    private int $awardId;

    /**
     * @var bool
     */
    private bool $completed;

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * Participant id of nominator.
     * @var int
     */
    private int $nominatorId;

    /**
     * Id of participant nominated.
     * @var int
     */
    private int $participantId;

    public function getAllowVote(): bool
    {
        return $this->allowVote;
    }

    public function getApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return int
     */
    public function getAwardId(): int
    {
        return $this->awardId;
    }

    /**
     * @return bool
     */
    public function getCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    /**
     * @return int
     */
    public function getNominatorId(): int
    {
        return $this->nominatorId;
    }

    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     *
     * @param bool $allowVote
     * @return self
     */
    public function setAllowVote(bool $allowVote): self
    {
        $this->allowVote = $allowVote;
        return $this;
    }

    /**
     *
     * @param bool $approved
     * @return self
     */
    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;
        return $this;
    }

    /**
     * @param int $awardId
     */
    public function setAwardId(int $awardId): self
    {
        $this->awardId = $awardId;
        return $this;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @param int $cycleId
     */
    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    public function setNominatorId(int $nominatorId): self
    {
        $this->nominatorId = $nominatorId;
        return $this;
    }

    /**
     * @param int $participantId
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

}
