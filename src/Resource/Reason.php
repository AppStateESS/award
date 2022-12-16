<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Resource;

use award\AbstractClass\AbstractResource;

class Reason extends AbstractResource
{

    /**
     * The cycle to which this reason is associated.
     * @var int
     */
    protected int $cycleId = 0;

    /**
     * The document used for this reason.
     * @var int
     */
    protected int $documentId = 0;

    /**
     * The nomination to which this reason is associated.
     * @var int
     */
    protected int $nominationId = 0;

    /**
     * The text added to this reason.
     * @var string
     */
    protected string $reasonText = '';

    /**
     * The type of reason this is for: a nomination or reference.
     * AWARD_REASON_NOMINATION : 0
     * AWARD_REASON_REFERENCE : 1
     * @var int
     */
    protected int $reasonType = AWARD_REASON_NOMINATION;

    /**
     * The reference this reason may be associated with if the type is a reference.
     * @var int
     */
    protected int $referenceId = 0;

    public function __construct()
    {
        parent::__construct('award_reason');
    }

    public function getCycleId(): int
    {
        return $this->cycleId;
    }

    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    public function getNominationId(): int
    {
        return $this->nominationId;
    }

    public function getReasonText(): string
    {
        return $this->reasonText;
    }

    public function getReferenceId(): int
    {
        return $this->referenceId;
    }

    public function getReasonType(): int
    {
        return $this->reasonType;
    }

    public function isComplete(): bool
    {
        return strlen($this->reasonText) > 0 || $this->documentId > 0;
    }

    public function isNomination(): bool
    {
        return $this->reasonType === AWARD_REASON_NOMINATION;
    }

    public function isReference(): bool
    {
        return $this->reasonType === AWARD_REASON_REFERENCE;
    }

    public function setCycleId(int $cycleId)
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    public function setDocumentId(int $documentId)
    {
        $this->documentId = $documentId;
        return $this;
    }

    public function setNominationId(int $nominationId)
    {
        $this->nominationId = $nominationId;
        return $this;
    }

    public function setReasonText(string $reasonText)
    {
        $this->reasonText = strip_tags($reasonText);
        return $this;
    }

    public function setReferenceId(int $referenceId)
    {
        $this->referenceId = $referenceId;
        return $this;
    }

    public function setReasonType(int $reasonType)
    {
        $this->reasonType = $reasonType;
        return $this;
    }

}
