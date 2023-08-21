<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
	protected $fillable = [
        'employee',
        'contract_name',
        'subject',
        'value',
        'type',
        'start_date',
        'end_date',
        'notes',
        'edit_status',
        'contract_description',
        'created_by',
    ];

    public static function editstatus()
    {

        $editstatus = [
            'accept' => 'Accept',
            'decline' => 'Decline',

        ];
        return $editstatus;
    }

   public function employees()
    {
        return $this->hasOne('App\Models\User', 'id', 'employee');
    }

    public function types()
    {
        return $this->hasOne('App\Models\ContractType', 'id', 'type');
    }

    public static function getContractSummary($contracts)
    {
        $total = 0;

        foreach($contracts as $contract)
        {
            $total += $contract->value;
        }

        return \Auth::user()->priceFormat($total);
    }

}
