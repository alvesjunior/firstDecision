<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        ?string $message = null,
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        $payload = [
            'data' => $this->normalize($data),
            'message' => $message,
            'errors' => null,
        ];

        if ($data instanceof ResourceCollection || $data instanceof LengthAwarePaginator) {
            $payload['meta'] = $this->extractMeta($data);
        }

        return response()->json($payload, $status);
    }

    protected function error(
        string $message,
        mixed $errors = null,
        int $status = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return response()->json([
            'data' => null,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    protected function created(mixed $data = null, ?string $message = 'Recurso criado com sucesso.'): JsonResponse
    {
        return $this->success($data, $message, Response::HTTP_CREATED);
    }

    protected function noContent(?string $message = 'Recurso removido com sucesso.'): JsonResponse
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'errors' => null,
        ], Response::HTTP_OK);
    }

    private function normalize(mixed $data): mixed
    {
        if ($data instanceof JsonResource) {
            return $data->resolve();
        }

        if ($data instanceof LengthAwarePaginator) {
            return $data->items();
        }

        return $data;
    }

    private function extractMeta(mixed $data): array
    {
        $paginator = $data instanceof ResourceCollection ? $data->resource : $data;

        if (! $paginator instanceof LengthAwarePaginator) {
            return [];
        }

        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
