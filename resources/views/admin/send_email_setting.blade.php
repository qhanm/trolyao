@extends('admin_master')
@section ('active')
Quản lý email về gói thầu
@endsection
@section('email_active')
active
@endsection
@section ('content')

<script src="{{ asset('libs/datatables.min.js') }}"></script>
<link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">

<meta name="_token" content="{{ csrf_token() }}">

   <?php // $data = DB::table('keywords')->where('user_id',Auth::user()->id)->get();  
   $user_array = DB::table('users')->where('level',1)->get();
   ?>

   <div  class="col-sm-12">
    <div style="text-align: center; margin-top: 50px;">
     <div id ="loader" align="center" style="display: none;">
      <div  class="loader">

      </div>
      Đang gửi email đến người dùng...
    </div>
    <h3 >Danh sách email người dùng</h3>    
  </div>
  <div id="table_bm"> 
   <table class= 'table table-bordered table-striped' id='lst'>
    <thead>
      <tr>
        <td colspan="9">

          <!--<input type="submit" class="btn btn-danger" name="btn_delete" id="btn_delete" value="Xóa" style="float: right;margin-left: 10px">-->
          <form>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">                      
            <button  type="button" class="btn btn-success  " data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="Delete"> <i class="glyphicon glyphicon-send"> </i> <span  style="margin-right: 5px;"> Gửi người được chọn </span>  </button>


          </form>

        </td>
      </tr>
      <tr>
        <th width="120"><input style="" type="checkbox" onClick="toggle(this)" name="" value="" ><label style="margin-left: 5px;">Chọn tất cả</label> </th>
        <th width="30">Stt</th>
        <th>Tên</th>
        <th>Email</th>
        <th width="100">Nội dung email</th>

        <th width="100">Gửi email</th>
      </tr>
    </thead> 
    <?php $i = 1; ?>
    @foreach ($user_array as $value)
    <tr id="">
     <td><input type="checkbox" name="id[]" class="checkbox" value="{!!$value->id!!}"></td>
     <td>{!! $i++ !!}</td>
     <td >{!!$value->name!!}</td>
     <td >{!!$value->email!!}</td>

     <td style="text-align: center;"> <button class="btn btn-primary btn-xs edit_bm   glyphicon glyphicon-blackboard chitiet"  data-toggle="modal" data-target="#modal_edit_keywork" value="{!!$value->id!!}"></button></td>
     <td style="text-align: center;">
      <button class="btn btn-primary btn-xs edit_bm   glyphicon glyphicon-send tukhoa"  data-toggle="modal" data-target="#modal_edit_keywork" value="{!!$value->id!!}"></button>


    </td>
  </tr>

  @endforeach

</table>

<!-- Model Cập Nhật thông tin -->
<!-- Modal -->
<!-- Button trigger modal -->



<!-- Modal -->
<!-- Trigger the modal with a button -->


<!-- Modal thêm-->
<div class="modal fade" id="ViewEmail" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nội dung email</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-success" data-dismiss="modal">Đóng</button>

      </div>
    </div>

  </div>
</div>




<div id = "result"></div>




<script type="text/javascript">


  $(document).ready(function(){


    //Xóa từ khóa
    $("#Delete").click(function(){
      var r = confirm("Bạn có muốn gửi email đến những người được chọn?");
      if (r == true) {
       var id = [];  
       $("#loader").show();
       $(':checkbox:checked').each(function(i){  
         id[i] = $(this).val();  
       });  

       $.ajax({                   
        type : 'get',          
          url : 'sent_mail_many', //Here you will fetch records 
          data : {id:id},         
          success : function(data){

            $('#result').html(data);//Show fetched data from database
          }
        });
           //location.reload();
         } else {
          txt = "You pressed Cancel!";
        }

        


      })

 // Chỉnh Sửa Từ Khóa 
 $(".tukhoa").click(function(){
   // $('#modelEdit').modal('show');
   var id = $(this).val(); 
   $("#loader").show();
   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });

   var data = {
    id : id,

    _token : $('meta[name="_token"]').attr('content'),            
  };
  $.ajax({                   
    type : 'get',          
          url : 'custom_send_mail', //Here you will fetch records 
          data : data,         
          success : function(data){
            $('#result').html(data);//Show fetched data from database
             $("#loader").hide();
          }
        });

});

  // Thêm từ khóa
  $("#modelAdd").click(function(){
   $('#myModal').modal('show');
 });
  


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });


  $("#Add").click(function(){


    var data = {
      user_id : <?php echo Auth::user()->id?>,
      keyword     : $("#keyword").val(),               
      _token : $('meta[name="_token"]').attr('content'),            
    };
    $.ajax({                   
      type : 'get',          
          url : 'addkey', //Here you will fetch records 
          data : data,         
          success : function(data){
            $('#result').html(data);//Show fetched data from database
          }
        });
    location.reload();

  });
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
   $('#lst').DataTable();
 });
</script>

<script language="JavaScript">
  function toggle(source) {
    checkboxes = document.getElementsByName('id[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
    }
  }


// Xem chi tiết mail
 // Chỉnh Sửa Từ Khóa 
 $(".chitiet").click(function(){
   // $('#modelEdit').modal('show');
   var id = $(this).val(); 

   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });

   var data = {
    id : id,

    _token : $('meta[name="_token"]').attr('content'),            
  };
  $.ajax({                   
    type : 'get',          
          url : 'view_email', //Here you will fetch records 
          data : data,         
          success : function(data){
            $('#result').html(data);//Show fetched data from database
          }
        });

});

</script>

</div>
</div>
@endsection

