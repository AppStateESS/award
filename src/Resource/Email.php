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
 * @table email 
 */
class Email extends award\AbstractResource
{
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
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getReplyto() : string
    {
        return $this->replyto;
    }

    /**
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message) : self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $replyto
     */
    public function setReplyto(string $replyto) : self
    {
        $this->replyto = $replyto;
        return $this;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject) : self
    {
        $this->subject = $subject;
        return $this;
    }

}