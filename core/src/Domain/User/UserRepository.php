<?php

namespace App\Domain\User;

interface UserRepository
{
    public function obtainUser(): User;
}
