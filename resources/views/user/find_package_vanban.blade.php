
@extends('user_master')
@section ('find1_active')
active
@endsection
@section('active')
Tìm văn bản pháp luật
@endsection
@section('content')
<script src="{{ asset('libs/datatables.min.js') }}"></script>
    <link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">
 
 <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
<div class="col-sm-12"> 
    <?php  $user_id = Auth::user()->id  ;
    include(config_path().'/connect.php');
?>
     <span style="text-align:center"><h2> Danh sách văn bản pháp luật </h2></span>
                      <span style="font-size: 150%;"> Danh sách văn bản tìm thấy với từ khóa là: 
                       </span> <?php $truyvan_tukhoa = "Select * from keywords2 where user_id = '$user_id' ";
                $res = mysqli_query($conn,$truyvan_tukhoa);  
                if (isset($res))  {
                 
                foreach ($res as $key => $value) {
                      echo "<strong style=\"overflow: hidden; font-size: 17px;font-family: sans-serif; color: #FF0000;\">".$value['keyword']."</strong>"."<b>; </b>";
                   }  
                   }
                   else echo " "; ?>
                   <div  style="margin-top: 10px">
     <table  class= 'table table-bordered table-striped' id='lst'>
                  <thead>
                  <tr>
                  <td colspan="10">                   
                      
                      
                  </td>
                  </tr>
                  <tr>    
                    <th>Stt</th>               
                    <th>Tên văn bản</th>
                   
                    <th width="80"> Ngày cập nhật </th>
                  </tr>
                  </thead> 
                         
      <?php
            
                $ngayhientai = date('Y-m-d'); 
                $truyvan_tukhoa = "Select * from keywords2 where user_id = '$user_id' ";
                $res = mysqli_query($conn,$truyvan_tukhoa);   
                $i=1;
                if ( mysqli_num_rows($res)>0)       {  
                while ($hang = mysqli_fetch_array($res))
                {
                   $keyword =  $hang['keyword'];
                 

                   $sql = mysqli_query($conn,"select * from packages2 where (title like '%$keyword%')  order by id DESC ");
                   while ($r = mysqli_fetch_array($sql)) {
                   		if($r['hided'] != 1){  //Neu trang thai van ban khong bi an thi hien thi ra man hinh
                   			$ngaycapnhat =  substr($r['created_at'],0,10); 


                    		echo "<tr>
                                <td style='font-size: 150%;''>$i </td>  
                                 <td style='font-size: 150%;'> <a target = '_blank' href='{$r['link']}'>";
                                 //echo "{$r['title']}";
                                 $tieude = str_replace($keyword, "<u><font color=\"red\">$keyword</font></u>", $r['title']);  //Gach chan cac tu khoa
                                 echo $tieude;
                                 echo "</a>";
                                  if ($ngaycapnhat==$ngayhientai) 
                                  {
                                  	echo "<span style='color: red;' class='dm_new'><strong> Mới! </strong></span> <span class='editlinktip hasTip' style='text-decoration: none; color: #333;'>";
                                  	echo" </span>";
                                  }

                                 echo "</td>   
                                     
                                 <td style='font-size: 110%;'align='center'>"; 
                                 	$date = substr($r['created_at'],0,10);
							       $day = substr($date, 8, 2);
							       $month = substr($date, 5, 2);
							       $year = substr($date, 0, 4);
							       echo $day.'-'.$month.'-'.$year;
                                 //echo substr($r['created_at'],0,10);
                                 echo " </td>  
                        	</tr>";     
                        $i++; 
                   		}
                      
                  
                 }
                  }


                }
                else 
                  echo  "<tr  > <td colspan='3' style ={text-align } ='center'> Không có văn bản </td> </tr>";
               
            
             
       ?>
                     </table>
                     </div>
                 

</div>
</div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $('#lst').DataTable();
  })
</script>

@endsection
