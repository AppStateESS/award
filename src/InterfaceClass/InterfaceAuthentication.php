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

namespace award\InterfaceClass;

interface InterfaceAuthorization
{

    /**
     * Returns the email address of the user.
     * @return string
     */
    public function getEmail(): string;

    /**
     * Returns string for the user to click to log in.
     * This could be a link, button, etc.
     * @return string
     */
    public function getLogin(): string;

    /**
     * Returns string for the user to click to log out.
     * This could be a link, button, etc.
     * @return string
     */
    public function getLogout(): string;

    /**
     * Returns an array containing the user's first and last name:
     * Example:
     * return ['first'=>'Joe', 'last'=>'Blow'];
     *
     * The first name should be the user's preferred/chosen name.
     * @return array
     */
    public function getName(): array;

    /**
     * Returns if the current visitor is authenticated through
     * this service.
     * @return bool
     */
    public function isLoggedIn(): bool;
}
