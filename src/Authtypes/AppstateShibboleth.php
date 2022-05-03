<?php

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Authtypes;

use award\InterfaceClass\InterfaceAuthorization;

class AppstateShibboleth extends AbstractAuthorization
{

    protected $domain = 'appstate.edu';

}
