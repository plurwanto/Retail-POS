<?php
class kirim_otomatis extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/kirim_data_model');
        $this->load->helper('path');
        $this->load->library('email');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['msg'] = "";
            $data['content'] ='proses/kirim_oto';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function doThis() {
        $mylib = new globallib();
        $tgl1 = $mylib->ubah_tanggal($this->input->post("tglawal"));
        $tgl2 = $mylib->ubah_tanggal($this->input->post("tglakhir"));
        $tglawal = $this->input->post("tglawal");
        $tglakhir = $this->input->post("tglakhir");
        $isiattc = $this->isinya($tgl1, $tgl2); // buat attc nya
        $pathDta = set_realpath(APPPATH . "emailtemp");
        $judulattc = "BackupRetail";
        $this->createAttach("$judulattc", $isiattc);

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '30';

        $config['smtp_user'] = 'herborist2014@gmail.com';
        $config['smtp_pass'] = 'omah2017';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->email->initialize($config);


        /* Konfigurasi email keluar melalui mail server
          | */
        $kdC = $this->kirim_data_model->FindCabang();
        $kg = $kdC->KdGU;
        $cb = $kdC->Keterangan;
        $this->email->from('herborist2014@gmail.com', 'Omah Herborist');
        $this->email->to("dataomah@vci.co.id");  //diisi dengan alamat tujuan
      //  $this->email->cc("jko@vci.co.id");  //diisi dengan alamat tujuan
        $this->email->Subject('Backup Otomatis');
        $this->email->message("BackupRetail : " . $kg . "-" . $cb . $tgl1 . "s/d" . $tgl2);
        $this->email->attach($pathDta . $judulattc . ".txt");

        //$this->email->send();

        $kirim = $this->email->send();
        if ($kirim) {
            unlink($pathDta . $judulattc . ".txt");
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Omzet Tanggal ' .$tglawal . " s/d " . $tglakhir. ' Berhasil Dikirim </div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-stop"></i> Gagal Kirim Omzet, Hubungi IT </div>');
        }
        redirect('proses/kirim_otomatis');

    }

    function isinya($sPrevStart, $sPrevEnd) {
        $mylib = new globallib();
        $csv_terminated = "\n";
        $csv_separator = "#";
        $csv_enclosed = '';
        $csv_escaped = "\"";
        $schema_insert = "";
        $out = '';
        $batasjudul = ";";

        $this->load->model('proses/kirim_data_model', 'chat');


        $kdC = $this->kirim_data_model->FindCabang();
        $kg = $kdC->KdGU;
        $tabel = array('transaksi_header', 'transaksi_detail', 'trans_terima_header', 'trans_order_header', 'trans_opname_header', 'trans_retur_header', 'trans_lainlain_header');
//                $tabel = array('trans_lainlain_header');
//                $kondisi = array('TglDokumen');// kondisi untuk where
        $kondisi = array('Tanggal', 'Tanggal', 'TglDokumen', 'TglDokumen', 'TglDokumen', 'TglDokumen', 'TglDokumen'); // kondisi untuk where
        $output = "";
        $outputD = '';
        for ($i = 0; $i < count($tabel);) {
            $outputD .= $this->chat->getReportsDetail($tabel[$i], $kondisi[$i], $sPrevStart, $sPrevEnd, $csv_terminated, $csv_separator, $csv_enclosed, $csv_escaped, $schema_insert, $out, $batasjudul, $kg);
            $i++;
        }
        $output .= $outputD;
        // ----- +++ -----
        $tabelH = array('trans_terima_header', 'trans_order_header', 'trans_opname_header', 'trans_retur_header', 'trans_lainlain_header');
        $tabelD = array('trans_terima_detail', 'trans_order_detail', 'trans_opname_detail', 'trans_retur_detail', 'trans_lainlain_detail');
        $key = array('NoDokumen', 'NoDokumen', 'NoDokumen', 'NoDokumen', 'NoDokumen'); // kondisi untuk where
        $tgl = array('TglDokumen', 'TglDokumen', 'TglDokumen', 'TglDokumen', 'TglDokumen'); // kondisi untuk where
        $outputTrxD = '';

        for ($c = 0; $c < count($tabelH);) {
            $outputTrxD .= $this->chat->getDetailTrx($tabelD[$c], $tabelH[$c], $key[$c], $tgl[$c], $sPrevStart, $sPrevEnd, $csv_terminated, $csv_separator, $csv_enclosed, $csv_escaped, $schema_insert, $out, $batasjudul, $kg);
            $c++; //echo count($tabelH);die();
        }
        $output .= $outputTrxD;

        return $output;
    }

    function createAttach($subjek, $message) {
        $pathDta = set_realpath(APPPATH . "emailtemp");
        $handle = fopen($pathDta . $subjek . ".txt", "a");
        fwrite($handle, $message);
        fclose($handle);
    }

}

?>