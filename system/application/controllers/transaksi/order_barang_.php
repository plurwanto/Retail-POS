<?php
class order_barang extends Controller
{
    function __construct()
	{
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('transaksi/order_barang_model');   
    }
    
    function index()
	{
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
		
		if($sign == "Y")
		{	
//			$data['line'] 		= "";
			$data['temp_so_last'] 	= "";	
			$data['temp_so1'] 	= "";
			$data['temp_so2'] 	= "";
			$data['temp_so3'] 	= "";
			$data['temp_so4'] 	= "";
			$data['temp_so5'] 	= "";
			$data['temp_so6'] 	= "";
			$data['total_netto'] 	= "";
			$data['total_trans'] 	= "";
			$data['total_barang'] 	= "";
			
			$data['detail']			= $this->order_barang_model->detail();
			$data['transaksi']		= $this->order_barang_model->transaksi();
			$data['barang']		= $this->order_barang_model->barang();
					
		 	$this->load->view('transaksi/order_barang/vieworder',$data);
	    }
		else
		{
			$this->load->view('denied');
		}
    }
    
    function get_data()
    {
    	$pilihan= $yhis->input->post('pilihan');
		$no1	= $this->input->post('no1');
		$no2	= $this->input->post('no2');
		$tgl_1	= $this->input->post('tgl_1');
		$tgl_2	= $this->input->post('tgl_2');
		$kode1	= $this->input->post('kode1');
		$kode2	= $this->input->post('kode2');
		
		if(!empty($no1))
			{
				$NoStruk = "and NoStruk>='$no1' and NoStruk<='$no2'";
			}
		else{
				$NoStruk = "";
			}	
		
		if(!empty($tgl_1) and !empty($tgl_2))
			{
				$Tgltrans = "and Tanggal>='$tgl_1' and Tanggal <='$tgl_2'";	
			}	
		elseif(!empty($tgl_1) and empty($tgl_2))
			{
				$Tgltrans = "and Tanggal>='$tgl_1'";
			}
		elseif(empty($tgl_1) and !empty($tgl_2))
			{
				$Tgltrans = "and Tanggal <='$tgl_2'";	
			}
		else{
				$Tgltrans = "";	
			}
			
		if(!empty($kode1))
			{
				$kdbrg = "and PCode>='$kode1' and PCode<='$kode2'";
			}
		else{
				$kdbrg = "";
			}			
			
		if(!empty($pilihan))
			{
				if($pilihan=="detail")
				 	{
						$this->order_barang_model->detail($NoStruk,$Tgltrans,$kdbrg);
					}
				elseif($pilihan=="transaksi")
					{
						$this->order_barang_model->transaksi($NoStruk,$Tgltrans);
					}	
				else{
						$this->order_barang_model->barang($NoStruk,$Tgltrans,$kdbrg);
					}	
			}	
		
		
	
	}
    
	
}
?>