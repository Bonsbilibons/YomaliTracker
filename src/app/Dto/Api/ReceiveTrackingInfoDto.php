<?php

namespace App\Dto\Api;

use App\Abstract\Dto\BaseDto;

final class ReceiveTrackingInfoDto extends BaseDto
{
    public function __construct(
        public readonly string $pluginIdentifier,
        public readonly string $host,
        public readonly string $url,
        public readonly string $fingerprint,
        public readonly string $ip,
        public readonly string $client,
    )
    {
    }

    public static function fromArray(array $params): BaseDto
    {
       return new self(
           pluginIdentifier: $params['plugin-identifier'],
           host: $params['host'],
           url: $params['url'],
           fingerprint: $params['fingerprint'],
           ip: $params['ip'],
           client: $params['client'],
       );
    }
}
