<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PressDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['batch_id', 'machine_id', 'press_id', 'start_time', 'end_time', 'status'];
}
