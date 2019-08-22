<?php
class customer extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/customermodel');        
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
			$config['base_url']       = base_url().'index.php/master/customer/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/customer/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/customer/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
			
	        $config['total_rows'] = $this->customermodel->num_customer_row($id,$with);
	        $data['customerdata'] = $this->customermodel->getcustomerList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Customer","Gender","Nama","Alamat","Kota","Telepon","Tanggal Lahir","Tanggal Bergabung");	
	        $this->pagination->initialize($config);
	        $this->load->view('master/customer/viewcustomerlist', $data);
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
	     	$data['alm']	 = "";
	     	$data['kota']	 = "";
	     	$data['telp']	 = "";
	     	$data['genderp'] = "checked";
	     	$data['genderw'] = "";
	     	$data['tgl']   	 = "";
	    	$this->load->view('master/customer/addcustomer',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_customer($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				  = $this->uri->segment(4);
	    	$data['viewcustomer'] = $this->customermodel->getDetail($id);
	    	$data['edit'] 		  = false;
	    	$this->load->view('master/customer/vieweditcustomer', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_customer($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				  = $this->uri->segment(4);
	    	$data['viewcustomer'] = $this->customermodel->getDetail($id);
	    	$this->load->view('master/customer/deletecustomer', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('customer', array('KdCustomer' => $id));
		redirect('/master/customer/');
	}
    
    function edit_customer($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				  = $this->uri->segment(4);
	    	$data['viewcustomer'] = $this->customermodel->getDetail($id);
	    	$data['edit'] 		  = true;
	    	$this->load->view('master/customer/vieweditcustomer', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_customer(){
     	$mylib   = new globallib();
    	$id		 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$alm 	 = strtoupper(trim($this->input->post('alm')));
    	$telp 	 = strtoupper(trim($this->input->post('telp')));
    	$kota 	 = strtoupper(trim($this->input->post('kota')));
    	$gender	 = $this->input->post('gender');
     	$tgl	 = $this->input->post('tgl');
    	$data = array(
			'Nama' 		 => $nama,
			'Alamat'	 => $alm,
			'Kota'		 => $kota,
			'Telepon'	 => $telp,
			'TglLahir'	 => $mylib->ubah_tanggal($tgl),
			'Gender' 	 => $gender,
            'EditDate'	 => date("Y-m-d")
			);
		$this->db->update('customer', $data, array('KdCustomer' => $id));
    	redirect('/master/customer/');
    }
    
    function save_new_customer(){
     	$mylib = new globallib();
		$id		 = $this->input->post('kode');
    	$nama 	 = $this->input->post('nama');
    	$alm 	 = $this->input->post('alm');
    	$telp 	 = $this->input->post('telp');
    	$kota 	 = $this->input->post('kota');
    	$gender	 = $this->input->post('gender');
     	$tgl	 = $this->input->post('tgl');
    		
		$num  	 = $this->customermodel->get_id($id);
    	if($num!=0){
			$data['id']   	 = $id;
			$data['nama'] 	 = $nama;
			$data['msg']  	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$data['alm']	 = $alm;
	     	$data['kota']	 = $kota;
	     	$data['telp']	 = $telp;
//	     	$data['gender']	 = $gender;
			if($gender=="P")
				{
					$data['genderp'] = "checked";
	     			$data['genderw'] = "";
				}
			else{
					$data['genderp'] = "";
	     			$data['genderw'] = "checked";
				}	
	     	$data['tgl']  	 = $tgl;
			$this->load->view('master/customer/addcustomer', $data);
		}
		else{
			if($id !=="" && $nama !=="" && $alm !=="" && $telp !=="" && $kota !=="" && $gender !=="" && $tgl !=="")
				{
			
		 	$data = array(
				'KdCustomer' => $id,
				'Nama' 		 => $nama,
				'Alamat'	 => $alm,
				'Kota'		 => $kota,
				'Telepon'	 => $telp,
				'TglLahir'	 => $mylib->ubah_tanggal($tgl),
				'Gender' 	 => $gender,
   				'AddDate'    => date("Y-m-d")
            );
            $this->db->insert('customer', $data);
			redirect('/master/customer/');
			
				}
			else{
					$data['id']   	 	= $id;
					$data['nama'] 	 	= $nama;
					$data['msg'] 		= "<font color='red'><b>Error : Masih ada data yang kosong</b></font>";
					$data['alm']	 	= $alm;
	     			$data['kota']	 	= $kota;
	     			$data['telp']	 	= $telp;
//	     			$data['gender']	 = $gender;
					if($gender=="P")
						{
							$data['genderp'] = "checked";
	     					$data['genderw'] = "";
						}
					else{
							$data['genderp'] = "";
	     					$data['genderw'] = "checked";
						}	
	     			$data['tgl']  	 = $tgl;
					$this->load->view('master/customer/addcustomer', $data);
				}	
		}
	}
}
?>