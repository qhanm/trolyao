<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Mail;
use Input;
use DB;
use Auth;
use App\Packages;
use App\Packages2;
use App\SentEmail;
use App\SentEmail2;
use App\Http\Requests\AddUser;
use Hash;
use File; 
use App\Images;
use App\QtyKey;
use Excel;
use Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\MessageBag;
class AdminController extends Controller {
	public function UserList (){
    if(Auth::user()->level == 2){
      $user = User::select('id','name','email','level','status','receive_email', 'type')->orderBy('id','DESC')->get()->toArray();
      $qty = QtyKey::all();
        return view('admin.user_list',compact('user', "qty"));
    }else{
      return redirect()->back();
    }
	}
	public function GetUserAdd (){
		 return view ('admin.user_add');
	}
	// Post ajax Edit myacount 
    public function EditMyAccount (Request $request){
        // if ($request->ajax()){
        //   $id = Auth::user()->id;
        //   $user = User::find($id);
        //   if ($request->input('txtPass')){
        //       $this->validate ($request, 
        //           ['txtRePass'=>'same:txtPass'],
        //           ['txtRePass.same'=>"Mật khẩu không trùng khớp"]);
        //       $pass = $request->txtPass;
        //       $user->password = Hash::make($pass);
        //   }
        //   $user->name = $request->txtUser;
        //   $user->email = $request->txtEmail;      
        //   $user->remember_token = $request->input('remember_token');          
        //   $user->save();      
        //   $success = "<script type='text/javascript'>
        //   alert ('Thay đổi thông tin thành công');
        //   location.reload();
        //   </script>";
        //   return  $success;
        // }

      if ($request->ajax()){
          $success = "";  //Initialize $success variable
          $id = Auth::user()->id;
          $user = User::find($id);
          if($request->txtUser == "" || 
             $request->txtPass == "" || 
             $request->txtRePass == "" || 
             $request->txtEmail == ""){ //Cac request nay lay tu view admin.account_manager => #Save
              $success = "<script> alert(\"Vui lòng nhập đầy đủ thông tin\") </script>";
          }
          else{
            if ($request->input('txtPass')){
                $this->validate ($request, 
                    ['txtRePass'=>'same:txtPass'],
                    ['txtRePass.same'=>"Mật khẩu không trùng khớp"]);
                $pass = $request->txtPass;
                $user->password = Hash::make($pass);
              }
              if((strlen(strstr($request->txtEmail, "@gmail.com")) > 0) ||
                  (strlen(strstr($request->txtEmail, "@yahoo.com.vn")) > 0) ||
                  (strlen(strstr($request->txtEmail, "@yahoo.com")) > 0) ||
                  (strlen(strstr($request->txtEmail, "@hotmail.com")) > 0) ||
                  (strlen(strstr($request->txtEmail, "@student.ctu.edu.vn")) > 0) ||
                  (strlen(strstr($request->txtEmail, "@ctu.edu.vn")) > 0) ){
                $user->name = $request->txtUser;
                $user->email = $request->txtEmail;      
                $user->remember_token = $request->input('remember_token');          
                $user->save();      
                $success = "<script type='text/javascript'>
                alert ('Thay đổi thông tin thành công');
                location.reload();
                </script>";
              }
              else{
                  $success = "<script type='text/javascript'>
                  alert ('Email không đúng định dạng');
                  location.reload();
                  </script>";
              }
          }
          
          return $success;
        }

    }
    // Get Model Ajax Edit account 
     public function GetEditMyAccount (Request $request) {
        if ($request->ajax()){
          $id = $request->id;
          $data = User::findOrFail($id)->toArray();  


          echo "       <form action=''  name = 'editForm'>
          <div class='form-group'>
          <input type='hidden' name='id' id='_id' value='"; echo $id; echo "'/>";
          echo "                             <input type='hidden' name='_token' value='"; 
          echo csrf_token();
          echo "'>
          <label>Tên người dùng</label>
          <input class='form-control' name='txtUser' id='edtName' value='"; 
          echo $data['name']; 

          echo "'  />
          </div>   ";
          echo "<div class='form-group'>
          <label>Mật khẩu</label>
          <input type='password' class='form-control' name='txtPass' id='edtPass' value='' placeholder='Vui lòng nhập mật khẩu' />
          </div>
          <div class='form-group'>
          <label>Nhập lại mật khẩu</label>
          <input type='password' class='form-control' name='txtRePass' id='edtRePass' value='' placeholder='Nhập lại mật khẩu' />
          </div>";
          echo "<div class='form-group'>
          <label>Email</label>
          <input type='email' class='form-control' name='txtEmail' id='edtEmail' value='";
          echo  $data['email'];
          echo " ' placeholder='Please Enter Email' />
          </div>      
          <form>";


          echo " <script>
          $('#EditModel').modal('show');
          </script> ";
       // DB::table('users')->where('id',$id)->delete();
      }
}


	// Thêm User từ Admin
    //Cập nhật code
    // public function AddUser (Request $request){

    //         // $nguoidung = new User();
    //         // $nguoidung->name = $request->name;
    //         // $nguoidung->email = $request->email;        
    //         // $nguoidung->password = bcrypt($request->password);
    //         // $nguoidung->remember_token = $request->input('remember_token');
    //         // $nguoidung->level =  $request->level;   
    //         // $nguoidung->status = 1;             
    //         // if($nguoidung->save())     
    //         //     return response(['error'=>false, 'message'=>true], 200);
    //         // else
    //         //     return response(['error'=>false, 'message'=>false], 200);
    //         return response(['error'=>false, 'message'=>true], 200);
            
    //     //end cập nhật code

    // }
    // 
    
