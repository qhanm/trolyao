@extends('user_master')
@section ('find_active')
active
@endsection
@section('active')
Tìm gói thầu
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
   <span style="text-align:center"><h2> Danh sách gói thầu </h2></span>
                    <span style="font-size: 150%;"> Danh sách gói thầu tìm thấy với từ khóa là: 
                     </span> <?php $truyvan_tukhoa = "Select * from keywords where user_id = '$user_id' ";
              $res = mysqli_query($conn,$truyvan_tukhoa);  
               ?>
               @foreach ($res as $key => $value)
        <strong style="overflow: hidden; font-size: 17px; font-family: sans-serif; color: #FF0000;"><span>{{ $value['keyword'] }}</span></strong>
       @if ($key < (count($res)-1)) <label style="margin-left: -5px; "><b>; </b></label>
       @else <label style="margin-left: -3px; "><b>; </b></label>
       @endif
 @endforeach
            
                 <div  style="margin-top: 10px">
   <table  class= 'table table-bordered table-striped' id='lst'>
                <thead>
                <tr>
                <td colspan="10">                   
                    
                    
                </td>
                </tr>
                <tr>    
                  <th>Stt</th>               
                  <th>Tên gói thầu</th>
                  <th>Bên mời thầu</th>
                  <th width="80"> Ngày đăng </th>
                </tr>
                </thead> 
                       
    <?php
          
              $ngayhientai = date('Y-m-d'); 
              $truyvan_tukhoa = "Select * from keywords where user_id = '$user_id' ";
              $res = mysqli_query($conn,$truyvan_tukhoa);   
              $i=1;
              if ( mysqli_num_rows($res)>0)       {  
              while ($hang = mysqli_fetch_array($res))
              {
                 $keyword =  $hang['keyword'];
               

                 $sql = mysqli_query($conn,"select * from packages where title like '%$keyword%' or bidder like  '%$keyword%'  ORDER BY id DESC ");
                 while ($r = mysqli_fetch_array($sql)) {
                    if($r['hided'] != 1){  //Neu trang thai goi thau khong bi an thi hien thi ra man hinh
                      $ngaycapnhat =  substr($r['created_at'],0,10); 
                     
                      echo "<tr>
                              <td >$i </td>  
                               <td style='font-size: 110%;' > <a target = '_blank' href='{$r['link']}'>";
                               // {$r['title']}
                               $tengoithau = str_replace($keyword, "<u><font color=\"red\">$keyword</font></u>", $r['title']);  //Gach chan cac tu khoa
                                 echo $tengoithau;
                               echo "</a>";
                                if ($ngaycapnhat==$ngayhientai) 
                                {
                                  echo "<span style='color: red;' class='dm_new'><strong> Mới! </strong></span> <span class='editlinktip hasTip' style='text-decoration: none; color: #333;'>";
                                  echo" </span>";
                                }

                               echo "</td>   
                              <td style='font-size: 110%;'>";
                              //{$r['bidder']}
                              $benmoithau = str_replace($keyword, "<u><font color=\"red\">$keyword</font></u>", $r['bidder']);  //Gach chan cac tu khoa
                              echo $benmoithau;
                              echo "</td>";       
                               echo "<td style='font-size: 110%; align='center'>"; 
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
                echo  "<tr  > <td colspan='3' style ={text-align } ='center'> Không có gói thầu </td> </tr>";              
          
           
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