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

namespace award\View;

use award\AbstractClass\AbstractView;

class ReferenceView extends AbstractView
{

    public static function referencesNotRequired()
    {
        return self::centerCard('References not required', self::getTemplate('Error/ReferencesNotRequired'), 'danger');
    }

}
