<!-- Phần xử lý -->
<?php
    //require("../../../process/simple_html_dom.php");
    // kết nối CSDL
    //require("../../../config/connect.php");
    
    /*** Chay tren server ***/
    //require($_SERVER['DOCUMENT_ROOT']."/process/simple_html_dom.php"); // /home/kh1rca3up2dw/public_html/process/simple_html_dom.php
    //require($_SERVER['DOCUMENT_ROOT']."/config/connect.php");
    
    require(base_path()."/process/simple_html_dom1.9.php");
    require(base_path()."/config/connect.php");
    mysqli_query($conn,"SET character_set_results=utf8");
    mb_language('uni');
    mb_internal_encoding('UTF-8');
    mysqli_query($conn,"set names 'utf8'");
    $conn->set_charset("utft");
    $ti[]="";
    $data_key = array('1','3','5','15','10','12');
    $hanghoa = "";
    $xaylap = "";
    $tuvan = "";
    $phituvan = "";
    $honhop = "";
    $luachonnhadautu = "";
    foreach ($data_key as $key => $value) {
      $url = 'http://muasamcong.mpi.gov.vn:8082/GG/EP_SSJ_GGQ7012.jsp?gubun='.$value.'&lang=';
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //CURLOPT_RETURNTRANSFER = TRUE để curl_exec() trả về chuỗi chứ không xuất thẳng ra màn hình.

      /*
       * XXX: This is not a "fix" for your problem, this is a work-around.  You
       * should fix your local CAs
       */
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      /* Set a browser UA so that we aren't told to update */
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36');
      $res = curl_exec($ch);
      //if ($res === false) { die(curl_error($ch)); }  //Check loi
      curl_close($ch); ////
      $dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);
      if($value == 1){
        $goithau= $dom->load($res, true, true);
      } else if($value == 3){
          $xaylap =$dom->load($res, true, true);
      } else if($value == 5 ){
          $tuvan = $dom->load($res, true, true);
      } else if($value == 15){
          $phituvan = $dom->load($res, true, true);
      } else if($value == 10){
          $honhop = $dom->load($res, true, true);
      } else if($value == 12){
          $luachonnhadautu = $dom->load($res, true, true);
      }

    }

    $title = array();   // Mảng tên gói thầu
    $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
    $bidder = array();   // mảng thông tin bên mời thầu

       /******************* Hang hoa **********************/
        // Lấy dữ liệu hàng hóa
        //foreach ( $content = $goithau->find("td a")  as  $value) {
        foreach ( $content = $goithau->find("tr td[class=tdb] a")  as  $value) {
            if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                {// Lấy dữ liệu ID_NO với goAspTuchLive
                 $id_no = substr( $value->attr['onclick'],30,2);
                 $bid_no =substr( $value->attr['onclick'], 15 ,11);
                }
            else {// Lấy dữ liệu ID_NO với goAspTuch
                $id_no = substr( $value->attr['onclick'],26,2);
                $bid_no =substr( $value->attr['onclick'], 11 ,11);
            }
            //$link_temp = "http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=1";
            $link_temp = "http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=1";
            array_push($link, $link_temp) ;
             // Tên gói thầu
            $title_temp = $value->innertext;
            array_push($title, $title_temp);
        }
             // Tìm thông tin bên mời thầu
        //foreach ( $content = $goithau->find("tr")  as  $value) {
        //    $bidder_temp =  $value->children(2)->innertext();
        //    array_push($bidder, $bidder_temp);
        //}
        foreach($content = $goithau->find("tr") as $value){
            //$bidder_temp =  $value->children(2)->innertext();
//          $bidder_temp =  $value->innertext();
            $vl = $value->find("td[class=tdb]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
            $bidder_temp = $vl;
            array_push($bidder, $bidder_temp);
        }
        // Xóa phần tử thừa trong mảng người mời thầu
        //array_shift($bidder);
        // Thêm dữ liệu
        //$data = "";
          for ( $i= 0; $i < count($title) ; $i++ ) {
            mysqli_query($conn,"SET character_set_client=utf8");
            mysqli_query($conn,"SET character_set_connection=utf8" );
            $tengoithau = trim(stripslashes($title[$i]));
            $lienket = $link[$i];
            $benmoithau = trim(stripslashes($bidder[$i]));
            $qr = "Select * from packages where link='$lienket'";
            $kiemtratrung = DB::select($qr);

            //$kiemtratrung = mysqli_query($conn, "SELECT * FROM packages WHERE link='$lienket'");
            //$dem = mysqli_num_rows($kiemtratrung);

            //$dem = 0;
            $dem = count($kiemtratrung);
            if($dem == 0){
                $goithau = new App\Packages;
                $goithau->title =$tengoithau;
                $goithau->link = $lienket;
                $goithau->bidder = $benmoithau;
                $goithau->cate_id = 'HH';
                $goithau->save();

                //$data .= ((int)$i+1).", ".$tengoithau.", ".$lienket.", ".$benmoithau.", HH -------------";
            }

         }

         //$file = fopen("data.txt", "w+");
         //fwrite($file, $data);
         //fclose($file);

        /******************* Xay lap *******************/
              $title = array();   // Mảng tên gói thầu
              $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
              $bidder = array();   // mảng thông tin bên mời thầu


              // Lấy dữ liệu tên gói thầu
            foreach($content = $xaylap->find("tr td[class=tdb] a") as $value) {
              if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                   {// Lấy dữ liệu ID_NO với goAspTuchLive
                    $id_no = substr( $value->attr['onclick'],30,2);
                    $bid_no =substr( $value ->attr['onclick'], 15 ,11);
                   }
              else {// Lấy dữ liệu ID_NO với goAspTuch
                 $id_no = substr( $value->attr['onclick'],26,2);
                 $bid_no =substr( $value ->attr['onclick'], 11 ,11);
                   }
                 //$link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=3";
                  $link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=3";
               array_push($link, $link_temp) ;
               // Tên gói thầu
                $title_temp = $value->innertext;
                  array_push($title, $title_temp);
    }
             // Tìm thông tin bên mời thầu
          foreach($content = $xaylap->find("tr") as $value){
              //$bidder_temp =  $value->children(2)->innertext();
  //          $bidder_temp =  $value->innertext();
              $vl = $value->find("td[class=tdb]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
              $bidder_temp = $vl;
              array_push($bidder, $bidder_temp);
          }
         // Xóa phần tử thừa trong mảng người mời thầu
          //array_shift($bidder);
           // Thêm dữ liệu
                for ( $i= 0; $i < count($title) ; $i++ ) {
                 mysqli_query($conn,"SET character_set_client=utf8");
                 mysqli_query($conn,"SET character_set_connection=utf8" );
                 $tengoithau = trim(stripslashes($title[$i]));
                 $lienket = $link[$i];
                 $benmoithau = trim(stripslashes($bidder[$i]));
                 $qr = "Select * from packages where link ='$lienket'";
                 $kiemtratrung = DB::select($qr);

                $dem = count($kiemtratrung);
                if ($dem == 0)
                 {
                 $goithau = new App\Packages;
                 $goithau->title =$tengoithau;
                 $goithau->link = $lienket;
                 $goithau->bidder = $benmoithau;
                 $goithau->cate_id = 'XL';
                 $goithau->save();
               }

            }

            /******************* Tu van *******************/
            $title = array();   // Mảng tên gói thầu
            $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
            $bidder = array();   // mảng thông tin bên mời thầu


             // Lấy dữ liệu tên gói thầu
            foreach($content = $tuvan->find("tr td[class=tdb] a") as $value) {
              if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                   {// Lấy dữ liệu ID_NO với goAspTuchLive
                    $id_no = substr( $value->attr['onclick'],30,2);
                    $bid_no =substr( $value ->attr['onclick'], 15 ,11);
                   }
              else {// Lấy dữ liệu ID_NO với goAspTuch
                 $id_no = substr( $value->attr['onclick'],26,2);
                 $bid_no =substr( $value ->attr['onclick'], 11 ,11);
                   }
                 //$link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=5";
                  $link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=5";
               array_push($link, $link_temp) ;
               // Tên gói thầu
                $title_temp = $value->innertext;
                  array_push($title, $title_temp);
    }
             // Tìm thông tin bên mời thầu
          foreach($content = $tuvan->find("tr") as $value){
              //$bidder_temp =  $value->children(2)->innertext();
  //          $bidder_temp =  $value->innertext();
              $vl = $value->find("td[class=tdb]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
              $bidder_temp = $vl;
              array_push($bidder, $bidder_temp);
          }
         // Xóa phần tử thừa trong mảng người mời thầu
          //array_shift($bidder);
           // Thêm dữ liệu
                for ( $i= 0; $i < count($title) ; $i++ ) {
                 mysqli_query($conn,"SET character_set_client=utf8");
                 mysqli_query($conn,"SET character_set_connection=utf8" );
                 $tengoithau = trim(stripslashes($title[$i]));
                 $lienket = $link[$i];
                 $benmoithau = trim(stripslashes($bidder[$i]));
                 $qr = "Select * from packages where  link ='$lienket'";
                 $kiemtratrung = DB::select($qr);

                $dem = count($kiemtratrung);
                if ($dem == 0)
                 {
                 $goithau = new App\Packages;
                 $goithau->title =$tengoithau;
                 $goithau->link = $lienket;
                 $goithau->bidder = $benmoithau;
                 $goithau->cate_id = 'TV';
                 $goithau->save();
               }

            }

            /******************* Phi tu van *******************/
            $title = array();   // Mảng tên gói thầu
            $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
            $bidder = array();   // mảng thông tin bên mời thầu


             // Lấy dữ liệu tên gói thầu
            foreach($content = $phituvan->find("tr td[class=tdc] a") as $value){
              if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                   {// Lấy dữ liệu ID_NO với goAspTuchLive
                    $id_no = substr( $value->attr['onclick'],30,2);
                    $bid_no =substr( $value ->attr['onclick'], 15 ,11);
                   }
              else {// Lấy dữ liệu ID_NO với goAspTuch
                 $id_no = substr( $value->attr['onclick'],26,2);
                 $bid_no =substr( $value ->attr['onclick'], 11 ,11);
                   }
                 //$link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=15";
                   $link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=15";
               array_push($link, $link_temp) ;
               // Tên gói thầu
                $title_temp = $value->innertext;
                  array_push($title, $title_temp);
    }
             // Tìm thông tin bên mời thầu
          foreach($content = $phituvan->find("tr") as $value){
              //$bidder_temp =  $value->children(2)->innertext();
  //          $bidder_temp =  $value->innertext();
              $vl = $value->find("td[class=tdc]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
              $bidder_temp = $vl;
              array_push($bidder, $bidder_temp);
          }
         // Xóa phần tử thừa trong mảng người mời thầu
          //array_shift($bidder);
           // Thêm dữ liệu
                for ( $i= 0; $i < count($title) ; $i++ ) {
                 mysqli_query($conn,"SET character_set_client=utf8");
                 mysqli_query($conn,"SET character_set_connection=utf8" );
                 $tengoithau = trim(stripslashes($title[$i]));
                 $lienket = $link[$i];
                 $benmoithau = trim(stripslashes($bidder[$i]));
                 $qr = "Select * from packages where link ='$lienket'";
                 $kiemtratrung = DB::select($qr);

                $dem = count($kiemtratrung);
                if ($dem == 0)
                 {
                 $goithau = new App\Packages;
                 $goithau->title =$tengoithau;
                 $goithau->link = $lienket;
                 $goithau->bidder = $benmoithau;
                 $goithau->cate_id = 'PTV';
                 $goithau->save();
               }

            }

              /******************* Hon hop *******************/
              $title = array();   // Mảng tên gói thầu
              $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
              $bidder = array();   // mảng thông tin bên mời thầu

            // Lấy dữ liệu tên gói thầu
            foreach($content = $honhop->find("tr td[class=tdb] a") as $value){
              if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                   {// Lấy dữ liệu ID_NO với goAspTuchLive
                    $id_no = substr( $value->attr['onclick'],30,2);
                    $bid_no =substr( $value ->attr['onclick'], 15 ,11);
                   }
              else {// Lấy dữ liệu ID_NO với goAspTuch
                 $id_no = substr( $value->attr['onclick'],26,2);
                 $bid_no =substr( $value ->attr['onclick'], 11 ,11);
                   }
                 //$link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=10";
                   $link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=10";
               array_push($link, $link_temp) ;
               // Tên gói thầu
                $title_temp = $value->innertext;
                  array_push($title, $title_temp);
    }
             // Tìm thông tin bên mời thầu
          foreach($content = $honhop->find("tr") as $value){
              //$bidder_temp =  $value->children(2)->innertext();
  //          $bidder_temp =  $value->innertext();
              $vl = $value->find("td[class=tdb]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
              $bidder_temp = $vl;
              array_push($bidder, $bidder_temp);
          }
         // Xóa phần tử thừa trong mảng người mời thầu
          //array_shift($bidder);
           // Thêm dữ liệu
                for ( $i= 0; $i < count($title) ; $i++ ) {
                 mysqli_query($conn,"SET character_set_client=utf8");
                 mysqli_query($conn,"SET character_set_connection=utf8" );
                 $tengoithau = trim(stripslashes($title[$i]));
                 $lienket = $link[$i];
                 $benmoithau = trim(stripslashes($bidder[$i]));
                 $qr = "Select * from packages where  link ='$lienket'";
                 $kiemtratrung = DB::select($qr);

                $dem = count($kiemtratrung);
                if ($dem == 0)
                 {
                 $goithau = new App\Packages;
                 $goithau->title =$tengoithau;
                 $goithau->link = $lienket;
                 $goithau->bidder = $benmoithau;
                 $goithau->cate_id = 'HHP';
                 $goithau->save();
               }

            }

            /******************* Lua chon nha dau tu *******************/
            $title = array();   // Mảng tên gói thầu
            $link = array();     // mảng địa chỉ thông tin chi tiết gói thầu
            $bidder = array();   // mảng thông tin bên mời thầu


             // Lấy dữ liệu tên gói thầu
            foreach($content = $luachonnhadautu->find("tr td[class=tdb] a") as $value){
              if (substr( $value->attr['onclick'],0,13)=='goAspTuchLive')
                   {// Lấy dữ liệu ID_NO với goAspTuchLive
                    $id_no = substr( $value->attr['onclick'],30,2);
                    $bid_no =substr( $value ->attr['onclick'], 15 ,11);
                   }
              else {// Lấy dữ liệu ID_NO với goAspTuch
                 $id_no = substr( $value->attr['onclick'],26,2);
                 $bid_no =substr( $value ->attr['onclick'], 11 ,11);
                   }
                 //$link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&&bid_turnno=$id_no&bid_type=12";
                   $link_temp  ="http://muasamcong.mpi.gov.vn:8081/GG/EP_MPV_GGQ999.jsp?bid_no=".$bid_no."&bid_turnno=".$id_no."&bid_type=12";
               array_push($link, $link_temp) ;
               // Tên gói thầu
                $title_temp = $value->innertext;
                  array_push($title, $title_temp);
    }
             // Tìm thông tin bên mời thầu
          foreach($content = $luachonnhadautu->find("tr") as $value){
              //$bidder_temp =  $value->children(2)->innertext();
  //          $bidder_temp =  $value->innertext();
              $vl = $value->find("td[class=tdb]",1)->innertext;  //Truyen so 0 phia sau => du lieu se tra ve kieu chuoi (neu ko truyen so 0, du lieu tra ve kieu mang)
              $bidder_temp = $vl;
              array_push($bidder, $bidder_temp);
          }
         // Xóa phần tử thừa trong mảng người mời thầu
          //array_shift($bidder);
           // Thêm dữ liệu
                for ( $i= 0; $i < count($title) ; $i++ ) {
                 mysqli_query($conn,"SET character_set_client=utf8");
                 mysqli_query($conn,"SET character_set_connection=utf8" );
                 $tengoithau = trim(stripslashes($title[$i]));
                 $lienket = $link[$i];
                 $benmoithau = trim(stripslashes($bidder[$i]));
                 $qr = "Select * from packages where  link ='$lienket'";
                 $kiemtratrung = DB::select($qr);

                $dem = count($kiemtratrung);
                if ($dem == 0)
                 {
                 $goithau = new App\Packages;
                 $goithau->title =$tengoithau;
                 $goithau->link = $lienket;
                 $goithau->bidder = $benmoithau;
                 $goithau->cate_id = 'NDT';
                 $goithau->save();
               }

            }

              //header('location: ../http://tuoitre.vn');

           // return view ('admin.list_package');
             //return redirect('admin/list-package');
?>
