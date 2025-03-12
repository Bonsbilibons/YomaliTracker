<?php

namespace App\Services\Api;

use App\Models\Plugin;
use App\Repositories\PluginRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class PluginService
{
    public function __construct(private readonly PluginRepository $pluginRepository)
    {
    }

    public function getByIdentifier(string $identifier): Plugin|Model
    {
        return Cache::remember($identifier, config('app.default_model_ttl'), function () use ($identifier) {
            return $this->pluginRepository->getByIdentifier($identifier);
        });
    }
}
