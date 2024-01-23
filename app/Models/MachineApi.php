<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineApi extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['machine_id', 'batch_id', 'press_id', 'cavity', 'drop_time', 'end_time'];
}
