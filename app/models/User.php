<?php

namespace Horizon\App\Models;

/**
 * Class User
 * @package Horizon\App\Models
 * 
 * Generated from table: users
 */
class User
{
    /**
     * The table associated with the model
     * @var string
     */
    private $table = 'users';

    /**
     * The attributes that are mass assignable
     * @var array
     */
    private $fillable = ['email', 'password', 'created_at', 'role'];

    /**
     * The primary key for the model
     * @var string
     */
    private $primaryKey = 'id';

    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var \DateTime */
    private $created_at;

    /** @var array */
    private $role;


    /**
     * Get the value of id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of email
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the value of created_at
     * @return \DateTime
     */
    public function getCreated_at(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     * @param \DateTime $created_at
     * @return self
     */
    public function setCreated_at(\DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Get the value of role
     * @return array
     */
    public function getRole(): array
    {
        return $this->role;
    }

    /**
     * Set the value of role
     * @param array $role
     * @return self
     */
    public function setRole(array $role): self
    {
        $this->role = $role;
        return $this;
    }
}