<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model {
	protected $table = 'packages';
	//protected $fillable = ['id','title','link','bidder','hided']; 
	//protected $fillable = ['id','title','link','bidder','cate_id','hided'];  //Insert cate_id

	protected $fillable = ['id','title','link','bidder','cate_id','hided'];  //Insert cate_id
	
	//public $timestamps = false;


}
