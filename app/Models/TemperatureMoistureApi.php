<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemperatureMoistureApi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['machine_id','temperature','moisture','start_time','end_time'];

}
