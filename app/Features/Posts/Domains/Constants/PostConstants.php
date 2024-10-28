<?php

namespace App\Features\Posts\Domains\Constants;

interface PostConstants
{
    const CREATED = 1;
    const ACTIVE = 2;

    const STATUES = [
        self::CREATED => "Created",
        self::ACTIVE => "Active",
    ];
}
