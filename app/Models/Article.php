<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public function operations()
    {
        return $this->belongsTo(Operation::class);
    }

    public function refobs()
    {
        return $this->hasMany(Refobs::class, 'refobs_id', 'id');
    }
}
