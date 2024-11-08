<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    // Define the table associated with the model
    protected $table = 'attendance';

    // Specify the fillable fields to prevent mass assignment vulnerabilities
    protected $fillable = [
        'user_unique_id',
        'scanned_at', 
        'count_attendance'
    ];

}