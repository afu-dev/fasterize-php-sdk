<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

final readonly class Options
{
    public const string DEFAULT_URI = "https://api.fasterize.com";

    /**
     * @param array{client_builder: ClientBuilder, uri_factory: UriFactoryInterface} $options
     */
    public function __construct(
        private array $options = [],
    ) {
    }

    public function getClientBuilder(): ClientBuilder
    {
        return $this->options["client_builder"] ?? new ClientBuilder();
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->options["uri_factory"] ?? Psr17FactoryDiscovery::findUriFactory();
    }

    public function getUri(): UriInterface
    {
        return $this->getUriFactory()->createUri($this->options["uri"] ?? self::DEFAULT_URI);
    }
}
