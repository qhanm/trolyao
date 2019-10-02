@extends('admin_master')
@section('list_active')
active
@endsection
@section('active')
Danh Sách Tài Khoản
@endsection
@section ('content')
<link rel="stylesheet" type="text/css" href="{{ asset('public/mystyle.css') }}">
<script src="{{ asset('public/libs/datatables.min.js') }}"></script>
<link href="{{ asset('public/libs/datatables.min.css') }}" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function(){
		$('#lst').DataTable();
	});
</script>
<style type="text/css" media="screen">
.error{color: red;}
</style>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<iframe width="100%" height="800px" src="https://docs.google.com/spreadsheets/d/1kPuq03LyZHgMJSfa7lhAgHpo888zXTmoujbNlc3peY8/edit?usp=sharing"></iframe>
			</div>
		</div>
	</div>
</div>	
@endsection
