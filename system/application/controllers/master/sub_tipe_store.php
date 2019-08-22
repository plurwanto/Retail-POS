<?php
class sub_tipe_store extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/sub_tipe_storemodel');   
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
			$config['base_url']       = base_url().'index.php/master/sub_tipe_store/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/sub_tipe_store/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/sub_tipe_store/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows']     	= $this->sub_tipe_storemodel->num_sub_tipe_store_row($id,$with);
	        $data['sub_tipe_storedata'] = $this->sub_tipe_storemodel->getsub_tipe_storeList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/sub_tipe_store/viewsub_tipe_storelist', $data);
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
	     	$data['master']  = $this->sub_tipe_storemodel->getMaster();
	     	$data['master1'] = "";
	     	$data['mgrup']   = $this->sub_tipe_storemodel->getGrupHarga();
	     	$data['grup']    = "";
	    	$this->load->view('master/sub_tipe_store/addsub_tipe_store',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_sub_tipe_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 						= $this->uri->segment(4);
	    	$data['viewsub_tipe_store'] = $this->sub_tipe_storemodel->getDetail($id);
	    	$data['master'] 			= $this->sub_tipe_storemodel->getMaster();
	    	$data['mgrup']   			= $this->sub_tipe_storemodel->getGrupHarga();
	    	$data['edit'] 				= false;
	    	$this->load->view('master/sub_tipe_store/vieweditsub_tipe_store', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_sub_tipe_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 						= $this->uri->segment(4);
	    	$data['viewsub_tipe_store'] = $this->sub_tipe_storemodel->getDetail($id);
	    	$this->load->view('master/sub_tipe_store/deletesub_tipe_store', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('sub_tipe_store', array('KdSubTipeStore' => $id));
		redirect('/master/sub_tipe_store/');		
	}
    
    function edit_sub_tipe_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 						= $this->uri->segment(4);
	    	$data['viewsub_tipe_store'] = $this->sub_tipe_storemodel->getDetail($id);
	    	$data['master'] 			= $this->sub_tipe_storemodel->getMaster();
	    	$data['mgrup']   			= $this->sub_tipe_storemodel->getGrupHarga();
	    	$data['edit'] 				= true;
	    	$this->load->view('master/sub_tipe_store/vieweditsub_tipe_store', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_sub_tipe_store(){
    	$id 	 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$grup	 = $this->input->post('grup');
    	$data = array(
    		  'Keterangan'		=> $nama,
    		  'KdTipeStore'		=> $master1,
    		  'KdGrupHarga'	=> $grup,
              'EditDate'		=> date("Y-m-d")
			);
		$this->db->update('sub_tipe_store', $data, array('KdSubTipeStore' => $id));
    	redirect('/master/sub_tipe_store/');
    }
    function save_new_sub_tipe_store(){
		$id 	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$master1 = strtoupper(trim($this->input->post('master')));
    	$grup	 = $this->input->post('grup');
    	$num 	 = $this->sub_tipe_storemodel->get_id($id);   	
    	if($num == 0)
		{
		 	$data = array(
               'KdSubTipeStore' => $id ,
               'Keterangan' 	=> $nama ,
               'KdTipeStore' 	=> $master1 ,
               'KdGrupHarga'	=> $grup,
               'AddDate'		=> date("Y-m-d")
            );

            $this->db->insert('sub_tipe_store', $data); 
			redirect('master/sub_tipe_store');
		}
		else
		{
			$data['id'] 	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['master']  = $this->sub_tipe_storemodel->getMaster();
			$data['master1'] = $this->input->post('master');
			$data['mgrup']	 = $this->sub_tipe_storemodel->getGrupHarga();
			$data['grup']	 = $grup;
			$data['msg'] 	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";			
			$this->load->view('master/sub_tipe_store/addsub_tipe_store', $data);
		}
	}
}
?>