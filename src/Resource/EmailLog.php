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
 * @table emaillog 
 */
class EmailLog extends award\AbstractResource
{
    /**
     * @var int
     */
    private int $dateSent;

    /**
     * @var int
     */
    private int $emailId;

    /**
     * @var int
     */
    private int $participantId;

    /**
     * @returns int
     */
    public function getDateSent() : int
    {
        return $this->dateSent;
    }

    /**
     * @returns int
     */
    public function getEmailId() : int
    {
        return $this->emailId;
    }

    /**
     * @returns int
     */
    public function getParticipantId() : int
    {
        return $this->participantId;
    }

    /**
     * @param int $dateSent
     */
    public function setDateSent(int $dateSent) : self
    {
        $this->dateSent = $dateSent;
        return self;
    }

    /**
     * @param int $emailId
     */
    public function setEmailId(int $emailId) : self
    {
        $this->emailId = $emailId;
        return self;
    }

    /**
     * @param int $participantId
     */
    public function setParticipantId(int $participantId) : self
    {
        $this->participantId = $participantId;
        return self;
    }

}