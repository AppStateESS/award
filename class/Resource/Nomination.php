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
class Nomination extends AbstractResource
{
    /**
     * @var int
     */
    private int $awardId;

    /**
     * @var int
     */
    private int $bannerId;

    /**
     * @var bool
     */
    private bool $completed;

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * @var int
     */
    private int $documentId;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var int
     */
    private int $nominatorId;

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
    public function getBannerId() : int
    {
        return $this->bannerId;
    }

    /**
     * @returns bool
     */
    public function getCompleted() : bool
    {
        return $this->completed;
    }

    /**
     * @returns int
     */
    public function getCycleId() : int
    {
        return $this->cycleId;
    }

    /**
     * @returns int
     */
    public function getDocumentId() : int
    {
        return $this->documentId;
    }

    /**
     * @returns string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @returns string
     */
    public function getFirstName() : string
    {
        return $this->firstName;
    }

    /**
     * @returns string
     */
    public function getLastName() : string
    {
        return $this->lastName;
    }

    /**
     * @returns int
     */
    public function getNominatorId() : int
    {
        return $this->nominatorId;
    }

    /**
     * @param int $awardId
     */
    public function setAwardId(int $awardId) : self
    {
        $this->awardId = $awardId;
        return self;
    }

    /**
     * @param int $bannerId
     */
    public function setBannerId(int $bannerId) : self
    {
        $this->bannerId = $bannerId;
        return self;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed) : self
    {
        $this->completed = $completed;
        return self;
    }

    /**
     * @param int $cycleId
     */
    public function setCycleId(int $cycleId) : self
    {
        $this->cycleId = $cycleId;
        return self;
    }

    /**
     * @param int $documentId
     */
    public function setDocumentId(int $documentId) : self
    {
        $this->documentId = $documentId;
        return self;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) : self
    {
        $this->email = $email;
        return self;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName) : self
    {
        $this->firstName = $firstName;
        return self;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;
        return self;
    }

    /**
     * @param int $nominatorId
     */
    public function setNominatorId(int $nominatorId) : self
    {
        $this->nominatorId = $nominatorId;
        return self;
    }

}