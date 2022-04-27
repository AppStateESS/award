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
 * @table award
 */
class Award extends award\AbstractResource
{

    /**
     * If true, the nominator is listed in award details.
     * @var bool
     */
    private bool $creditNominator;

    /**
     * A description of the award.
     *
     * @var string
     */
    private string $description;

    /**
     * If TRUE, nominator must submit a letter.
     * @var bool
     */
    private bool $nominatedDocRequired;

    /**
     * If TRUE, the award details are shown publicly.
     * @var bool
     */
    private bool $publicView = true;

    /**
     * If TRUE, references must submit a letter.
     * @var bool
     */
    private bool $referenceDocRequired;

    /**
     * @var int
     */
    private int $referencesAmount;

    /**
     * @var bool
     */
    private bool $selfNominate;

    /**
     * @var bool
     */
    private bool $tipNominated;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var int
     */
    private int $winnerAmount;

    /**
     * Get the value of creditNominator
     *
     * @return  bool
     */
    public function getCreditNominator()
    {
        return $this->creditNominator;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getNominatedDocRequired(): int
    {
        return $this->nominatedDocRequired;
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
    public function getReferenceDocRequired(): int
    {
        return $this->referenceDocRequired;
    }

    /**
     * @return int
     */
    public function getReferencesAmount(): int
    {
        return $this->referencesAmount;
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
        return $this->title;
    }

    /**
     * @return int
     */
    public function getWinnerAmount(): int
    {
        return $this->winnerAmount;
    }

    /**
     * Set the value of creditNominator
     *
     * @param  bool  $creditNominator
     * @return  self
     */
    public function setCreditNominator(bool $creditNominator)
    {
        $this->creditNominator = $creditNominator;

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
     * @param int $nominatedDocRequired
     */
    public function setNominatedDocRequired(int $nominatedDocRequired): self
    {
        $this->nominatedDocRequired = $nominatedDocRequired;
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
     * @param int $referenceDocRequired
     */
    public function setReferenceDocRequired(int $referenceDocRequired): self
    {
        $this->referenceDocRequired = $referenceDocRequired;
        return $this;
    }

    /**
     * @param int $referencesAmount
     */
    public function setReferencesAmount(int $referencesAmount): self
    {
        $this->referencesAmount = $referencesAmount;
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
    public function setTipNominated(bool $tipNominated)
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
