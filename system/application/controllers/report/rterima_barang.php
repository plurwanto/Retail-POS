<?php
class rterima_barang extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('report/rterima_model');
    }

    function index() {

        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
//print_r($_POST);die();
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['aa'] = "checked";
            $data['bb'] = "";
            $data['cc'] = "";
            $data['display1'] = "display:none";
            $data['display2'] = "display:none";
            $data['display3'] = "display:none";
            $data['display4'] = "display:none";
            $data['pilihan'] = $this->input->post('pilihan');

            $pilihan = $this->input->post('pilihan');
            $nodok = $this->input->post('nodok');
            $tgl_1 = $this->input->post('tgl_1');
            $tgl_2 = $this->input->post('tgl_2');
            $kode1 = $this->input->post('kode1');
            $kode2 = $this->input->post('kode2');
            $jenis = $this->input->post('jenis');
            $tdet = $this->input->post('tdet');
            $par = $this->input->post('par');


            if (!empty($tgl_1) and ! empty($tgl_2)) {
                $tgl1 = $mylib->ubah_tanggal($tgl_1);
                $tgl2 = $mylib->ubah_tanggal($tgl_2);
                $Tgltrans = "TglDokumen between '$tgl1' and '$tgl2'";
            } elseif (!empty($tgl_1) and empty($tgl_2)) {
                $tgl1 = $mylib->ubah_tanggal($tgl_1);
                $Tgltrans = "TglDokumen>='$tgl1'";
            } elseif (empty($tgl_1) and ! empty($tgl_2)) {
                $tgl2 = $mylib->ubah_tanggal($tgl_2);
                $Tgltrans = "TglDokumen <='$tgl2'";
            } else {
                $Tgltrans = "";
            }

            $data['tgl_1'] = $tgl_1;
            $data['tgl_2'] = $tgl_2;
            if ($pilihan == "detail") {
                $data['aa'] = "checked";
                $data['bb'] = "";
                $data['display1'] = "";
                $data['display2'] = "display:none";
                $data['detail'] = $this->rterima_model->detail($Tgltrans);
            } elseif ($pilihan == "transaksi") {
                $data['aa'] = "";
                $data['bb'] = "checked";
                $data['display2'] = "";
                $data['display1'] = "display:none";
                $data['transaksi'] = $this->rterima_model->transaksi($Tgltrans);
            } else {
                $data['aa'] = "checked";
                $data['bb'] = "";
                $data['display1'] = "";
                $data['display2'] = "";
            }


//            if ((!empty($tgl_1) && !empty($tgl_2))or ! empty($nodok)) {//echo "masuk";
//                $data['total_netto'] = "";
//                $data['aa'] = "checked";
//                $data['nodok'] = $nodok;
//                $data['tdet'] = $tdet;
//                $data['dd'] = "";
//                $data['display1'] = "";
//               // if (empty($tdet)) { //echo "ok";die();
//               //     $data['detail'] = $this->rterima_model->Nodetail($Tgltrans, $nodok);
//               // } else {
//                    $data['detail'] = $this->rterima_model->detail($Tgltrans);
//               // }
////                                                        echo "<pre>";print_r($data['detail']);echo "</pre>";
////                                                        die();
//                $data['tgl_1'] = $tgl_1;
//                $data['tgl_2'] = $tgl_2;
//
//                //$this->load->view('transaksi/report/viewdetail',$data);
//            } else {
//                $data['total_barang'] = "";
//                $data['merek'] = "";
//                $data['bb'] = "";
//                $data['cc'] = "checked";
//                $data['dd'] = "";
//                $data['display1'] = "display:none";
//
////							$data['barang']			= $this->rterima_model->barang($NoStruk,$Tgltrans,$kondisi);
//                //$this->load->view('transaksi/report/viewbarang',$data);
//            }
//
//            if (empty($par)) {
//                $data['excel'] = "";
//            } else {
//                $data['excel'] = $par;
//            }
            $data['mbrand'] = $this->rterima_model->getBrand();
            $data['content'] = 'report/terima_barang/view_terima';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

}

?>