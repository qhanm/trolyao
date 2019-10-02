@extends('newhome')
@section('content')
<div class="container">
	<div class="row">
		<div  class="col-md-12">
			<div class="table-responsive">
				<div class="pakage-css">
					<div class="title-goi-thau">Danh sách gói thầu mới</div>
					<table class="table table-striped table-bordered table-hover" id="lst">
						<thead>
							<tr >
								<th>Stt</th>                                
								<th class="text-center">Tên gói thầu</th>
								<th class="text-center">Bên mời thầu</th>
								<th>Ngày đăng</th>
							</tr>
						</thead>
						<tbody id="result">
							@if(count($goithau)>0)
							<?php $i = 1;?>
								@foreach($goithau as $item)
									<?php 
										$dinh_dang_cu =  substr($item->created_at,0,10);
										$ngaycapnhat = date("d-m-Y", strtotime($dinh_dang_cu));
									?>
									<tr>
										<td style="vertical-align: middle;">{{ $i }}</td>
										<td style="vertical-align: middle;"><a target = '_blank' href = "{!!$item->link!!}">{{ $item->title }}</a></td>
										<td style="vertical-align: middle;">{{ $item->bidder }}</td>
										<td style="vertical-align: middle;">{{ $ngaycapnhat }}</td>
									</tr>
									<?php  $i++; ?>
								@endforeach
							@else
								<tr>
									<td colspan="4" class="text-center">
										<h3 class="text-danger">Chưa có gói thầu mới</h3>
									</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div  class="col-md-12">
			<div class="table-responsive">
				<div class="pakage-css">
					<div class="title-goi-thau">Danh sách gói văn bản mới</div>
					<table class="table table-striped table-bordered table-hover" id="lst">
						<thead>
							<tr >
								<th>Stt</th>                                
								<th  class="text-center">Tên văn bản</th>
								<th>Ngày đăng</th>
							</tr>
						</thead>
						<tbody id="result">
							@if(count($vanban)>0)
							<?php $i = 1;?>
								@foreach($vanban as $item)
									<?php 
										$dinh_dang_cu2 =  substr($item->created_at,0,10);
										$ngaycapnhat2 = date("d-m-Y", strtotime($dinh_dang_cu2));
									?>
									<tr>
										<td style="vertical-align: middle;">{{ $i }}</td>
										<td style="vertical-align: middle;"><a target = '_blank' href = "{!!$item->link!!}">{{ $item->title }}</a></td>
										<td style="vertical-align: middle;">{{ $ngaycapnhat2 }}</td>
									</tr>
									<?php  $i++; ?>
								@endforeach
							@else
								<tr>
									<td colspan="4" class="text-center">
										<h3 class="text-danger">Chưa có văn bản mới</h3>
									</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div style="margin-top: 40px;"></div>
</div>
@endsection
