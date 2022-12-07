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
    private string $filename;

    /**
     * Id for the nomination reason document. Only > 0 for nominations.
     * @var int
     */
    private int $nominationId = 0;

    /**
     * Id for the reference reason document. Only > 0 for references.
     * @var int
     */
    private int $referenceId = 0;

    /**
     * @var string
     */
    private string $title;

    public function __construct()
    {
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
    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    public function getReferenceId(): int
    {
        return $this->referenceId;
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
    public function setNominationId(int $nominationId): self
    {
        $this->nominationId = $nominationId;
        return $this;
    }

    public function setReferenceId(int $referenceId): self
    {
        $this->referenceId = $referenceId;
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
