<?php
class bank extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/bankmodel');        
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
		 	$segs 		  = $this->uri->segment_array();
  		    $arr 		  = "index.php/".$segs[1]."/".$segs[2]."/";
		 	$data['link'] = $sign = $mylib->restrictLink($arr);
	     	$id 		  = $this->input->post('stSearchingKey');
	        $with 		  = $this->input->post('searchby');
	        $this->load->library('pagination');
	        
	        $config['full_tag_open']  = '<div class="pagination">';
	        $config['full_tag_close'] = '</div>';
	        $config['cur_tag_open']   = '<span class="current">';
	        $config['cur_tag_close']  = '</span>';
	        $config['per_page']       = '13';
	        $config['first_link'] 	  = 'First';
	        $config['last_link'] 	  = 'Last';
	        $config['num_links'] 	  = 2;
	        $page					  = $this->uri->segment(4);
	        $config['base_url']       = base_url().'index.php/master/bank/index/';
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
		
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/bank/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			 	else{
					$page ="";
				}
			}
			else{
				if($this->uri->segment(5)!=""){
					$with 					= $this->uri->segment(4);
				 	$id 					= $this->uri->segment(5);
				 	$config['base_url']     = base_url().'index.php/master/bank/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        
	        $config['total_rows'] = $this->bankmodel->num_bank_row($id,$with); 
	        $data['bankdata']     = $this->bankmodel->getBankList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/bank/viewbanklist', $data);
		}
		else{
			$this->load->view('denied');
		}     	
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
			$data['msg']  = "";
	     	$data['id']   = "";
    	 	$data['nama'] = "";
    		$this->load->view('master/bank/add',$data);
		}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_bank($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 			  = $this->uri->segment(4);
	    	$data['viewbank'] = $this->bankmodel->getDetail($id);
	    	$data['edit'] 	  = false;
	    	$this->load->view('master/bank/viewedit', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_bank($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 			  = $this->uri->segment(4);
	    	$data['viewbank'] = $this->bankmodel->getDetail($id);
	    	$this->load->view('master/bank/delete', $data);	    
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('bank', array('KdBank' => $id));
		redirect('/master/bank/');
	}
    
    function edit_bank($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 			  = $this->uri->segment(4);
	    	$data['viewbank'] = $this->bankmodel->getDetail($id);
	    	$data['edit']  	  = true;
	    	$this->load->view('master/bank/viewedit', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_bank(){
    	$id   = $this->input->post('kode');
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$data = array(
    		  'NamaBank'	=> $nama,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('bank', $data, array('KdBank' => $id));
    	redirect('/master/bank/');
    }
    function save_new_bank(){
		$id   = strtoupper(trim($this->input->post('kode')));
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$num  = $this->bankmodel->get_id($id);
    	if($num!=0){
			$data['id']   = $this->input->post('kode');
			$data['nama'] = $this->input->post('nama');
			$data['msg']  = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/bank/add', $data);
		}
		else{
		 	$data = array(
               'KdBank'   => $id ,
               'NamaBank' => $nama ,
               'AddDate'  => date("Y-m-d")
            );
			$this->db->insert('bank', $data);
			redirect('/master/bank/');
		}
	}	
}
?>