<?php

namespace App\Features\Posts\Domains\Constants;

interface PostConstants
{
    const CREATED = 1;
    const INPROGRESS = 2;
    const ACTIVE = 3;

    const STATUES = [
        self::CREATED => "Created",
        self::INPROGRESS => "In Progress",
        self::ACTIVE => "Active",
    ];
}
