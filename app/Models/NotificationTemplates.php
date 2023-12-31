<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplates extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'type', 'slug', 'created_by'
    ];

    public static $types = [
        'slack' => 'Slack',
        'telegram' => 'Telegram',
    ];

}
