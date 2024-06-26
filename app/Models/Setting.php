<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_key',
        'user_id',
        'cv',
    ];

   
   public function user()
   {
       return $this->belongsTo(User::class);
   }

}
