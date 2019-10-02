<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Trợ Lý Ảo</title>
	
	<!-- Tro toi file app.css - asset mac dinh se tro toi folder public -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>

	<nav class="navbar navbar-default">
		<div style="background:#0066CC; color:#FFFFFF ;" class="container-fluid">
			<div class="navbar-header" >
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				  <span class="logo-lg">  <img height="100px" width="110px;" alt="logo"  style="margin-left: 80px; margin-top: 0px;" src="{{ asset('photo/logo/logo.png') }}"> </span>

				  


			</div>



			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


				
				<ul class="nav navbar-nav navbar-right">
					<!-- Trỏ tới đường dẫn (hay Route) '/home' -->
					<li><a style="color:#FFFFFF ;"  href="{{url('/home')}}">Trang chủ</a></li>
					@if (Auth::guest()) 
						<li><a style="color:#FFFFFF ;"  href="{{route('getLogin')}}">Đăng nhập</a></li> <!-- Trỏ tới Route tên 'getLogin' -->
						<!-- Trỏ tới Route tên 'getRegister' -->
						<li><a style="color:#FFFFFF ;"  href="{{route('getRegister')}}">Đăng ký</a></li>

						{{-- <!- Trỏ tới đường dẫn (hay Route) '/auth/register' ->
						<li><a style="color:#FFFFFF ;"  href="{{ url('/auth/register') }}">Đăng ký</a></li> --}}
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Đăng xuất</a></li>
							</ul>

						</li>


					@endif

				</ul>

				


				
			</div>



		</div>





	</nav>
		

	<div class="container"  >
		<form role ="search" method="get" id="searchform" action="{{route('search')}}">	
			

    		<div class="input-group pull-right col-md-4"  >

  				<div class="input-group " >

    		
	        		<input type="text" class="form-control"    value name="key" 
	            	placeholder="Tìm kiếm với tên văn bản..."> <span class="input-group-btn ">

	            	<button type="submit"  class="btn btn-default">
	                	<span class="glyphicon glyphicon-search"></span>
	            	</button>
	        			</span>
	  			</div>   
    
    		</div>
    		

		</form>
	</div>

				



	



	@yield('content')





	<!-- Scripts -->
	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
	{{-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script> --}}


	


</body>
</html>