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
 * @table reference
 */
class Reference extends AbstractResource
{

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * Document id of reference reason
     * @var int
     */
    private ?int $reasonDocument;

    /**
     * Text of the reference's reason for approving the nomination.
     * @var string
     */
    private ?string $reasonText;

    /**
     * @var int
     */
    private int $nominationId;

    /**
     * @var int
     */
    private int $participantId;

    public function __construct()
    {
        parent::__construct('award_reference');
    }

    /**
     * @return int
     */
    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    /**
     * @return int
     */
    public function getReasonDocument(): ?int
    {
        return $this->reasonDocument ?? null;
    }

    /**
     * @return int
     */
    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    public function getReasonText(): string
    {
        return $this->reasonText ?? '';
    }

    /**
     * @param int $reasonDocument
     */
    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    /**
     * @param int $reasonDocument
     */
    public function setReasonDocument(int $reasonDocument): self
    {
        $this->reasonDocument = $reasonDocument;
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

    /**
     * @param int $participantId
     */
    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

    public function setReasonText(string $reasonText): self
    {
        $this->reasonText = $reasonText;
        return $this;
    }

}
