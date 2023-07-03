<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiHelper
{
    /**
     * Returns Json Response
     *
     * @param boolean     $success Status type for the response
     * @param string      $message Message to be sent along with the response
     * @param object|null $data    Data to be sent with the response.
     *
     * @return JsonResponse
     */
    public static function jsonTripple(bool $success, string $message, ?object $data)
    {
        if ($data == null) {
            $data = (object)[];
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
