<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineApiSum extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['machine_id', 'batch_id', 'press_id', 'sum_cavity', 'date'];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }
}
