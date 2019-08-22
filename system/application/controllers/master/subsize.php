<?php
class subsize extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/subsizemodel');   
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
		 	$segs 		  = $this->uri->segment_array();
  		    $arr 		  = "index.php/".$segs[1]."/".$segs[2]."/";
		 	$data['link'] = $mylib->restrictLink($arr);
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
	        $config['num_links']  	  = 2;        
			$config['base_url']       = base_url().'index.php/master/subsize/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/subsize/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/subsize/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->subsizemodel->num_subsize_row($id,$with);
	        $data['subsizedata']  = $this->subsizemodel->getSubsizeList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/subsize/viewsubsizelist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
	     	$data['msg']	  = "";
	     	$data['id']	 	  = "";
	     	$data['nama']	  = "";
	     	$data['realsize'] = "";
	     	$data['master']   = $this->subsizemodel->getMaster();
	     	$data['master1']  = "";
	    	$this->load->view('master/subsize/addsubsize',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_subsize($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewsubsize'] = $this->subsizemodel->getDetail($id);
	    	$data['master'] 	 = $this->subsizemodel->getMaster();
	    	$data['edit'] 		 = false;
	    	$this->load->view('master/subsize/vieweditsubsize', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_subsize($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewsubsize'] = $this->subsizemodel->getDetail($id);
	    	$this->load->view('master/subsize/deletesubsize', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('subsize', array('KdSubSize' => $id));
		redirect('/master/subsize/');
	}
    
    function edit_subsize($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				 = $this->uri->segment(4);
	    	$data['viewsubsize'] = $this->subsizemodel->getDetail($id);
	    	$data['master'] 	 = $this->subsizemodel->getMaster();
	    	$data['edit'] 		 = true;
	    	$this->load->view('master/subsize/vieweditsubsize', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_subsize(){
    	$id 	  = $this->input->post('kode');
    	$nama 	  = strtoupper(trim($this->input->post('nama')));
    	$realsize = strtoupper(trim($this->input->post('realsize')));
    	$master1  = strtoupper(trim($this->input->post('master')));
    	$data = array(
    		  'Ukuran'		=> $nama,
    		  'NumericSize'	=> $realsize,
    		  'KdSize'		=> $master1,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('subsize', $data, array('KdSubSize' => $id));
    	redirect('/master/subsize/');
    }
    function save_new_subsize(){
		$id 	  = strtoupper(trim($this->input->post('kode')));
    	$nama 	  = strtoupper(trim($this->input->post('nama')));
    	$realsize = strtoupper(trim($this->input->post('realsize')));
    	$master1  = strtoupper(trim($this->input->post('master')));
    	$num 	  = $this->subsizemodel->get_id($id);   	
    	if($num == 0)
		{
		 	$data = array(
               'KdSubSize'		=> $id ,
               'NumericSize'	=> $realsize,
               'Ukuran' 		=> $nama ,
               'KdSize' 		=> $master1 ,
               'AddDate' 		=> date("Y-m-d")
            );
            
            $this->db->insert('subsize', $data); 
			redirect('master/subsize');
		}
		else
		{
			$data['id'] 	  = $this->input->post('kode');
			$data['nama'] 	  = $this->input->post('nama');
			$data['master']   = $this->subsizemodel->getMaster();
			$data['master1']  = $this->input->post('master');
			$data['realsize'] = $this->input->post('realsize');
			$data['msg'] 	  = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";			
			$this->load->view('master/subsize/addsubsize', $data);
		}
	}
}
?>