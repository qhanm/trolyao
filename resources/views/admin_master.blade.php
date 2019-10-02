<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Trợ Lý Ảo </title>
  <!-- Tell the browser to be responsive to screen width -->
   <link rel="stylesheet" href="{{ asset('new/bootstrap.min.css') }}">
  <script src="{{ asset('new/jquery.min.js') }}"></script>
  <script src="{{ asset('new/bootstrap.min.js') }}"></script>
   <link href="{{ asset('libs/datatables.min.css') }}" rel="stylesheet">
     <link href="{{ asset('libs/buttons.dataTables.min.css') }}" rel="stylesheet">
     <link href="{{ asset('libs/jquery.dataTables.min.css') }}" rel="stylesheet">
       <link href="{{ asset('loader.css') }}" rel="stylesheet">

   <link rel="stylesheet" href="{{ asset('bootstraps/bootstrap.min.css') }}">
     <script src="{{ asset('bootstraps/jquery.min.js') }}"></script>
     <script src="{{ asset('bootstraps/bootstrap.min.js') }}"></script>
     <script src="{{ asset('libs/datatables.min.js') }}"></script>
      <link rel="stylesheet" href="{{ asset('bootstraps/bootstrap.min.css') }}">
     <script src="{{ asset('bootstraps/jquery.min.js') }}"></script>
     <script src="{{ asset('bootstraps/bootstrap.min.js') }}"></script>


  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/slidebar.css') }}">
  <script src="{{ asset('libs/jquery.validate.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top"  style="height: 100px; background: #006699"  >
      <!-- Sidebar toggle button-->

      <div class="navbar-custom-menu">
       <a class="nav navbar-nav" href="/">
         <!-- <li><a style="width: 90px;" href="{{ url('admin/admin_profile') }}"> Trang Chủ </a></li> -->
          <img height="80px" width="600px;" alt="logo"  style="margin-left: -900px; margin-top: 5px;" src="{{ asset('photo/logo/giaiphap2.png') }}"/>
        </a>

        <ul class="nav navbar-nav navbar-right">

        <?php
        $ten = Auth::user()->name;
        $sotu = str_word_count($ten);
        $mang = explode(' ', $ten);
        $phantu = $sotu - 2;
        $name = $ten;

        ?>
          @if (Auth::guest())
            <li><a href="{{ url('/auth/login') }}">Đăng nhập</a></li>
            <li><a href="{{ url('/auth/register') }}">Đăng ký</a></li>
          @else

            <li style="margin-right: 20px;" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Xin chào <strong> {{$name }}</strong> <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('admin_profile') }}"> <span class="glyphicon glyphicon-info-sign"></span> Thông tin tài khoản</a></li>
                 <li><a href="{{ url('/authen/getLogout') }}"><span class="glyphicon glyphicon-off"></span> Đăng xuất</a></li>

              </ul>
            </li>
          @endif
        </ul>

      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->

  <aside class="main-sidebar">
  <div style="background: #51A9FF ; height: 100px; margin-top: -50px;">
   <div class="logo-lg"> <a class="home_" href="/"> <img height="100px" width="110px;" alt="logo"  style="margin-left: 80px; margin-top: 0px;" src="{{ asset('photo/logo/logo.png') }}"></a></div>
  </div >



   <nav  class="navbar navbar-inverse" style="height: 100px;">
    <!-- sidebar: style can be found in sidebar.less -->

    <section class="sidebar">
      <!-- Sidebar user panel -->

      <div class="user-panel">

        <div class="pull-left image">
        <!-- Thay đổi ảnh Avatar -->
        <?php
        $id = Auth::user()->id;

        $src = DB::table('images')->where('user_id',$id)->select('src')->get();

        ?>
        @if (count($src)== 1 )
         <div style="margin-left: 15px; margin-top: 20px; width: 80px; height: 80px;"> <a  target="_blank" href="..//<?php foreach ($src as  $value) {
           echo $value->src;}?>"> <img src="../<?php foreach ($src as  $value) {
           echo $value->src;   }?>" class="img-circle" alt="User Image" width="60px" height="60px"/>
          </a> </div>
        </div>
        @else
        <div style="margin-left: 15px; margin-top: 20px; width: 80px; height: 80px;"> <a target="_blank" href="{{asset('photo/image/avatar.png')}}"> <img  src="{{asset('photo/image/avatar.png')}}" alt="User Image" width="60px" height="60px"/>
          </a> </div>
        </div>
        @endif



        <div style="margin-left: 30px; margin-top: 20px;" class="pull-left info">

          <p>{{$name}}</p>

          <a href="{!! route ('admin_profile')!!}"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
