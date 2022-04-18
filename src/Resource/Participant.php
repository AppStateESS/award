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
 * @table particpant
 */
class Participant extends \award\AbstractResource
{

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $hash;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string
     */
    private string $password;

    public function __construct()
    {
        parent::__construct('award_participant');
    }

    /**
     * Creates a random hash.
     */
    public function createHash()
    {
        $this->setHash(md5(microtime() . rand()));
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName ?? '';
    }

    /**
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName ?? '';
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     *
     * @param string $hash
     * @return self
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

}
