<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ClientBuilder
{
    /** @var list<Plugin> */
    private array $plugins = [];

    public function __construct(
        private ?ClientInterface $httpClient = null,
        private ?RequestFactoryInterface $requestFactoryInterface = null,
        private ?StreamFactoryInterface $streamFactoryInterface = null,
    ) {
        if ($this->httpClient === null) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        if ($this->requestFactoryInterface === null) {
            $this->requestFactoryInterface = Psr17FactoryDiscovery::findRequestFactory();
        }

        if ($this->streamFactoryInterface === null) {
            $this->streamFactoryInterface = Psr17FactoryDiscovery::findStreamFactory();
        }
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        $finalClient = (new PluginClientFactory())->createClient($this->httpClient, $this->plugins);

        return new HttpMethodsClient(
            $finalClient,
            $this->requestFactoryInterface,
            $this->streamFactoryInterface,
        );
    }

    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[] = $plugin;
    }
}
