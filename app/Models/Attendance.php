<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    public $timestamps = false;

    protected $table = 'attendances';


    protected $fillable = [
        'student_id', 
        'date',       
        'status'      
    ];

    public function student()
    {
        return $this->belongsTo(Yourmodel::class, 'student_id');
    }
}