    public function postRegisterUser(Request $request){
        try{
            $rule = [
                "email" => "unique:users",
            ];
            $message = [
                "email.unique" => "Email đã tồn tại",
            ];
            $validator = Validator::make($request->all(), $rule, $message);
            if($validator->fails()){
                return response()->json(['error'=>true, 'message'=>$validator->errors()]);
            }
            else{
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->txtPass);
                $user->level = $request->rdoLevel; 
                if($request->rdoLevel == 1){ //là thành viên
                  $user->type = 2;
                  $user->type_packages = $request->cbPacket;
                }
                else { // là admin
                  $user->type = 1;
                  $user->type_packages = 3;
                }
                $user->remember_token = $request->input('remember_token');
                $user->status = 1;
                $user->receive_email = $request->receive_email;  //Gan trang thai nhan email hang ngay la true
                if($user->save()) 
                    return response(['error'=>false, 'message'=>true], 200);
                else
                    return response(['error'=>false, 'message'=>false], 200);
            }
        }catch(QueryException $ex){
            return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
        }catch(PDOException $ex){
            return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
        } 
    }

    public function GetUserEdit ($id){
    	$data = User::findOrFail($id)->toArray();    	
    	return view ('admin.user_edit',compact('data'));
    	

    }
    // Edit User  (By Ajax ())
    public function GetEditUser (Request $request) {
        if ($request->ajax()){
            $id = $request->id;
            $data = User::findOrFail($id)->toArray();  


            echo "       
                <div class='form-group'>
                 <input type='hidden' name='id' id='_id' value='"; echo $id; echo "'/>";
            echo "                             <input type='hidden' name='_token' value='"; 
            echo csrf_token();
            echo "'>
            <label>Tên người dùng</label>
            <input class='form-control' name='edtName' id='edtName' value='"; 
            echo $data['name']; 

            echo "'  />
            </div>   ";
            echo "<div class='form-group'>
            <label>Mật khẩu</label>
            <input type='password' class='form-control' name='edtPass' id='edtPass' value=''placeholder='Vui lòng nhập mật khẩu' />
            </div>
            <div class='form-group'>
            <label>Nhập lại mật khẩu</label>
            <input type='password' class='form-control' value='' name='editRePass' id='editRePass' placeholder='Nhập lại mật khẩu' />
            <span style='color:red' id='errorRepass' class='hidden'></span>
            </div>";
            echo "<div class='form-group'>
            <label>Email</label>
            <input type='email' class='form-control' name='edtEmail' id='edtEmail' value='";
            echo  $data['email'];
            echo " ' placeholder='Please Enter Email' /><span style='color:red' id='errorEmail2' class='hidden'></span>
            </div>";
            echo " <div class='form-group'>
            <label>Cấp độ</label>
            <label class='radio-inline'>
            <input name='rdLevel' class='rdLevel' value='2'  type='radio' id='admin'  onclick='changeType();'";

            if ( $data['level']==2){

              echo "  checked='checked' ";
          }

          echo " >Admin
          </label>
          <label class='radio-inline'>
          <input name='rdLevel' class='rdLevel' value='1' type='radio' id='user' onclick='changeType();'";
          if ( $data['level'] == 1)
          {
             echo " checked='checked'";
         }
        echo ">Người dùng</label></div>";
        echo " <div class='form-group";
            if ( $data['level']==2){
                echo " hidden";
            }
        echo "' id='type_user'>
            <label>Loại người dùng</label>
            <label class='radio-inline'>
            <input name='rdType' class='rdType' value='2'  type='radio' ";

            if ( $data['type']==2 || $data['type']==1){

              echo "  checked='checked' ";
          }

          echo " >Bình thường
          </label>
          <label class='radio-inline'>
          <input name='rdType' class='rdType' value='3' type='radio' ";
          if ( $data['type'] == 3)
          {
             echo " checked='checked'";
         }
        echo ">VIP</label></div>";

         echo "<div class='form-group "; 
            if ( $data['level']==2){
                echo " hidden";
            }
         echo "' id='cbPacket2'>
                <label for='typePacket'>Chọn danh sách nhận tin:</label>
                <select class='form-control' id='typePacket2' name='typePacket2'>";
         echo       "<option value='1'"; if($data['type_packages']==1){ echo " selected='selected'"; } echo " >Văn bản pháp luật</option>";
         echo       "<option value='2'"; if($data['type_packages']==2){ echo " selected='selected'"; } echo " >Gói thầu</option>";        
         echo       "<option value='3'"; if($data['type_packages']==3){ echo " selected='selected'"; } echo " >Tất cả</option>";
        echo   "</select>
              </div>";
        echo "<div class='form-group'><label>Trạng thái </label> <label class='radio-inline'><input name='rdStatus' value='1' class='rdStatus' type='radio'";
       if ( $data['status']==1) {
           echo " checked='checked'";
       }
       echo "

       >Hoạt động
       </label>
       <label class='radio-inline'>
       <input name='rdStatus' value='0' type='radio' class='rdStatus'";
       if ( $data['status']==0)
       {   
          echo "  checked='checked'";
      }
      echo "
      > Ngừng hoạt động
      </label>
      </div>";
      echo "<div class='form-group'>
      <label style='margin-top: -5px; '  >Nhận thông tin gói thầu mới qua email</label>
      <input style='margin-left:5px; margin-top:10px; ' type='checkbox'   name='cbEmail' class = 'cbEmail' value='1'";

      if ($data['receive_email']==1) 
        echo "checked = 'checked'";

    echo " id='cbEmail' value='";
    echo $data['receive_email'];
    echo "'/>
    </div>
    <p id='errorLogin' style='color: red'></p>";


    echo " <script>
    $('#EditModel').modal('show');
    </script> ";
       // DB::table('users')->where('id',$id)->delete();
}
}

 // Get trang thống kê
    public function GetDashBoard(){
     if(Auth::user()->level == 2)    //Nếu quyền là Admin
        return view ('admin.dash_board');
      else return redirect()->back();
    }
    public function postEditUser ( Request $request){
        try{
            $id = $request->id;
            $user = User::find($id);
            if($user->email != $request->email){
                $rule = [
                    "email" => "unique:users",
                ];
                $message = [
                    "email.unique" => "Email đã tồn tại",
                ];
                $validator = Validator::make($request->all(), $rule, $message);
                if($validator->fails()){
                    return response()->json(['error'=>true, 'message'=>$validator->errors()]);
                }
                else{                    
                    $user->email = $request->email;  
                }
            }
            $user->name = $request->txtUser;
            if($request->txtPass != null || $request->txtPass != ""){
              $user->password = Hash::make($request->txtPass);
            }
            $user->receive_email = $request->cbEmail;
            $user->status =  $request->rdoStatus;    
            $user->remember_token = $request->input('remember_token');
            $user->level = $request->rdoLevel;
            if($request->rdoLevel == 2){
                $user->type = 1;
                $user->type_packages = 3;
            }else{
                $user->type = $request->type;
                $user->type_packages = $request->cbPacket2;
              
            }
            if($user->save()) 
              return response(['error'=>false, 'message'=>true], 200);
            else
              return response(['error'=>false, 'message'=>false], 200);
            
            
        }catch(QueryException $ex){
            return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
        }catch(PDOException $ex){
            return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
        } 
    }
	

