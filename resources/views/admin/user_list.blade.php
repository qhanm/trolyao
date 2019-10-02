@extends('admin_master')
@section('list_active')
active
@endsection
@section('active')
Quản lý người dùng
@endsection
@section ('content')
<link rel="stylesheet" type="text/css" href="{{ asset('mystyle.css') }}">
<script src="{{ asset('libs/datatables.min.js') }}"></script>
<link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">
<script type="text/javascript">
  $(document).ready(function(){
   $('#lst').DataTable();
 });
</script>
<style type="text/css" media="screen">
  .error{color: red;}
</style>
      <!-- Page Content -->
      <div class="col-sm-12">
        <div id="page-wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">

               <h3  style="text-align: center; " >Danh sách tài khoản </h3>


             </div>
             <!-- /.col-lg-12 -->
             <script src="{{ asset('js/script_bm.js') }}"></script>
            
             <div style="margin-top: 100px;" >
              <table class="table table-striped table-bordered table-hover" id="lst">
                <thead>
                 <tr>
                  <td colspan="10">

                    <!--<input type="submit" class="btn btn-danger" name="btn_delete" id="btn_delete" value="Xóa" style="float: right;margin-left: 10px">-->
                    <form>
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="Delete">Xóa tài khoản</button>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="btnAdd" id="btnAdd">Thêm tài khoản</button>
                    </form>
                  </td>
                </tr>

                  <tr align="center">
                    <th width="0"><input style="" type="checkbox" onClick="toggle(this)" name="" value="" ><label style="margin-left: 5px;">Tất cả</label> </th>

                    <th width="10">Stt</th>

                    <th>Họ tên</th>
                    <th>Email </th>
                    <th>Cấp độ</th>
                    <th>Người dùng</th>
                    <th>Trạng thái</th>
                    <th>Nhận email hằng ngày</th>
                    <th>Xóa</th>
                    <th>Cập nhật</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1;?>
                  @foreach ($user as $value)
                  <tr class="odd gradeX" align="center">
                   <td align="center"><input type="checkbox" name="id[]" class="checkbox" value="{!!$value['id']!!}"></td>
                   <td>{{$i++}}</td>
                   <td style="text-align: left;">{!!$value['name']!!}</td>
                   <td style="text-align: left;">{!!$value['email']!!}</td>
                   <td>
                    @if ($value['level']==2)
                      Admin
                    @elseif ($value['level']==1)
                      User
                    @endif
                  </td>
                   <td>
                    @if ($value['type']==3)
                      Vip
                    @elseif ($value['type']==2)
                      Bình Thường
                    @else
                      Admin
                    @endif
                  </td>
                  <td>
                    @if ($value['status']==1)
                    <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span>
                    @else
                    <span class="glyphicon  glyphicon glyphicon-minus-sign" aria-hidden="true" style="color:red"></span>

                    @endif
                  </td>
                  <td>
                    @if ($value['receive_email']==1)
                    <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span>
                    @else


                    @endif
                  </td>
                  <td>
                    <button class="btn btn-primary btn-xs   glyphicon glyphicon-trash delete-user"  value="{!!$value['id']!!}">

                    </button>
                  </td>

                  <td class="center"> <button class="btn btn-primary btn-xs   glyphicon glyphicon-pencil Edit_Model"  value="{!!$value['id']!!}">

                  </button> </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
  </div>
  <!-- /.col-sm-12 -->

    <!-- Trigger the modal with a button -->

  <!-- Modal thêm-->
  <div class="modal fade" id="addModel" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm tài khoản</h4>
        </div>
        <form action="" method="POST" id="addForm">
        <div class="modal-body">
          <div class="form-group">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="form-group">
                <label>Tên người dùng</label>
                <input class="form-control" name="name" id="name" placeholder="Nhập Họ Tên" />
              </div>
              <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" class="form-control" id="txtPass" name="txtPass" placeholder="Nhập Mật Khẩu" />
              </div>
              <div class="form-group">
                <label>Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="txtRePass" id="txtRePass" placeholder="Nhập lại mật khẩu" />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Nhập email" />
                <span style="color:red" id="errorEmail" class="hidden"></span>
              </div>
              <div class="form-group">
                <label>Cấp độ</label>
                <label class="radio-inline">
                  <input name="rdoLevel" id="adminadd" value="2" checked="" type="radio" onclick="changeType2();">Admin
                </label>
                <label class="radio-inline">
                  <input name="rdoLevel" id="useradd" value="1" type="radio" onclick="changeType2();">Thành viên
                </label> 
              </div>
               <div class="form-group" id="cbPacket">
                <label for="typePacket">Chọn danh sách nhận tin:</label>
                <select class="form-control" id="typePacket" name="typePacket">
                  <option value="1">Văn bản pháp luật</option>
                  <option value="2">Gói thầu</option>
                  <option value="3">Tất cả</option>
                </select>
              </div> 
              <p id="errorLogin" style="color: red"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Thêm</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        </div>
        </form>
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
          <h4 class="modal-title">Cập nhật tài khoản</h4>
        </div>
        <form action='' method="post"  id = 'editForm'>
          <div class="modal-body">
          <div class="form-group" id="kq">




          </div>
          </div>
          <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id='Save'>Cập nhật</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Đóng</button>

          </div>
          </form>
      </div>
    
    </div>
  </div>




    <div id="result"></div>


