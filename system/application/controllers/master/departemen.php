<?php
class departemen extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/departemenmodel');
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign = $mylib->getAllowList("all");
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
	        $config['base_url']       = base_url().'index.php/master/departemen/index/';
	        $config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/departemen/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/departemen/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows']     = $this->departemenmodel->num_departemen_row($id,$with); 
	        $data['departemendata']   = $this->departemenmodel->getDepartemenList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/departemen/viewdepartemenlist', $data);
        }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
	     	$data['msg']  = "";
	     	$data['id']   = "";
	     	$data['nama'] = "";
	    	$this->load->view('master/departemen/adddepartemen',$data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function view_departemen($id){
    	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
	     	$id 				    = $this->uri->segment(4);
	    	$data['viewdepartemen'] = $this->departemenmodel->getDetail($id);
	    	$data['edit'] 			= false;
	    	$this->load->view('master/departemen/vieweditdepartemen', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_departemen($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
	     	$id 					= $this->uri->segment(4);
	    	$data['viewdepartemen'] = $this->departemenmodel->getDetail($id);
	    	$this->load->view('master/departemen/deletedepartemen', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('departemen', array('KdDepartemen' => $id));
		redirect('/master/departemen/');
	}
    
    function edit_departemen($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 					= $this->uri->segment(4);
	    	$data['viewdepartemen'] = $this->departemenmodel->getDetail($id);
	    	$data['edit'] 			= true;
	    	$this->load->view('master/departemen/vieweditdepartemen', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function save_departemen(){
    	$id   = $this->input->post('kode');
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$data = array(
    		  'Keterangan'	=> $nama,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('departemen', $data, array('KdDepartemen' => $id));
    	redirect('/master/departemen/');
    }
    function save_new_departemen(){
		$id   = strtoupper(trim($this->input->post('kode')));
    	$nama = strtoupper(trim($this->input->post('nama')));
    	$num  = $this->departemenmodel->get_id($id);
    	if($num!=0){
			$data['id']   = $this->input->post('kode');
			$data['nama'] = $this->input->post('nama');
			$data['msg']  = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/departemen/adddepartemen', $data);
		}
		else{
		 	$data = array(
               'KdDepartemen' => $id ,
               'Keterangan'   => $nama ,
               'AddDate'      => date("Y-m-d")
            );
			$this->db->insert('departemen', $data);
			redirect('/master/departemen/');
		}
	}
}
?>