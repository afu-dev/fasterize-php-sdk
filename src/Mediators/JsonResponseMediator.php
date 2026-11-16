<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk\Mediators;

use Psr\Http\Message\ResponseInterface;

final readonly class JsonResponseMediator
{
    public static function getContent(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
