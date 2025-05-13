<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'endpoint',
        'petition_body',
        'response_code',
        'response_body',
        'client_ip',
        'timestamp',
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<int, string>
     */
    protected $casts = [
        'petition_body' => 'array', // If you expect JSON bodies
        'response_body' => 'array', // If your API primarily returns JSON
        'timestamp' => 'datetime',
    ];

    public $timestamps = false;
}
