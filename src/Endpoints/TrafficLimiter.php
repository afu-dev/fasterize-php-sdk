<?php

declare(strict_types=1);

namespace AFU\FasterizeSdk\Endpoints;

use AFU\FasterizeSdk\Mediators\JsonResponseMediator;
use AFU\FasterizeSdk\Sdk;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-type TrafficLimiterShape = array{
 *      traffic_limiter: array{
 *        installed: boolean, settings: array{
 *          enabled: boolean,
 *          parameters: array{
 *            bypass_by_client_ip: array,
 *            bypass_by_request_url: array,
 *            max_sessions: int,
 *            overflow_page: string,
 *            ttl: int
 *          },
 *        },
 *      },
 *      waiting_rooms: array{
 *        installed: boolean,
 *         settings: array{
 *           enabled: boolean,
 *           parameters: array{
 *             bypass_audience: array,
 *             rooms: list{
 *               array{
 *                 description: string,
 *                 enabled: boolean,
 *                 excluded_paths: list{
 *                   array{
 *                     enabled: boolean,
 *                     label: string,
 *                     target: list{string},
 *                   },
 *                 },
 *                 mode: string,
 *                 name: string,
 *                 path: string,
 *                 queue_all: boolean,
 *                 queueing_method: string,
 *                 redirection_page: string,
 *                 session_duration: int,
 *                 session_renewal: boolean,
 *                 total_active_users: int,
 *               },
 *             }
 *           }
 *         }
 *       }
 *     }
 */
final readonly class TrafficLimiter
{
    public function __construct(private Sdk $sdk)
    {
    }

    /**
     * @return TrafficLimiterShape
     */
    public function get(): array
    {
        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->get(
                "/v2/configs/{:config_id}/apps/traffic-limiter"
            ),
        );
    }

    /**
     * @param TrafficLimiterShape $body
     */
    public function put(array $body): array
    {
        return JsonResponseMediator::getContent(
            $this->sdk->getHttpClient()->put(
                "/v2/configs/{:config_id}/apps/traffic-limiter",
                body: json_encode($body, JSON_THROW_ON_ERROR)
            ),
        );
    }
}