<script type="text/javascript">
  var listQtyKey = <?php echo $qty; ?>;


  function changeType2(){       
    if($("#adminadd").prop("checked")){
       $("#cbPacket").addClass('hidden');
    }         
    if($("#useradd").prop("checked")){
      $("#cbPacket").removeClass('hidden');
    }
  }

  function changeType(){       
    if($("#admin").prop("checked")){
       $("#type_user").addClass('hidden');
       $("#cbPacket2").addClass('hidden');
    }         
    if($("#user").prop("checked")){
      $("#type_user").removeClass('hidden');
      $("#cbPacket2").removeClass('hidden');
    }
  }

 
// Xóa người dùng
 // Chọn TẤT CẢ
 $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
  }
});

 function toggle(source) {
  checkboxes = document.getElementsByName('id[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
// Truyền dữ liệu xóa
 //Xóa từ khóa
 $("#Delete").click(function(){

  var r = confirm("Bạn có muốn xóa người dùng đã chọn?");
  if (r == true) {
   var id = [];
   $(':checkbox:checked').each(function(i){
     id[i] = $(this).val();
   });

   $.ajax({
    type : 'get',
      url : 'user_delete_many', //Here you will fetch records
      data : {id:id},
      success : function(data){
        alert("Xóa thành công");
        setTimeout(function(){
          location.reload();
          
        }, 200);
      }
    });

 } else {
  txt = "You pressed Cancel!";
}

})



// Thêm người dùng
 // Thêm từ khóa
  // Thêm từ khóa
  $("#btnAdd").click(function(){
     $('#addModel').modal('show');
     $('#adminadd').prop("checked", true);
     $('#cbPacket').addClass('hidden');
  });


/*  $("#Add").click(function(){
   // $('#modelEdit').modal('show');


     alert(userName);
 });
 */

 // $("#Add").click(function(){

 //   $.ajaxSetup({
 //    headers: {
 //      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
 //    }
 //  });
 //   var userName = $('#txtUser').val();
 //   var email = $('#txtEmail').val();
 //   var txtPass = $('#txtPass').val();
 //   var level = $('input[name=rdoLevel]:checked', '#addForm').val();

 //   var data = {
 //    txtUser : userName,
 //    txtEmail     : email,
 //    txtPass      : txtPass,
 //    rdoLevel : level,
 //    _token : $('meta[name="_token"]').attr('content'),
 //  };
 //  $.ajax({
 //    type : 'get',
          //url : 'add_account', //Here you will fetch records
         // data : data,
          //success : function(data){
          //  $('#result').html(data);//Show fetched data from database
           // window.location = "http:\/\/trolyvanban.haugiang.gov.vn/";
          //}
        //});
 // location.reload();
//});





 $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
  }
});

 $(".keyword").click (function(){
  var id =  ($(this).attr('value'));
  var data = {
    user_id : id ,

  };

  $.ajax({
    type : 'get',
          url : 'model_keyword', //Here you will fetch records
          data : data,
          success : function(data){
            $('#result').html(data);//Show fetched data from database
          }
        });

});



 // Chỉnh Sửa Từ Khóa
 $(".Edit_Model").click(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });
  var id = $(this).val();
  var data = {
    id : id,
    _token : $('meta[name="_token"]').attr('content'),
  };
  $.ajax({
    type : 'get',
    url : 'edit_ajax', //Here you will fetch records
    data : data,
    success : function(data){
      $('#kq').html(data);//Show fetched data from database

    }
  });
          // $('#EditModel').modal('show');
          //  location.reload();
});


