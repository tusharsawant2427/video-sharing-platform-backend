<?php

namespace App\Helpers\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class Utils A set of utility methods
 * @package App\Http\Helper
 */
class Utils
{
    /**
     * Validates the data with the given validation rules
     * @param array<mixed> $validationRules
     * @param array<mixed> $data
     * @return array<mixed>
     * @throws ValidationException
     */
    public static function validateOrThrow(array $validationRules, array $data): array
    {

        $validator = Validator::make($data, $validationRules);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->getMessageBag()->toArray());
        }
        return $validator->validated();
    }
}
