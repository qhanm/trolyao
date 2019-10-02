@extends('user_master')
@section ('dashboard_active')
active
@endsection
@section('active')
Dashboard
@endsection
@section ('content')
<!DOCTYPE html>
<html>
<head>
   <script src="{{ asset('chart/Chart.min.js') }}"></script>       
   <link rel="stylesheet" type="text/css" href="{{asset('datatable/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('datatable/buttons.dataTables.min.css')}} ">
    <link rel="stylesheet" type="text/css" href="{{asset('datatable/datatables.min.css')  }}">
    <link rel="icon" href="http://www.thuthuatweb.net/wp-content/themes/HostingSite/favicon.ico" type="image/x-ico"/>
</head>
<body>
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
    $data_goithau = [];
    $data_vanban = [];
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
    //echo "Có tất cả ".count($data_in_week)." gói thầu mới trong tuần vừa qua (từ ngày ".$lastweek." đến ngày ".substr($today,0,10).") </br>";
    $data_goithau[0] = count($data_in_week); //Đưa số lượng gói thầu trong tuần vào array

    //Kiểm tra trong tuần có bao nhiêu văn bản mới
    $data_vanban_in_week = DB::select("SELECT * FROM packages2 WHERE (created_at >= '$lastweek' AND created_at <= '$today') AND hided IS NULL");
    //echo "Có tất cả ".count($data_vanban_in_week)." văn bản mới trong tuần vừa qua (từ ngày ".$lastweek." đến ngày ".substr($today,0,10).") </br></br>";
    $data_vanban[0] = count($data_vanban_in_week); //Đưa số lượng văn bản trong tuần vào array

    //Kiểm tra trong tháng có bao nhiêu gói thầu mới
    $data_in_month = DB::select("SELECT * FROM packages WHERE (created_at >= '$lastmonth' AND created_at <= '$today') AND hided IS NULL");
    //echo "Có tất cả ".count($data_in_month)." gói thầu mới trong tháng vừa qua (từ ngày ".$lastmonth." đến ngày ".substr($today,0,10).") </br>";
    $data_goithau[1] = count($data_in_month); //Đưa số lượng gói thầu trong tháng vào array

    //Kiểm tra trong tháng có bao nhiêu văn bản mới
    $data_vanban_in_month = DB::select("SELECT * FROM packages2 WHERE (created_at >= '$lastmonth' AND created_at <= '$today') AND hided IS NULL");
    //echo "Có tất cả ".count($data_vanban_in_month)." văn bản mới trong tháng vừa qua (từ ngày ".$lastmonth." đến ngày ".substr($today,0,10).") </br></br>";
    $data_vanban[1] = count($data_vanban_in_month); //Đưa số lượng văn bản trong tháng vào array

    $thongKeGoiThau = json_encode($data_goithau, JSON_UNESCAPED_UNICODE);
    $thongKeVanBan = json_encode($data_vanban, JSON_UNESCAPED_UNICODE);
?>

<p>
Có tất cả <a href="{!!url('user/homegoithau');!!}"><?=count($data_in_week);?></a> gói thầu mới trong tuần vừa qua (từ ngày <?=$lastweek;?> đến ngày <?=substr($today,0,10);?>) </br>
Có tất cả <a href="{!!url('user/homevanban');!!}"><?=count($data_vanban_in_week);?></a> văn bản mới trong tuần vừa qua (từ ngày <?=$lastweek;?> đến ngày <?=substr($today,0,10);?>) </br>
</p>

<p>
Có tất cả <a href="{!!url('user/homegoithau');!!}"><?=count($data_in_month);?></a> gói thầu mới trong tháng vừa qua (từ ngày <?=$lastmonth;?> đến ngày <?=substr($today,0,10);?>) </br>
Có tất cả <a href="{!!url('user/homevanban');!!}"><?=count($data_vanban_in_month);?></a> văn bản mới trong tháng vừa qua (từ ngày <?=$lastmonth;?> đến ngày <?=substr($today,0,10);?>) </br>
</p>

<table align="center" border="1" borderColor="#33CCFF" style="margin-top: 20px; ">
  <tr>
    <td>
<!-- Biểu đồ thống kê số lượng gói thầu theo tuần và tháng -->
 <div align="center" style="padding: 40px;">
  <span style="text-align: center; font-style: italic;"  > <strong> Biểu đồ thống kê số lượng gói thầu theo tuần và tháng </strong></span> </br></br>
   <canvas id="bieudo" width="250" height="200" style="display: block;"></canvas>
 </div> 
    </td>

    <td>
 <!-- Biểu đồ thống kê số lượng văn bản theo tuần và tháng -->
 <div align="center" style="padding: 40px;">
  <span style="text-align: center; font-style: italic;"  > <strong> Biểu đồ thống kê số lượng văn bản theo tuần và tháng </strong></span> </br></br>
   <canvas id="bieudo_vanban" width="250" height="200" style="display: block;"></canvas>
 </div> 
</td>
 </tr>
</table></br></br>

<script>
// Biểu đồ cột gói thầu
var data = {
    datasets: [{
        data: <?php echo $thongKeGoiThau; ?> ,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
        ],
         borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
        fillColor : "rgba(0,255,0,0.9)",
        label: 'Biểu đồ thống kê các gói thầu theo lĩnh vực' // for legend
    }],
    labels: 
          <?php 
            $range = ["Tuần", "Tháng"];
            echo json_encode($range); ?> 
    
};
  // get bar chart canvas
  var bieudo = document.getElementById("bieudo").getContext("2d"); 
  // draw bar chart
  new Chart(bieudo).Bar(data);


// Biểu đồ cột văn bản
var data_vanban = {
    datasets: [{
        data: <?php echo $thongKeVanBan; ?> ,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
        ],
         borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
        fillColor : "rgba(255, 127, 0, 0.9)",
        label: 'Biểu đồ thống kê các gói thầu theo lĩnh vực' // for legend
    }],
    labels: 
          <?php 
            $range_vanban = ["Tuần", "Tháng"];
            echo json_encode($range_vanban); ?>  
};
  // get bar chart canvas
  var bieudo_vanban = document.getElementById("bieudo_vanban").getContext("2d");
  // draw bar chart
  new Chart(bieudo_vanban).Bar(data_vanban);


</script>


                  </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>


  
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
</body>
</html>
    
@endsection