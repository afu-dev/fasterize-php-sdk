<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk\Endpoints;

use AFU\FasterizeSdk\Mediators\JsonResponseMediator;
use AFU\FasterizeSdk\Sdk;

final readonly class CachePurge
{
    public function __construct(private Sdk $sdk)
    {
    }

    public function purgeAll(?string $description = null, ?string $supportTicket = null): array
    {
        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->post(
                $this->sdk->uri(
                    "/v2/configs/{:config_id}/cache/purge",
                    ["description" => $description, "supportTicket" => $supportTicket],
                ),
                body: "{}",
            ),
        );
    }
}
