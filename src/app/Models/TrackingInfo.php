<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class TrackingInfo
 *
 * @property integer $id
 * @property string $fingerprint
 * @property string $url
 * @property string $ip
 * @property string $client
 * @property integer $plugin_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Plugin $plugin
 */
final class TrackingInfo extends Model
{
    use HasFactory;

    protected $table = 'tracking_info';

    protected $fillable = [
        'fingerprint',
        'url',
        'ip',
        'client',
        'plugin_id',
    ];

    public function plugin()
    {
        return $this->belongsTo(
            Plugin::class,
            'plugin_id',
            'id'
        );
    }
}
