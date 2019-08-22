<?php
class gudang extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/gudangmodel');   
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign = $mylib->getAllowList("all");
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
			$config['base_url']       = base_url().'index.php/master/gudang/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){		        
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/gudang/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/gudang/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows']     = $this->gudangmodel->num_gudang_row($id,$with);
	        $data['gudangdata']       = $this->gudangmodel->getGudangList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/gudang/viewgudanglist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
	     	$data['msg']  	 = "";
	     	$data['id']   	 = "";
	     	$data['nama']    = "";
	     	$data['panjang'] = "";
	     	$data['lebar'] 	 = "";
	     	$data['tinggi']  = "";
	    	$this->load->view('master/gudang/addgudang',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_gudang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				= $this->uri->segment(4);
	    	$data['viewgudang'] = $this->gudangmodel->getDetail($id);
	    	$data['edit'] 		= false;
	    	$this->load->view('master/gudang/vieweditgudang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_gudang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				= $this->uri->segment(4);
	    	$data['viewgudang'] = $this->gudangmodel->getDetail($id);
	    	$this->load->view('master/gudang/deletegudang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('gudang', array('KdGudang' => $id));
		redirect('/master/gudang/');
	}
    
    function edit_gudang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				= $this->uri->segment(4);
	    	$data['viewgudang'] = $this->gudangmodel->getDetail($id);
	    	$data['edit'] 		= true;
	    	$this->load->view('master/gudang/vieweditgudang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_gudang(){
    	$id   	= $this->input->post('kode');
    	$nama 	= strtoupper(trim($this->input->post('nama')));
    	$panjang= strtoupper(trim($this->input->post('panjang')));
    	$lebar 	= strtoupper(trim($this->input->post('lebar')));
    	$tinggi = strtoupper(trim($this->input->post('tinggi')));
    	$data = array(
    		  'Keterangan'	=> $nama,
    		  'Panjang' 	=> $panjang,
    		  'lebar' 		=> $lebar ,
              'Tinggi' 		=> $tinggi ,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('gudang', $data, array('KdGudang' => $id));
    	redirect('/master/gudang/');
    }
    function save_new_gudang(){
		$id 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	= strtoupper(trim($this->input->post('nama')));
    	$panjang= strtoupper(trim($this->input->post('panjang')));
    	$lebar 	= strtoupper(trim($this->input->post('lebar')));
    	$tinggi = strtoupper(trim($this->input->post('tinggi')));
    	$num 	= $this->gudangmodel->get_id($id);
    	if($num!=0){
			$data['id']   	= $this->input->post('kode');
			$data['nama'] 	= $this->input->post('nama');
			$data['panjang']= $this->input->post('panjang');
	     	$data['lebar'] 	= $this->input->post('lebar');
	     	$data['tinggi'] = $this->input->post('tinggi');
			$data['msg']  	= "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/gudang/addgudang', $data);
		}
		else{
		 	$data = array(
               'KdGudang'   => $id,
               'Keterangan' => $nama,
               'Panjang' 	=> $panjang,
               'lebar' 		=> $lebar,
               'Tinggi' 	=> $tinggi,
               'AddDate'    => date("Y-m-d")
            );
            $this->db->insert('gudang', $data);
			redirect('/master/gudang/');
		}
	}
}
?>