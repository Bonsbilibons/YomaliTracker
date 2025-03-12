<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class ReceiveTrackingInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'plugin-identifier' => [
                'required',
                'string',
                'exists:plugins,identifier'
            ],
            'host' => [
                'required',
                'string'
            ],
            'url' => [
                'required',
                'string'
            ],
            'fingerprint' => [
                'required',
                'string'
            ],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        $data['ip'] = $this->getClientIp();
        $data['client'] = $this->header('User-Agent');

        return $data;
    }
}
