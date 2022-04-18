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

namespace award\Cycle;

/**
 * @table reference
 */
class Cycle extends award\AbstractResource
{

    /**
     * @var int
     */
    private int $awardId;

    /**
     * @var int
     */
    private int $awardMonth;

    /**
     * @var int
     */
    private int $awardYear;

    /**
     * @var int
     */
    private int $endDate;

    /**
     * @var int
     */
    private int $startDate;

    /**
     * @var bool
     */
    private bool $voteAllowed;

    /**
     * @var string
     */
    private string $voteType;

    /**
     * @returns int
     */
    public function getAwardId(): int
    {
        return $this->awardId;
    }

    /**
     * @returns int
     */
    public function getAwardMonth(): int
    {
        return $this->awardMonth;
    }

    /**
     * @returns int
     */
    public function getAwardYear(): int
    {
        return $this->awardYear;
    }

    /**
     * @returns int
     */
    public function getEndDate(): int
    {
        return $this->endDate;
    }

    /**
     * @returns int
     */
    public function getStartDate(): int
    {
        return $this->startDate;
    }

    /**
     * @returns bool
     */
    public function getVoteAllowed(): bool
    {
        return $this->voteAllowed;
    }

    /**
     * @returns int
     */
    public function getVoteType(): int
    {
        return $this->voteType;
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
     * @param int $awardMonth
     */
    public function setAwardMonth(int $awardMonth): self
    {
        $this->awardMonth = $awardMonth;
        return $this;
    }

    /**
     * @param int $awardYear
     */
    public function setAwardYear(int $awardYear): self
    {
        $this->awardYear = $awardYear;
        return $this;
    }

    /**
     * @param int $endDate
     */
    public function setEndDate(int $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @param int $startDate
     */
    public function setStartDate(int $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param bool $voteAllowed
     */
    public function setVoteAllowed(bool $voteAllowed): self
    {
        $this->voteAllowed = $voteAllowed;
        return $this;
    }

    /**
     * @param int $voteType
     */
    public function setVoteType(int $voteType): self
    {
        $this->voteType = $voteType;
        return $this;
    }

}
