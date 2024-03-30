<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YourModel extends Model
{
    protected $table = 'personalinfo'; // Replace 'your_table' with the actual table name
    protected $fillable = ['name', 'number', 'age', 'dpt', 'gpa']; // Replace with the actual column names you want to fill
}

