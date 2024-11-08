<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use HasFactory, SoftDeletes;

    // Define the table associated with the model
    protected $table = 'register';

    // Specify the fillable fields to prevent mass assignment vulnerabilities
    protected $fillable = [
        'user_unique_id',
        'fullname', 
        'email', 
        'mobile', 
        'gov_id', 
        'arrival_date', 
        'arrival_time', 
        'working_place', 
        'stay_selected_at', 
        'departuring_place', 
        'departuring_date', 
        'departuring_time', 
        'adults', 
        'children', 
        'accomodation_request'
    ];

}