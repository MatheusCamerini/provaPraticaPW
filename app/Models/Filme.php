<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filme extends Model{
    use SoftDeletes;
    protected $fillable = ['user_id', 'nome', 'sinopse', 'ano', 'categoria', 'capa', 'trailer'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}