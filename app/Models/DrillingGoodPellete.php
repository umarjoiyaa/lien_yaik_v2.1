<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrillingGoodPellete extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function pellete()
    {
        return $this->belongsTo(Pellete::class, 'pellete_id', 'id');
    }
}
