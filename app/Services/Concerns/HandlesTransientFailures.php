<?php

namespace App\Services\Concerns;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Throwable;

trait HandlesTransientFailures
{
    /**
     * Retry requests that failed because of a connection error,
     * a server error or rate limiting. The final response is returned
     * as-is, so callers decide whether a failed response should throw.
     */
    protected function retryTransientFailures(PendingRequest $request): PendingRequest
    {
        return $request->retry(
            3,
            1000,
            fn (Throwable $e) => $e instanceof ConnectionException
                || ($e instanceof RequestException && $this->isTransientFailure($e->response)),
            throw: false,
        );
    }

    protected function isTransientFailure(Response $response): bool
    {
        return $response->serverError() || $response->tooManyRequests();
    }
}
