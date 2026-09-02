<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filme extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'nome', 'sinopse', 'ano', 'categoria', 'capa', 'trailer'];

    protected $casts = [
        'ano' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCapaUrlAttribute()
    {
        return $this->capa ? asset('storage/' . $this->capa) : asset('img/sem-capa.png');
    }
}