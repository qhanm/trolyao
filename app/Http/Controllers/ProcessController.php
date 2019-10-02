<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use DB;
use App\Packages;
use App\User;
use App\Http\Controllers\MotGoiThau;
use Illuminate\Http\Request;

class ProcessController extends Controller {

	public function SendEmail (Request $request){
		$content  = $request->content;
			$emails = ['phulong95@gmail.com','trolyao.goithau@gmail.com'];
			// send ('tempblade,mảng nội dung,function($mesage)')
			Mail::send('process.email',array('content' => $content ),
			 function($message) use ($emails) {
				$message->to($emails)->subject('Phan hồi');
			});

			//Session:flash('flash_message','Gửi mail thanh công');
			return view ('process.send_email');


	}

	public function GuiGoiThau ($emails , $noidung ){
			//$emails = ['phulong95@gmail.com','trolyao.goithau@gmail.com'];
			// send ('tempblade,mảng nội dung,function($mesage)')
			Mail::send('process.email',array('noidung'=>$noidung),
			 function($message) use ($emails) {
				$message->to($emails)->subject('Phan hồi');
			});
	}

	public function SendAll(){

		$emails =DB::table('users')->select('email')->get();
		$email  = array();

		foreach ($emails as $value) {
			array_push($email,$value->email);
		}
		$title = 'Tôi Là Trợ Lý Ảo';

	return 	ProcessController::GuiGoiThau($email,$title);
	}



	public function GuiEmail () {
		$data =DB::table('users')->get();
		$title = 'Đây là mail mới nhất';
		foreach ($data as $bac1) {
			$mail = $bac1->email;
			$id = $bac1->id;
			$user_name = $bac1->name;
			$tukhoa = DB::table('keywords')->where('user_id',$id)->get();
			$noidung = array();
			$count = 0;
			foreach ($tukhoa as  $bac2) {
				$key = $bac2->keyword;
				$goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%'");
				$count +=count($goithau);
				array_push($noidung,$goithau);
			}

			if ($count>0) {
			Mail::send('process.email',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name),
			 function($message) use ($mail) {
				$message->to($mail)->subject('Phan hồi');
			});
		}
		 //   return 	ProcessController::GuiGoiThau($mail,$noidung);
		}
	}

	//Cron Jobs Update Packages Method
	public function CronJobsUpdatePackage(){
		return view('process.getdata');
	}

	//Cron Jobs Update Packages Van Ban Method
	public function CronJobsUpdatePackageVanBan(){
		return view('process.getdatavanban');
	}

	//Random Cron Jobs Update Packages Method
	public function RandomCronJobsUpdatePackage(){
		ini_set('max_execution_time', 3600000);  //Set thoi gian thuc thi toi da la 3600 giay = 60 phut
		//$random_minute = rand(0,9);  //Lay random so phut tu 0 -> 9
		$minute = 0;
		$query = DB::select("SELECT * FROM config WHERE config_name='Số phút thực hiện cron job'");
		foreach ($query as $key => $value) {
			$minute = $value->config_value;
		}

		//$minute = file_get_contents(public_path()."/sophutcronjobs.txt"); //Lay so phut
		$random_minute = rand(0, $minute-1);
		$second = $random_minute*60; //Lay so phut random nhan voi 60 giay
		//$ms = $second * 1000;
		sleep($second);
		//sleep(300);
		return view('process.getdata');
			
		//echo "<script>setTimeout(function(){ window.location='cronJobsUpdatePackage'; }, $second);</script>";
		
		//echo "<script>setTimeout(function(){ alert('$ms'); }, 3000);</script>";
	}

	//Random Cron Jobs Update Packages Van Ban Method
	public function RandomCronJobsUpdatePackageVanBan(){
		ini_set('max_execution_time', 3600000);  //Set thoi gian thuc thi toi da la 3600 giay = 60 phut
		//$random_minute = rand(0,9);  //Lay random so phut tu 0 -> 9
		$minute = 0;
		$query = DB::select("SELECT * FROM config WHERE config_name='Số phút thực hiện cron job'");
		foreach ($query as $key => $value) {
			$minute = $value->config_value;
		}

		//$minute = file_get_contents(public_path()."/sophutcronjobs.txt"); //Lay so phut
		$random_minute = rand(0, $minute-1);
		$second = $random_minute*60; //Lay so phut random nhan voi 60 giay

		sleep($second);
		return view('process.getdatavanban');
	}


    public function UpdatePackage (Request $request){

       if ($request->ajax()){
       		return view("process.getdata", compact('success'));

	       	// echo "<script>";
	       	// 	echo "alert(\"Exists AJAX Request\");";
	       	// echo "</script>";
       		
       		//$file = fopen(app_path()."/data.txt","w+");  //Tao file data trong thu muc app
       		//$file = fopen(base_path()."/data.txt","w+"); //Tao file data trong thu muc root
       		//fwrite($file, "Hello, today is Monday");
       		//fclose($file);
       }
    }

    public function UpdatePackagevanban (Request $request){

       if ($request->ajax()){
       		return view("process.getdatavanban", compact('success'));
       }
    }

// Thêm từ khóa (Android)
   public function AddKeyWord (Request $request){
      	$response = array();
   		if ( isset($request->id) && isset($request->add) && isset($request->key_word)) {

   			$response['result'] = "ok";
   			return response()->json($response);
   		}
   		else
   		{
   			$response['result'] = "not";
   			return response()->json($response);
   		}
   }

// Lay thông tin goi thau theo  từ khóa
// lấy thông tin tất cả gói thầu sang JSON
    public function GetAllData (){
    	$data=DB::table('packages')->get();
    	return response()->json($data);
    }

}