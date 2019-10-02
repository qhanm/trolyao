@extends('user_master')
@section ('dashboard_active')
active
@endsection
@section('active')
Dashboard
@endsection
@section ('content')
<script src="{{ asset('libs/datatables.min.js') }}"></script>
    <link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">
 
   
   <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 style="text-align: center; "> Dashboard  </h2> </br>
     <span > Thông tin được lấy từ địa chỉ: <a href="http://vbpl.vn/pages/vanbanmoi.aspx" target="_blank">http://vbpl.vn/pages/vanbanmoi.aspx</a> và <a href="http://muasamcong.mpi.gov.vn" target="_blank">http://muasamcong.mpi.gov.vn</a></span> </br>


<?php
    $user_id = Auth::user()->id;  //Lấy user_id hiện tại
    //echo $user_id;
    $count_goithau = 0; 
    $count_vanban = 0;

    //Thực hiện đếm tổng số gói thầu có liên quan đến các từ khóa tìm kiếm
    $tukhoa = DB::table("keywords")->where("user_id", $user_id)->get();
    foreach ($tukhoa as $key => $value) {
        $key = $value->keyword;
        $data = DB::select("SELECT * FROM packages WHERE (title LIKE '%$key%' OR bidder LIKE '%$key%') AND hided IS NULL ORDER BY id DESC");

        if(count($data) > 0){
            $count_goithau += count($data);
        }
    }

    //Thực hiện đếm tổng số văn bản có liên quan đến các từ khóa tìm kiếm
    $tukhoa_vanban = DB::select("SELECT * FROM keywords2 WHERE user_id='$user_id'");
    foreach ($tukhoa_vanban as $key => $value) {
        $key_vanban = $value->keyword;
        $data_vanban = DB::select("SELECT * FROM packages2 WHERE title LIKE '%$key_vanban%' AND hided IS NULL ORDER BY id DESC");
        if(count($data_vanban) > 0){
            $count_vanban += count($data_vanban);
        }

    }
?>
  
  <p> <i> Có tổng cộng <a href="{{route('find_package')}}"><?=$count_goithau;?></a> gói thầu liên quan đến các từ khóa mà bạn đã tìm kiếm </i></br>
  <i>Có tổng cộng <a href="{{route('find_package_vanban')}}"><?=$count_vanban;?></a> văn bản liên quan đến các từ khóa mà bạn đã tìm kiếm </i></p>

<?php 
  // Lấy ngày hiện tại
    $today = date('Y-m-d H:i:s');
    //echo "Hôm nay là ". $today."</br>";

    // Trừ đi 1 tuần
    $lastweek = strtotime(date("Y-m-d", strtotime($today)) . " -1 week");
    $lastweek = strftime("%Y-%m-%d", $lastweek);
    //echo "Một tuần vừa qua là ".$lastweek."</br>";

    // Cộng thêm 1 tuần
    $nextweek = strtotime(date("Y-m-d", strtotime($today)) . " +1 week");
    $nextweek = strftime("%Y-%m-%d", $nextweek);
    //echo "Một tuần sau là ".$nextweek."</br>";

    // Trừ đi 1 tháng
    $lastmonth = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
    $lastmonth = strftime("%Y-%m-%d", $lastmonth);
    //echo "Một tháng vừa qua là ".$lastmonth."</br>";

    // Cộng thêm 1 tháng
    $nextmonth = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
    $nextmonth = strftime("%Y-%m-%d", $nextmonth);
    //echo "Một tháng sau là ".$nextmonth."</br>";

    //Kiểm tra trong tuần có bao nhiêu gói thầu mới
    $data_in_week = DB::select("SELECT * FROM packages WHERE (created_at >= '$lastweek' AND created_at <= '$today') AND hided IS NULL");
    echo "Có tất cả ".count($data_in_week)." gói thầu mới trong tuần vừa qua (từ ngày ".$lastweek." đến ngày ".substr($today,0,10).") </br>";

    //Kiểm tra trong tuần có bao nhiêu văn bản mới
    $data_vanban_in_week = DB::select("SELECT * FROM packages2 WHERE (created_at >= '$lastweek' AND created_at <= '$today') AND hided IS NULL");
    echo "Có tất cả ".count($data_vanban_in_week)." văn bản mới trong tuần vừa qua (từ ngày ".$lastweek." đến ngày ".substr($today,0,10).") </br></br>";

    //Kiểm tra trong tháng có bao nhiêu gói thầu mới
    $data_in_month = DB::select("SELECT * FROM packages WHERE (created_at >= '$lastmonth' AND created_at <= '$today') AND hided IS NULL");
    echo "Có tất cả ".count($data_in_month)." gói thầu mới trong tháng vừa qua (từ ngày ".$lastmonth." đến ngày ".substr($today,0,10).") </br>";

    //Kiểm tra trong tháng có bao nhiêu văn bản mới
    $data_vanban_in_month = DB::select("SELECT * FROM packages2 WHERE (created_at >= '$lastmonth' AND created_at <= '$today') AND hided IS NULL");
    echo "Có tất cả ".count($data_vanban_in_month)." văn bản mới trong tháng vừa qua (từ ngày ".$lastmonth." đến ngày ".substr($today,0,10).") </br></br>";
