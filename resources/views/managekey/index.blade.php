@extends('user_master')
@section('active')
Quản lý Từ Khóa
@endsection
@section('content')
 <!-- Page Content -->
     <?php 
     //dd($lstKeyWord);
     ?>   
<div class="col-sm-12">
<div style="text-align: center; ">
     <h2>Danh sách từ khóa đã thêm vào</h2>    
</div>
      <div id="table_bm"> 
               <div class="alert alert-success" id="success" hidden="true"></div><div id="lst_wrapper" class="dataTables_wrapper no-footer"><div class="dataTables_length" id="lst_length"><label>Hiển thị <select name="lst_length" id="select_list" aria-controls="lst" class=""><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> dòng</label></div><div id="lst_filter" class="dataTables_filter"><label style="cursor: pointer;" id="btn-search">Tìm kiếm<input type="search" class="" id="txtSearch" placeholder="" aria-controls="lst"></label></div><table class="table table-bordered table-striped dataTable no-footer" id="lst" role="grid" aria-describedby="lst_info">
                  <thead>
                  <tr role="row"><td colspan="9" rowspan="1">
                    
                      <!--<input type="submit" class="btn btn-danger" name="btn_delete" id="btn_delete" value="Xóa" style="float: right;margin-left: 10px">-->
                      <form>
                        <input type="hidden" name="_token" value="UEdcMh1tszTbFFLK9Yi8KuFq6pA0PNbLJkbs7dt1">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Add" id="modelAdd">Thêm từ khóa</button>
                        <button type="button" class="btn btn-danger Delete_TuKhoa" data-toggle="modal" data-target="#modaltukhoa" data-backdrop="static" style="float: right;margin-left: 10px" name="Delete" id="Delete">Xóa từ khóa</button>
                      </form>
                      
                  </td></tr>
                  <tr role="row"><th width="30" class="sorting_asc" tabindex="0" aria-controls="lst" rowspan="1" colspan="1" aria-sort="ascending" aria-label=": activate to sort column descending" style="width: 30px;"></th><th width="50" class="sorting" tabindex="0" aria-controls="lst" rowspan="1" colspan="1" aria-label="Stt: activate to sort column ascending" style="width: 50px;">Stt</th><th class="sorting" tabindex="0" aria-controls="lst" rowspan="1" colspan="1" aria-label="Từ khóa: activate to sort column ascending" style="width: 470px;">Từ khóa</th><th class="sorting" tabindex="0" aria-controls="lst" rowspan="1" colspan="1" aria-label="Thao tác: activate to sort column ascending" style="width: 390px;">Thao tác</th></tr>
                  </thead>
                   
                    <tbody id="tblContent">
           
                    
              
                      </tbody>
                  </table>
                  <div class="dataTables_info" id="lst_info" role="status" aria-live="polite">
                  Hiển thị 1 đến 1 của 1 dòng
              </div>

             <div class="dataTables_paginate paging_simple_numbers" id="lst_paginate">
                  

              </div>
             
          </div>

<!-- Model Cập Nhật thông tin -->
  <!-- Modal thêm-->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Thêm từ khóa</h4>
        </div>
        <form action="" method="post" id="addKeyword" name="addKeyword" novalidate="novalidate">
          <div class="modal-body">
           <input name="_token" type="hidden" value="{!! csrf_token() !!}" />
           <div class="form-group">
            <label for="usr">Từ khóa:</label>
            <input type="text" class="form-control" id="keyword" name="keyword">
            <p id="errorName" style="color:red" class="hidden">Từ khóa đã tồn tại!</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Thêm</button>
          <button type="button" class="btn btn-success" data-dismiss="modal">Đóng</button>

        </div>
      </form>
    </div>

  </div>
  </div>
  <!-- Modal Edit Từ Khóa-->
  <div class="modal fade" id="modelEdit" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Thay đổi từ khóa</h4>
        </div>
        <form action="" method="post" id="editKeyword" name="editKeyword" novalidate="novalidate">
          <div class="modal-body">
            <input type="hidden" name="_token" value="UEdcMh1tszTbFFLK9Yi8KuFq6pA0PNbLJkbs7dt1">
            <div class="form-group">
              <label for="usr">Từ khóa:</label>
              <input type="hidden" class="form-control" id="id_edit" name="id_edit" value="">
              <input type="text" class="form-control" id="keyword_edit" name="keyword_edit" value="" required="" aria-required="true">
              <p id="errorName2" class="hidden" style="color:red"></p>
            </div>
            <p id="errorLogin"></p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success Editvanban">Cập nhật</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Đóng</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
    });

  // Thêm từ khóa
     $("#modelAdd").click(function(){
        $("#keyword").val('');
        $('#myModal').modal('show');
     });
  


  });
