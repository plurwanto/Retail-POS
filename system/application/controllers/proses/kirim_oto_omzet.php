<?php
class kirim_oto_omzet extends Controller {
	function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/Omzet_all_model');
        $this->load->helper('path');
        $this->load->library('email'); 
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
	        $data['track'] = $mylib->print_track();
			$data['msg'] = "";
	        $this->load->view('proses/kirim_oto_omzet', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
	function doThis()
	{   
            $mylib 		= new globallib();
          //  $tgl1   	= $mylib->ubah_tanggal($this->input->post("tglawal"));
          //  $tgl2   	= $mylib->ubah_tanggal($this->input->post("tglakhir"));
            //$tanggal = $this->session->userdata('Tanggal_Trans');
//			$bulan = substr($tanggal,3,2);
//			$tahun = substr($tanggal,6,4);
			$bulan = date("m");
			$tahun = date("Y");
			$isiattc 	= $this->isinya($bulan,$tahun);// buat attc nya
            $pathDta	= set_realpath(APPPATH."emailtemp");
            $judulattc	= "SalesRetailOH";
			
            $this->createAttach("$judulattc",$isiattc);

          date_default_timezone_set('Asia/Jakarta');
		$this->email->clear(TRUE);
		$config['protocol']  = 'smtp';
		$config['smtp_host'] = '192.168.0.98';
		$config['mailtype']  = 'html';
		$config['wordwrap']  = FALSE;
		$this->email->initialize($config);
 
            
		/*Konfigurasi email keluar melalui mail server
|               */
           
            $this->email->from('jko@vci.co.id','Omah Herborist (Automatic)'); 
            $this->email->to("jko@vci.co.id");  //diisi dengan alamat tujuan
            $this->email->Subject('Sales Retail OH'); 
            $this->email->message("periode : ".$bulan."-".$tahun."<p>".$isiattc); 
            $this->email->attach($pathDta.$judulattc.".xls");

            //$this->email->send();

if (!$this->email->send()) {
   $data['msg'] = show_error($this->email->print_debugger()); }
  else {
	 unlink($pathDta.$judulattc.".xls");
    $data['msg'] = "Backup berhasil di kirim";
  }

		$data['msg'] = "Backup berhasil di kirim";
		$this->load->view('proses/kirim_oto', $data);
	}
        
		function isinya($bulan,$tahun){
            $mylib	= new globallib();
			$data	= $this->Omzet_all_model->getterimaList($bulan,$tahun);
//print_r($data);die();
				$output  = "";
				$output  .= "<html><body><table border='1'>";
				$output  .= "<tr>";
					$output  .= "<th>Kode Counter</th>";
					$output  .= "<th>Nama Counter</th>";
					$output  .= "<th>ITM</th>";
					$output  .= "<th>STD</th>";
					$output  .= "<th>SPD</th>";
					$output  .= "<th>DSC</th>";
					$output  .= "<th>VOU</th>";
					$output  .= "<th>NET</th>";
					$output  .= "<th>APC</th>";
					$output  .= "<th>Last trans</th>";
				$output  .=  "</tr>";

        $n=0;
		for($a = 0;$a<count($data);$a++){
			$output  .= "<tr>";
			$output  .= "	<td nowrap>".$data[$a]['Gudang']."</td>";
			$output  .= "	<td nowrap>".stripslashes($data[$a]['Keterangan'])."</td>";
			$output  .= "	<td nowrap>".number_format($data[$a]['ITM'],'','','.')."</td>";
			$output  .= "	<td nowrap>".number_format($data[$a]['STD'],'','','.')."</td>";
			$output  .= "	<td nowrap align='right'>".number_format($data[$a]['Nilai'],'','','.')."</td>";
			$output  .= "	<td nowrap>&nbsp;</td>";
			$output  .= "	<td nowrap align='right'>".number_format($data[$a]['VOU'],'','','.')."</td>";
			$output  .= "	<td nowrap align='right'>".number_format(($data[$a]['Nilai']-$data[$a]['VOU']),'','','.')."</td>";
			$output  .= "	<td nowrap align='right'>".number_format(($data[$a]['Nilai']/$data[$a]['STD']),'','','.')."</td>";
			$output  .= "	<td nowrap>".$data[$a]['tgl']." ".$data[$a]['jam']."</td>";
			$output  .= "</tr>";
			$n=$n+$data[$a]['Nilai'];
		}
			$output  .= "<tr>";
			$output  .= " <th nowrap colspan='7'>Total</th>";
            $output  .= " <th nowrap align='right'>".number_format($n,'','','.')."</th>";
			$output  .= "</tr>";
			$output  .= "</table></body></html>";
               
            return $output;
        }
    function createAttach($subjek,$message){
		$pathDta	= set_realpath(APPPATH."emailtemp");
		$handle = fopen($pathDta.$subjek.".xls", "a");
		fwrite($handle, $message);
		fclose($handle);
	}
}
?>