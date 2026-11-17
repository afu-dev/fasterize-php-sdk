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

    private ClientInterface $httpClient;

    private RequestFactoryInterface $requestFactoryInterface;

    private StreamFactoryInterface $streamFactoryInterface;

    public function __construct(
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactoryInterface = null,
        ?StreamFactoryInterface $streamFactoryInterface = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactoryInterface = $requestFactoryInterface ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactoryInterface = $streamFactoryInterface ?? Psr17FactoryDiscovery::findStreamFactory();
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
