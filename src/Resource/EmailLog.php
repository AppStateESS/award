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
     * @return int
     */
    public function getDateSent() : int
    {
        return $this->dateSent;
    }

    /**
     * @return int
     */
    public function getEmailId() : int
    {
        return $this->emailId;
    }

    /**
     * @return int
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
        return $this;
    }

    /**
     * @param int $emailId
     */
    public function setEmailId(int $emailId) : self
    {
        $this->emailId = $emailId;
        return $this;
    }

    /**
     * @param int $participantId
     */
    public function setParticipantId(int $participantId) : self
    {
        $this->participantId = $participantId;
        return $this;
    }

}