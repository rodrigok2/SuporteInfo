<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'vip';

    protected $fillable = [
        'contrato_id_sul',
        'ultima_os_notificada',
    ];
}
