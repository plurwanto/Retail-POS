<?php
class grup_barang extends Controller
{
	function __construct()
	{
            parent::Controller();
		$this->load->library('globallib');
                $this->load->library('pagination');
                $this->load->model('pop/grup_barangmodel');
    }
	
    function index()
	{
     	$id   = $this->input->post('stSearchingKey');
        $with = $this->input->post('searchby');
		
//        $config['full_tag_open']  = '<div class="pagination">';
//        $config['full_tag_close'] = '</div>';
//        $config['cur_tag_open']   = '<span class="current">';
//        $config['cur_tag_close']  = '</span>';
        $config['base_url']       = base_url().'index.php/pop/grup_barang/index';
        $config['per_page']       = 5;
        $config['num_links']  	  = 3;
        $config['first_link'] 	  = 'First';
        $config['last_link'] 	  = 'Last';
//        $row					  = $this->uri->segment(5);
		$page					  = $this->uri->segment(4);
//		$config['uri_segment']    = 5;
		$with 					  = $this->input->post('searchby');
		$id   					  = "";
		$flag1					  = "";
		if($with!=""){
		 	$id    = $this->input->post('stSearchingKey');
	        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/grup_barang/index/'.$page."/".$with."/".$id."/";
				$page 					= $this->uri->segment(8);
				$config['uri_segment']  = 7;
			}
		 	else{
				$page ="";
			}
		}
		else{
			if($this->uri->segment(6)!=""){
				$with 					= $this->uri->segment(6);
			 	$id 					= $this->uri->segment(7);
			 	$config['base_url']     = base_url().'index.php/pop/grup_barang/index/'.$page."/".$with."/".$id."/";
				$page 					= $this->uri->segment(8);
				$config['uri_segment']  = 7;
			}
		}
		
        $config['total_rows']    = $this->grup_barangmodel->num_grup_barang_row($id,$with);
        $data['grup_barangdata'] = $this->grup_barangmodel->getgrup_barangList($config['per_page'],$page,$id,$with);
        $this->pagination->initialize($config);

        $data['row_no']			 = $page;
		$data['module']          = 'sales';
		
        $this->load->view('pop/grup_baranglist', $data);	    
    }   
}
?>