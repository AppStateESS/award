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
 * @table reference
 */
class Reference extends award\AbstractResource
{

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * @var int
     */
    private int $documentId;

    /**
     * @var int
     */
    private int $nominationId;

    /**
     * @var int
     */
    private int $participantId;

    /**
     * @returns int
     */
    public function getCycleId(): int
    {
        return $this->documentId;
    }

    /**
     * @returns int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @returns int
     */
    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    /**
     * @returns int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * @param int $documentId
     */
    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return self;
    }

    /**
     * @param int $documentId
     */
    public function setDocumentId(int $documentId): self
    {
        $this->documentId = $documentId;
        return self;
    }

    /**
     * @param int $nominationId
     */
    public function setNominationId(int $nominationId): self
    {
        $this->nominationId = $nominationId;
        return self;
    }

    /**
     * @param int $participantId
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return self;
    }

}
