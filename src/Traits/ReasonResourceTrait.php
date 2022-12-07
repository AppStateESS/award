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

namespace award\Traits;

class ReasonResourceTrait
{

    /**
     * Nominator completed the reason for the nomination.
     * Requirement depends on award setting.
     * @var bool
     */
    protected bool $reasonComplete = false;

    /**
     * ID of the award_document used for the nomination reason.
     * @var int
     */
    protected int $reasonDocument = 0;

    /**
     * The reason for the nomination. May be empty due to not
     * required or because a document was uploaded instead.
     * @var string
     */
    protected ?string $reasonText = null;

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

}