</script>
<script>
    $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
  //Cập nhật (31/10/2018) Thêm người keyword
  $(function(){


  });


$(document).ready(function(){

    var pageRecord = $("#select_list").val();

    $(document).on("change", "#pageRecord", function(){
        var pageRecord = $("#lst_length").val();
        loadData(pageRecord, 1 , $("#txtSearch").val());
    })

    loadData(pageRecord, 1, $("#txtSearch").val());
    function loadData(pageRecord, pageCurrent, keySearch){

        if(pageCurrent === null || pageCurrent === '' || pageCurrent === undefined){
            pageCurrent = $(".current").data("idx");
        }

        if(keySearch === null || keySearch === '' || keySearch === undefined){
            keySearch = 'null';
        }

        $.ajax({
            type: "get",
            url: "getManageKeyWordList/" + pageRecord + "/" + pageCurrent + "/" + keySearch ,
            success: function(response){

                datas = JSON.parse(response);

                var render = "";

                var renderPaginate = "<div>";

                stt = 1+ ((pageCurrent - 1) * pageRecord);

                $.each(datas.data, function(key, value){

                    var rClass = (key + 1) % 2 === 0 ? "old" : "even"; 

                     render += `
                    <tr id="" role="row" class="${rClass}">
                        <td style="text-align: center;" class="sorting_1"><input type="checkbox" name="id[${value.key_word_id}]" class="checkbox" value="${value.key_word_id}"></td>
                        <td style="font-size: 150%;">${stt}</td>                    
                          <td style="font-size: 150%;">${value.keyword}</td>
                          <td>
                              <button class="btn btn-primary btn-xs edit_bm glyphicon glyphicon-pencil tukhoa" data-toggle="modal" data-target="#modal_edit_keywork" data-id="${value.key_word_id}"></button>
                              
                              <button type="button" name="Del_GoiThau" id="" class="btn btn-xs btn-danger Del_GoiThau glyphicon glyphicon glyphicon-trash" data-id="${value.key_word_id}" value="43"></button>
                          </td>
                    </tr>
                     `;
                     stt = stt + 1;
                })

                renderPaginate += `
                <a class="paginate_button previous disabled" aria-controls="lst" data-dt-idx="0" tabindex="0" id="lst_previous">
              Trang trước</a>
                `;

                for(var i = 0; i < datas.paginate.countPage; i++){
                    renderPaginate += `
                    <span><a class="paginate_button ${ datas.paginate.currentPage == i+1 ? "current" : "" }" aria-controls="lst" data-idx="${i+1}" tabindex="${i}">${i+1}</a></span>
                    `;
                }

               $("#tblContent").html(render);

               renderPaginate += `
             <a class="paginate_button next disabled" aria-controls="lst" data-dt-idx="2" tabindex="0" id="lst_next">Trang tiếp</a> 
               `;

               $("#lst_paginate").html(renderPaginate);
            },
            error: function(response){
                console.log(response);
                alert("Có lỗi xảy ra trong quá trình tải dữ liệu. Vui lòng kiểm tra lại");
            }
        })
    }


    $("#addKeyword").validate({
      rules: {
        keyword: {   
          required: true  
        }
      }, 
      messages: {
        keyword: {
          required: "Xin vui lòng nhập từ khóa!"
        }
      }, 
      submitHandler: function(form) {
        $.ajax({
            type: "get", 
            url: "addManageKeyword", 
            data: {
                _token: $("_token").val(),
                keyword : $("#keyword").val(),   
            },
            success: function(data){

                data = JSON.parse(data);
                if(data.status == 1){
                    $('#myModal').modal('hide');

                    loadData(pageRecord, 1, $("#txtSearch").val());
                }else{
                    alert("Thêm không thành công");
                }
            } ,
            error: function() {
                console.log("ERROR! Can't to login");
            }
        });
      }
    });

    $(document).on("click", ".Del_GoiThau", function(){
        var r = confirm("Bạn có muốn xóa từ khóa?");
      if(r == true){
          var id = $(this).data("id");
          $.ajax({                   
            type : 'get',          
            url : 'deleteManagerKeyWork/' + id, //Here you will fetch records       
            success : function(data){
              data = JSON.parse(data);

                console.log(data.status);
                if(data.status == 1){
                    loadData(pageRecord, 1, $("#txtSearch").val());
                }else{
                    alert("Xóa không thành công");
                }
            },
            error: function(data){

            }
          });
      }
    })

    $(document).on("click", ".tukhoa", function(){
        var idK = $(this).data("id");
        $("#id_edit").val("");
        $("#keyword_edit").val("")
        var id = $(this).val(); 
        $.ajax({
          url     : "editManageKeyWord/" + idK,
          type    : "get",
          success:function(re){
            re = JSON.parse(re);
            console.log(re[0]);
              $("#keyword_edit").val(re[0].keyword)
              $("#id_edit").val(idK);
              $('#modelEdit').modal('show');
          }
        });
    })

    $(document).on("click", ".paginate_button", function(){
        var pageCurrent = $(this).data("idx");
        var pageRecord = $("#select_list").val();
        loadData(pageRecord, pageCurrent);
    })

    $(document).on("change", "#select_list", function(){
        var pageRecord = $("#select_list").val();
        loadData(pageRecord, 1, $("#txtSearch").val());
    })

    $(document).on("click", "#btn-search", function(){
        var ketSearch = $("#txtSearch").val();
        var pageRecord = $("#select_list").val();
        loadData(pageRecord, 1, ketSearch);

    }) 


    $(document).on("click", ".Editvanban", function(e){
        e.preventDefault();
        var keyword = $("#keyword_edit").val();
        var id = $("#id_edit").val();

        $.ajax({
            type: "get",
            url: "updateManageKeyWord",
            data: {
                id: id,
                keyword: keyword
            },
            success: function(res){
                res = JSON.parse(res);

                if(res.status == 1){
                    $('#modelEdit').modal('hide');
                    loadData(pageRecord, 1, $("#txtSearch").val());
                }else{
                    alert("Sửa không thành công, vui lòng kiểm tra lại");
                }
            },
            error: function(res){
                alert("Có lỗi xảy ra trong quá trình sửa, vui lòng kiểm tra lại");
            }
        })
    })


    $(".Delete_TuKhoa").click(function(){
      var r = confirm("Bạn có muốn xóa từ khóa?");
        if (r == true) {
         var id = [];  
       $(':checkbox:checked').each(function(i){  
         id[i] = $(this).val();  
        });  

         $.ajax({                   
          type : 'get',          
          url : 'deleteMultyKey', //Here you will fetch records 
          data : {id:id},         
          success : function(data){
                loadData(pageRecord, 1, "");
          }
        });
           
       } else {
        txt = "You pressed Cancel!";
       }
    });

})
</script>

</div>
</div>

@endsection