// Get profile 
 public function GetProfile (){    	
    if(Auth::user()->level == 2)
		  return view('admin.account_manager');		
    else 
      return redirect()->back();

}

 public function UpLoadImage (Request $request){  	
 	  if ($request->file('file')){
 	  $id = Auth::user()->id; 	
 	  $name = $id."+".Input::file('file')->getClientOriginalName();
 	  $src = 'public/photo/image/'.$name; 	 	
 	  $result = Images::where('user_id','=',$id);
 	  $row = count($result);
 	
 	  if ($row == 1) {
 	  	$path =DB::table('images')->where('user_id',$id)->select('src')->get();
 	  	foreach ($path as $key => $value) {
 	  		$delete=  $value->src;

 	  		File::Delete($delete);
 	  	}
 	  	
 		DB::table('images')->where('user_id',$id)->delete();
 		Input::file('file')->move('public/photo/image', $name); 
 	    $image = new Images;
 	    $image->user_id = $id;
 	    $image->src = $src;
 	    $image->save();
 	    return view ('admin.account_manager');
 	    
 	} else 
 	{
 		 Input::file('file')->move('public/photo/image', $name); 
 	  $image = new Images;
 	  $image->user_id = $id;
 	  $image->src = $src;
 	  $image->save();
 	   return 'File was moved.';
      return view ('admin.account_manager');  

}  
 	} else {
 		$erorr = "Bạn chưa chọn ảnh";
 		 return view('admin.account_manager',compact('erorr'));
 	}

     

 }

 public function GetEditAccount (){
	$id = Auth::user()->id;
	$data = User::findOrFail($id)->toArray();  
	return view ('admin.edit_account',compact('data'));
}

public function DeleteUser (Request $request) {
    if ($request->ajax()){
        $id = $request->id;
        if(User::destroy($id))
            return "<script> location.reload(); </script>";
    } 
}
public function postEditAccount (Request $request){     	
     	$id = Auth::user()->id;
     	$user = User::find($id);
     	if ($request->input('txtPass')){
     		$this->validate ($request, 
     			['txtRePass'=>'same:txtPass'],
     			['txtRePass.same'=>"Mật khẩu không trùng khớp"]);
     		$pass = $request->txtPass;
     		$user->password = Hash::make($pass);
     	}
     	$user->name = $request->txtUser;
		$user->email = $request->txtEmail;		
		$user->remember_token = $request->input('remember_token');			
		$user->save();		
	    $success = "<script type='text/javascript'>
        alert ('Thay đổi thông tin thành công');
</script>";
		return view ('admin.account_manager',compact('success'));

    }
    
    public function GetListPackage (){
    	return view ('admin.list_package');
    }

     public function GetListPackagekho (){
        return view ('admin.list_package_kho');
    }


     public function GetListPackagevanban (){
      if(Auth::user()->level == 2)  //Neu level == 2 thi quyen la "Admin"
        return view('admin.list_package_vanban');
      else 
        return redirect()->back();
    }
   
