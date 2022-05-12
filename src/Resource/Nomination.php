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
     * Banner ID of student only. May be left blank for non-student
     * or disabled.
     * @var int
     */
    private int $bannerId = 0;

    /**
     * @var bool
     */
    private bool $completed;

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * @var string
     */
    private string $email;

    /**
     * If chosen/preferred name exists in data point, it will
     * be used instead of given first name.
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * Index of participant id. Named nominator to prevent
     * confusion with person nominated.
     * @var int
     */
    private int $nominatorId;

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
     * @return int
     */
    public function getBannerId(): int
    {
        return $this->bannerId;
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return int
     */
    public function getNominatorId(): int
    {
        return $this->nominatorId;
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
     * @param int $bannerId
     */
    public function setBannerId(int $bannerId): self
    {
        $this->bannerId = $bannerId;
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

    /**
     * @param string $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param int $nominatorId
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

}
