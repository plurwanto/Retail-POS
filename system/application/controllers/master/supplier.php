<?php
class supplier extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/suppliermodel');   
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
			$config['base_url']       = base_url().'index.php/master/supplier/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/supplier/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/supplier/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
			
	        $config['total_rows'] = $this->suppliermodel->num_supplier_row($id,$with);
	        $data['supplierdata'] = $this->suppliermodel->getsupplierList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Supplier","Nama","Alamat 1","Alamat 2","Kota","Telepon","Fax","CP","NPWP","Nama Pajak","Alamat 1 Pajak","Alamat 2 Pajak","Kota Pajak","Tipe Bayar","Tipe Kirim","TOP");	
	        $this->pagination->initialize($config);
	        $this->load->view('master/supplier/viewsupplierlist', $data);
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
	     	$data['nama']	 = "";
	     	$data['alm1']	 = "";
	     	$data['alm2']	 = "";
	     	$data['kota']	 = "";
	     	$data['telp']	 = "";
	     	$data['fax']	 = "";
	     	$data['cp']		 = "";
	     	$data['npwp'] 	 = "";
	     	$data['namapjk'] = "";
	     	$data['alm1pjk'] = "";
	     	$data['alm2pjk'] = "";
	     	$data['kotapjk'] = "";
	     	$data['bayar'] 	 = "";
	     	$data['kirim'] 	 = "";
	     	$data['top'] 	 = "";
	    	$this->load->view('master/supplier/addsupplier',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_supplier($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				  = $this->uri->segment(4);
	    	$data['viewsupplier'] = $this->suppliermodel->getDetail($id);
	    	$data['edit'] 		  = false;
	    	$this->load->view('master/supplier/vieweditsupplier', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_supplier($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				  = $this->uri->segment(4);
	    	$data['viewsupplier'] = $this->suppliermodel->getDetail($id);
	    	$this->load->view('master/supplier/deletesupplier', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('supplier', array('KdSupplier' => $id));
		redirect('/master/supplier/');
	}
    
    function edit_supplier($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				  = $this->uri->segment(4);
	    	$data['viewsupplier'] = $this->suppliermodel->getDetail($id);
	    	$data['edit'] 		  = true;
	    	$this->load->view('master/supplier/vieweditsupplier', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_supplier(){
    	$id		 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$alm1 	 = strtoupper(trim($this->input->post('alm1')));
    	$alm2 	 = strtoupper(trim($this->input->post('alm2')));
    	$kota 	 = strtoupper(trim($this->input->post('kota')));
    	$telp 	 = strtoupper(trim($this->input->post('telp')));
    	$fax  	 = strtoupper(trim($this->input->post('fax')));
    	$cp	  	 = strtoupper(trim($this->input->post('cp')));
    	$npwp 	 = strtoupper(trim($this->input->post('npwp')));
    	$namapjk = strtoupper(trim($this->input->post('namapjk')));
    	$alm1pjk = strtoupper(trim($this->input->post('alm1pjk')));
    	$alm2pjk = strtoupper(trim($this->input->post('alm2pjk')));
    	$kotapjk = strtoupper(trim($this->input->post('kotapjk')));
    	$bayar   = strtoupper(trim($this->input->post('bayar')));
    	$kirim   = strtoupper(trim($this->input->post('kirim')));
    	$top     = strtoupper(trim($this->input->post('top')));
    	$data = array(
			'Keterangan' => $nama,
			'Alamat1'	 => $alm1,
			'Alamat2'	 => $alm2,
			'Kota'		 => $kota,
			'Telepon'	 => $telp,
			'NoFax'		 => $fax,
			'ContactPrs' => $cp,
			'NPWP'		 => $npwp,
			'NamaPjk'	 => $namapjk,
			'Alm1Pjk'	 => $alm1pjk,
			'Alm2Pjk'	 => $alm2pjk,
			'KotaPjk'	 => $kotapjk,
			'TipeBayar'	 => $bayar,
			'TipeKirim'	 => $kirim,
			'TOP'		 => $top,
            'EditDate'	 => date("Y-m-d")
			);
		$this->db->update('supplier', $data, array('KdSupplier' => $id));
    	redirect('/master/supplier/');
    }
    function save_new_supplier(){
		$id   	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$alm1 	 = strtoupper(trim($this->input->post('alm1')));
    	$alm2 	 = strtoupper(trim($this->input->post('alm2')));
    	$kota 	 = strtoupper(trim($this->input->post('kota')));
    	$telp 	 = strtoupper(trim($this->input->post('telp')));
    	$fax  	 = strtoupper(trim($this->input->post('fax')));
    	$cp	  	 = strtoupper(trim($this->input->post('cp')));
    	$npwp 	 = strtoupper(trim($this->input->post('npwp')));
    	$namapjk = strtoupper(trim($this->input->post('namapjk')));
    	$alm1pjk = strtoupper(trim($this->input->post('alm1pjk')));
    	$alm2pjk = strtoupper(trim($this->input->post('alm2pjk')));
    	$kotapjk = strtoupper(trim($this->input->post('kotapjk')));
    	$bayar   = strtoupper(trim($this->input->post('bayar')));
    	$kirim   = strtoupper(trim($this->input->post('kirim')));
    	$top     = strtoupper(trim($this->input->post('top')));
    	$num  	 = $this->suppliermodel->get_id($id);
    	if($num!=0){
			$data['id']   	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['msg']  	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$data['alm1']	 = $this->input->post('alm1');
	     	$data['alm2']	 = $this->input->post('alm2');
	     	$data['kota']	 = $this->input->post('kota');
	     	$data['telp']	 = $this->input->post('telp');
	     	$data['fax']	 = $this->input->post('fax');
	     	$data['cp']		 = $this->input->post('cp');
	     	$data['npwp'] 	 = $this->input->post('npwp');
	     	$data['namapjk'] = $this->input->post('namapjk');
	     	$data['alm1pjk'] = $this->input->post('alm1pjk');
	     	$data['alm2pjk'] = $this->input->post('alm2pjk');
	     	$data['kotapjk'] = $this->input->post('kotapjk');;
	     	$data['bayar'] 	 = $this->input->post('bayar');
	     	$data['kirim'] 	 = $this->input->post('kirim');
	     	$data['top'] 	 = $this->input->post('top');
			$this->load->view('master/supplier/addsupplier', $data);
		}
		else{
		 	$data = array(
               'KdSupplier' => $id,
               'Keterangan' => $nama,
               'Alamat1'	=> $alm1,
               'Alamat2'	=> $alm2,
               'Kota'		=> $kota,
               'Telepon'	=> $telp,
               'NoFax'		=> $fax,
               'ContactPrs' => $cp,
               'NPWP'		=> $npwp,
               'NamaPjk'	=> $namapjk,
               'Alm1Pjk'	=> $alm1pjk,
               'Alm2Pjk'	=> $alm2pjk,
               'KotaPjk'	=> $kotapjk,
               'TipeBayar'	=> $bayar,
               'TipeKirim'	=> $kirim,
               'TOP'		=> $top,
               'AddDate'    => date("Y-m-d")
            );
            $this->db->insert('supplier', $data);
			redirect('/master/supplier/');
		}
	}
}
?>