// Gửi Emmail khi Admin yêu cầu
   public function SendMail (){    
    $data =DB::table('users')->get();
         foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords')->where('user_id',$id)->get();
            $noidung = array();         
            $count = 0;
           foreach ($tukhoa as  $bac2) {
                $key = $bac2->keyword;              
                $goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%' order by id DESC");      
                
                foreach ($goithau as $key => $value) {
                        $id_cankiemtra = $value->id;  
                        $id_taikhoan  = $id;                  
                        $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'"); 
                                        
                    
                        if (count($ketquakiemtra)==0){
                            //echo $value->title;                                 
                             array_push($noidung,$value);
                             $sent_emails = new SentEmail;
                             $sent_emails->user_id=$id_taikhoan;
                             $sent_emails->package_id = $id_cankiemtra;
                             $sent_emails->save();
                           

                        }
                }
               
                
                }                       
            if ($count>0) {
            Mail::send('process.email',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin gói thầu');
            }); 
            }
        
         //   return    ProcessController::GuiGoiThau($mail,$noidung);
            
        }
    //    return view ('admin.user_list');
     //return view ('admin.list_package');

   }
   ///Mở giao diện send email cho từng người
   public function SendEmailSetting (){
    if(Auth::user()->level == 2)   //Neu level == 2 thi role la "Admin"
       return view ('admin.send_email_setting');
    else return redirect()->back();
   }

    public function SendEmailSettingvanban (){
      if(Auth::user()->level == 2)   //Neu level == 2 thi role la "Admin"
        return view ('admin.send_email_setting_vanban');
      else return redirect()->back();
   }
 
 // Xử lý gửi dữ liệu cho từng người.
   public function CustomSendMail (Request $request){
        $user_id  = $request->id;    
        $data =DB::table('users')->where('id',$user_id)->get();
         foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords')->where('user_id',$id)->get();
            $noidung = array();    

            $count = 0;
            foreach ($tukhoa as  $bac2) {
                $key = $bac2->keyword;              
                //$goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%' order by id DESC");   
                $goithau = DB::select("SELECT * FROM packages WHERE (title LIKE '%$key%' OR bidder LIKE '%$key%') AND hided IS NULL ORDER BY id DESC");   //    
                
                if(count($goithau) > 0){  //
                  foreach ($goithau as $key => $value) {
                        $id_cankiemtra = $value->id;  
                        $id_taikhoan  = $id;                  
                        $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'"); 
                                        
                    
                        if (count($ketquakiemtra)==0){
                            //echo $value->title;                                 
                             array_push($noidung,$value);
                             $sent_emails = new SentEmail;
                             $sent_emails->user_id=$id_taikhoan;
                             $sent_emails->package_id = $id_cankiemtra;
                             $sent_emails->save();
                           

                        }
                  }
                }  //
                
               
                
                }      

            $count = count ($noidung);
            

        // Gửi email                
            if ($count>0) {
            Mail::send('process.email',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin gói thầu');
            }); 

             return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
            } else 
            {
                 return " <script type='text/javascript'> $('#loader').hide(); alert('Không có gói thầu để gửi email'); </script>";
            } 
            
        
         //   return    ProcessController::GuiGoiThau($mail,$noidung);
           
            
        }
    //    return view ('admin.user_list');
     

   }
   // Xem chi tiết Email (by ajax)
   public function ViewEmail(Request $request){
    if ($request->ajax())
    {
        $id = $request->id;
        $user = User::find($id);
        $tukhoa = DB::table('keywords')->where('user_id',$id)->select('keyword')->get();      
          $noidung = array();
      
          $count = 0;
         foreach ($tukhoa as  $bac2) {
          $key = $bac2->keyword;              
                //$goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%' order by id DESC");    
                $goithau = DB::select("SELECT * FROM packages WHERE (title LIKE '%$key%' OR bidder LIKE '%$key%') AND hided IS NULL ORDER BY id DESC");   //    
                
                if(count($goithau) > 0){  //  
                
                    foreach ($goithau as $key => $value) {
                            $id_cankiemtra = $value->id;  
                            $id_taikhoan  = $id;                  
                            $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'"); 
                                            
                        
                            if (count($ketquakiemtra)==0){
                                //echo $value->title;                                 
                                 array_push($noidung,$value);
                                                           

                            }
                    }
                }  //
               
                
    }
        
                     $count = count ($noidung);
                         
        echo  " <div class='modal fade'id='viewmail_model'role='dialog'>
                    <div class='modal-dialog'>    
           
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button'class='close'data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Nội dung email sẽ được gửi đến người dùng</h4>
        </div>
        <div class='modal-body'>

                
         <p> Xin chào <strong>  $user->name </strong>! </p>

        </p>
        <p>Đây là nội dung gói thầu của bạn với từ khóa là: "    ;
             foreach ($tukhoa as $value)
             {
                echo $value->keyword;
                 echo ", ";
             }

       echo "</p> ";

        if ($count > 0 ) {
 
        echo "<table border='1'> <tr> <th>Tên gói thầu</th><th>Bên mời thầu</th><th>Ngày cập nhật</th> </tr>";

            foreach ($noidung as $value){
                   
                 if (isset ($value->link)){

                    echo "

<tr>
        
    <td>
      <a style='text-decoration: none;' href='"; echo $value->link;echo "'> "; echo $value->title ; echo "</a>
    </td>
    <td>
      <span>"; echo $value->bidder; echo "</span>
    </td>
    <td>";
        $date = substr($value->created_at,0,10);
        $day = substr($date, 8, 2);
        $month = substr($date, 5, 2);
        $year = substr($date, 0, 4);
        echo $day.'-'.$month.'-'.$year;
    echo "</td> 
    
    </tr>
                    ";
       
    
    } // ìf nhỏ 
    else {
     break; 
     } //else   
   
    } // foreach 

   

  
echo "</table>";
} 
else {
  echo  "<p>Không có nội dung email cho tài khoản này</p>";
} // elese





     
          echo "<button type='button' class='btn btn-success' data-dismiss='modal'>Đóng</button>
                <button type='button' class='btn btn-success' data-dismiss='modal'>Gửi</button>
                 </div>
                  </div>
      
             </div>
             </div> 
                 <script>
            $('#viewmail_model').modal('show');
                </script>  ";

    }
   }





