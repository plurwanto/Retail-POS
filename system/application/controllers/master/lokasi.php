<?php
class lokasi extends Controller {

    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/lokasimodel');
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
			$config['base_url']       = base_url().'index.php/master/lokasi/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/lokasi/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/lokasi/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->lokasimodel->num_lokasi_row($id,$with);
	        $data['lokasidata']   = $this->lokasimodel->getlokasiList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Lokasi","Keterangan","Tingkat","Parent","Luas","Tipe Lokasi");
	        $this->pagination->initialize($config);
	        $this->load->view('master/lokasi/viewlokasilist', $data);
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
	     	$data['tipe']    = $this->lokasimodel->getMaster();
	     	$data['tipenil'] = "";
	     	$data['tingkat'] = "";
	     	$data['panjang'] = "";
	     	$data['lebar']   = "";
	     	$data['tinggi']  = "";
	     	$data['parent']  = $this->lokasimodel->getParent();
	     	$data['vparent'] = "";
	    	$this->load->view('master/lokasi/addlokasi',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function view_lokasi($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				= $this->uri->segment(4);
	    	$data['viewlokasi'] = $this->lokasimodel->getDetail($id);
			$data['tipe']  	 	= $this->lokasimodel->getMaster();
	     	$data['parent']  	= $this->lokasimodel->getParent();
	    	$data['edit'] 		= false;
	    	$this->load->view('master/lokasi/vieweditlokasi', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_lokasi($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				= $this->uri->segment(4);
	    	$data['viewlokasi'] = $this->lokasimodel->getDetail($id);
	    	$this->load->view('master/lokasi/deletelokasi', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('lokasi', array('KdLokasi' => $id));
		redirect('/master/lokasi/');
	}

    function edit_lokasi($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				= $this->uri->segment(4);
	    	$data['viewlokasi'] = $this->lokasimodel->getDetail($id);
	    	$data['tipe']  	 	= $this->lokasimodel->getMaster();
	     	$data['parent']  	= $this->lokasimodel->getParent();
	    	$data['edit'] 		= true;
	    	$this->load->view('master/lokasi/vieweditlokasi', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function save_lokasi(){
    	$id 	 	= $this->input->post('kode');
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));
    	$tipenil 	= $this->input->post('tipe');
    	$tingkat 	= strtoupper(trim($this->input->post('tingkat')));
    	$panjang 	= strtoupper(trim($this->input->post('panjang')));
    	$lebar   	= strtoupper(trim($this->input->post('lebar')));
    	$tinggi  	= strtoupper(trim($this->input->post('tinggi')));
    	$parentcode = $this->input->post('vparent');
    	$data = array(
    		  'Keterangan'	=> $nama,
    		  'Tingkat'		=> $tingkat,
    		  'ParentCode'	=> $parentcode,
    		  'Panjang'		=> $panjang,
    		  'Lebar'		=> $lebar,
    		  'Tinggi'		=> $tinggi,
    		  'KdTipe'		=> $tipenil,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('lokasi', $data, array('KdLokasi' => $id));
    	redirect('/master/lokasi/');
    }
    function save_new_lokasi(){
		$id 	 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));
    	$tipenil 	= $this->input->post('tipe');
    	$tingkat 	= strtoupper(trim($this->input->post('tingkat')));
    	$panjang 	= strtoupper(trim($this->input->post('panjang')));
    	$lebar   	= strtoupper(trim($this->input->post('lebar')));
    	$tinggi  	= strtoupper(trim($this->input->post('tinggi')));
    	$parentcode = $this->input->post('vparent');
    	$num 	    = $this->lokasimodel->get_id($id);
    	if($num == 0)
		{
		 	$data = array(
				'KdLokasi' 		=> $id ,
				'Keterangan' 	=> $nama ,
				'Tingkat' 		=> $tingkat ,
				'ParentCode'	=> $parentcode,
				'Panjang'		=> $panjang,
				'Lebar'			=> $lebar,
				'Tinggi'		=> $tinggi,
				'KdTipe'		=> $tipenil,
				'AddDate'		=> date("Y-m-d")
            );
            $this->db->insert('lokasi', $data);
			redirect('master/lokasi');
		}
		else
		{
			$data['id'] 	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['tipe']  	 = $this->lokasimodel->getMaster();
			$data['tipenil'] = $tipenil;
	     	$data['tingkat'] = $this->input->post('tingkat');
	     	$data['panjang'] = $this->input->post('panjang');
	     	$data['lebar']   = $this->input->post('lebar');
	     	$data['tinggi']  = $this->input->post('tinggi');
	     	$data['parent']  = $this->lokasimodel->getParent();
	     	$data['vparent'] = $parentcode;
			$data['msg'] 	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/lokasi/addlokasi', $data);
		}
	}
}
?>