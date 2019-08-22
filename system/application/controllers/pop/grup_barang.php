<?php
class grup_barang extends Controller
{
	function __construct()
	{
        parent::Controller();
		$this->load->library('globallib');
        $this->load->model('pop/grup_barangmodel');   
    }
	
    function index($module = '')
	{
     	$id   = $this->input->post('stSearchingKey');
        $with = $this->input->post('searchby');
        $this->load->library('pagination');
		
        $config['full_tag_open']  = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';
        $config['cur_tag_open']   = '<span class="current">';
        $config['cur_tag_close']  = '</span>';
        $config['per_page']       = '13';
        $config['first_link'] 	  = 'First';
        $config['last_link'] 	  = 'Last';
        $config['num_links']  	  = 2;
        $row					  = $this->uri->segment(5);
		$config['base_url']       = base_url().'index.php/pop/grup_barang/index/'.$module.'/'.$row."/";
		$page					  = $this->uri->segment(6);		
		$config['uri_segment']    = 6;
		$with 					  = $this->input->post('searchby');
		$id   					  = "";
		$flag1					  = "";
		if($with!=""){
		 	$id    = $this->input->post('stSearchingKey');
	        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/grup_barang/index/'.$module.'/'.$row."/".$with."/".$id."/";
				$page 					= $this->uri->segment(8);
				$config['uri_segment']  = 8;
			}
		 	else{
				$page ="";
			}
		}
		else{
			if($this->uri->segment(7)!=""){
				$with 					= $this->uri->segment(6);
			 	$id 					= $this->uri->segment(7);
			 	$config['base_url']     = base_url().'index.php/pop/grup_barang/index/'.$module.'/'.$row."/".$with."/".$id."/";
				$page 					= $this->uri->segment(8);
				$config['uri_segment']  = 8;
			}
		}
		
        $config['total_rows']    = $this->grup_barangmodel->num_grup_barang_row($id,$with);
        $data['grup_barangdata'] = $this->grup_barangmodel->getgrup_barangList($config['per_page'],$page,$id,$with);
        $data['row_no']			 = $row;
		$data['module']          = $module;
        $this->pagination->initialize($config);
		
        $this->load->view('pop/grup_baranglist', $data);	    
    }   
}
?>