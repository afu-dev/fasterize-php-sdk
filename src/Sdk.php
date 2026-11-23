<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk;

use AFU\FasterizeSdk\Endpoints\TrafficLimiter;
use AFU\FasterizeSdk\Plugins\FasterizeAuthorizationByConfigId;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;

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

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    public function trafficLimiter(): TrafficLimiter
    {
        return new TrafficLimiter($this);
    }
}
