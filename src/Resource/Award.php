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
 * @table award
 */
class Award extends AbstractResource
{

    /**
     * Determines if award is accessible by participants.
     * @var bool
     */
    private bool $active = false;

    /**
     * If true, all nominations require approval before moving forward.
     * @var bool
     */
    private bool $approvalRequired = false;

    /**
     * If true, the nominator is listed in award details.
     * @var bool
     */
    private bool $creditNominator = false;

    /**
     * Indicates how often the award is offered:
     * - monthly
     * - yearly
     * - random
     *
     * @var string
     */
    private string $cycleTerm = 'yearly';

    /**
     * The default vote type passed to cycles.
     * @var string
     */
    private string $defaultVoteType = AWARD_DEFAULT_VOTE_TYPE;

    /**
     *
     * @var bool
     */
    private bool $deleted = false;

    /**
     * A description of the award.
     *
     * @var string
     */
    private string $description;

    /**
     * Method by which a winner is determined.
     * 0 - popular vote
     * 1 - judged
     * @var int
     */
    private int $judgeMethod = 1;

    /**
     * If TRUE, nominator must submit a letter.
     * @var bool
     */
    private bool $nominationReasonRequired = false;

    /**
     * Id of participant that submitted the award.
     * @var int
     */
    private int $participantId = 0;

    /**
     * If TRUE, the award details are shown publicly.
     * @var bool
     */
    private bool $publicView = true;

    /**
     * If TRUE, references must submit a letter.
     * @var bool
     */
    private bool $referenceReasonRequired = false;

    /**
     * @var int
     */
    private int $referencesRequired = 0;

    /**
     * @var bool
     */
    private bool $selfNominate = false;

    /**
     * @var bool
     */
    private bool $tipNominated = false;

    /**
     * @var string
     */
    private string $title;

    /**
     * The winner total for this award.
     * @var int
     */
    private int $winnerAmount = 1;

    public function __construct()
    {
        parent::__construct('award_award');
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getApprovalRequired(): bool
    {
        return $this->approvalRequired;
    }

    /**
     * Get the value of creditNominator
     *
     * @return  bool
     */
    public function getCreditNominator(): bool
    {
        return $this->creditNominator;
    }

    /**
     *
     * @return string
     */
    public function getCycleTerm(): string
    {
        return $this->cycleTerm;
    }

    public function getDefaultVoteType(): string
    {
        return $this->defaultVoteType;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * @return int
     */
    public function getJudgeMethod(): int
    {
        return $this->judgeMethod;
    }

    /**
     * @return bool
     */
    public function getNominationReasonRequired(): bool
    {
        return $this->nominationReasonRequired;
    }

    /**
     * @return int
     */
    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    /**
     * Returns the resource's values without some administrative data.
     */
    public function getParticipantValues()
    {
        $ignore = [
            'active', 'approvalRequired', 'creditNominator',
            'deleted', 'judgeMethod', 'tipNominate', 'winnerAmount',
            'defaultVoteType'
        ];
        return $this->getValues($ignore);
    }

    /**
     * @return bool
     */
    public function getPublicView(): bool
    {
        return $this->publicView;
    }

    /**
     * @return int
     */
    public function getReferenceReasonRequired(): bool
    {
        return $this->referenceReasonRequired;
    }

    /**
     * @return int
     */
    public function getReferencesRequired(): int
    {
        return $this->referencesRequired;
    }

    /**
     * @return bool
     */
    public function getSelfNominate(): bool
    {
        return $this->selfNominate;
    }

    /**
     * Get the value of tipNominated
     *
     * @return  bool
     */
    public function getTipNominated()
    {
        return $this->tipNominated;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * @return int
     */
    public function getWinnerAmount(): int
    {
        return $this->winnerAmount;
    }

    /**
     *
     * @param bool $active
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function setApprovalRequired(bool $approvalRequired)
    {
        $this->approvalRequired = $approvalRequired;
        return $this;
    }

    /**
     * Set the value of creditNominator
     *
     * @param bool $creditNominator
     * @return self
     */
    public function setCreditNominator(bool $creditNominator): self
    {
        $this->creditNominator = $creditNominator;

        return $this;
    }

    /**
     *
     * @param string $cycleTerm
     * @return self
     */
    public function setCycleTerm(string $cycleTerm): self
    {
        $this->cycleTerm = $cycleTerm;
        return $this;
    }

    /**
     *
     * @param string $defaultVoteType
     * @return self
     */
    public function setDefaultVoteType(string $defaultVoteType): self
    {
        $this->defaultVoteType = $defaultVoteType;
        return $this;
    }

    public function setDeleted($deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $judgeMethod
     * @return self
     */
    public function setJudgeMethod(int $judgeMethod): self
    {
        $this->judgeMethod = $judgeMethod;
        return $this;
    }

    /**
     * @param int $nominatedDocRequired
     */
    public function setNominationReasonRequired(bool $nominationReasonRequired): self
    {
        $this->nominationReasonRequired = $nominationReasonRequired;
        return $this;
    }

    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;
        return $this;
    }

    /**
     * @param bool $publicView
     */
    public function setPublicView(bool $publicView): self
    {
        $this->publicView = $publicView;
        return $this;
    }

    /**
     * @param bool $referenceReasonRequired
     */
    public function setReferenceReasonRequired(bool $referenceReasonRequired): self
    {
        $this->referenceReasonRequired = $referenceReasonRequired;
        return $this;
    }

    /**
     * @param int $referencesRequired
     */
    public function setReferencesRequired(int $referencesRequired): self
    {
        $this->referencesRequired = $referencesRequired;
        return $this;
    }

    /**
     * @param bool $selfNominate
     */
    public function setSelfNominate(bool $selfNominate): self
    {
        $this->selfNominate = $selfNominate;
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

    /**
     * Set the value of tipNominated
     *
     * @param  bool  $tipNominated
     *
     * @return  self
     */
    public function setTipNominated(bool $tipNominated): self
    {
        $this->tipNominated = $tipNominated;

        return $this;
    }

    /**
     * @param int $winnerAmount
     */
    public function setWinnerAmount(int $winnerAmount): self
    {
        $this->winnerAmount = $winnerAmount;
        return $this;
    }

}
