@extends('admin_master')
@section ('active')
Quản lý gói thầu
@endsection
@section('pack_active')
active
@endsection
@section ('content')
<script src="{{ asset('libs/datatables.min.js') }}"></script>
<link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">

<!-- Page Content -->
<div id="page-wrapper">
  <div class="container-fluid">
   
    <div class="row">
      <div class="col-lg-12">
        <div id ="loader" align="center" style="display: none; ">
          <div  class="loader">
          </div>
          Đang cập nhật dữ liệu...
        </div>
        <h3 style="text-align: center; "> Danh sách các gói thầu  </h3> 
        <span > Thông tin được lấy từ  Hệ Thống Đấu Thầu Điện Tử của bộ Kế Hoạch và Đầu Tư tại địa chỉ: <a href="http://muasamcong.mpi.gov.vn" target="_blank">http://muasamcong.mpi.gov.vn</a></span>

      


      <div style="margin: 10px;">

        <div class="form-group" style="width: 250px; margin-top: 10px;">
          <label for="sel1">Chọn lĩnh vực đấu thầu:</label>
          <select class="form-control" id="luachon">
           <?php $linhvuc =  DB::table('category')->get(); ?>
           <option  value="all"> <strong> Tất cả</strong></option>
           @foreach ($linhvuc as $value )
           <option  value="{{$value->cate_id}}"> <strong>  {{$value->cate_name }}</strong></option>
           @endforeach
           <?php
           $ngayhientai = date('Y-m-d'); 

           ?>
         </select>
       </div>
     </div>

     <!-- /.col-lg-12 -->
     <table class="table table-striped table-bordered table-hover" id="lst">
      <thead>
        <tr>
          <td colspan="9">

            <!--<input type="submit" class="btn btn-danger" name="btn_delete" id="btn_delete" value="Xóa" style="float: right;margin-left: 10px">-->
            <form>

              <input type="hidden" name="_token" value="{{ csrf_token() }}">  


              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="Delete">Xóa gói thầu</button>

              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="ShowHide">Hiện/Ẩn</button>   

              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="update_package">Cập nhật </button>


            </form>

          </td>
        </tr>
        <tr align="center">
         <th width="0"><input style="" type="checkbox" onClick="toggle(this)" name="" value="" ><label style="margin-left: 5px;"></label> </th>

         <th width="10">Stt</th>                                
         <th width="500">Tên gói thầu</th>
         <th>Bên mời thầu</th>
         <th width="80">Ngày đăng</th>
         <th width="70">Hiển thị</th>

       </tr>
     </thead>
     <tbody id="result">
       <?php  

       $data = DB::table('packages')->distinct()->orderBy('id', 'desc')->get(); 
       $i=1;
       ?>
       @foreach ($data as $value) 


       <tr>
        <td><input type="checkbox" name="id[]" class="checkbox" value="{!!$value->id!!}"></td>       
        <td>{{$i}}</td>
        <td><a target = '_blank' href = "{!!$value->link!!}">{!!$value->title!!}
         <?php $ngaycapnhat =  substr($value->created_at,0,10); ?>
         @if ($ngayhientai == $ngaycapnhat) 
         <span style="color: red;" class="dm_new"><strong> Mới! </strong></span> <span class="editlinktip hasTip" style="text-decoration: none; color: #333;"><img src="{{asset('image/tooltip.png')}}" border="0" alt="Tooltip"></span>
         @endif
       </a></td>
       <td>{!!$value->bidder!!}</td>
       <td align="center"> 
       	<?php
       		$date = substr($value->created_at,0,10);
       		$day = substr($date, 8, 2);
       		$month = substr($date, 5, 2);
       		$year = substr($date, 0, 4);
       		echo $day.'-'.$month.'-'.$year;
       	?>
       	<!-- {{substr($value->created_at,0,10)}}  -->
   	   </td> 
       @if (is_null($value->hided))
       <td align="center">   <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true" style="color:green"></span>  </td>
       @else 
       <td align="center">
         <span class="glyphicon  glyphicon glyphicon-remove" aria-hidden="true" style="color:red"></span> </td> 
         @endif




       </tr>
       <?php $i++; ?>
       @endforeach
     </tbody>
   </table>

 </div>