?>

<div class="form-group" style="width: 250px; margin-top: 10px;">
  <label for="sel1">Chọn hình thức thống kê:</label>
  <select class="form-control" id="luachon">
    <option  value="all"> <strong> Chọn hình thức </strong></option>
    <option value="thongkegoithau_theotuan"><strong> Thống kê gói thầu theo tuần </strong></option>
    <option value="thongkegoithau_theothang"><strong> Thống kê gói thầu theo tháng </strong></option>
    <option value="thongkevanban_theotuan"><strong> Thống kê văn bản theo tuần </strong></option>
    <option value="thongkevanban_theothang"><strong> Thống kê văn bản theo tháng </strong></option>
    <?php
    $ngayhientai = date('Y-m-d'); 
  // Giờ hiện tại
  $data = DB::table('packages')->where('id',155)->select('created_at')->get();  
?>
  </select>
</div>

<div >
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="lst">
                        <thead>
                            <tr align="center">
                                <th width="10">Stt</th>
                                <th>Tên gói thầu</th>
                                <th>Bên mời thầu</th>
                                <th width="80">Ngày đăng </th>                                
                            </tr>
                        </thead>
                        <tbody id="result">
                        <?php  
                        //$data = DB::table('packages')->where('hided',null)->distinct()->orderBy('id', 'desc')->get(); 
                        $data = DB::select("SELECT * FROM packages WHERE (created_at >= '$lastweek' AND created_at <= '$today') AND hided IS NULL ORDER BY id DESC");
                                $i=1;
                                
                          ?>
                        @foreach ($data as $value) 

                         <tr><td>{{$i}}</td><td><a target = '_blank' href = "{!!$value->link!!}">{!!$value->title!!}
                          <?php $ngaycapnhat =  substr($value->created_at,0,10); ?>
                         @if ($ngayhientai == $ngaycapnhat) 
                         <span style="color: red;" class="dm_new"><strong> Mới! </strong></span> <span class="editlinktip hasTip" style="text-decoration: none; color: #333;"><img src="{{asset('image/tooltip.png')}}" border="0" alt="Tooltip"></span>
                         @endif</a></td><td>{!!$value->bidder!!}</td>

                              <td align="center"> {{substr($value->created_at,0,10)}} </td> 

                         </tr>
                          <?php $i++; ?>
                      @endforeach
                       
                        </tbody>
                    </table>
                    </div>

                  </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>




        <!-- /#page-wrapper -->
         <script type="text/javascript">
            $(document).ready(function(){
                 $('#lst').DataTable();
            });

            $("#luachon").change(function(){
               var hinh_thuc_thong_ke = ($(this).val());

                 $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      }
                  });


                var data = {
                    hinh_thuc_thong_ke : hinh_thuc_thong_ke,             
                    _token : $('meta[name="_token"]').attr('content'),            
                }; 

                 $.ajax({                   
                  type : 'get',          
                  url : 'getHinhThucThongKe', //Here you will fetch records 
                  data : data,         
                success : function(data){
                  $('#result').html(data);//Show fetched data from database
                }
              });
            });

     
  </script>
  <link rel="stylesheet" type="text/css" href="{{asset('datatable/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('datatable/buttons.dataTables.min.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('datatable/datatables.min.css')  }}">
    <script type="text/javascript" src="{{asset('datatable/bootstrap.min.css')}}j"></script>
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
