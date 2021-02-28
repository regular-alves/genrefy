<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyUser extends Model
{
    use HasFactory;
    protected $table = 'spotify_users';
    protected $fillable = [
        'spotify_id',
        'authorization',
        'token',
        'refresh_token',
    ];
}
