<?php

namespace App\Repositories;

use App\Models\TrackingInfo;

final class TrackingInfoRepository
{
    public function create(array $data): TrackingInfo
    {
        $trackingInfo = new TrackingInfo();
        $trackingInfo->fill($data);
        $trackingInfo->save();

        return $trackingInfo;
    }
}
