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
 * @table cyclelog 
 */
class CycleLog extends AbstractResource
{
    /**
     * @var string
     */
    private string $action;

    /**
     * @var int
     */
    private int $documentId;

    /**
     * @var int
     */
    private int $participantId;

    /**
     * @returns string
     */
    public function getAction() : string
    {
        return $this->action;
    }

    /**
     * @returns int
     */
    public function getDocumentId() : int
    {
        return $this->documentId;
    }

    /**
     * @returns int
     */
    public function getParticipantId() : int
    {
        return $this->participantId;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action) : self
    {
        $this->action = $action;
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
     * @param int $participantId
     */
    public function setParticipantId(int $participantId) : self
    {
        $this->participantId = $participantId;
        return self;
    }

}