// Xu ly luu tru




// Xóa tài khoản

$(".delete-user").click (function(){
  var r = confirm("Bạn có muốn xóa người dùng đã chọn?");
  if (r == true) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
    });
    var id =  ($(this).attr('value'));
    var data = {
      id : id ,
      _token : $('meta[name="_token"]').attr('content'),

    };
    $.ajax({
      type : 'get',
      url : 'user_delete', //Here you will fetch records
      data : data,
      success : function(data){  
        alert("Xóa thành công");
        setTimeout(function(){
          location.reload();
        }, 200);
         
      }
    });
  } else {
    txt = "You pressed Cancel!";
  }
});



</script>
<script type="text/javascript">

 // $("#Save").click(function(){

  //  $.ajaxSetup({
  //   headers: {
  //     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
  //   }
  // });
  //  var id = $('#_id').val();
  //  var userName = $('#edtName').val();
  //  var email = $('#edtEmail').val();
  //  var txtPass = $('#edtPass').val();
  //  var level  = $("input[type='radio'].rdLevel:checked").val();
  //  var status  = $("input[type='radio'].rdStatus:checked").val();
  //  if ($("#cbEmail").is(":checked"))
  //  {
  //   var receive_email  = 1;


  // } else receive_email  = null;



//   var data = {
//      id :id,
//      txtUser : userName,
//      txtEmail     : email,
//      txtPass      : txtPass,
//      rdoLevel : level,
//      rdoStatus : status,
//      cbEmail:receive_email,
//      _token : $('meta[name="_token"]').attr('content'),
//    };
//    $.ajax({
//     type : 'get',
//     url : 'edit-user', //Here you will fetch records
//     data : data,
//     success : function(data){
//       $('#result').html(data);//Show fetched data from database
//     }
//   });

// });