/////////////////////////////////////////////////
      // Xem chi tiết Email (by ajax)
   public function ViewEmailvanban(Request $request){
    if ($request->ajax())
    {
        $id = $request->id;
        $user = User::find($id);
        $tukhoa = DB::table('keywords2')->where('user_id',$id)->select('keyword')->get();      
          $noidung = array();
      
          $count = 0;
         foreach ($tukhoa as  $bac2) {
          $key = $bac2->keyword;              
                //$goithau = DB::select("select * from packages2 where title like '%$key%'");      
                $goithau = DB::select("SELECT * FROM packages2 WHERE title LIKE '%$key%' AND hided IS NULL ORDER BY id DESC");     //
                
                if(count($goithau) > 0){  //
                  foreach ($goithau as $key => $value) {
                          $id_cankiemtra = $value->id;  
                          $id_taikhoan  = $id;                  
                          $ketquakiemtra = DB::select("select * from sent2_email where user_id='$id_taikhoan' and package2_id ='$id_cankiemtra'"); 
                                          
                      
                          if (count($ketquakiemtra)==0){
                              //echo $value->title;                                 
                               array_push($noidung,$value);
                                                         

                          }
                  }
                }  //
               
                
    }
        
                     $count = count ($noidung);
                         
        echo  " <div class='modal fade'id='viewmail_model'role='dialog'>
                    <div class='modal-dialog'>    
           
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button'class='close'data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Nội dung email sẽ được gửi đến người dùng</h4>
        </div>
        <div class='modal-body'>

                
         <p> Xin chào <strong>  $user->name </strong>! </p>

        </p>
        <p>Đây là nội dung văn bản của bạn với từ khóa là: "    ;
             foreach ($tukhoa as $value)
             {
                echo $value->keyword;
                echo ", ";
             }
       echo "</p> ";

        if ($count > 0 ) {
 
        echo "<table border='1'> <tr> <th>Văn bản mới</th><th>Ngày cập nhật</th> </tr>";

            foreach ($noidung as $value){
                   
                 if (isset ($value->link)){

                    echo "

<tr>
        
    <td>
      <a style='text-decoration: none;' href='"; echo $value->link;echo "'> "; echo $value->title ; echo "</a>
    </td>
    <td>
      <span>"; 
      //echo $value->created_at; 
          $date = substr($value->created_at,0,10);
          $day = substr($date, 8, 2);
          $month = substr($date, 5, 2);
          $year = substr($date, 0, 4);
          echo $day.'-'.$month.'-'.$year;
      echo "</span>
    </td>
    
    
    </tr>
                    ";
       
    
    } // ìf nhỏ 
    else {
     break; 
     } //else   
   
    } // foreach 

   

  
echo "</table>";
} 
else {
  echo  "<p>Không có nội dung email cho tài khoản này</p>";
} // elese





     
          echo "<button type='button' class='btn btn-success' data-dismiss='modal'>Đóng</button>
                <button type='button' class='btn btn-success' data-dismiss='modal'>Gửi</button>
                 </div>
                  </div>
      
             </div>
             </div> 
                 <script>
            $('#viewmail_model').modal('show');
                </script>  ";

    }
   }



// Xử lý gửi dữ liệu cho từng người.
   public function CustomSendMailvanban (Request $request){
        $user_id  = $request->id;    
        $data =DB::table('users')->where('id',$user_id)->get();
         foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords2')->where('user_id',$id)->get();
            $noidung = array();    

            $count = 0;
            foreach ($tukhoa as  $bac2) {
                $key = $bac2->keyword;              
                $goithau = DB::select("SELECT * FROM packages2 WHERE title LIKE '%$key%' AND hided IS NULL ORDER BY id DESC");   //
                
                if(count($goithau) > 0){  //
                  foreach ($goithau as $key => $value) {
                        $id_cankiemtra = $value->id;  
                        $id_taikhoan  = $id;                  
                        $ketquakiemtra = DB::select("select * from sent2_email where user_id='$id_taikhoan' and package2_id ='$id_cankiemtra'"); 
                                        
                    
                        if (count($ketquakiemtra)==0){
                            //echo $value->title;                                 
                             array_push($noidung,$value);
                             $sent_emails = new SentEmail2;
                             $sent_emails->user_id=$id_taikhoan;
                             $sent_emails->package2_id = $id_cankiemtra;
                             $sent_emails->save();
                           

                        }
                
                  }
                }  //
               
                
                }      

            $count = count ($noidung);
            

        // Gửi email                
            if ($count>0) {
            Mail::send('process.email2',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin văn bản');
            }); 

             return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
            } else 
            {
                 return " <script type='text/javascript'> $('#loader').hide(); alert('Không có văn bản để gửi email'); </script>";
            } 
            
        
         //   return    ProcessController::GuiGoiThau($mail,$noidung);
           
            
        }
    //    return view ('admin.user_list');
     

   }



 // Gửi dũ liệu theo danh sách được chọn

    public function SendMailManyvanban (Request $request){
        $user_id  = $request->id;          
        if (count($user_id)==0)
        {
           return " <script type='text/javascript'> $('#loader').hide(); alert('Vui lòng chọn người dùng để gửi'); </script>"; 
        }
        else {
       foreach ($user_id as $key => $value) {       
           $data =DB::table('users')->where('id',$value)->get();
           foreach ($data as $bac1) {
              $mail = $bac1->email;
              $id = $bac1->id;
              $user_name = $bac1->name;
              $tukhoa = DB::table('keywords2')->where('user_id',$id)->get();
              $noidung = array();    

              $count = 0;
              foreach ($tukhoa as  $bac2) {
                  $key = $bac2->keyword;                 
                  $goithau = DB::select("SELECT * FROM packages2 WHERE title LIKE '%$key%' AND hided IS NULL ORDER BY id DESC");   //   
                  
                  if(count($goithau) > 0){ //
                    foreach ($goithau as $key => $value) {
                          $id_cankiemtra = $value->id;  
                          $id_taikhoan  = $id;                  
                          $ketquakiemtra = DB::select("select * from sent2_email where user_id='$id_taikhoan' and package2_id ='$id_cankiemtra'"); 
                                          
                      
                          if (count($ketquakiemtra)==0){
                              //echo $value->title;                                 
                               array_push($noidung,$value);
                               $sent_emails = new SentEmail2;
                               $sent_emails->user_id=$id_taikhoan;
                               $sent_emails->package2_id = $id_cankiemtra;
                               $sent_emails->save();
                             

                          }
                    }
                  } //
                  
                 
                  
                  }      

              $count = count ($noidung);
              

          // Gửi email                
              if ($count>0) {
              Mail::send('process.email2',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
               function($message) use ($mail) {
                  $message->to($mail)->subject('Thông tin văn bản');
              }); 

               return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
              }
              else 
              {
                   return " <script type='text/javascript'> $('#loader').hide(); alert('Không có văn bản để gửi email'); </script>";
              } 
              
          
           //   return    ProcessController::GuiGoiThau($mail,$noidung);
             
              
          }

    //    return view ('admin.user_list');
       }
        //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
}
   }
   ///////////////////////////////////////////////////////
   // Gửi dũ liệu theo danh sách được chọn

    public function SendMailMany (Request $request){
        $user_id  = $request->id;          
        if (count($user_id)==0)
        {
           return " <script type='text/javascript'> $('#loader').hide(); alert('Vui lòng chọn người dùng để gửi'); </script>"; 
        }
        else {
       foreach ($user_id as $key => $value) {       
         $data =DB::table('users')->where('id',$value)->get();
         foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords')->where('user_id',$id)->get();
            $noidung = array();    

            $count = 0;
            foreach ($tukhoa as  $bac2) {
                $key = $bac2->keyword;              
                $goithau = DB::select("SELECT * FROM packages WHERE (title LIKE '%$key%' OR bidder LIKE '%$key%') AND hided IS NULL ORDER BY id DESC");   //       
                
                if(count($goithau) > 0){  //
                  foreach ($goithau as $key => $value) {
                        $id_cankiemtra = $value->id;  
                        $id_taikhoan  = $id;                  
                        $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'"); 
                                        
                    
                        if (count($ketquakiemtra)==0){
                            //echo $value->title;                                 
                             array_push($noidung,$value);
                             $sent_emails = new SentEmail;
                             $sent_emails->user_id=$id_taikhoan;
                             $sent_emails->package_id = $id_cankiemtra;
                             $sent_emails->save();
                           

                        }
                
                  }
                }  //
               
                
                }      

            $count = count ($noidung);
            

        // Gửi email                
            if ($count>0) {
            Mail::send('process.email',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin gói thầu');
            }); 

             return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
            }
            else 
            {
                  return " <script type='text/javascript'> $('#loader').hide(); alert('Không có gói thầu để gửi email'); </script>";
            } 
            
        
         //   return    ProcessController::GuiGoiThau($mail,$noidung);
           
            
        }

    //    return view ('admin.user_list');
       }
        //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
}
   }

 // Xem thông tin Email đến người dùng
   public function ViewMail (Request $request){
          $id =  $request->id;     
          $name = DB::table('users')->where('id',$id)->select('name')->get(); 
          $tukhoa = DB::table('keywords')->where('user_id',$id)->get();
          $noidung = array();
      
          $count = 0;
         foreach ($tukhoa as  $bac2) {
           $key = $bac2->keyword;        
           $goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%' ");      
        
             $count +=count($goithau);       
        
        array_push($noidung,$goithau);
        
        }        
                       
       return view('admin.view_mail',compact(['noidung','count','tukhoa','name']));


   }

