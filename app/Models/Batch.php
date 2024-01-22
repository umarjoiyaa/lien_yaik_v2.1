<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function finalChecking()
    {
        return $this->hasOne(FinalChecking::class, 'batch_id');
    }

    public function warehouseIn()
    {
        return $this->hasOne(WarehouseIn::class, 'batch_id');
    }

    public function productionOrder()
    {
        return $this->hasOne(ProductionOrder::class, 'batch_id');
    }
}
