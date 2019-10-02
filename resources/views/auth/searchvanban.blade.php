@extends('newhome')
@section('content')

<div class="container">
  <div class="row">
    <div  class="col-md-12">
      <div class="table-responsive">
        <div class="pakage-css">
        
          

          <div class="title-goi-thau">Danh sách văn bản tìm thấy</div>
          <table class="table table-striped table-bordered table-hover" id="lst">
            <thead>
              <tr >
                <th>Stt</th>                                
                <th class="text-center" >Tên văn bản</th>
                
                <th width="90" class="text-center" >Ngày đăng</th>
                
              </tr>
            </thead>
            <tbody id="result">
              @if(count($van_ban)>0)
              <?php $i = ($van_ban->currentpage()-1)* $van_ban->perpage();?>
                @foreach($van_ban as $item)
                  <?php 
                    $dinh_dang_cu =  substr($item->created_at,0,10);
                    $ngaycapnhat = date("d-m-Y", strtotime($dinh_dang_cu));
                  ?>
                  <tr>
                    <td style="vertical-align: middle;">{{ $i+1  }}</td>
                    <td style="vertical-align: middle;"><a target = '_blank' href = "{!!$item->link!!}">{{ $item->title }}</a></td>
                    
                    <td style="vertical-align: middle;">{{ $ngaycapnhat }}</td>
                    
                  </tr>
                  <?php  $i++; ?>
                @endforeach

              @else
                <tr>
                  <td colspan="5" class="text-center" >
                    <h3 class="text-danger">Không tìm thấy văn bản</h3>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
         <?php echo $van_ban->render(); ?>

        </div>
      </div>
    </div>
  </div>
  
@endsection
