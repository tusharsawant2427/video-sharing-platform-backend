<?php

namespace App\Features\Users\Http\v1\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        #[Email]
        public string $email,
        public string $password
    ) {}
}
