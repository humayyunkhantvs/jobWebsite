<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;


    protected $fillable = [
        'title', 'description', 'address', 'email', 'website', 'phone', 'company', 'category', 'country', 'city', 'state', 'zip', 'salary', 'type', 'deadline', 'published_at', 'job_status'
    ];

    // public function applicants()
    // {
    //     return $this->hasMany(Applicant::class,'job_id','id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
