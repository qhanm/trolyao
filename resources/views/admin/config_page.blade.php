@extends('admin_master')
@section('config_active')
active
@endsection
@section('active')
Cấu hình dữ liệu 
@endsection
@section ('content')
<link rel="stylesheet" type="text/css" href="{{ asset('mystyle.css') }}">
<script src="{{ asset('libs/datatables.min.js') }}"></script>
<link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">

<style type="text/css" media="screen">
  .error{color: red;}
</style>

<?php
	$value_goithau = 0;
	$value_vanban = 0;
	$value_sophutcronjob = 0;
?>
        
<div id="page-wrapper">
	<div class="container-fluid">
		 @if(session("success"))
		 <div class="alert alert-success fixed-alert">
		 	<span class="glyphicon glyphicon-certificate"></span>
		 	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		 	{{session("success")}}
		 </div>
		 @endif
		 @if(session("error"))
		 <div class="alert alert-danger fixed-alert">
		 	<span class="glyphicon glyphicon-certificate"></span>
		 	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		 	{{session("error")}}
		 </div>
		 @endif
		<div class="row">
			<div class="col-lg-12">
				<h2  style="text-align: center; " >Cấu hình dữ liệu </h2>
			</div>
			<div style="margin-top: 100px;" >

				<div class="col-sm-6">
					<div class="wallpaper">
						<div class="wallpaper-heading">
							Số lượng gói thầu hiển thị
						</div>
						<div class="wallpaper-content">
							<div class="pull-left">Số lượng hiển thị hiện tại là <span><?php 
								//echo file_get_contents('goithau(khongduocxoa).txt'); 
								$config_name = "Số lượng gói thầu";
								$soluonggoithau = DB::select("SELECT * FROM config WHERE config_name='$config_name'");
								//$row = mysqli_fetch_array($soluonggoithau);
								foreach ($soluonggoithau as $key => $value) {
									$value_goithau = $value->config_value;
								}
								echo $value_goithau;
							?></span> dòng dữ liệu</div>
							<div class="pull-right"><a href="" data-toggle="modal" data-target="#EditQtyPakage"><i class="glyphicon glyphicon-cog"></i> Thay đổi</a></div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="wallpaper">
						<div class="wallpaper-heading">
							Số lượng văn bản hiển thị 
						</div>
						<div class="wallpaper-content">
							<div class="pull-left">Số lượng hiển thị hiện tại là <span><?php 
								//echo file_get_contents('vanban(khongduocxoa).txt'); 
								$config_name = "Số lượng văn bản";
								$soluongvanban = DB::select("SELECT * FROM config WHERE config_name='$config_name'");
								//$row = mysqli_fetch_array($soluonggoithau);
								foreach ($soluongvanban as $key => $value) {
									$value_vanban = $value->config_value;
								}
								echo $value_vanban;
							?></span> dòng dữ liệu</div>
							<div class="pull-right"><a href="" data-toggle="modal" data-target="#EditQtyPakage2"><i class="glyphicon glyphicon-cog"></i> Thay đổi</a></div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="wallpaper">
						<div class="wallpaper-heading">
							Số lượng từ khóa của khách hàng
						</div>
						<div class="wallpaper-content">
							<div class="pull-left">
								<ul>
									@foreach($qty3 as $item)
										@if($item->id > 1)
										<li>
											Khách <span style="color: #444; text-transform: lowercase;">{{$item->roles}}:</span>
											@if($item->qty > 0)
												<span>{{$item->qty}} từ khóa</span> 
											@else
												<span>không giới hạn</span> 
											@endif
										</li>
										@endif
									@endforeach
								</ul>
							</div>
							<div class="pull-right"><a href="#" name="btnAddKey" id="btnAddKey"><i class="glyphicon glyphicon-cog"></i> Thay đổi</a></div>
							<div class="clear"></div>
						</div>
					</div>					
				</div>
				
				<div class="col-sm-6">
					<div class="wallpaper">
						<div class="wallpaper-heading">
							Số phút thực hiện lấy dữ liệu tự động 
						</div>
						<div class="wallpaper-content">
							<div class="pull-left">Số phút thực hiện lấy dữ liệu tự động hiện tại là <span>
								<?php //echo file_get_contents(public_path().'/sophutcronjobs.txt'); 
									$config_name = "Số phút thực hiện cron job";
									$sophutcronjob = DB::select("SELECT * FROM config WHERE config_name='$config_name'");
									//$row = mysqli_fetch_array($soluonggoithau);
									foreach ($sophutcronjob as $key => $value) {
										$value_sophutcronjob = $value->config_value;
									}
									echo $value_sophutcronjob;
								?>	
								</span> phút </div>
							<div class="pull-right"><a href="" data-toggle="modal" data-target="#cronJobs"><i class="glyphicon glyphicon-cog"></i> Thay đổi</a></div>
							<div class="clear"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- gói thầu -->
<div class="modal fade" id="EditQtyPakage" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cập nhật số lượng hiển thị gói thầu</h4>
			</div>
			<form action='{{route("addqtygoitahu")}}' method="post"  id = 'editForm'>
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label>Số lượng dòng dữ liệu</label>
						<?php 
						//$qty = file_get_contents('goithau(khongduocxoa).txt'); 
							$qty = $value_goithau;
						?>
						<input type="number" class="form-control" name="qty" id="qty" min="1" value="{{$qty}}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id='Save'>Cập nhật</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- văn bản -->
