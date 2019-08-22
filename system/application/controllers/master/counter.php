<?php
class counter extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/countermodel');   
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
			$config['base_url']       = base_url().'index.php/master/counter/index/';
			$page					  = $this->uri->segment(4);		
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/counter/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			 	else{
					$page ="";
				}
			}
			else{
				if($this->uri->segment(5)!=""){
					$with 			= $this->uri->segment(4);
				 	$id 			= $this->uri->segment(5);
				 	$config['base_url']     = base_url().'index.php/master/counter/index/'.$with."/".$id."/";
					$page 			= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
			
	        $config['total_rows']     = $this->countermodel->num_counter_row($id,$with);
	        $data['counterdata']  = $this->countermodel->getcounterList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/counter/viewcounterlist', $data);
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
	    	$this->load->view('master/counter/addcounter',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_counter($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id                         = $this->uri->segment(4);
	    	$data['viewcounter']    = $this->countermodel->getDetail($id);
	    	$data['edit']               = false;
	    	$this->load->view('master/counter/vieweditcounter', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_counter($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 					 = $this->uri->segment(4);
	    	$data['viewcounter'] = $this->countermodel->getDetail($id);
	    	$this->load->view('master/counter/deletecounter', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('counter', array('KdCounter' => $id));
		redirect('/master/counter/');
	}
    
    function edit_counter($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 					 = $this->uri->segment(4);
	    	$data['viewcounter'] = $this->countermodel->getDetail($id);
	    	$data['edit'] 			 = true;
	    	$this->load->view('master/counter/vieweditcounter', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_counter(){
    	$id   = $this->input->post('kode');
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$data = array(
    		  'Keterangan'	=> $nama,
                  'EditDate'	=> date("Y-m-d H:i:s")
			);
		$this->db->update('counter', $data, array('KdCounter' => $id));
    	redirect('/master/counter/');
    }
    function save_new_counter(){
		$id   = strtoupper(trim($this->input->post('kode')));
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$num  = $this->countermodel->get_id($id);
    	if($num!=0){
			$data['id']   = $this->input->post('kode');
			$data['nama'] = $this->input->post('nama');
			$data['msg']  = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/counter/addcounter', $data);
		}
		else{
		 	$data = array(
               'KdCounter'   => $id ,
               'Keterangan' => $nama ,
               'AddDate'    => date("Y-m-d H:i:s")
            );
            $this->db->insert('counter', $data);
			redirect('/master/counter/');
		}
	}
}
?>