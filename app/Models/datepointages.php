<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class datepointages extends Model
{
    use HasFactory;
    public function operations(){
        return $this->belongsToMany(Operation::class);
    }
    public function users(){
        return $this->belongsToMany(User::class);
    }
  
}