<div class="modal fade" id="EditQtyPakage2" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cập nhật số lượng hiển thị văn bản</h4>
			</div>
			<form action='{{route("addqtyvanban")}}' method="post"  id = 'editForm'>
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label>Cấp độ</label>
						<?php 
						//$qty2 = file_get_contents('vanban(khongduocxoa).txt'); 
							$qty2 = $value_vanban;
						?>
						<input type="number" class="form-control" name="qty" id="qty" min="1" value="{{$qty2}}">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id='Save'>Cập nhật</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>

				</div>
			</form>
		</div>

	</div>
</div>

<!-- Số lượng từ khóa -->
<div class="modal fade" id="addKey" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cập nhật số lượng từ khóa</h4>
        </div>
        <form action="{{route('editNoKey')}}" method="POST" id="addKeyForm">
          <div class="modal-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label class='radio-inline'><input name='rdTypeUser2' class='rdTypeUser2' id="vip" value='3' type='radio' onclick="changeQtyType()">VIP</label>
              <label class='radio-inline'><input name='rdTypeUser2' class='rdTypeUser2' id="bt" value='2' type='radio' checked='checked' onclick="changeQtyType()">Bình thường</label>
            </div>
            <div class="form-group">
               <label class='radio-inline'><input name='cbKey' class='cbKey' id="cbKey1" value='0' type='radio' onclick="hideshowQtyKey();">Không giới hạng</label>
              <label class='radio-inline'><input name='cbKey' class='cbKey' id="cbKey2" value='1' type='radio' checked='checked' onclick="hideshowQtyKey();">Có giới hạn</label>
            </div>
            <div class="form-group" id="hidenshowNoKey">
                <label>Giới hạn số lượng từ khóa</label>
                <input class="form-control" name="noKey" id="noKey" type="number" min="1" value="1" required="" />
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


<!-- Lấy dữ liệu tự động -->
<div class="modal fade" id="cronJobs" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cập nhật số phút lấy văn bản và gói thầu tự động</h4>
			</div>
			<form action='{{route("minuteToCronJobs")}}' method="post"  id = 'editForm'>
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label>Số phút cập nhật dữ liệu</label>
						<?php 
						//$qty2 = file_get_contents(public_path().'/sophutcronjobs.txt'); //Lay so phut cap nhat du lieu tu file $_SERVER['DOCUMENT_ROOT']."/public/sophutcronjobs.txt"
							$qty2 = $value_sophutcronjob;
						?>
						<!-- <input type="number" class="form-control" name="qty" id="qty" min="1" value="{{$qty2}}"> -->
						
						<select class="form-control" name="selectMinute" id="selectMinute">
							<option value="Unknown"> ----- Chọn số phút ----- </option>
							<option <?php if($qty2==5) echo "selected=selected"; ?>value="5"> 5 phút một lần </option>
							<option <?php if($qty2==10) echo "selected=selected"; ?> value="10"> 10 phút một lần </option>
							<option <?php if($qty2==30) echo "selected=selected"; ?> value="30"> 30 phút một lần </option>
							<option <?php if($qty2==60) echo "selected=selected"; ?> value="60"> 60 phút một lần </option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id='Save'>Cập nhật</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>

				</div>
			</form>
		</div>

	</div>
</div>

  <script >
  	var listQtyKey = <?php echo $qty3; ?>;

  	function hideshowQtyKey(){       
  		if($("#cbKey1").prop("checked"))
  			$("#hidenshowNoKey").addClass('hidden');
  		if($("#cbKey2").prop("checked"))
  			$("#hidenshowNoKey").removeClass('hidden');
  	}

  	function changeQtyType() {
  		if($("#vip").prop("checked")){
  			for(i=0; i<=listQtyKey.length; i++){
  				if(listQtyKey[i].id == $("input[type='radio'].rdTypeUser2:checked").val()){
  					if(listQtyKey[i].qty == 0){
  						$('#cbKey1').prop('checked', true);
  						$('#noKey').val(1);
  						$('#hidenshowNoKey').addClass('hidden');
  					}else{
  						$('#cbKey2').prop('checked', true);
  						$('#noKey').val(listQtyKey[i].qty);
  						$('#hidenshowNoKey').removeClass('hidden');
  					}
  					break;
  				}
  			}
  		}else{
  			for(i=0; i<=listQtyKey.length; i++){
  				if(listQtyKey[i].id == $("input[type='radio'].rdTypeUser2:checked").val()){
  					if(listQtyKey[i].qty == 0){
  						$('#cbKey1').prop('checked', true);
  						$('#hidenshowNoKey').addClass('hidden');
  					}else{
  						$('#cbKey2').prop('checked', true);
  						$('#noKey').val(listQtyKey[i].qty);
  						$('#hidenshowNoKey').removeClass('hidden');
  					}
  					break;
  				}
  			}
  		}

  	}
  	$(document).ready(function(){
  		$("#btnAddKey").click(function(){
  			for(i=0; i<=listQtyKey.length; i++){
  				if(listQtyKey[i].id == $("input[type='radio'].rdTypeUser2:checked").val()){
  					if(listQtyKey[i].qty == 0){
  						$('#cbKey1').prop('checked', true);
  						$('#noKey').val(1);
  						$('#hidenshowNoKey').addClass('hidden');
  					}else{
  						$('#cbKey2').prop('checked', true);
  						$('#noKey').val(listQtyKey[i].qty);
  						$('#hidenshowNoKey').removeClass('hidden');
  					}
  					break;
  				}
  			}
  			$('#addKey').modal('show');
  		});
  	});
  </script>
 @endsection