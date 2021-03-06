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
class EmailTemplate extends award\AbstractResource
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
    private string $subject;

    /**
     * @var string
     */
    private string $title;

    /**
     * @return int
     */
    public function getAwardId(): int
    {
        return $this->awardId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getReplyto(): string
    {
        return $this->replyto;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param int $awardId
     */
    public function setAwardId(int $awardId): self
    {
        $this->awardId = $awardId;
        return $this;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $replyto
     */
    public function setReplyto(string $replyto): self
    {
        $this->replyto = $replyto;
        return $this;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

}
