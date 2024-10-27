<?php

namespace App\Features\Users\Domains\Constants;

interface UserConstants
{
    const CREATE_VALIDATION_RULES = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required'
    ];

    const UPDATE_VALIDATION_RULES = [
        'name' => 'required|max:255',
        'email' => 'email|unique:users,email,',
    ];
}
