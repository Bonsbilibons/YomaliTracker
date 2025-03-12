<?php

namespace App\Repositories;

use App\Models\Plugin;
use Illuminate\Database\Eloquent\Model;

final class PluginRepository
{
    public function getByIdentifier(string $identifier): Plugin|Model|null
    {
        return Plugin::query()->firstWhere('identifier', '=', $identifier);
    }
}
