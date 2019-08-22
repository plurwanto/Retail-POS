<?php
class kemasan extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/kemasanmodel');   
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
	        $config['num_links']  	  = 2;        
			$config['base_url']       = base_url().'index.php/master/kemasan/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/kemasan/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/kemasan/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->kemasanmodel->num_kemasan_row($id,$with);
	        $data['kemasandata']  = $this->kemasanmodel->getkemasanList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/kemasan/viewkemasanlist', $data);
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
	    	$this->load->view('master/kemasan/addkemasan',$data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function view_kemasan($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewkemasan'] = $this->kemasanmodel->getDetail($id);
	    	$data['edit'] 		 = false;
	    	$this->load->view('master/kemasan/vieweditkemasan', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_kemasan($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewkemasan'] = $this->kemasanmodel->getDetail($id);
	    	$this->load->view('master/kemasan/deletekemasan', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('kemasan', array('KdKemasan' => $id));
		redirect('/master/kemasan/');
	}
    
    function edit_kemasan($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				 = $this->uri->segment(4);
	    	$data['viewkemasan'] = $this->kemasanmodel->getDetail($id);
	    	$data['edit'] 		 = true;
	    	$this->load->view('master/kemasan/vieweditkemasan', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function save_kemasan(){
    	$id   = $this->input->post('kode');
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$data = array(
    		  'NamaKemasan'	=> $nama,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('kemasan', $data, array('KdKemasan' => $id));
    	redirect('/master/kemasan/');
    }
    function save_new_kemasan(){
		$id   = strtoupper(trim($this->input->post('kode')));
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$num  = $this->kemasanmodel->get_id($id);
    	if($num!=0){
			$data['id']   = $this->input->post('kode');
			$data['nama'] = $this->input->post('nama');
			$data['msg']  = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/kemasan/addkemasan', $data);
		}
		else{
		 	$data = array(
               'KdKemasan' 	 => $id ,
               'NamaKemasan' => $nama ,
               'AddDate'     => date("Y-m-d")
            );
            $this->db->insert('kemasan', $data);
			redirect('/master/kemasan/');
		}
	}
}
?>