<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pellete extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function batches()
    {
        return $this->belongsTo(Batch::class, 'batch', 'id');
    }
}
