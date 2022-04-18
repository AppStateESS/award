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
 * @table cyclewinner 
 */
class CycleWinner extends \award\AbstractResource
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
     * @var string
     */
    private string $description;

    /**
     * @var string
     */
    private string $image;

    /**
     * @var int
     */
    private int $nominationId;

    /**
     * @returns int
     */
    public function getAwardId() : int
    {
        return $this->awardId;
    }

    /**
     * @returns int
     */
    public function getCycleId() : int
    {
        return $this->cycleId;
    }

    /**
     * @returns string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @returns string
     */
    public function getImage() : string
    {
        return $this->image;
    }

    /**
     * @returns int
     */
    public function getNominationId() : int
    {
        return $this->nominationId;
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
     * @param string $description
     */
    public function setDescription(string $description) : self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image) : self
    {
        $this->image = $image;
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

}