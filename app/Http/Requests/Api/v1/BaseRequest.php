<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * The base request.
 *
 * All requests should extend this class.
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * Extend the default failedValidation method to return a JSON response
     * with the validation errors and success status.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if (request()?->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ]));
        }

        parent::failedValidation($validator);
    }
}
