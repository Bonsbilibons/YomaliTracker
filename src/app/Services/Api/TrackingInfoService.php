<?php

namespace App\Services\Api;

use App\Abstract\Dto\BaseDto;
use App\Dto\Api\ReceiveTrackingInfoDto;
use App\Exceptions\ValidationException;
use App\Models\Plugin;
use App\Models\TrackingInfo;
use App\Repositories\TrackingInfoRepository;
use Illuminate\Support\Carbon;

final class TrackingInfoService
{
    public function __construct(
        private readonly TrackingInfoRepository $trackingInfoRepository,
        private readonly PluginService $pluginService
    )
    {
    }

    public function receiveTackingInfo(ReceiveTrackingInfoDto $receiveTrackingInfoDto): void
    {
        /** @var Plugin $plugin */
        $plugin = $this->pluginService->getByIdentifier($receiveTrackingInfoDto->get('plugin_identifier'));

        if ($plugin->host != $receiveTrackingInfoDto->get('host')) {
            throw new ValidationException();
        }

        $dataForInsert = $receiveTrackingInfoDto->toArray();
        $dataForInsert['plugin_id'] = $plugin->id;

        if (is_null($plugin->period)) {
            $this->create($dataForInsert);

            return;
        }

        /** @var TrackingInfo|null $lastPluginFingerprintTrackingInfo */
        $lastPluginFingerprintTrackingInfo = $plugin->trackingInfo()
            ->orderByDesc('created_at')
            ->where('fingerprint', '=', $receiveTrackingInfoDto->get('fingerprint'))
            ->first();

        if (is_null($lastPluginFingerprintTrackingInfo)) {
            $this->create($dataForInsert);

            return;
        }

        if (
            $lastPluginFingerprintTrackingInfo->created_at->diffInSeconds(Carbon::now())
                >= parsePeriodToSeconds($plugin->period)
        ) {
            $this->create($dataForInsert);
        }
    }

    public function create(array|BaseDto $data): TrackingInfo
    {
        $data = is_array($data) ? $data : $data->toArray();

        return $this->trackingInfoRepository->create($data);
    }
}
