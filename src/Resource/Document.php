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
 * @table document
 */
class Document extends AbstractResource
{

    /**
     * Date created
     * @var \DateTime
     */
    protected \DateTime $created;

    /**
     * @var string
     */
    private string $filename = '';

    /**
     * Id for the reason to which it is associated
     * @var int
     */
    private int $reasonId = 0;

    /**
     * @var string
     */
    private string $title = '';

    public function __construct()
    {
        $this->created = new \DateTime;
        parent::__construct('award_document');
    }

    public function getCreated(string $format = null)
    {
        return $this->created->format($format ?? 'Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getReasonId(): int
    {
        return $this->reasonId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setCreated(string $datetime)
    {
        $this->created = new \DateTime($datetime);
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param int $nominationId
     */
    public function setReasonId(int $reasonId): self
    {
        $this->reasonId = $reasonId;
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

    public function stampCreated()
    {
        $this->created = new \DateTime();
    }

}
