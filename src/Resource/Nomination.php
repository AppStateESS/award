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

use award\AbstractClass\AbstractResource;
use award\Traits\ReasonResourceTrait;

/**
 * @table nomination
 */
class Nomination extends AbstractResource
{

    use ReasonResourceTrait;

    /**
     * Determines if judges are allowed to vote for this nomination.
     * Could be set to false for a "sudden death" vote in a tie.
     * @var bool
     */
    protected bool $allowVote = true;

    /**
     * Determines if this nomination is allowed to pushed forward in
     * the process.
     * @var bool
     */
    protected bool $approved = false;

    /**
     * @var int
     */
    protected int $awardId = 0;

    /**
     * @var bool
     */
    protected bool $completed = false;

    /**
     * @var int
     */
    protected int $cycleId;

    /**
     * Participant id of nominator.
     * @var int
     */
    protected int $nominatorId = 0;

    /**
     * Id of participant nominated.
     * @var int
     */
    protected int $nominatedId = 0;

    /**
     * All references are selected and they have completed their
     * reasons (if required).
     * @var bool
     */
    protected bool $referencesComplete = false;

    /**
     * All required references have been selected.
     * Requirement depends on award setting.
     * @var bool
     */
    protected bool $referencesSelected = false;

    public function __construct()
    {
        parent::__construct('award_nomination');
    }

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

    public function getNominatedId(): int
    {
        return $this->nominatedId;
    }

    public function getReferencesComplete(): bool
    {
        return $this->referencesComplete;
    }

    public function getReferencesSelected(): bool
    {
        return $this->referencesSelected;
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
    public function setNominatedId(int $participantId): self
    {
        $this->nominatedId = $participantId;
        return $this;
    }

    public function setReferencesComplete(bool $referencesComplete)
    {
        $this->referencesComplete = $referencesComplete;
        return $this;
    }

    public function setReferencesSelected(bool $referencesSelected)
    {
        $this->referencesSelected = $referencesSelected;
        return $this;
    }

}
