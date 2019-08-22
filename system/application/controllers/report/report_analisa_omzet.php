<?php
class report_analisa_omzet extends Controller
{
    function __construct()
	{
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('report/report_omzet');
    }

    function index()
    {

        $mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
//print_r($_POST);die();
		if($sign == "Y")
		{
			$data['aa']             = "checked";
			$data['bb']		= "";
			$data['cc']		= "";
			$data['display1']	= "display:none";
			$data['display2']	= "display:none";
			$data['display3']	= "display:none";
			$data['display4']	= "display:none";
			$data['pilihan']	= $this->input->post('pilihan');

			$pilihan= $this->input->post('pilihan');
			$merek	= $this->input->post('Brand');
			$tgl_1	= $this->input->post('tgl_1');
			$tgl_2	= $this->input->post('tgl_2');
			$kode1	= $this->input->post('kode1');
			$kode2	= $this->input->post('kode2');
			$jenis	= $this->input->post('jenis');
			$par	= $this->input->post('par');
			

			if(!empty($tgl_1) and !empty($tgl_2))
				{
					$tgl1	= $mylib->ubah_tanggal($tgl_1);
					$tgl2	= $mylib->ubah_tanggal($tgl_2);
					$Tgltrans = "and Tanggal between '$tgl1' and '$tgl2'";
				}
			elseif(!empty($tgl_1) and empty($tgl_2))
				{
					$tgl1	= $mylib->ubah_tanggal($tgl_1);
					$Tgltrans = "and a.Tanggal>='$tgl1'";
				}
			elseif(empty($tgl_1) and !empty($tgl_2))
				{
					$tgl2	= $mylib->ubah_tanggal($tgl_2);
					$Tgltrans = "and a.Tanggal <='$tgl2'";
				}
			else{
					$Tgltrans = "";
				}

			
			if(!empty($merek))
                        {
							
							$data['total_netto'] 	= "";
							$data['aa']		= "checked";
							$data['merek']		= $merek;
							$data['cc']		= "";
							$data['dd']		= "";
							$data['display1']	= "";
							$data['detail']		= $this->report_omzet->detail($Tgltrans,$merek);
                                                        $data['tgl_1']= $tgl_1;
                                                        $data['tgl_2']= $tgl_2;

							//$this->load->view('transaksi/report/viewdetail',$data);
                        }else{
							$data['total_barang'] 	= "";
							$data['merek']				= "";
							$data['bb']				= "";
							$data['cc']				= "checked";
							$data['dd']				= "";
							$data['display1']		= "display:none";

//							$data['barang']			= $this->report_omzet->barang($NoStruk,$Tgltrans,$kondisi);

							//$this->load->view('transaksi/report/viewbarang',$data);
                        }

			if(empty($par)){
                            $data['excel']="";
                        }else{
                            $data['excel']=$par;
                        }
                        $data['mbrand'] = $this->report_omzet->getBrand();
			$this->load->view('report/omzet/view_omzet',$data);

	    }
		else
			{
				$this->load->view('denied');
			}
    }
}
?>