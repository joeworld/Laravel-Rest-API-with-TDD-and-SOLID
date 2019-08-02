<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

	protected $fillable = ['title','summary','content'];
	
	/**
	
	 * The owner of this delicious recipe
	
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	
	 */

	public function author(){
   	 	return $this->belongsTo(User::class);
	}


}