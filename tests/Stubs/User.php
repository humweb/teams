<?php

namespace Humweb\Teams\Tests\Stubs;
use Humweb\Teams\Traits\TeamUserTrait;
use Illuminate\Foundation\Auth\User as LaravelUser;

class User extends LaravelUser
{
    use TeamUserTrait;
}