// Xóa gói thầu đã chọn.
   public function DeletePackage (Request $request){
  if ($request->ajax()){
    $id = $request->id;
    foreach ($id as $key => $value) {
          Packages::destroy($value);

    }

    //return view ('admin.list_package',compact('success'));
     
  }

} 

public function DeletePackagevanban (Request $request){
  if ($request->ajax()){
    $id = $request->id;
    foreach ($id as $key => $value) {
          Packages2::destroy($value);

    }

    //return view ('admin.list_package',compact('success'));
     
  }

} 
// Hiện/ ẩn gói thầu
public function ShowAndHide (Request $request){
    if ($request->ajax()){

    $id = $request->id;
     if(isset ($id)  ){
        foreach ($id as $key => $value) {
          $packages = Packages::find ($value);
          if (isset($packages)){
            if (is_null($packages->hided)){
              $packages->hided = 1;
            }
            else {
              $packages->hided = null;      
            }
            $packages->save();
          }
        }
    }
    }
}

// Hiện/ ẩn văn bản
public function ShowAndHide2 (Request $request){
    if ($request->ajax()){

    $id = $request->id;
     if(isset ($id)  ){
        foreach ($id as $key => $value) {
          $packages = Packages2::find ($value);
          if (isset($packages)){
            if (is_null($packages->hided)){
              $packages->hided = 1;
            }
            else {
              $packages->hided = null;      
            }
            $packages->save();
          }
        }
    }
    }
}



// Xóa người dùng đã chọn
public function DeleteManyUser (Request $request){
  if ($request->ajax()){
    $id = $request->id;
    foreach ($id as $key => $value) {
        User::destroy($value);
    }
      return "<script> location.reload(); </script>";
        //return view ('admin.list_package',compact('success'));
    } 
}

