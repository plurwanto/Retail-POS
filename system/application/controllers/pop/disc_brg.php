<?php
class disc_brg extends Controller {
    
    function __construct(){
        parent::Controller();
		$this->load->library('globallib');        
        $this->load->model('pop/disc_brgmodel');   
    }    
    function index(){
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
        $row					  = $this->uri->segment(4);
		$config['base_url']       = base_url().'index.php/pop/disc_brg/index/'.$row."/";
		$page					  = $this->uri->segment(5);		
		$config['uri_segment']    = 5;
		$with 					  = $this->input->post('searchby');
		$id   					  = "";
		$flag1					  = "";
		if($with!=""){
		 	$id    = $this->input->post('stSearchingKey');
	        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/disc_brg/index/'.$row."/".$with."/".$id."/";
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
			 	$config['base_url']     = base_url().'index.php/pop/disc_brg/index/'.$row."/".$with."/".$id."/";
				$page 					= $this->uri->segment(7);
				$config['uri_segment']  = 7;
			}
		}
		
        $config['total_rows'] = $this->disc_brgmodel->num_disc_brg_row($id,$with);
        $data['disc_brgdata'] = $this->disc_brgmodel->getdisc_brgList($config['per_page'],$page,$id,$with);
        $data['row_no']		  = $row;
        $this->pagination->initialize($config);
        $this->load->view('pop/disc_brglist', $data);	    
    }   
}
?>