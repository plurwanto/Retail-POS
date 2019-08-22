<?php
class brgretur extends Controller
{
	function __construct()
	{
        parent::__construct();
		$this->load->library('globallib');
        $this->load->model('pop/barangmodel');
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
		$row					  = $this->uri->segment(4);
		$config['base_url']       = base_url().'index.php/pop/brgterima/index/'.$row."/";
		$page					  = $this->uri->segment(5);
		$config['uri_segment']    = 5;
		$flag1					  = "";
		if($with!=""){
	        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/brgterima/index/'.$row."/".$with."/".$id."/";
				$page 					= $this->uri->segment(7);
				$config['uri_segment']  = 7;
			}
		 	else{
				$page ="";
			}
		}
		else{
			if($this->uri->segment(6)!=""){
				$with 					= $this->uri->segment(5);
			 	$id 					= $this->uri->segment(6);
			 	$config['base_url']     = base_url().'index.php/pop/brgterima/index/'.$row."/".$with."/".$id."/";
				$page 					= $this->uri->segment(7);
				$config['uri_segment']  = 7;
			}
		}

        $config['total_rows']	= $this->barangmodel->num_barang_row(addslashes($id),$with);
        $data['barangdata'] 	= $this->barangmodel->getbarangList($config['per_page'],$page,addslashes($id),$with);
        $data['row_no']			= $row;
        $this->pagination->initialize($config);

        $this->load->view('pop/baranglist', $data);
    }
}
?>