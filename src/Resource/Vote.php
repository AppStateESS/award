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
 * @table vote 
 */
class Vote extends award\AbstractResource
{
    /**
     * @var int
     */
    private int $awardId;

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * @var int
     */
    private int $judgeId;

    /**
     * @var int
     */
    private int $nominationId;

    /**
     * @var int
     */
    private int $score;

    /**
     * @return int
     */
    public function getAwardId() : int
    {
        return $this->awardId;
    }

    /**
     * @return int
     */
    public function getCycleId() : int
    {
        return $this->cycleId;
    }

    /**
     * @return int
     */
    public function getJudgeId() : int
    {
        return $this->judgeId;
    }

    /**
     * @return int
     */
    public function getNominationId() : int
    {
        return $this->nominationId;
    }

    /**
     * @return int
     */
    public function getScore() : int
    {
        return $this->score;
    }

    /**
     * @param int $awardId
     */
    public function setAwardId(int $awardId) : self
    {
        $this->awardId = $awardId;
        return $this;
    }

    /**
     * @param int $cycleId
     */
    public function setCycleId(int $cycleId) : self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    /**
     * @param int $judgeId
     */
    public function setJudgeId(int $judgeId) : self
    {
        $this->judgeId = $judgeId;
        return $this;
    }

    /**
     * @param int $nominationId
     */
    public function setNominationId(int $nominationId) : self
    {
        $this->nominationId = $nominationId;
        return $this;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score) : self
    {
        $this->score = $score;
        return $this;
    }

}