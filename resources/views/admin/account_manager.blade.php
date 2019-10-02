@extends('admin_master')
@section('thongtin_active')
active
@endsection
@section('active')
Thông tin tài khoản
@endsection
@section ('content')
<?php if (isset($success))
      echo $success; 
   ?>
<meta name="_token" content="{{ csrf_token() }}">
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
   <?php  $data = DB::table('users')->where('id',Auth::user()->id)->get(); ?>
          @foreach ($data as $value) 
                       
     <div class="col-sm-12  ">
       <h2 style="text-align: center; "> Thông tin tài khoản</h2> 
     <div id="table" style="background:   #F5F5DC">
     <table class="table table-striped" style=" border: 1px solid #c4e2ff;">
    <thead>
      
    </thead>
    <tbody>
      <tr>
        <td >Họ tên: {!! $value->name !!}</td>
       
      </tr>
    </thead>
   
      <tr>
         <td>Email: {!! $value->email !!}</td>      
        
      </tr>
      <tr>
         <td>Loại tài khoản: @if (($value->level)==2)
                    Admin
                @else if 
                   Người dùng
                @endif
          </td>       
      </tr>
      <tr>
             
       
      </tr>
       <tbody>
    @endforeach
   
     </tbody>

     <tr> <td colspan="2">  <!-- Upload -->
     
    
         <button type="button" class="btn btn-success" data-dismiss="modal" id='EditMyAccount'>Thay đổi thông tin</button>

        </td>
       </tr>
  </table>
  </div>
  </div>
  </div>
  </div>

  </div>
  </div>


   <!--- Model Sửa -->
   <div class="modal fade" id="EditModel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thay đổi thông tin tài khoản</h4>
        </div>
        <div class="modal-body">
          <div class="form-group" id="kq">         
          </div>
      <span  align="center" ><strong>Thêm ảnh đại diện của bạn</strong></span>
        <form style="margin-top: 5px;" action="{{ url('admin/image') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <input id="sortpicture" type="file" name="sortpic" value="Chọn ảnh" />
        <span id="error" > @if(isset($error))
                            {{$error}}
                          @endif
            </span>
          </div>
          <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id='Save'>Cập nhật</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Đóng</button>

          </div>
      </div>
      
    </div>
  </div>

<div id="result"> </div>

<script type="text/javascript">
 // Thay đổi thông tin 

     $("#EditMyAccount").click(function(){
           

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
             var id = $(this).val(); 

     
          var data = {
              id : <?php echo Auth::user()->id;?> ,            
              _token : $('meta[name="_token"]').attr('content'),            
        };
          $.ajax({                   
          type : 'get',          
          url : 'getEditMyAccount', //Here you will fetch records 
          data : data,         
          success : function(data){
            $('#kq').html(data);//Show fetched data from database
          }
        });
     });


     // Lưu thông tin 

     $("#Save").click(function(){    

         // Thay đổi ảnh đại diện
         //kiem tra trinh duyet co ho tro File API
    var file_data = $('#sortpicture').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
                               
    $.ajax({
                url: 'image', // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){
                  //  alert(data); // display response from the PHP script, if any
                }
     });
         //
        
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
            var id = <?php echo Auth::user()->id;?> ; 
            var userName = $('#edtName').val();
            var email = $('#edtEmail').val();
            var txtPass = $('#edtPass').val();
            var txtRePass = $('#edtRePass').val(); //Add this line
            var level  = $("input[type='radio'].rdLevel:checked").val();    
            var status  = $("input[type='radio'].rdStatus:checked").val();    

     
          var data = {
              id :id,
              txtUser : userName,
              txtEmail     : email,  
              txtPass      : txtPass,    
              txtRePass    : txtRePass,  //Add this line
              _token : $('meta[name="_token"]').attr('content'),            
        };
          $.ajax({                   
          type : 'get',          
          url : 'edit_account', //Here you will fetch records 
          data : data,         
          success : function(data){
            $('#result').html(data);//Show fetched data from database
          }
        });
            
     
     });





  $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
  $(document).ready(function() {
    $("#upload").click (function(){
         $.ajax({                   
          type : 'get',          
          url : 'image', //Here you will fetch records                 
          success : function(data){
            $('#error').html(data);//Show fetched data from database
          }
        });
    })
   
  });
</script>
<script type="text/javascript">
 $('#upload').click( function() {
   //kiem tra trinh duyet co ho tro File API
    if (window.File && window.FileReader && window.FileList && window.Blob)
    {
      // lay dung luong va kieu file tu the input file
        var fsize = $('#file')[0].files[0].size;
        var ftype = $('#file')[0].files[0].type;
        var fname = $('#file')[0].files[0].name;
 
       switch(ftype)
        {
            case 'image/png':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/pjpeg':
                alert("Cập nhật thành công");
                break;
            default:
                alert('Tệp không phải ảnh!');
        }
 
    }else{
        alert("Please upgrade your browser, because your current browser lacks some new features we need!");
    }
});
 </script>
@endsection

  