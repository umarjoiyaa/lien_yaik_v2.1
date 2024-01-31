<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialInDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['mi_id', 'qty', 'item_id'];

    public function item()
    {
        return $this->belongsTo(Material::class, 'item_id', 'id');
    }
}
