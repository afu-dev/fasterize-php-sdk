<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk;

use AFU\FasterizeSdk\Endpoints\CachePurge;
use AFU\FasterizeSdk\Endpoints\Targets;
use AFU\FasterizeSdk\Endpoints\TrafficLimiter;
use AFU\FasterizeSdk\Plugins\FasterizeAuthorizationByConfigId;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriInterface;

final class Sdk
{
    private ClientBuilder $clientBuilder;

    public function __construct(
        private readonly string $configId,
        private readonly string $secretToken,
        private readonly Options $options = new Options(),
    ) {
        $this->clientBuilder = $this->options->getClientBuilder();

        $this->clientBuilder->addPlugin(new BaseUriPlugin($options->getUri()));

        $this->clientBuilder->addPlugin(
            new HeaderDefaultsPlugin([
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "User-Agent" => "FasterizePhpSdkClient/0.0.1",
            ]),
        );

        $this->clientBuilder->addPlugin(new FasterizeAuthorizationByConfigId($this->configId, $this->secretToken));
    }

    /**
     * @internal
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    /**
     * @internal Used by the endpoint classes only.
     *
     * @param array<string, scalar|null> $query null values are dropped
     */
    public function uri(string $path, array $query = []): UriInterface
    {
        return Psr17FactoryDiscovery::findUriFactory()->createUri($path)->withQuery(http_build_query($query));
    }

    public function trafficLimiter(): TrafficLimiter
    {
        return new TrafficLimiter($this);
    }

    public function targets(): Targets
    {
        return new Targets($this);
    }

    public function cachePurge(): CachePurge
    {
        return new CachePurge($this);
    }
}
