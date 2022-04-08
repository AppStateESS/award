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
 * @table emailtemplate 
 */
class EmailTemplate extends AbstractResource
{
    /**
     * @var int
     */
    private int $awardId;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var string
     */
    private string $replyto;

    /**
     * @var string
     */
    private string $subject;

    /**
     * @var string
     */
    private string $title;

    /**
     * @returns int
     */
    public function getAwardId() : int
    {
        return $this->awardId;
    }

    /**
     * @returns string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @returns string
     */
    public function getReplyto() : string
    {
        return $this->replyto;
    }

    /**
     * @returns string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * @returns string
     */
    public function getTitle() : string
    {
        return $this->title;
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
     * @param string $message
     */
    public function setMessage(string $message) : self
    {
        $this->message = $message;
        return self;
    }

    /**
     * @param string $replyto
     */
    public function setReplyto(string $replyto) : self
    {
        $this->replyto = $replyto;
        return self;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject) : self
    {
        $this->subject = $subject;
        return self;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return self;
    }

}