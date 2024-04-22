<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Applicant extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'address',
        'city',
        'state',
        'zip_code',
        'cv',
        'description',
        'job_id',
        'user_id',
        'job_title',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
