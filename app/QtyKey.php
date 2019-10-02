<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QtyKey extends Model {

	//protected $table ='qtyKey';
	protected $table ='qtykey';
	protected $fillable = ['id','role','qty'];

}