// Sửa người dùng
  $(function(){

    $("#editForm").validate({
      rules: {
        edtName: {   
          required: true  
        },
        edtEmail: {
          required: true,
          email: true
        },
        edtPass : { 
          maxlength:32,
          minlength:8
        },
        editRePass : { 
          equalTo: "#edtPass" 
        },
      }, 
      messages: {
        edtName: {
          required: "Xin vui lòng nhập tên !"
        },
        edtEmail: {
          required: "Xin vui lòng nhập email !",
          email: "Email không đúng định dạng"
        },
        edtPass: {
          maxlength : "Mật khẩu không được quá 32 ký tự",
          minlength : "Mật khẩu không được nhỏ hơn 8 ký tự",
        },
        editRePass: {
          equalTo : "Nhập lại mật khẩu không chính xác!",
        }
      }, 
      submitHandler: function(form) {
        var id = $('#_id').val();
        var userName = $('#edtName').val();
        var email = $('#edtEmail').val();
        var txtPass = $('#edtPass').val();
        var level  = $("input[type='radio'].rdLevel:checked").val();
        var status  = $("input[type='radio'].rdStatus:checked").val();
        var type  = $("input[type='radio'].rdType:checked").val();
        var cbPacket2 = $('#typePacket2').val();
        //var receive_email  = 1;
        if ($("#cbEmail").is(":checked"))
        {
          var receive_email  = 1;
        } else receive_email  = null;

        var data = {
         id :id,
         txtUser : userName,
         email     : email,
         txtPass      : txtPass,
         rdoLevel : level,
         rdoStatus : status,
         cbEmail : receive_email,
         type: type,
         cbPacket2: cbPacket2,
         _token : $('meta[name="_token"]').attr('content'),
       };

        $.ajax({
          type: "get", 
          url: "edit-user", 
          data: data,
           headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          success: function(data){
            if(data.error == true){
              if(data.message.email != undefined)
                $('#errorEmail2').removeClass("hidden").text(data.message.email[0]); 
            }else{
              if(data.message == true){
                location.reload();
              }
              else{
                $("#errorLogin").text("Sửa Không thành công vui lòng kiểm tra thông tin!").show().fadeOut( 5000 );
              }
            }
          } ,error: function() {
            console.log("ERROR! Can't to login");
          }
        });
      }
    });

    // $("#addKeyForm").validate({
    //   submitHandler: function(form) {
    //     var qty = $('#noKey').val();
    //     var type  = $("input[type='radio'].rdTypeUser:checked").val();
    //     if ($("#cbKey").is(":checked"))
    //     {
    //       var cbKey  = 0;


    //     } else cbKey  = 1;



    //     var data = {,
    //      qty : status,
    //      cbKey: cbKey,
    //      type: type,
    //      _token : $('meta[name="_token"]').attr('content'),
    //    };

    //     $.ajax({
    //       type: "get", 
    //       url: "editNoKey", 
    //       data: data,
    //        headers: {
    //         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //       },
    //       success: function(data){
           
    //         if(data.message == true){
    //           location.reload();
    //         }
    //         else{
    //           $("#errorLogin").text("Sửa Không thành công vui lòng kiểm tra thông tin!").show().fadeOut( 5000 );
    //         }
            
    //       } ,error: function() {
    //         console.log("ERROR! Can't to login");
    //       }
    //     });
    //   }
    // });

  });
</script>


<script>
  //Cập nhật (31/10/2018) Thêm người dùng
  $(function(){
    $("#addForm").validate({
      rules: {
        name: {   
          required: true  
        },
        txtEmail: {
          required: true,
          email: true
        },
        txtPass : { 
          required: true, 
          maxlength:32,
          minlength:8
        },
        txtRePass : { 
          required: true, 
          equalTo: "#txtPass" 
        },
      }, 
      messages: {
        name: {
          required: "Xin vui lòng nhập tên !"
        },
        txtEmail: {
          required: "Xin vui lòng nhập email !",
          email: "Email không đúng định dạng"
        },
        txtPass: {
          required: "Xin vui lòng nhập mật khẩu !",
          maxlength : "Mật khẩu không được quá 32 ký tự",
          minlength : "Mật khẩu không được nhỏ hơn 8 ký tự",
        },
        txtRePass: {
          required: "Xin vui lòng nhập lại mật khẩu !",
          equalTo : "Nhập lại mật khẩu không chính xác!",
          
        }
      }, 
      submitHandler: function(form) {
        $.ajax({
          type: "get", 
          url: "adduser", 
          data: {
            name : $('#name').val(),
            email : $('#txtEmail').val(),
            txtPass : $('#txtPass').val(),
            rdoLevel : $('[name="rdoLevel"]:radio:checked').val(),
            cbPacket: $('#typePacket').val(),
            receive_email : 1, //Gan trang thai nhan email hang ngay la true
             _token : $('meta[name="_token"]').attr('content'),
          },
          success: function(data){
            if(data.error == true){
              if(data.message.email != undefined)
                $('#errorEmail').removeClass("hidden").text(data.message.email[0]); 
            }else{
              if(data.message == true){
                location.reload();
              }
                 
              else{
                $("#errorLogin").text("Thêm Không thành công vui lòng kiểm tra thông tin!").show().fadeOut( 5000 );
              }
            }
          } ,error: function() {
            console.log("ERROR! Can't to login");
          }
        });
      }
    });
  });
</script>
@endsection
