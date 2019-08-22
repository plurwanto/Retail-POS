<?php
class kirim_data extends Controller {
	function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/kirim_data_model');
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
	        $data['track'] = $mylib->print_track();
			$data['msg'] = "";
                if($this->input->post()){
                     $data['tgl_1']	= $this->input->post('tgl_1');
                     $data['tgl_2']	= $this->input->post('tgl_2');
                }        
                     $data['tgl_1']     = date('d-m-Y') ;
                     $data['tgl_2']     = date('d-m-Y') ;
	        $this->load->view('proses/kirim_data', $data);
	    }
		else{
			$this->load->view('denied');
            }
    }
        function doThis($sPrevStart = null, $sPrevEnd = null) {
           $mylib = new globallib();
            $csv_terminated = "\n";
            $csv_separator  = "#";
            //$csv_enclosed   = '"';
            $csv_enclosed   = '';
            $csv_escaped    = "\"";
            $schema_insert  = "";
            $out            = '';
            $batasjudul     = ";";
           $sPrevStart = $mylib->ubah_tanggal($this->input->post('tgl_1'));
           $sPrevEnd   = $mylib->ubah_tanggal($this->input->post('tgl_2'));

                // Load your model - rename the "your_model_name"
                $this->load->model('proses/kirim_data_model','chat');

            

//                $kdC = $this->cabang();
                $kdC = $this->kirim_data_model->FindCabang();
                $kg = $kdC->KdGU;
//                $outputH = $this->chat->getReports($sPrevStart,$sPrevEnd);
                $tabel = array('transaksi_header','transaksi_detail','trans_terima_header','trans_order_header','trans_opname_header','trans_retur_header','trans_lainlain_header');
//                $tabel = array('trans_lainlain_header');
//                $kondisi = array('TglDokumen');// kondisi untuk where
                $kondisi = array('Tanggal','Tanggal','TglDokumen','TglDokumen','TglDokumen','TglDokumen','TglDokumen');// kondisi untuk where
                $output  = "";
                $outputD ='';
                for ($i=0;$i<count($tabel);){
                    $outputD .= $this->chat->getReportsDetail($tabel[$i],$kondisi[$i],$sPrevStart,$sPrevEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul,$kg);
                    $i++;
                }
                $output .= $outputD;
                // ----- +++ -----
                $tabelH      = array('trans_terima_header','trans_order_header','trans_opname_header','trans_retur_header','trans_lainlain_header');
                $tabelD      = array('trans_terima_detail','trans_order_detail','trans_opname_detail','trans_retur_detail','trans_lainlain_detail');
                $key    = array('NoDokumen','NoDokumen','NoDokumen','NoDokumen','NoDokumen');// kondisi untuk where
                $tgl    = array('TglDokumen','TglDokumen','TglDokumen','TglDokumen','TglDokumen');// kondisi untuk where
                $outputTrxD ='';
                
                for ($c=0;$c<count($tabelH);){
                    $outputTrxD .= $this->chat->getDetailTrx($tabelD[$c],$tabelH[$c],$key[$c],$tgl[$c],$sPrevStart,$sPrevEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul,$kg);
                    $c++;//echo count($tabelH);die();
                }
                $output .= $outputTrxD;

                $filename = $sPrevStart . '_' . $sPrevEnd . ' extrak.csv';

                header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
                header( "Content-Length: " . strlen( $output ) );
                header( "Content-type: text/x-csv" );
                //header( "Content-type: text/csv" );
                //header( "Content-type: application/csv" );
                header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
                echo $output;

                return;
        }
        function cabang(){
            $kdC = $this->kirim_data_model->FindCabang();
//            print_r($kdC);
              $csv_terminated = "\n";
            $csv_separator  = "##";
            $batasjudul     = "##";
		$no = $kdC->KdCabang;
                $kd = "KdCabang=$no$batasjudul$csv_terminated"; 
		return $kd;
        }
}
?>