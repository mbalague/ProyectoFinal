<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
	
	// Relación de uno a muchos / de uno a muchos
	public function comments(){
		//Ordenamos los comentaros por orden
		return $this->hasMany('App\Comment')->orderBy('id', 'desc');
	}
	
	// Relación de uno a muchos
	public function likes(){
		return $this->hasMany('App\Like');
	}
	
	// Relación de Muchos a Uno
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
}
