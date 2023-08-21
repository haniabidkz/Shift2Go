<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    protected $table = 'leave_rules';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'message',
        'leave_rules_json',
        'created_at'
    ];

    public function getRuleDate() {
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $start_date = date("<b><big>d</big></b> M Y", strtotime($start_date));
        $end_date = date("<b><big>d</big></b> M Y", strtotime($end_date));
        return $start_date.' - '.$end_date;
    }
}
