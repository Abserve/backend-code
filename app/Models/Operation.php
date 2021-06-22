<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;
    public function missions()
    {
        return $this->belongsTo('Mission');
    }
    //////////////op_id
    public function articles()
    {
        return $this->hasMany(Article::class, 'op_id', 'id');
    }
    public function datep()
    {
        return $this->hasMany(datepointages::class, 'operationn_id', 'id');
    }

    protected $fillable = [
        'date_operation','qte_controlée',
        'qte_ok','qte_notOk',
        'détail_defaut',
        'buch_num','Delivery_num','remarque'
    ];
}
