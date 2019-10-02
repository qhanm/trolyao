@extends('admin_master')
@section('active')
Dashboard
@endsection
@section('dashboard_active')
active
@endsection
@section('content')
  <h3 style="text-align: center; "> Thống kê </h3> </br></br>
<!DOCTYPE html>
<html>
<head>
	 <script src="{{ asset('chart/Chart.min.js') }}"></script>       
    <link rel="icon" href="http://www.thuthuatweb.net/wp-content/themes/HostingSite/favicon.ico" type="image/x-ico"/>
</head>
<body>   
<!-- Thống kê số lượng gói thầu theo lĩnh vực -->
 <div align="center" style="margin:20px;">
 	<span style="text-align: center; font-style: italic;"  > <strong> Biểu đồ thống kê gói thầu theo lĩnh vực </strong></span> </br></br>
 	 <canvas id="bieudo" width="500" height="200" style="display: block;"></canvas>
 </div> </br></br>

<!-- Thống kê số lượng người đăng ký theo thời gian -->
 <div align="center" style="margin:20px;">
 	<span style="text-align: center; font-style: italic;"  > <strong> Biểu đồ thống kê số lượng người đăng ký vào các tháng của năm <?php $nam = date('Y'); echo $nam; ?> </strong></span> </br></br>
 	 <canvas id="buyers" width="500" height="200" style="display: block;" ></canvas> 
 </div> </br></br></br></br>

<!-- line chart canvas element -->

       <div align="center" style="margin: 20px;"> 
       	<span style="text-align:center; font-style: italic;"><strong> Biểu đồ thống kê số lượng gói thầu theo từ khóa của từng lĩnh vực </strong></span> </br></br>
       	<canvas id="bieudotron" width="500" height="200" style="display: block;"></canvas>  
       </div></br>

        <!-- pie chart canvas element -->
        {{-- <canvas id="countries" width="500" height="200"></canvas> --}}
        <!-- bar chart canvas element -->
        {{-- <canvas id="income" width="500" height="200"></canvas> --}}

         
           {{-- <canvas id="bieudotron" width="500" height="200"></canvas>   --}}
 
    
</body>
</html>

	<?php $mang = array();
	  $tukhoa = array();
	 
	  $data = DB::table('keywords')->get();
	
	  foreach ($data as $value) {
	  	  array_push($tukhoa, $value->keyword);
	  }
	   for ($i = 0 ; $i <count($tukhoa) ; $i++)
	  {
	  	array_push($mang, $i);

	  }

	
	// Thống kê số lượng gói thầu
	  $data_linhvuc =  DB::table('category')->get();
	 $tenlinhvuc = array();
	 $count_linhvuc =array();
	 foreach ($data_linhvuc as $key => $value) {
	 	 array_push($tenlinhvuc, $value->cate_name);
	 	 $lv = App\Packages::where ('cate_id',$value->cate_id)->count();
	 	 array_push($count_linhvuc,$lv);
	 	}
	 	$dulieu = json_encode($count_linhvuc); //dữ liệu JSON để đưa vào biểu đồ
	 	//echo $dulieu;


	 //Thống kê số lượng người đăng ký theo tháng của năm hiện tại
	 	$currentYear = date('Y');
	 	//echo $currentYear;
	 	$count_users_array = [];
	 	//$data_users_in_january = DB::select("SELECT * FROM users WHERE created_at >= '$currentYear-01-01' AND created_at <= '$currentYear-01-31'");
	 	//echo count($data_users_in_january);
	 	for($month = 1; $month <= 12; $month++){
	 		$data_users = DB::select("SELECT * FROM users WHERE created_at >= '$currentYear-0$month-01' AND created_at <= '$currentYear-0$month-31'");
	 		$count = count($data_users);
	 		//echo count($data_users);
	 		array_push($count_users_array, (int)$count);
	 	}
	 	$data_users_every_month = json_encode($count_users_array, JSON_UNESCAPED_UNICODE); //dữ liệu JSON để đưa vào biểu đồ


	 //Thống kê số lượng gói thầu theo từ khóa
	 $count_keyword = [];
	 $tukhoa_goithau = DB::table('keywords')->get();
	 $count_hh = 0; $count_ndt = 0; $count_hhp = 0; $count_ptv = 0; $count_tv = 0; $count_xl = 0;
	 foreach ($tukhoa_goithau as $key => $value) {
	 	$keyword = $value->keyword;
	 	$data_tukhoa_goithau = DB::select("SELECT * FROM packages WHERE (title LIKE '%$keyword%' OR bidder LIKE '%$keyword%') AND hided IS NULL");
	 	foreach ($data_tukhoa_goithau as $key2 => $value2) {
	 		$category = $value2->cate_id; //Lay id cua linh vuc
	 		switch($category){
	 			case "HH": $count_hh++;  //Tăng biến đếm gói thầu hàng hóa lên 1 giá trị
	 					   break; 
	 			case "HHP": $count_hhp++;
	 					   break;
	 			case "NDT": $count_ndt++;
	 					   break;
	 			case "PTV": $count_ptv++;
	 					   break;
	 			case "TV": $count_tv++;
	 					   break;
	 			case "XL": $count_xl++;
	 					   break;
	 			default: break;
	 		}
	 	}
	 }
	 $count_keyword[0] = $count_hh;
	 $count_keyword[1] = $count_hhp;
	 $count_keyword[2] = $count_ndt++;
	 $count_keyword[3] = $count_ptv++;
	 $count_keyword[4] = $count_tv++;
	 $count_keyword[5] = $count_xl++;
	 $data_count_keyword = json_encode($count_keyword, JSON_UNESCAPED_UNICODE);


	 //echo $count_hh.", ".$count_hhp.", ".$count_ndt.", ".$count_ptv.", ".$count_tv.", ".$count_xl;

	?>
    
