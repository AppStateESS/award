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
 * @table document 
 */
class Document extends award\AbstractResource
{
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var int
     */
    private int $nominationId;

    /**
     * @var string
     */
    private string $title;

    /**
     * @return string
     */
    public function getFilename() : string
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getNominationId() : int
    {
        return $this->nominationId;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename) : self
    {
        $this->filename = $filename;
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
     * @param string $title
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;
        return $this;
    }

}