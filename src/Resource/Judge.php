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
 * @table reference
 */
class Judge extends award\AbstractResource
{

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * @var int
     */
    private int $participantId;

    /**
     * @returns int
     */
    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    /**
     * @returns int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
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
     * @param int $participantId
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

}
