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

/**
 * @table reference
 */
class Cycle extends AbstractResource
{

    /**
     * The award this cycle represents. Note there is a unique
     * key on awardId, awardMonth, and awardYear. No repeats
     * are allowed.
     * @var int
     */
    private int $awardId = 0;

    /**
     * The month this award's cycle represents.
     * @var int
     */
    private int $awardMonth = 0;

    /**
     * The year this award's cycle represents
     * @var int
     */
    private int $awardYear = 0;

    /**
     * If true, the winner has been selected and the cycle is complete.
     * @var bool
     */
    private bool $completed = false;

    /**
     * @var bool
     */
    private bool $deleted = false;

    /**
     * Deadline for nominations
     * @var int
     */
    private int $endDate = 0;

    /**
     * The last date that the endDate was updated.
     * @var int
     */
    private int $lastEndDate = 0;

    /**
     * Start time for allowing nominations.
     * @var int
     */
    private int $startDate = 0;

    /**
     * Determines the time frame of the cycle.
     * -monthly
     * -yearly
     * -random
     * @var string
     */
    private string $term = 'yearly';

    /**
     * If true, judges or participants are allowed to vote on nominees
     * for this cycle. This should be an administrative toggle.
     * @var bool
     */
    private bool $voteAllowed = false;

    /**
     * Indicate method of voting. The default type
     * is 'choose', wherein each judge picks one winner.
     * @var string
     */
    private string $voteType = 'SingleVote';

    public function __construct()
    {
        parent::__construct('award_cycle');
    }

    /**
     *
     * @param type $format
     * @return string
     */
    public function formatEndDate($format = '%l:%M %p, %B %e, %Y'): string
    {
        return strftime($format, $this->endDate);
    }

    /**
     *
     * @param type $format
     * @return string
     */
    public function formatStartDate($format = '%l:%M %p, %B %e, %Y'): string
    {
        return strftime($format, $this->startDate);
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
    public function getAwardMonth(): int
    {
        return $this->awardMonth;
    }

    /**
     * @return int
     */
    public function getAwardYear(): int
    {
        return $this->awardYear;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return bool
     */
    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return int
     */
    public function getEndDate(): int
    {
        return $this->endDate;
    }

    public function getLastEndDate(): int
    {
        return $this->lastEndDate;
    }

    /**
     * Returns the resource's values without some administrative data.
     */
    public function getParticipantValues()
    {
        $ignore = [
            'deleted', 'voteAllowed', 'voteType', 'completed'
        ];
        return $this->getValues($ignore);
    }

    /**
     * Returns the cycle's period as a string based on the term.
     * If the term is not a time frame, returns null.
     * @return string|null
     */
    public function getPeriod(): string
    {
        if ($this->term === 'yearly') {
            return (string) $this->awardYear;
        } elseif ($this->term === 'monthly') {
            return $this->awardMonth . ', ' . $this->awardYear;
        }
    }

    /**
     * @return int
     */
    public function getStartDate(): int
    {
        return $this->startDate;
    }

    public function getTerm(): string
    {
        return $this->term;
    }

    /**
     * @return bool
     */
    public function getVoteAllowed(): bool
    {
        return $this->voteAllowed;
    }

    /**
     * @return string
     */
    public function getVoteType(): string
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

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * Sets the default month and year depending on the
     * award's term.
     */
    public function setDefaultPeriod()
    {
        $year = (int) strftime('%Y');
        $month = (int) strftime('%l');
        if ($this->term === 'yearly') {
            $this->setAwardYear($year);
        } elseif ($this->term === 'monthly') {
            if ($month === 12) {
                $month = 1;
                $year++;
            }
            $this->setAwardMonth($month);
            $this->setAwardYear($year);
        }
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
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

    public function setLastEndDate(int $lastEndDate): self
    {
        $this->lastEndDate = $lastEndDate;
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

    public function setTerm(string $term)
    {
        $this->term = $term;
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
     * @param string $voteType
     */
    public function setVoteType(string $voteType): self
    {
        $this->voteType = $voteType;
        return $this;
    }

    public function stampLastEndDate()
    {
        $this->lastEndDate = $this->endDate;
    }

}