// Chọn gói thầu theo lĩnh vực
    public function GetLinhVuc (Request $request){
      if ($request->ajax())
        {
          
          $ma_linh_vuc = $request->ma_linh_vuc; 
          if($ma_linh_vuc==='all') {
            $goi_thau = DB::table('packages')->orderBy('id', 'desc')->get();
            $i =1 ;
                      foreach ($goi_thau as $value) {
                         $ngaycapnhat =  substr($value->created_at,0,10); 
                         $ngayhientai = date('Y-m-d');   

                        echo "<tr> <td><input type='checkbox' name='id[]'' class='checkbox' value='{!!$value->id!!}'></td>   <td>$i</td><td><a href='$value->link' target ='_blank'>$value->title";                          
                         if ($ngayhientai == $ngaycapnhat) {
                            echo "<span style='color: red;' class='dm_new'><strong> Hot! </strong></span> <span class='editlinktip hasTip' style='text-decoration: none; color: #333;'>";
                         echo "<img src='"; echo asset('/image/tooltip.png'); 
                         echo "' border='0' alt='Tooltip'></span> </td>";
                     }
                        echo "<td>$value->bidder</td>";
                          echo "<td align='center'>";
                         echo substr($value->created_at,0,10); 
                          echo " </td> ";
                         if (is_null($value->hided))
                            echo "                <td align='center'>   <span class='glyphicon glyphicon glyphicon-ok' aria-hidden='true' style='color:green'></span>  </td>";
                         else 
                         echo "<td align='center'>
                           <span class='glyphicon  glyphicon glyphicon-remove' aria-hidden='true' style='color:red'></span> </td> ";
                        

                      
                        $i++;
          }
}
          else {
          $goi_thau = DB::table('packages')->where('cate_id',$ma_linh_vuc)->orderBy('id', 'desc')->get();               $i =1 ;
                      foreach ($goi_thau as $value) {
                         $ngaycapnhat =  substr($value->created_at,0,10); 
                         $ngayhientai = date('Y-m-d');    
                        echo "<tr><td><input type='checkbox' name='id[]'' class='checkbox' value='{!!$value->id!!}'></td> <td>$i</td><td><a href='$value->link' target ='_blank'>$value->title";                          
                         if ($ngayhientai == $ngaycapnhat) {
                            echo "<span style='color: red;' class='dm_new'><strong> Hot! </strong></span> <span class='editlinktip hasTip' style='text-decoration: none; color: #333;'>";
                         echo "<img src='"; echo asset('/image/tooltip.png'); 
                         echo "' border='0' alt='Tooltip'></span> </td>";
                     }
                        echo "<td>$value->bidder</td>";
                        echo "<td align='center'>";
                         echo substr($value->created_at,0,10); 
                          echo " </td> ";
                         if (is_null($value->hided))
                            echo "                <td align='center'>   <span class='glyphicon glyphicon glyphicon-ok' aria-hidden='true' style='color:green'></span>  </td>";
                         else 
                         echo "<td align='center'>
                           <span class='glyphicon  glyphicon glyphicon-remove' aria-hidden='true' style='color:red'></span> </td> ";
                        $i++;
                          }
}

        }
        else echo "không";

    }

//// Gửi email gói thầu cho tất cả user
public function SendEmailGoiThauToAllUsers (){
    $data =DB::select("select * from users where status = '1' and level = '1' and receive_email = '1' ");
    $success = 0; //
   
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
                $goithau = DB::select("SELECT * FROM packages WHERE (title LIKE '%$key%' OR bidder LIKE '%$key%') AND hided IS NULL ORDER BY id DESC");   //       
                
                if(count($goithau) > 0){  //    
                
                    foreach ($goithau as $key => $value) {
                            $id_cankiemtra = $value->id;  
                            $id_taikhoan  = $id;                  
                            $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'"); 
                                            
                        
                            if (count($ketquakiemtra)==0){
                                //echo $value->title;                                 
                                 array_push($noidung,$value);
                                 $sent_emails = new SentEmail;
                                 $sent_emails->user_id=$id_taikhoan;
                                 $sent_emails->package_id = $id_cankiemtra;
                                 $sent_emails->save();
                               

                            }
                    }
                  } //
               
                
                }                 
            
         $count = count ($noidung);
         echo $count;
        // Gửi email                
            if ($count>0) {
            Mail::send('process.email',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin gói thầu');
            }); 

             //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
                  $success = 1;  //Gui thanh cong
            } 
         //   return    ProcessController::GuiGoiThau($mail,$noidung);     
        }
        if($success == 1){
            echo "Gửi thành công";
        }
        else{
            echo "Không có gói thầu để gửi email";
        }
}


//// Gửi email văn bản cho tất cả user
public function SendEmailVanBanToAllUsers (){
    $data =DB::select("select * from users where status = '1' and level = '1' and receive_email = '1' ");
    $success = 0; //
   
        $title = 'Đây là mail mới nhất';
         foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords2')->where('user_id',$id)->get();
            $noidung = array();    

            $count = 0;
            foreach ($tukhoa as  $bac2) {
                $key = $bac2->keyword;              
                $goithau = DB::select("SELECT * FROM packages2 WHERE title LIKE '%$key%' AND hided IS NULL ORDER BY id DESC");   //       
                
                if(count($goithau) > 0){  //    
                
                    foreach ($goithau as $key => $value) {
                            $id_cankiemtra = $value->id;  
                            $id_taikhoan  = $id;                  
                            $ketquakiemtra = DB::select("select * from sent2_email where user_id='$id_taikhoan' and package2_id ='$id_cankiemtra'"); 
                                            
                        
                            if (count($ketquakiemtra)==0){
                                //echo $value->title;                                 
                                 array_push($noidung,$value);
                                 $sent_emails = new SentEmail2;
                                 $sent_emails->user_id=$id_taikhoan;
                                 $sent_emails->package2_id = $id_cankiemtra;
                                 $sent_emails->save();
                               

                            }
                    }
                  } //
               
                
                }                 
            
         $count = count ($noidung);
         echo $count;
        // Gửi email                
            if ($count>0) {
            Mail::send('process.email2',array('noidung'=>$noidung,'count'=>$count,'name'=>$user_name,'keyword'=>$tukhoa),
             function($message) use ($mail) {
                $message->to($mail)->subject('Thông tin văn bản');
            }); 

             //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
                  $success = 1;  //Gui thanh cong
            } 
         //   return    ProcessController::GuiGoiThau($mail,$noidung);     
        }
        if($success == 1){
            echo "Gửi thành công";
        }
        else{
            echo "Không có văn bản pháp luật để gửi email";
        }
}


