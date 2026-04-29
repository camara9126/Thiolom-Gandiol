<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session_caisse extends Model
{
    protected $fillable = [
        'user_id',
        'opened_at',
        'closed_at',
        'total_ventes',
        'total_encaisse',
        'nombre_ventes',
     ];
}
