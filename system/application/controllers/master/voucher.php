<?php
class voucher extends Controller {

    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/vouchermodel');
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
			$config['base_url']       = base_url().'index.php/master/voucher/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/voucher/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/voucher/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}

	        $config['total_rows'] = $this->vouchermodel->num_voucher_row($id,$with);
	        $data['voucherdata']  = $this->vouchermodel->getvoucherList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Voucher","Keterangan","Nominal");
	        $this->pagination->initialize($config);
	        $this->load->view('master/voucher/viewvoucherlist', $data);
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
	     	$data['nilai']	 = "";
	    	$this->load->view('master/voucher/addvoucher',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function view_voucher($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewvoucher'] = $this->vouchermodel->getDetail($id);
	    	$data['edit'] 		 = false;
	    	$this->load->view('master/voucher/vieweditvoucher', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_voucher($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewvoucher'] = $this->vouchermodel->getDetail($id);
	    	$this->load->view('master/voucher/deletevoucher', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('voucher', array('KdVoucher' => $id));
		redirect('/master/voucher/');
	}

    function edit_voucher($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				 = $this->uri->segment(4);
	    	$data['viewvoucher'] = $this->vouchermodel->getDetail($id);
	    	$data['edit'] 		 = true;
	    	$this->load->view('master/voucher/vieweditvoucher', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function save_voucher(){
		$mylib = new globallib();
    	$id		 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$nilai 	 = strtoupper(trim($this->input->post('nilai')));
    	$data = array(
			'Keterangan' => $nama,
			'Nominal'	 => $mylib->ubah_format_awal($nilai),
            'EditDate'	 => date("Y-m-d")
			);
		$this->db->update('voucher', $data, array('KdVoucher' => $id));
    	redirect('/master/voucher/');
    }
    function save_new_voucher(){
		$mylib = new globallib();
		$id   	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$nilai 	 = strtoupper(trim($this->input->post('nilai')));
    	$num  	 = $this->vouchermodel->get_id($id);
    	if($num!=0){
			$data['id']   	 = $this->input->post('kode');
			$data['nama'] 	 = $this->input->post('nama');
			$data['msg']  	 = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$data['nilai']	 = $this->input->post('nilai');
			$this->load->view('master/voucher/addvoucher', $data);
		}
		else{
		 	$data = array(
               'KdVoucher'  => $id,
               'Keterangan' => $nama,
               'Nominal'	=> $mylib->ubah_format_awal($nilai),
               'AddDate'    => date("Y-m-d")
            );
            $this->db->insert('voucher', $data);
			redirect('/master/voucher/');
		}
	}
}
?>