</div>
<!-- /.row -->

<!-- /.container-fluid -->


</div>
</div>


<!-- /#page-wrapper -->
<script type="text/javascript">
  $(document).ready(function(){
   $('#lst').DataTable(


    );
});
</script>
<script type="text/javascript">

   

  $("#luachon").change(function(){
   var ma_linh_vuc = ($(this).val());

   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
  });


   var data = {
    ma_linh_vuc : ma_linh_vuc,             
    _token : $('meta[name="_token"]').attr('content'),            
  }; 

  $.ajax({                   
    type : 'get',          
            url : 'linhvuc_admin', //Here you will fetch records 
            data : data,         
            success : function(data){
            $('#result').html(data);//Show fetched data from database
          }
        });


});


</script>

<script language="JavaScript">
  function toggle(source) {
    checkboxes = document.getElementsByName('id[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
    }
  }

// Xóa gói thầu
 //Xóa từ khóa
 $("#Delete").click(function(){
  var r = confirm("Bạn có muốn xóa gói thầu đã chọn?");
  if (r == true) {
   var id = [];  
   $(':checkbox:checked').each(function(i){  
     id[i] = $(this).val();  
   });  

   $.ajax({                   
    type : 'get',          
          url : 'delete_package', //Here you will fetch records 
          data : {id:id},         
          success : function(data){

            $('#result').html(data);//Show fetched data from database
          }
        });
   setTimeout(function() 
   {
     // alert("Xóa thành công");
     location.reload(); 
   }, 500);

 } else {
  txt = "You pressed Cancel!";
}
})

 
    // Ẩn hiện dữ liệu

    $("#ShowHide").click(function(){
      var r = confirm("Bạn có muốn thay đổi trạng thái gói thầu?");
      if (r == true) {
       var id = [];  
       $(':checkbox:checked').each(function(i){  
         id[i] = $(this).val();  
       });  

       $.ajax({                   
        type : 'get',          
          url : 'showandhide', //Here you will fetch records 
          data : {id:id},         
          success : function(data){

            $('#result').html(data);//Show fetched data from database
          }
        });
       setTimeout(function() 
       {
     // alert("Xóa thành công");
     location.reload(); 
   }, 500);

     } else {
      txt = "You pressed Cancel!";
    }




  })
// Cập nhật
$("#update_package").click(function(){     
  $("#loader").show();         

  $.ajax({                   
    type : 'get',          
          url : 'process/update_package', //Here you will fetch records   

          success : function(data){

            $('#result').html(data);//Show fetched data from database

          }
        });

  setTimeout(function() 
  {
    $("#loader").hide();  
    location.reload(); 

  }, 9000);  //Neu server nhanh thi giam thoi gian cho xuong, de tiet kiem thoi gian cua nguoi dung





})




</script>


<link rel="stylesheet" type="text/css" href="{{asset('datatable/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('datatable/buttons.dataTables.min.css')}} ">
<link rel="stylesheet" type="text/css" href="{{asset('datatable/datatables.min.css')  }}">
<script type="text/javascript" src="{{asset('datatable/bootstrap.min.css')}}"></script>
<script type="text/javascript" src="{{asset('datatable/vfs_fonts.js')  }}"></script>
<script type="text/javascript" src="{{ asset('datatable/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{asset('datatable/jszip.min.js')  }}"></script>
<script type="text/javascript" src="{{asset('datatable/jquery-1.12.4.js')  }}"></script>
<script type="text/javascript" src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{asset('datatable/datatables.min.js')  }}"></script>
<script type="text/javascript" src="{{asset('datatable/dataTables.buttons.min.js')  }}"></script>
<script type="text/javascript" src="{{asset('datatable/buttons.print.min.js')  }}"></script>
<script type="text/javascript" src="{{ asset('datatable/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('datatable/buttons.flash.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#lst').DataTable( {
     dom: 'Blfrtip',

        // "dom": '<"top"i>rt<"bottom"flp><"clear">',
        buttons: [
        'copy',  'excel', 'print'
        ],
         //paging: true
         
       } );


  } );
</script>
@endsection
