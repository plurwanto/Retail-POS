<?php
class opnamebrg extends Controller
{
	function __construct()
	{
        parent::__construct();
		$this->load->library('globallib');
        $this->load->model('pop/opnamebrg_model');
    }

    function index()
	{
     	$id   = $this->input->post('stSearchingKey');
        $with = $this->input->post('searchby');
        $this->load->library('pagination');

        $config['full_tag_open']  = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';
        $config['cur_tag_open']   = '<span class="current">';
        $config['cur_tag_close']  = '</span>';
        $config['per_page']       = '14';
        $config['first_link'] 	  = 'First';
        $config['last_link'] 	  = 'Last';
        $config['num_links']  	  = 2;
            $row                      = $this->uri->segment(4);
            $pcode                    = $this->uri->segment(6);
            $pcodebarang              = $this->uri->segment(7);
		$config['base_url']       = base_url().'index.php/pop/opnamebrg/index/'.$row."/".$pcode."/".$pcodebarang."/";
		$page			  = $this->uri->segment(8);
		$config['uri_segment']    = 8;
		$flag1			  = "";
		if($with!=""){
	        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/opnamebrg/index/'.$row."/".$pcode."/".$pcodebarang."/".$with."/".$id."/";
				$page 			= $this->uri->segment(10);
				$config['uri_segment']  = 10;
			}
		 	else{
				$page ="";
			}
		}
		else{
			if($this->uri->segment(10)!=""){
				$with 			= $this->uri->segment(8);
			 	$id 			= $this->uri->segment(9);
			 	$config['base_url']     = base_url().'index.php/pop/opnamebrg/index/'.$row."/".$pcode."/".$pcodebarang."/".$with."/".$id."/";
				$page 			= $this->uri->segment(10);
				$config['uri_segment']  = 10;
			}
		}
		$tanggal = $this->session->userdata('Tanggal_Trans');
		$bulan = substr($tanggal,3,2);
		$tahun = substr($tanggal,6,4);
		$field = "QtyAkhir".$bulan;
        $config['total_rows']	= $this->opnamebrg_model->num_barang_row(addslashes($id),$with,$field,$tahun,$pcodebarang);
        $data['barangdata'] 	= $this->opnamebrg_model->getbarangList($config['per_page'],$page,addslashes($id),$with,$field,$tahun,$pcodebarang);
        $data['row_no']		= $row;
		$data['tahun'] = $tahun;
		$data['pcode'] = $pcode;
        $this->pagination->initialize($config);
        $this->load->view('pop/opnamebrg_list', $data);
    }
}
?>