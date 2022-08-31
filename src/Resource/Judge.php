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
class Judge extends AbstractResource
{

    /**
     * @var int
     */
    protected int $cycleId = 0;

    /**
     * @var int
     */
    protected int $participantId = 0;

    /**
     * @var bool
     */
    protected bool $voteComplete = false;

    public function __construct()
    {
        parent::__construct('award_judge');
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
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    public function getVoteComplete(): bool
    {
        return $this->voteComplete;
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

    public function setVoteComplete(bool $voteComplete): self
    {
        $this->voteComplete = $voteComplete;
        return $this;
    }

}
