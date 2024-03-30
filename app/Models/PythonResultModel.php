<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PythonResultModel extends Model
{
    protected $table = 'python_result_models';

    protected $fillable = ['class_name', 'softmax_value', 'timestamp'];

    protected $guarded = ['id'];

    public $timestamps = false;
}
