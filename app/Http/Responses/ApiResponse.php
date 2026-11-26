<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    /**
     * Return a standardized success JSON response.
     *
     * @param  array<string, mixed>  $meta
     */
    public static function success(
        mixed $data = null,
        string $message = 'Operación completada con éxito.',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (! empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $statusCode);
    }

    /**
     * Return a standardized error JSON response.
     */
    public static function error(
        string $message = 'Ha ocurrido un error.',
        mixed $errors = null,
        int $statusCode = 400
    ): JsonResponse {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $statusCode);
    }

    /**
     * Return a standardized paginated JSON response.
     *
     * @param  LengthAwarePaginator<int|string, mixed>  $paginator
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'Datos recuperados con éxito.'
    ): JsonResponse {
        return self::success(
            data: $paginator->items(),
            message: $message,
            statusCode: 200,
            meta: [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more_pages' => $paginator->hasMorePages(),
            ]
        );
    }
}
