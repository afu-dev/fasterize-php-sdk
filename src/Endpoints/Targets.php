<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk\Endpoints;

use AFU\FasterizeSdk\Mediators\JsonResponseMediator;
use AFU\FasterizeSdk\Sdk;

final readonly class Targets
{
    public function __construct(private Sdk $sdk)
    {
    }

    /**
     * @param array{_start?: int, _end?: int, _order?: "ASC"|"DESC", _sort?: string, q?: string} $query
     */
    public function getAll(array $query = []): array
    {
        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->get($this->sdk->uri("/v2/configs/{:config_id}/targets", $query)),
        );
    }

    public function get(string $targetId): array
    {
        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->get("/v2/configs/{:config_id}/targets/" . rawurlencode($targetId)),
        );
    }

    public function put(string $targetId, array $body): array
    {
        if (isset($body["id"])) {
            unset($body["id"]);
        }

        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->put(
                "/v2/configs/{:config_id}/targets/" . rawurlencode($targetId),
                body: json_encode($body, JSON_THROW_ON_ERROR),
            ),
        );
    }
}
