<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractAttachment extends Model
{
	protected $fillable = [
        'contract_id',
        'file_name',
        'file_path',
        'created_by',
    ];

     public function clientattattchment()
    {        
       return  $this->hasOne(Profile::class,'user_id', 'created_by');               
    }
}
