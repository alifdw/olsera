<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $guarded = [];
    use HasFactory;

    
    public function pajak()
    {
        return $this->hasMany(Pajak::class);
    }
}
