<?php
class subarea extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/subareamodel');   
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
		 	$segs 		  = $this->uri->segment_array();
  		    $arr  		  = "index.php/".$segs[1]."/".$segs[2]."/";
		 	$data['link'] = $mylib->restrictLink($arr);
	     	$id 		  = $this->input->post('stSearchingKey');
	        $with		  = $this->input->post('searchby');
	     	$this->load->library('pagination');

	        $config['full_tag_open']  = '<div class="pagination">';
	        $config['full_tag_close'] = '</div>';
	        $config['cur_tag_open']   = '<span class="current">';
	        $config['cur_tag_close']  = '</span>';
	        $config['per_page']       = '13';
	        $config['first_link'] 	  = 'First';
	        $config['last_link'] 	  = 'Last';
	        $config['num_links']  	  = 2;        
			$config['base_url']       = base_url().'index.php/master/subarea/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/subarea/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/subarea/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows']     = $this->subareamodel->num_subarea_row($id,$with);
	        $data['subareadata']    = $this->subareamodel->getSubareaList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/subarea/viewsubarealist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
     		$data['msg']	 = "";
	     	$data['id']		 = "";
	     	$data['nama']	 = "";
	     	$data['master']  = $this->subareamodel->getMaster();
	     	$data['master1'] = "";
	    	$this->load->view('master/subarea/addsubarea',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_subarea($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 					= $this->uri->segment(4);
	    	$data['viewsubarea']  = $this->subareamodel->getDetail($id);
	    	$data['master'] 		= $this->subareamodel->getMaster();
	    	$data['edit'] 			= false;
	    	$this->load->view('master/subarea/vieweditsubarea', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_subarea($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 					= $this->uri->segment(4);
	    	$data['viewsubarea']  = $this->subareamodel->getDetail($id);
	    	$this->load->view('master/subarea/deletesubarea', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('subarea', array('KdSubArea' => $id));
		redirect('/master/subarea/');	
	}
    
    function edit_subarea($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 					= $this->uri->segment(4);
	    	$data['viewsubarea'] 	= $this->subareamodel->getDetail($id);
	    	$data['master'] 		= $this->subareamodel->getMaster();
	    	$data['edit'] 			= true;
	    	$this->load->view('master/subarea/vieweditsubarea', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_subarea(){
    	$id 	 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$data = array(
    		  'Keterangan'	=> $nama,
    		  'KdArea'		=> $master1,
              'EditDate'		=> date("Y-m-d")
			);
		$this->db->update('subarea', $data, array('KdSubArea' => $id));
    	redirect('/master/subarea/');
    }
    function save_new_subarea(){
		$id 	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$num 	 = $this->subareamodel->get_id($id);   	
    	if($num == 0)
		{
		 	$data = array(
               'KdSubArea' 	=> $id ,
               'Keterangan' 	=> $nama ,
               'KdArea' 		=> $master1 ,
               'AddDate'		=> date("Y-m-d")
            );

            $this->db->insert('subarea', $data); 
			redirect('master/subarea');
		}
		else
		{
			$data['id'] 	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['master']  = $this->subareamodel->getMaster();
			$data['master1'] = $this->input->post('master');
			$data['msg'] 	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";			
			$this->load->view('master/subarea/addsubarea', $data);
		}
	}
}
?>