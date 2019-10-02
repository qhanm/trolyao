<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use DB;
use App\GoiThau;
use App\User;
use App\Http\Controllers\MotGoiThau;
use App\SentEmail;
use App\SentEmail2;
use Illuminate\Http\Request;

class emailUser extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'email:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $data = DB::select("select * from users where status = '1' and level = '1' and receive_email = '1' ");

        $title = 'Đây là mail mới nhất';
        foreach ($data as $bac1) {
            $mail = $bac1->email;
            $id = $bac1->id;
            $user_name = $bac1->name;
            $tukhoa = DB::table('keywords')->where('user_id', $id)->get();
            $tukhoa2 = DB::table('keywords2')->where('user_id', $id)->get();
            $noidung = array();
            $noidung2 = array();

            $count = 0;
            $count2 = 0;
            foreach ($tukhoa as $bac2) {
                $key = $bac2->keyword;
                $goithau = DB::select("select * from packages where title like '%$key%' or bidder like  '%$key%' order by id DESC");

                foreach ($goithau as $key => $value) {
                    $id_cankiemtra = $value->id;
                    $id_taikhoan = $id;
                    $ketquakiemtra = DB::select("select * from sent_emails where user_id='$id_taikhoan' and package_id ='$id_cankiemtra'");


                    if (count($ketquakiemtra) == 0) {
                        //echo $value->title;                                 
                        array_push($noidung, $value);
                        $sent_emails = new SentEmail();
                        $sent_emails->user_id = $id_taikhoan;
                        $sent_emails->package_id = $id_cankiemtra;
                        $sent_emails->save();
                    }
                }
            }

            foreach ($tukhoa2 as $bac3) {
                $key = $bac3->keyword;
                $vanban = DB::select("select * from packages2 where title like '%$key%' order by id DESC");

                foreach ($vanban as $key => $value) {
                    $id_cankiemtra2 = $value->id;
                    $id_taikhoan2 = $id;
                    $ketquakiemtra2 = DB::select("select * from sent2_email where user_id='$id_taikhoan2' and package2_id ='$id_cankiemtra2'");


                    if (count($ketquakiemtra2) == 0) {
                        //echo $value->title;                                 
                        array_push($noidung2, $value);
                        $sent_emails = new SentEmail2();
                        $sent_emails->user_id = $id_taikhoan2;
                        $sent_emails->package2_id = $id_cankiemtra2;
                        $sent_emails->save();
                    }
                }
            }

            $count = count($noidung);
            $count2 = count($noidung2);
            echo $count;
            // Gửi email                
            if ($count > 0) {
                Mail::send('process.email', array('noidung' => $noidung, 'count' => $count, 'name' => $user_name, 'keyword' => $tukhoa), function($message) use ($mail) {
                    $message->to($mail)->subject('Thông Tin Gói Thầu');
                });
                //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
            }

            if ($count2 > 0) {
                Mail::send('process.email2', array('noidung' => $noidung2, 'count' => $count2, 'name' => $user_name, 'keyword' => $tukhoa2), function($message) use ($mail) {
                    $message->to($mail)->subject('Thông Tin Văn Bản');
                });
                //return " <script type='text/javascript'> $('#loader').hide(); alert('Gửi thành công'); </script>";
            }
            //   return    ProcessController::GuiGoiThau($mail,$noidung);
        }
    }

}
