<?php namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\User;  //link: app/User
use Hash;
use Mail;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AddUser;
class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}
	public function getRegister () {
		return view ('auth.register');
	}
// Đăng ký thành viên
	public function postRegister (RegisterRequest $request){
		//Thông báo lỗi xem trong file /app/Http/Requests/RegisterRequest.php

		//Nếu mật khẩu nhập lại khớp với mật khẩu đã nhập trước đó
		if($request->password_confirmation == $request->password){
			$thanhvien = new User();		
			$mail = $request->email;
			$thanhvien->name = $request->name;
			$thanhvien->email = $request->email;		
			$thanhvien->password = password_hash(($request->password), PASSWORD_DEFAULT);
			$thanhvien->remember_token = $request->_token;	
			$thanhvien->type = 2;  //Admin
			$thanhvien->type_packages = $request->typePacket;
			$thanhvien->status = 0;
			// resources/views/emails/register.blade.php

			//Gửi mail xác nhận
			 Mail::send('emails.register',array('token'=>$request->_token),
	             function($message) use ($mail) {
	                $message->to($mail)->subject('Xác nhận đăng ký tài khoản');
	          }); 	
			 //Session::flash('flash_message', 'Send message successfully!');

			$thanhvien->save();		

			// //Set thoi gian timeout cho moi lan doc du lieu
			// ini_set('max_execution_time', 300); //300 seconds = 5 minutes
			// ini_set('default_socket_timeout', 100); // 100 seconds = 1 Minutes 40 sec

			return redirect('authen/Registersuccess');  //Chuyen huong ve dia chi 'authen/Registersuccess'
		}
		else{  //Nếu nhập lại mật khẩu sai
			echo "<script>";
				echo "alert(\"Nhập lại mật khẩu không đúng!\");";
				echo "window.location=\"getRegister\";";   //Trả về form đăng ký
			echo "</script>";
		}
	}

// Xác nhận email
	public function VerifyEmail (Request $request){
		if (isset($request))
		{
			$token =  $request->token;
			//$token = $request->_token;
		    $data = User::where('remember_token',$token)->get()->toArray();
		    foreach ($data as $key => $value) {		   
		    	$id = $value['id'];		
				$thanhvien = User::find($id);
				$thanhvien->level = 1 ;
				$thanhvien->status = 1;
				$thanhvien->receive_email = 1;
				$thanhvien->save();
		}
			return redirect('welcome');

		}

	}
//
	public function Welcome ()
	{
		return view ('auth.success');
	}
	public function postLogin (LoginRequest $request){

		//Thông báo lỗi xem trong file /app/Http/Requests/LoginRequest.php
		$user  = array('email' =>$request->email ,'password'=> $request->password, 'level'=> 1,'status' => 1 );
		$admin  = array('email' =>$request->email ,'password'=> $request->password, 'level'=> 2,'status' => 1 );
		
		if ($this->auth->attempt($user))   //Neu quyen la User
		{		  
			session(['roles' => 1]);
		  	//return redirect ('user/homevanban');
		  	return redirect('user/dashboard_user');
		}
		else if ($this->auth->attempt($admin)){    //Neu quyen la Admin
			//return redirect('admin/list-package-vanban');
			session(['roles' => 2]);
			return redirect('admin/dash_board');
		}

	    else {
	    	echo "<script>";
	    		echo "alert(\"Địa chỉ email hoặc mật khẩu không đúng!\");";
	    		echo "window.location=\"getLogin\";";
	    	echo "</script>";
		  	//return redirect('authen/getLogin');
		  }
		
		
	}
		
	public function getLogin (){
		return view('auth.login');
	}	

	public function getLogout (){
		$this->auth->logout();
		return redirect('authen/getLogin');
	}
	
	
	public function UserAccount (){
		return view('user.account_manager');
	}
  

// Thêm User từ Admin
    public function AddUser (AddUser $request){
    	$nguoidung = new User;
    	$nguoidung->name = $request->txtUser;
		$nguoidung->email = $request->txtEmail;		
		$nguoidung->password = Hash::make($request->txtPass);
		$nguoidung->remember_token = $request->_token;
		$nguoidung->level =  $request->rdoLevel;				
		$nguoidung->save();						
		
	//	return redirect ('authen/getLogin');

    }
    public function Registersuccess (){
    	return view('auth.register_success');
    }
	


    

}
