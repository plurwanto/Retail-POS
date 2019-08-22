<?php
class subkategori extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/subkategorimodel');   
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
		 	$segs 			= $this->uri->segment_array();
  		    $arr 			= "index.php/".$segs[1]."/".$segs[2]."/";
		 	$data['link'] 	= $mylib->restrictLink($arr);
	     	$id 			= $this->input->post('stSearchingKey');
	        $with 			= $this->input->post('searchby');
	        $this->load->library('pagination');

	        $config['full_tag_open']  = '<div class="pagination">';
	        $config['full_tag_close'] = '</div>';
	        $config['cur_tag_open']   = '<span class="current">';
	        $config['cur_tag_close']  = '</span>';
	        $config['per_page']       = '13';
	        $config['first_link'] 	  = 'First';
	        $config['last_link'] 	  = 'Last';
	        $config['num_links']  	  = 2;        
			$config['base_url']       = base_url().'index.php/master/subkategori/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/subkategori/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/subkategori/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows']     = $this->subkategorimodel->num_subkategori_row($id,$with);
	        $data['subkategoridata']  = $this->subkategorimodel->getSubKategoriList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/subkategori/viewsubkategorilist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
	     	$data['msg']	= "";
	     	$data['id']		= "";
	     	$data['nama']	= "";
	     	$data['master'] = $this->subkategorimodel->getMaster();
	     	$data['master1']= "";
	    	$this->load->view('master/subkategori/addsubkategori',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_subkategori($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 					 = $this->uri->segment(4);
	    	$data['viewsubkategori'] = $this->subkategorimodel->getDetail($id);
	    	$data['master'] 		 = $this->subkategorimodel->getMaster();
	    	$data['edit'] 			 = false;
	    	$this->load->view('master/subkategori/vieweditsubkategori', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_subkategori($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 					 = $this->uri->segment(4);
	    	$data['viewsubkategori'] = $this->subkategorimodel->getDetail($id);
	    	$this->load->view('master/subkategori/deletesubkategori', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id = $this->input->post('kode');
	     	$this->db->delete('subkategori', array('KdSubKategori' => $id));
			redirect('/master/subkategori/');
		}
		else{
			$this->load->view('denied');
		}
	}
    
    function edit_subkategori($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 					 = $this->uri->segment(4);
	    	$data['viewsubkategori'] = $this->subkategorimodel->getDetail($id);
	    	$data['master'] 		 = $this->subkategorimodel->getMaster();
	    	$data['edit'] 			 = true;
	    	$this->load->view('master/subkategori/vieweditsubkategori', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_subkategori(){
    	$id 	 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$data = array(
    		  'NamaSubKategori'	=> $nama,
    		  'KdKategori'		=> $master1,
              'EditDate'		=> date("Y-m-d")
			);
		$this->db->update('subkategori', $data, array('KdSubKategori' => $id));
    	redirect('/master/subkategori/');
    }
    function save_new_subkategori(){
		$id 	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$num	 = $this->subkategorimodel->get_id($id);   	
    	if($num == 0)
		{
		 	$data = array(
               'KdSubKategori' 	 => $id ,
               'NamaSubKategori' => $nama ,
               'KdKategori' 	 => $master1 ,
               'AddDate' 		 => date("Y-m-d")
            );
            
            $this->db->insert('subkategori', $data); 
			redirect('master/subkategori');
		}
		else
		{
			$data['id'] 	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['master']  = $this->subkategorimodel->getMaster();
			$data['master1'] = $this->input->post('master');
			$data['msg'] 	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";			
			$this->load->view('master/subkategori/addsubkategori', $data);
		}
	}
}
?>