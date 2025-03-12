<?php

namespace App\Http\Controllers\Api;

use App\Dto\Api\ReceiveTrackingInfoDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReceiveTrackingInfoRequest;
use App\Services\Api\TrackingInfoService;
use Illuminate\Http\JsonResponse;

final class YomaliTrackerController extends Controller
{
    public function __construct(private readonly TrackingInfoService $trackingService)
    {
    }

    public function receiveTrackingInfo(ReceiveTrackingInfoRequest $request): JsonResponse
    {
        $this->trackingService->receiveTackingInfo(ReceiveTrackingInfoDto::fromArray($request->validated()));

        return response()->json([
           'success' => true
        ]);
    }
}
