<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiTrait
{

    /**
     * standardize our response
     *
     * @param $data
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    protected function response($data, array $errors = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'errors' => $errors,
            'data' => $data
        ], $code);
    }
}
