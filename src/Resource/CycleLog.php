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

use award\Resource\Cycle;

/**
 * @table cyclelog
 */
class CycleLog extends award\AbstractResource
{

    /**
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
    private int $documentId = 0;

    /**
     * @var int
     */
    private int $participantId = 0;

    /**
     * Timestamp for log entry
     * @var string
     */
    private string $stamped;

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
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    public function getStamped(): string
    {
        return $this->stamped;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): self
    {
        $this->action = $action;
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

}
