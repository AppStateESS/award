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
 * @table nomination
 */
class Nomination extends AbstractResource
{

    /**
     * Determines if judges are allowed to vote for this nomination.
     * Could be set to false for a "sudden death" vote in a tie.
     * @var bool
     */
    private bool $allowVote = true;

    /**
     * Determines if this nomination is allowed to pushed forward in
     * the process.
     * @var bool
     */
    private bool $approved = false;

    /**
     * @var int
     */
    private int $awardId = 0;

    /**
     * @var bool
     */
    private bool $completed = false;

    /**
     * @var int
     */
    private int $cycleId;

    /**
     * Participant id of nominator.
     * @var int
     */
    private int $nominatorId = 0;

    /**
     * Id of participant nominated.
     * @var int
     */
    private int $nominatedId = 0;

    /**
     * Nominator completed the reason for the nomination.
     * Requirement depends on award setting.
     * @var bool
     */
    private bool $reasonComplete = false;

    /**
     * ID of the award_document used for the nomination reason.
     * @var int
     */
    private int $reasonDocument = 0;

    /**
     * The reason for the nomination. May be empty due to not
     * required or because a document was uploaded instead.
     * @var string
     */
    private ?string $reasonText = null;

    /**
     * References completed their reason input.
     * Requirement depends on award setting.
     * @var bool
     */
    private bool $referenceReasonComplete = false;

    /**
     * All required references have been selected.
     * Requirement depends on award setting.
     * @var bool
     */
    private bool $referencesSelected = false;

    public function __construct()
    {
        parent::__construct('award_nomination');
    }

    public function getAllowVote(): bool
    {
        return $this->allowVote;
    }

    public function getApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return int
     */
    public function getAwardId(): int
    {
        return $this->awardId;
    }

    /**
     * @return bool
     */
    public function getCompleted(): bool
    {
        return $this->completed;
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
    public function getNominatorId(): int
    {
        return $this->nominatorId;
    }

    public function getNominatedId(): int
    {
        return $this->nominatedId;
    }

    public function getReasonComplete(): bool
    {
        return $this->reasonComplete;
    }

    public function getReasonDocument(): int
    {
        return $this->reasonDocument;
    }

    public function getReasonText(): string
    {
        return $this->reasonText ?? '';
    }

    public function getReferenceReasonComplete(): bool
    {
        return $this->referenceReasonComplete;
    }

    public function getReferencesSelected(): bool
    {
        return $this->referencesSelected;
    }

    /**
     *
     * @param bool $allowVote
     * @return self
     */
    public function setAllowVote(bool $allowVote): self
    {
        $this->allowVote = $allowVote;
        return $this;
    }

    /**
     *
     * @param bool $approved
     * @return self
     */
    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;
        return $this;
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
     * @param bool $completed
     */
    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @param int $cycleId
     */
    public function setCycleId(int $cycleId): self
    {
        $this->cycleId = $cycleId;
        return $this;
    }

    public function setDocumentId(int $documentId): self
    {
        $this->documentId = $documentId;
        return $this;
    }

    public function setNominatorId(int $nominatorId): self
    {
        $this->nominatorId = $nominatorId;
        return $this;
    }

    /**
     * @param int $participantId
     */
    public function setNominatedId(int $participantId): self
    {
        $this->nominatedId = $participantId;
        return $this;
    }

    public function setReasonComplete(bool $reasonComplete)
    {
        $this->reasonComplete = $reasonComplete;
        return $this;
    }

    public function setReasonDocument(int $reasonDocument)
    {
        $this->reasonDocument = $reasonDocument;
        return $this;
    }

    public function setReasonText(string $reasonText)
    {
        $this->reasonText = $reasonText;
        return $this;
    }

    public function setReferenceReasonComplete(bool $referenceReasonComplete)
    {
        $this->referenceReasonComplete = $referenceReasonComplete;
        return $this;
    }

    public function setReferencesSelected(bool $referencesSelected)
    {
        $this->referencesSelected = $referencesSelected;
        return $this;
    }

}
