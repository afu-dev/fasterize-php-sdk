<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk\Endpoints;

use AFU\FasterizeSdk\Mediators\JsonResponseMediator;
use AFU\FasterizeSdk\Sdk;
use Psr\Http\Message\ResponseInterface;

final readonly class Posts
{
    public function __construct(private Sdk $sdk)
    {
    }

    public function all(): array
    {
        return JsonResponseMediator::getContent($this->sdk->getHttpClient()->get("/posts"));
    }

    public function get(int $id): array
    {
        return JsonResponseMediator::getContent($this->sdk->getHttpClient()->get("/posts/$id"));
    }
}
