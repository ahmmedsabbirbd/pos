<?php

namespace App\Traits;

trait HttpResponses {

    protected function success(string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message
        ], $code);
    }

    protected function error(string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message
        ], $code);
    }
}
