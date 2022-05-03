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

namespace award\AbstractClass;

use award\InterfaceClass\InterfaceAuthorization;

abstract class AbstractAuthorization implements InterfaceAuthorization
{

    protected string $domain;

    public function getDomain()
    {
        return $this->domain;
    }

    public function isDomainMatch(string $email)
    {
        if (!isset($this->domain)) {
            throw new \Exception('Authorization domain not set');
        }
        $match = '@' . $this->domain;
        return preg_match("/$match$/", $email);
    }

    /**
     *
     * @param string $domain
     * @return self
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
        return $this;
    }

}
