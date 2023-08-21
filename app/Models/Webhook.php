<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $fillable = [
        'module',
        'url',
        'method',
        'created_by',
    ];

    public static function module()
    {
        $webmodule = [
            'New Rotas' => 'New Rotas',
            'Cancel Rotas' => 'Cancel Rotas',
            'Rotas Time Change' => 'Rotas Time Change',
            'Days Off' => 'Days Off',
            'New Availability' => 'New Availability',
        ];
        return $webmodule;
    }

    public static function method()
    {
        $method = [
            'POST' => 'POST',
            'GET'  => 'GET',
        ];
        return $method;
    }
}
