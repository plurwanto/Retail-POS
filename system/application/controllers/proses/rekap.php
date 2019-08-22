<?php
class rekap extends Controller {
	function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/tutup_hari_model');
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
	        $data['track'] = $mylib->print_track();
			$data['msg'] = "";
			$last_update_query = $this->tutup_hari_model->getLastDate();
			$data['tanggal']= $last_update_query;
	        $this->load->view('proses/rekap', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
	function doThis()
	{
		$mylib = new globallib();
		$id = $this->session->userdata('userid');
		$tgl = $this->tutup_hari_model->getLastDate();//echo $tgl->TglTrans;die();
		$tahun = substr($tgl->TglTrans,0,4);
                $this->cetak($tgl->TglTrans,$tahun); // cetak rekap harian hanya untuk counter
            return "Seleasai";
	}
	
        
        function cetak($tgl,$tahun)
	{
               // $no	= $this->uri->segment(4);

                $printer = $this->tutup_hari_model->NamaPrinter($_SERVER['REMOTE_ADDR']);

                        $data['ip']             = $printer[0]['ip'];
                        $data['nm_printer']     = $printer[0]['nm_printer'];
                        $data['store']		= $this->tutup_hari_model->aplikasi();
			$data['header']		= $this->tutup_hari_model->all_trans($tgl);
//print_r($data['header']);
//			$data['detail']		= $this->tutup_hari_model->det_trans($no);
//die();
//                        $data['ip']    = "\\\\".."\LQ-2170s";

            if(!empty($data['header'])){
//		$this->load->view('proses/cetak_tutup',$data); // jika untuk tes
		$this->load->view('proses/cetak_transaksi',$data); // jika ada printernya
            }
        }
}
?>