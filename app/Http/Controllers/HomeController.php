<?php 
namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// }

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	// public function index()
	// {
	// 	return view('user.account_manager');
	// }

	public function index(){
		// $qty1 = "";   //Initialize variable $qty1
		// $qty2 = "";   //Initialize variable $qty2
		// //Neu file da ton tai thi lay noi dung cua file "goithau(khongduocxoa).txt"
		// if(file_exists('../../../goithau(khongduocxoa).txt')){   
		// 	$qty1 = file_get_contents('../../../goithau(khongduocxoa).txt');
		// }else{  //Neu khong ton tai thi tao file moi, ghi noi dung moi va lay noi dung file
		// 	$fileName = "../../../goithau(khongduocxoa).txt";
		//     $fs = fopen($fileName, 'w+');
		//     fwrite($fs, "10");
		//     fclose($fs);
		//     $qty1 = file_get_contents('../../../goithau(khongduocxoa).txt');
		// }

		// if(file_exists('../../../vanban(khongduocxoa).txt')){
		// 	$qty2 = file_get_contents('../../../vanban(khongduocxoa).txt');
		// }else{
		// 	//$fileName = "vanban(khongduocxoa).txt";
		// 	$fileName = "../../../vanban(khongduocxoa).txt";
		//     $fs = fopen($fileName, 'w+');
		//     //fwrite($fs, "10");
		//     fwrite($fs, "15");
		//     fclose($fs);
		//     $qty2 = file_get_contents('../../../vanban(khongduocxoa).txt');
		// }

		$qty1 = 0;   //Initialize variable $qty1
		$qty2 = 0;   //Initialize variable $qty2
		//include($_SERVER['DOCUMENT_ROOT']."/config/connect.php");
		include(base_path()."/config/connect.php");
		//include($_SERVER['DOCUMENT_ROOT']."/trolyao.cusc.vn_newDB/config/connect.php"); //

		$query_goithau = DB::select("SELECT * FROM config WHERE config_name='Số lượng gói thầu'");
		$query_vanban = DB::select("SELECT * FROM config WHERE config_name='Số lượng văn bản'");

		foreach ($query_goithau as $key => $value) {
			$qty1 = $value->config_value;
		}

		foreach ($query_vanban as $key => $value) {
			$qty2 = $value->config_value;
		}

		$goithau = DB::select("
			SELECT * FROM `packages` ORDER BY (created_at) DESC LIMIT 0,$qty1
		");  //Lay cac goi thau trong table "packages" va gioi han 0->10 goi
		$vanban = DB::select("
			SELECT * FROM `packages2` ORDER BY (created_at) DESC LIMIT 0,$qty2
		");  //Lay cac goi van ban trong table "packages2" va gioi han 0->15 goi
		return view("auth.home", compact("goithau", "vanban"));
	}

	
}
