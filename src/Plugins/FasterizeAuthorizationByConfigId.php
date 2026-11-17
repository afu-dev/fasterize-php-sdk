<?php

namespace AFU\FasterizeSdk\Plugins;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final readonly class FasterizeAuthorizationByConfigId implements Plugin
{
    public function __construct(
        private string $configId,
        private string $secretToken,
    ) {
    }

    #[\Override]
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $uri = $request->getUri();
        $path = urldecode($uri->getPath());

        $updatedPath = str_replace("{:config_id}", $this->configId, $path);

        return $next(
            $request
                ->withUri($uri->withPath($updatedPath))
                ->withHeader("Authorization", $this->secretToken)
        );
    }
}