<script>

// Biểu đồ tròn


var data = {
    datasets: [{
        data: <?php echo $dulieu; ?> ,
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
          <?php echo json_encode($tenlinhvuc); ?> 
    
};
 

	// get bar chart canvas
	var bieudo = document.getElementById("bieudo").getContext("2d");
	
	// draw bar chart
	new Chart(bieudo).Bar(data);


	// Thống kê số lượng người đăng ký 
	var buyerData = {
		labels :  [<?php for ($i=1;$i<=12;$i++) echo $i.","?>] ,
		datasets : [
		{
				fillColor : "rgba(173,216,230,0.4)",
				//strokeColor : "#ACC26D",
				strokeColor : "rgba(173,216,230,0.8)",
				pointColor : "#fff",
				//pointStrokeColor : "#9DB86D",
				pointStrokeColor : "#0099FF",
				//data :[3,5,9,10,15,20],
				data: <?=$data_users_every_month;?>
			}
		]
	}
	
	// get line chart canvas
	var buyers = document.getElementById('buyers').getContext('2d');

	// draw line chart
	new Chart(buyers).Line(buyerData);
	

	// Biểu đồ thống kê theo từ khóa
    // For a pie chart


	var data_bieudotron = {
    labels:  <?php echo json_encode($tenlinhvuc); ?> ,
    datasets: [
        {
            //data:  [999,545,5454,323,313,44],
            data: <?=$data_count_keyword;?>,
            backgroundColor: [
                "#FF6384",
                "#FF6384",
                "#FF6384",
                "#FF6384",
                "#36A2EB",
                "#FFCE56"
            ],
            hoverBackgroundColor: [
                "#FF6384",
                "#FF6384",
                "#FF6384",
                "#FF6384",
                "#36A2EB",
                "#FFCE56"
            ],
            fillColor : "rgba(255,165,0,0.5)",  // rgba(red, green, blue, transparent_level);
            pointColor : "#fff",
            pointStrokeColor : "rgba(255,140,0,1)",
            strokeColor : "rgba(255,140,0,0.3)"
        }]
};

// get line chart canvas
	 var bieudotron = document.getElementById('bieudotron').getContext('2d');
	// draw line chart
	new Chart(bieudotron).Line(data_bieudotron);

	
</script>
@endsection