<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractNote extends Model
{
     protected $fillable = [
        'contract_id',
        'notes',
        'created_by',
    ];

    public function clientnotes()
    {        
       return  $this->hasOne(Profile::class,'user_id', 'created_by');               
    }
}
