<?php 
/*
*
*	@Author: Nam.Quach
*
*/
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
//Paginator
use Illuminate\Pagination\Presenter;
class ManageKeyController extends Controller{

	public function index(){

		if( !Auth::user() ){
			return redirect("");
		}

		$user = Auth::user();

		$lstKeyWord = Db::table("manage_keyword")
					->join("users", "users.id", "=", "manage_keyword.user_id");

		if( $user->level == 1 ){
			$lstKeyWord = $lstKeyWord->where("user_id", $user->id);
		}

		$lstKeyWord = $lstKeyWord->get();

		if($user->level == 1){

			return view("managekey.index");
		}else{
			return view("admin.managekeyword");
		}

		
	}

	public function getManageKeyWordList($pageRecored = 10, $currentPage = 1, $keySearch = null){
		$user = Auth::user();

		$lstKeyWord = Db::table("manage_keyword")
					->join("users", "users.id", "=", "manage_keyword.user_id")
					;

		if( $user->level == 1 ){
			$lstKeyWord = $lstKeyWord->where("user_id", $user->id);
		}

		if(!empty($keySearch) && $keySearch != 'null'){
			$lstKeyWord = $lstKeyWord->where("keyword", "like", "%$keySearch%");
		}

		$lstKeyWord = $lstKeyWord->skip($pageRecored * ($currentPage - 1))->take($pageRecored)->get();

		$totalRecord = $this->getTotalRecord($user->id);

		return json_encode([
			"data" => $lstKeyWord,
			"paginate" => [
				"totalRecord" => $totalRecord,
				"currentPage" => $currentPage,
				"pageRecored" => $pageRecored,
				"countPage"	=> ceil($totalRecord / $pageRecored)
			]
		]);
	}


	public function getTotalRecord($user_id){

		$lstKeyWord = Db::table("manage_keyword")
					->join("users", "users.id", "=", "manage_keyword.user_id")->get();
					;

		return count($lstKeyWord);
	}


	public function addManageKeyword(Request $request){

		$user = Auth::user();

		$keyword = $request->keyword;

		$result = DB::table("manage_keyword")->insert([
			"user_id" => $user->id,
			"keyword" => $keyword
		]);

		if(!$result){
			return json_encode([
				"status" => 0,
				"data" => "Thêm không thành công!",
			]);
		}else{
			return json_encode([
				"status" => 1,
				"data" => "Thêm thành công!",
			]);
		}
	}

	public function deleteManagerKeyWork($id){
		$result = DB::table("manage_keyword")->where("key_word_id", $id)->delete();

		if(!$result){
			return json_encode([
				"status" => 0,
				"data" => "Thêm không thành công!",
			]);
		}else{
			return json_encode([
				"status" => 1,
				"data" => "Thêm thành công!",
			]);
		}
	}

	public function editManageKeyWord($id){
		$result = DB::table("manage_keyword")->where("key_word_id", $id)->get();

		return json_encode($result);
	}

	public function deleteMultyKey(Request $request){
		if( is_array($request->id) ){
			foreach ($request->id as $key => $value) {
				DB::table("manage_keyword")->where("key_word_id", $value)->delete();
			}
		}
	}

	public function updateManageKeyWord(Request $request){
		$user = Auth::user();

		$result = DB::table("manage_keyword")->where("key_word_id", $request->id)->update([
			"user_id" => $user->id,
			"keyword" => $request->keyword
		]);

		if(!$result){
			return json_encode([
				"status" => 0,
				"data" => "Thêm không thành công!",
			]);
		}else{
			return json_encode([
				"status" => 1,
				"data" => "Thêm thành công!",
			]);
		}
	}
}

/*
	CREATE TABLE manage_keyword (
	key_word_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	keyword TEXT NOT null ,
	user_id INT(10), 
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

)
*/