public function postEditNoKey(Request $request){
  try{
      $check = false;
      if($request->cbKey == 0){
        QtyKey::where('id', $request->rdTypeUser2)->update(['qty' => 0]);
        $check = true;
      }else{
        QtyKey::where('id', $request->rdTypeUser2)->update(['qty' => $request->noKey]);
        $check = true;
      }  
      if($check)
        return redirect()->back()->with("success", "Cập nhật số lượng từ khóa thành công");
      else
        return redirect()->back()->with("error", "Cập nhật số lượng từ khóa không thành công");
  }catch(QueryException $ex){
    return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
  }catch(PDOException $ex){
    return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
  } 
}

public function convertFile(){
  Excel::load(Input::file('excel'), function($reader){})->convert("csv");
}

public function addqtygoitahu(Request $request)
{
  try{
    $qty = $request->qty;
    //include($_SERVER['DOCUMENT_ROOT'].'/config/connect.php');
    include(base_path().'/config/connect.php');

    // mysqli_query($conn, "INSERT INTO config(config_id, config_name, config_value) VALUES ('1', 'Số lượng gói thầu', '1')");
    // mysqli_query($conn, "INSERT INTO config(config_name, config_value) VALUES ('Số lượng văn bản', '1')");
    // mysqli_query($conn, "INSERT INTO config(config_name, config_value) VALUES ('Số phút thực hiện cron job', '1')");

    //include($_SERVER['DOCUMENT_ROOT'].'/trolyao.cusc.vn_newDB/config/connect.php'); //
    if(is_numeric($qty)){
      $query = mysqli_query($conn, "UPDATE config SET config_value='$qty' WHERE config_name='Số lượng gói thầu'");
      if($query){
          return redirect()->back()->with("success", "Cập nhật số lượng hiển thị gói thầu thành công");
      }
      else{
          return redirect()->back()->with("error", "Cập nhật số lượng hiển thị gói thầu không thành công");
      }
    }
    else{
        return redirect()->back()->with("error", "vui lòng nhập dữ liệu là số");
    }
    // if(is_numeric($qty)){
    //     $fileName = "goithau(khongduocxoa).txt";
    //     $fs = fopen($fileName, 'w') or die("can't open file"); //Mở tập tin ở chế độ overite
    //     if(fwrite($fs, $qty)){
    //        fclose($fs);
    //        return redirect()->back()->with("success", "Cập nhật số lượng hiển thị gói thầu thành công");
    //    } 
    //    else{
    //       fclose($fs);
    //       return redirect()->back()->with("error", "Cập nhật số lượng hiển thị gói thầu không thành công");
    //   }
    // }else{
    //     return redirect()->back()->with("error", "vui lòng nhập dữ liệu là số");
    // }


  }catch(QueryException $ex){
    return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
  }catch(PDOException $ex){
    return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
  } 
}

  public function addqtyvanban(Request $request)
  {
      try{
        $qty = $request->qty;
        //include($_SERVER['DOCUMENT_ROOT'].'/config/connect.php');
        include(base_path().'/config/connect.php');
        //include($_SERVER['DOCUMENT_ROOT'].'/trolyao.cusc.vn_newDB/config/connect.php'); //
         if(is_numeric($qty)){
          $query = mysqli_query($conn, "UPDATE config SET config_value='$qty' WHERE config_name='Số lượng văn bản'");
            if($query){
                return redirect()->back()->with("success", "Cập nhật số lượng hiển thị văn bản thành công");
            }
            else{
                return redirect()->back()->with("error", "Cập nhật số lượng hiển thị văn bản không thành công");
            }
          //   $fileName = "vanban(khongduocxoa).txt";
          //   $fs = fopen($fileName, 'w') or die("can't open file"); //Mở tập tin ở chế độ overite
          //   if(fwrite($fs, $qty)){
          //      fclose($fs);
          //      return redirect()->back()->with("success", "Cập nhật số lượng hiển thị văn bản thành công");
          //  } 
          //  else{
          //     fclose($fs);
          //     return redirect()->back()->with("error", "Cập nhật số lượng hiển thị văn bản không thành công");
          // }
        }else{
            return redirect()->back()->with("error", "vui lòng nhập dữ liệu là số");
        }

      }catch(QueryException $ex){
        return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
      }catch(PDOException $ex){
        return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
      } 
  }

  public function getConfig($value='')
  {
    if(Auth::user()->level == 2){
      $qty3 = QtyKey::all();
      return view ('admin.config_page',compact("qty3"));
    }else{
      return redirect()->back();
    }
  }

  public function MinuteToCronJobs(Request $request)
  {
      try{
        $qty = $request->selectMinute;
        //include($_SERVER['DOCUMENT_ROOT'].'/config/connect.php');
        include(base_path().'/config/connect.php');
        //include($_SERVER['DOCUMENT_ROOT'].'/trolyao.cusc.vn_newDB/config/connect.php'); //
         if(is_numeric($qty)){
          $query = mysqli_query($conn, "UPDATE config SET config_value='$qty' WHERE config_name='Số phút thực hiện cron job'");
            if($query){
                return redirect()->back()->with("success", "Cập nhật số phút lấy dữ liệu thành công");
            }
            else{
                return redirect()->back()->with("error", "Cập nhật số phút lấy dữ liệu không thành công");
            }
          //   $fileName = public_path()."/sophutcronjobs.txt";
          //   $fs = fopen($fileName, 'w') or die("can't open file"); //Mở tập tin ở chế độ overite
          //   if(fwrite($fs, $qty)){
          //      fclose($fs);
          //      return redirect()->back()->with("success", "Cập nhật số phút lấy dữ liệu thành công");
          //  } 
          //  else{
          //     fclose($fs);
          //     return redirect()->back()->with("error", "Cập nhật số phút lấy dữ liệu không thành công");
          // }
        }else{
            return redirect()->back()->with("error", "Vui lòng chọn số phút cập nhật dữ liệu tự động");
        }

      }catch(QueryException $ex){
        return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
      }catch(PDOException $ex){
        return response(['error'=>true, 'message'=>$ex->getMessage()], 200);
      } 
  }

}
