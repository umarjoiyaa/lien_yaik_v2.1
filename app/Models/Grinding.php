<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grinding extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function pelletes()
    {
        return $this->hasManyThrough(Pellete::class, GrindingGoodPellete::class, 'gr_id', 'id', 'id', 'pellete_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }
}
