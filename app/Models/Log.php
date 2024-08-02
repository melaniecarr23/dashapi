<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'logs';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'message',
        'context',
        'type',
        'result',
        'status_code',
        'level',
        'token',
        'ip',
        'user_agent',
        'session',
        'origin',
        'path',
        'remote_host',
        'method',
        'port',
        'created_at',
        'updated_at'
    ];


}
