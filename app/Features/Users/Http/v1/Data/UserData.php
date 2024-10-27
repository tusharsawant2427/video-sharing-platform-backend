<?php

namespace App\Features\Users\Http\v1\Data;

use App\Features\Users\Domains\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public ?string $identifier,

        public string $name,
        #[Email]
        public string $email,

        #[Password(min: 6)]
        public ?string $password,

        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    public static function rules(): array
    {
        /**
         * @var User $user
         */
        $user = Auth::guard('api')->user();
        if (!empty($user)) {
            return [
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable',
            ];
        }

        return [
            'password' => 'required|min:6',
        ];
    }
}
