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

use award\Traits\TrackedTrait;

/**
 * @table particpant
 */
class Participant extends \award\AbstractResource
{

    use TrackedTrait;

    /**
     * @var bool
     */
    private bool $active = false;

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
        $this->constructDates();
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
     * Get the value of active
     *
     * @return  bool
     */
    public function getActive()
    {
        return $this->active;
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
     * Encrypts the raw password for saving in the database.
     * @param string $password
     */
    public function hashPassword(string $password): self
    {
        return $this->setPassword(password_hash($password, PASSWORD_BCRYPT));
    }

    /**
     * Verifies the password param against the current object's password hash.
     * @param string $password
     * @return bool
     */
    public function isPassword(string $password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Set the value of active
     *
     * @param  bool  $active
     * @return  self
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
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
     * Set the encrypted password hash.
     * @param string $passwordHash
     * @return self
     */
    public function setPassword(string $passwordHash)
    {
        $this->password = $passwordHash;
        return $this;
    }

}
