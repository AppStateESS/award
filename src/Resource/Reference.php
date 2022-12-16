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
use award\Traits\ReminderTrait;

/**
 * @table reference
 */
class Reference extends AbstractResource
{

    use ReminderTrait;

    /**
     * @var int
     */
    private int $awardId = 0;

    /**
     * @var int
     */
    private int $cycleId = 0;

    /**
     * @var int
     */
    private int $nominationId = 0;

    /**
     * Id of the participant serving as the reference.
     * @var int
     */
    private int $participantId = 0;

    /**
     * Id of reference reason
     * @var int
     */
    private int $reasonId = 0;

    public function __construct()
    {
        parent::__construct('award_reference');
    }

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
    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @return int
     */
    public function getReasonId(): int
    {
        return $this->reasonId;
    }

    public function setAwardId(int $awardId): self
    {
        $this->awardId = $awardId;
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
     * @param int $nominationId
     */
    public function setNominationId(int $nominationId): self
    {
        $this->nominationId = $nominationId;
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

    /**
     * @param int $reasonId
     */
    public function setReasonId(int $reasonId): self
    {
        $this->reasonId = $reasonId;
        return $this;
    }

}
