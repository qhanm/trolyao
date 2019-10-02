@extends('user_master')
@section ('trangchu1_active')
active
@endsection
@section('active')
Trang chủ
@endsection
@section ('content')
<script src="{{ asset('libs/datatables.min.js') }}"></script>
    <link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">
 
   
   <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 style="text-align: center; "> Thông tin các văn bản pháp luật  </h2> 
     <span > Thông tin được lấy từ địa chỉ: <a href="http://vbpl.vn/pages/vanbanmoi.aspx" target="_blank">http://vbpl.vn/pages/vanbanmoi.aspx</a></span>
                          
                        </h1>
  <div class="form-group" style="width: 250px; margin-top: 10px;">
  <?php 
  $ngayhientai = date('Y-m-d'); 
  // Giờ hiện tại
  $data = DB::table('packages2')->where('id',155)->select('created_at')->get() ?>
  </div>
              <div >
                    <!-- /.col-lg-12 -->
                    <table class="table table-striped table-bordered table-hover" id="lst">
                        <thead>
                            <tr align="center">
                                <th width="10">Stt</th>
                                <th>Tên văn bản</th>
                                
                                <th width="80">Ngày cập nhật </th>                                
                            </tr>
                        </thead>
                        <tbody id="result">
                        <?php  $data = DB::table('packages2')->where('hided',null)->distinct()->orderBy('id', 'desc')->get(); 
                                $i=1;
                                
                          ?>
                        @foreach ($data as $value) 

                         <tr><td>{{$i}}</td><td><a target='_blank' href="{!!$value->link!!}">{!!$value->title!!}
                          <?php $ngaycapnhat =  substr($value->created_at,0,10); ?>
                         @if ($ngayhientai == $ngaycapnhat) 
                         <span style="color: red;" class="dm_new"><strong> Mới! </strong></span> <span class="editlinktip hasTip" style="text-decoration: none; color: #333;"><img src="{{asset('image/tooltip.png')}}" border="0" alt="Tooltip"></span>
                         @endif</a></td>
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
            url : 'linhvuc', //Here you will fetch records 
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
