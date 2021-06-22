<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    public function operations()
    {
        return $this->hasMany(Operation::class, 'mission_id', 'id');
    } 
   public function sites()
    {
        return $this->hasMany(Site::class, 'missionn', 'id');
    }

    protected $fillable = [
        'date_declanche',
        'date_end',
        'qt_totale_article',
        'description',
        'nbr_operateur',
        'jours_qte',
    ];
}
