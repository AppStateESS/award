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
use award\Resource\Cycle;

/**
 * @table award_cyclelog
 */
class CycleLog extends AbstractResource
{

    /**
     * A short description of type of log
     * - judge_reminder
     * - reference_reminder
     * @var string
     */
    private string $action;

    /**
     * The key to the award
     * @var int
     */
    private int $awardId = 0;

    /**
     * The key to the cycle
     * @var int
     */
    private int $cycleId = 0;

    /**
     * @var int
     */
    private ?int $documentId;

    /**
     * @var int
     */
    private ?int $participantId;

    /**
     *
     * @var string
     */
    private ?string $username;

    /**
     * Timestamp for log entry
     * @var string
     */
    private string $stamped;

    public function __construct()
    {
        parent::__construct('award_cyclelog');
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
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
    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    /**
     * @return int
     */
    public function getDocumentId()
    {
        return $this->documentId ?? null;
    }

    /**
     * @return int
     */
    public function getParticipantId()
    {
        return $this->participantId ?? null;
    }

    public function getStamped()
    {
        return $this->stamped ?? null;
    }

    public function getUsername()
    {
        return $this->username ?? null;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function setAwardId(int $awardId): self
    {
        $this->awardId = $awardId;
        return $this;
    }

    /**
     * Sets the award and cycle id with the $cycle parameter.
     * @param Cycle $cycle
     */
    public function setCycle(Cycle $cycle)
    {
        $this->awardId = $cycle->awardId;
        $this->cycleId = $cycle->id;
    }

    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    /**
     * @param int $documentId
     */
    public function setDocumentId(int $documentId): self
    {
        $this->documentId = $documentId;
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

    public function setStamped(string $stamped)
    {
        $this->stamped = $stamped;
        return $this;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

}
