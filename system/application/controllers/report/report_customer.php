<?php
class report_customer extends Controller
{
    function __construct()
	{
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('report/report_CS');
    }
    
    function index()
	{
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
		
		if($sign == "Y")
		{	
			$data['aa']             = "checked";
			$data['bb']		= "";
			$data['cc']		= "";
			$data['dd']		= "";
			$data['display1']	= "display:none";
			$data['display2']	= "display:none";
			$data['display3']	= "display:none";
			$data['display4']	= "display:none";
			$data['pilihan']	= $this->input->post('pilihan');
			
			$pilihan= $this->input->post('pilihan');
			$no1	= $this->input->post('no1');
			$no2	= $this->input->post('no2');
			$tgl_1	= $this->input->post('tgl_1');
			$tgl_2	= $this->input->post('tgl_2');
			$kode1	= $this->input->post('kode1');
			$kode2	= $this->input->post('kode2');
			$jenis	= $this->input->post('jenis');
			$par	= $this->input->post('par');
                        $data['tgl_1']= $tgl_1;
                        $data['tgl_2']= $tgl_2;

			if(!empty($no1))
				{
					$NoStruk = "and NoStruk between '$no1' and '$no2'";
				}
			else{
					$NoStruk = "";
				}	
			
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
				
			if($jenis=="semua")
				{
					$kondisi = "";
				}	
			elseif($jenis=="tunai")
				{
					$kondisi = "and d.Jenis like '%T%'";
				}
			elseif($jenis=="kredit")
				{
					$kondisi = "and d.Jenis like '%K%'";
				}
			elseif($jenis=="debet")
				{
					$kondisi = "and d.Jenis like '%D%'";
				}	
			else{
					$kondisi = "and d.Jenis like '%V%'";
				}	
				
			if(!empty($pilihan))
				{
					if($pilihan=="detail")
					 	{
				 			$data['temp_so_last'] 	= "";	
							$data['temp_so1'] 	= "";
							$data['temp_so2'] 	= "";
							$data['temp_so3'] 	= "";
							$data['temp_so4'] 	= "";
							$data['temp_so5'] 	= "";
							$data['temp_so6'] 	= "";
							$data['temp_so7'] 	= "";
							$data['temp_so8'] 	= "";
							$data['temp_so9'] 	= "";
							$data['temp_so11'] 	= "";
							$data['total_netto'] 	= "";
							$data['aa']		= "checked";
							$data['bb']		= "";
							$data['cc']		= "";
							$data['dd']		= "";
							$data['display1']	= "";
							$data['display2']	= "display:none";
							$data['display3']	= "display:none";
							$data['display4']	= "display:none";
							$data['detail']		= $this->report_customer->detail($NoStruk,$Tgltrans,$kondisi);
						
							//$this->load->view('transaksi/report/viewdetail',$data);
						}
					elseif($pilihan=="transaksi")
						{
							$data['total_trans'] 	= "";
							$data['aa']				= "";
							$data['bb']				= "checked";
							$data['cc']				= "";
							$data['dd']				= "";
							$data['display1']		= "display:none";
							$data['display2']		= "";
							$data['display3']		= "display:none";
							$data['display4']		= "display:none";
		
							$data['transaksi']		= $this->report_customer->transaksi($NoStruk,$Tgltrans,$kondisi);
		
							//$this->load->view('transaksi/report/viewtransaksi',$data);
							
						}	
					elseif($pilihan=="barang")
						{
							$data['total_barang'] 	= "";
							$data['aa']				= "";
							$data['bb']				= "";
							$data['cc']				= "checked";
							$data['dd']				= "";
							$data['display1']		= "display:none";
							$data['display2']		= "display:none";
							$data['display3']		= "";
							$data['display4']		= "display:none";
		
							$data['barang']			= $this->report_customer->barang($NoStruk,$Tgltrans,$kondisi);
					
							//$this->load->view('transaksi/report/viewbarang',$data);
						}
					else{
							$data['tunai']		= "";
							$data['kredit']		= "";
							$data['debet']		= "";
							$data['voucher']	= "";
							$data['total_nilai']= "";	
							$data['aa']			= "";
							$data['bb']			= "";
							$data['cc']			= "";
							$data['dd']			= "checked";
							$data['display1']	= "display:none";
							$data['display2']	= "display:none";
							$data['display3']	= "display:none";
							$data['display4']	= "";
							$data['bayar']		= $this->report_customer->bayar($NoStruk,$Tgltrans,$kondisi);
						}
//                                                                                    print_r($data['barang']);die();

                                                
				}
			if(empty($par)){
                            $data['excel']="";
                        }else{
                            $data['excel']=$par;
                        }
                        $data['mCustomer'] = $this->report_CS->getCustomer();
			$this->load->view('report/retail_customer/view_retail',$data);

	    }
		else
			{
				$this->load->view('denied');
			}
    }
    
	
}
?>