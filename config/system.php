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
/**
 * Defines the type of authorization types used. AppstateShibboleth is
 * obviously specific to this university. Local is expected to be in the zero slot
 * and defines a participant who has entered their email and password locally in
 * the system.
 */
define('AWARD_AUTH_TYPES', [0 => 'local', 1 => 'AppstateShibboleth']);
define('AWARD_DEFAULT_VOTE_TYPE', 'choice');