</div>  
{{-- END wrapper --}}

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <div  style = "margin-top:-10px; margin-left: -10px; "   class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

       <ul class="nav navbar-nav" id="side-menu">
        
        <li class="@yield ('dashboard_active')">
          <a href="{!! route ('dash_board')!!}"><i class="glyphicon glyphicon-dashboard"></i> Dashboard <span class="fa arrow"></span></a>
        </li>

        <li class="@yield ('pack_active')">
          <a href="{!! route ('list-package')!!}"><i class="glyphicon glyphicon-list"></i> Quản lý gói thầu <span class="fa arrow"></span></a>
        </li>

        <li class="@yield ('pack1_active')">
          <a href="{!! route ('list-package-vanban')!!}"><i class="glyphicon glyphicon-list"></i> Quản lý văn bản pháp luật <span class="fa arrow"></span></a>
        </li>

        <li class="@yield ('email_active')">
          <a href="{!! route ('send_email_setting')!!}"><i class="glyphicon glyphicon-envelope"></i> <span style="margin-left: 3px; " > Quản lý email về gói thầu </span> <span class="fa arrow"></span></a>
        </li>

      <li class="@yield ('email1_active')">
        <a href="{!! route ('send_email_setting_vanban')!!}"><i class="glyphicon glyphicon-envelope"></i> <span style="margin-left: 3px; " > Quản lý email về văn bản pháp luật </span> <span class="fa arrow"></span></a>
      </li>


      <li class="@yield ('list_active')">
        <a href="{!! route ('list-user')!!}"><i class=" glyphicon glyphicon-th-list"></i><span style="margin-left: 3px; " > Quản lý người dùng</span> </a>
      </li>

      <li class="@yield ('config_active')">
        <a href="{!! route ('getConfig')!!}"><i class=" glyphicon glyphicon-cog"></i><span style="margin-left: 3px; " > Cấu hình dữ liệu</span> </a>
      </li>

      <li class="@yield ('thongtin_active')" >
        <a href="{!! route ('admin_profile')!!}"><i class="  glyphicon glyphicon-user"></i> Thông tin tài khoản</a>
      </li>

      <li class="@yield ('thongtin_active')" >
        <a href="{!! route ('admin_profile')!!}"><i class="  glyphicon glyphicon-user"></i> Thông tin tài khoản</a>
      </li>


    <!--   <li class="@yield ('dashboard_active')" >
        <a href="{{route('dash_board')}} "><i class=" glyphicon glyphicon-dashboard"></i> Thống kê</a>
      </li>
 -->


    </ul>
  </div>
  </section>
</nav>

<!-- <ul class="sidebar-menu">
  <li class="header">MAIN NAVIGATION</li>
  <li class="active treeview">
    <a href="#">
      <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu">
      <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
      <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
    </ul>

  </ul>  -->
    
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">@yield('active')</li>
      </ol>
    </section>
   <!-- Main content -->
    <!-- Page Content -->

         @yield('content')
         </div>
  <!-- /.content-wrapper -->
  <footer style="background: #00CED1;" class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Phiên bản</b> 1.0.0
    </div>

    <strong>Copyright &copy; 2018 by TroLyAo.</strong>
  </footer>


      

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Thay đổi Avatar -->
<script type="text/javascript">

var addNewLogo = function(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //Hiển thị ảnh vừa mới upload lên
            $('#logo-img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
        //submit form để upload ảnh
        $('#img-upload-form').submit();
    }
}
$(document).ready(function(){

  var submitImageForm = function(form){
    toggleLoading(); //show loading mask
    var data = {
            id  : <?php echo  Auth::user()->id ?>,
            image : new FormData(form) ,

        };
    $.ajax({
        url: "user/upload", //api upload phía server
        type: "post",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            alert (data);
            toggleLoading();
            alert('thành công');
        },
        error: function (data) {
           toggleLoading();
        }
    });
    return false;
}


});

</script>


<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->

<!-- Morris.js charts -->
<script src="{{ asset('js/raphael-min.js') }}"></script>
<script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>

<!-- datepicker -->
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<!-- AdminLTE for demo purposes -->


</body>
</html>
