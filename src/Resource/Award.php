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
     * @var string
     */
    private string $description;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var int
     */
    private int $nominatedDocRequired;

    /**
     * @var bool
     */
    private bool $publicView;

    /**
     * @var int
     */
    private int $referenceDocRequired;

    /**
     * @var int
     */
    private int $referencesAmount;

    /**
     * @var bool
     */
    private bool $selfNominate;

    /**
     * @var int
     */
    private int $winnerAmount;

    /**
     * @returns string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @returns int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @returns int
     */
    public function getNominatedDocRequired(): int
    {
        return $this->nominatedDocRequired;
    }

    /**
     * @returns bool
     */
    public function getPublicView(): bool
    {
        return $this->publicView;
    }

    /**
     * @returns int
     */
    public function getReferenceDocRequired(): int
    {
        return $this->referenceDocRequired;
    }

    /**
     * @returns int
     */
    public function getReferencesAmount(): int
    {
        return $this->referencesAmount;
    }

    /**
     * @returns bool
     */
    public function getSelfNominate(): bool
    {
        return $this->selfNominate;
    }

    /**
     * @returns string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @returns int
     */
    public function getWinnerAmount(): int
    {
        return $this->winnerAmount;
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
     * @param int $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;
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
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->title = $name;
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
