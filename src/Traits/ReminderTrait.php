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

trait ReminderTrait
{

    /**
     * Date of the last email reminder sent
     * @var \DateTime
     */
    protected ?\DateTime $lastReminder = null;

    public function getLastReminder(string $format = null)
    {
        return $this->lastReminder ? $this->lastReminder->format($format ?? 'Y-m-d H:i:s') : null;
    }

    public function setLastReminder(string $datetime)
    {
        $this->lastReminder = new \DateTime($datetime);
    }

    public function stampLastReminder()
    {
        $this->lastReminder = new \DateTime();
    }

}
