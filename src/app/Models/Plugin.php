<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @class Plugin
 *
 * @property integer $id
 * @property string $name
 * @property string $host
 * @property string $identifier
 * @property string|null $period
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection<TrackingInfo> $trackingInfo
 */
final class Plugin extends Model
{
    use HasFactory;

    protected $table = 'plugins';

    protected $fillable = [
        'name',
        'host',
        'identifier',
        'period',
    ];

    public function trackingInfo()
    {
        return $this->hasMany(
            TrackingInfo::class,
            'plugin_id',
            'id'
        );
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function (Plugin $plugin) {
            Cache::forget($plugin->identifier);
        });

        self::updating(function (Plugin $plugin) {
            $originalIdentifier = $plugin->getOriginal('identifier');

            if ($originalIdentifier !== $plugin->identifier) {
                Cache::forget($originalIdentifier);
            }
        });

        self::deleting(function (Plugin $plugin) {
            Cache::forget($plugin->identifier);
        });

        self::saved(function (Plugin $plugin) {
            Cache::forget($plugin->identifier);
        });
    }
}
