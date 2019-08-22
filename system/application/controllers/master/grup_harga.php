<?php
class grup_harga extends Controller {

    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/grup_hargamodel');
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
                $segs 		  = $this->uri->segment_array();
                $arr 		  = "index.php/".$segs[1]."/".$segs[2]."/";
                $data['link']     = $mylib->restrictLink($arr);
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
			$config['base_url']       = base_url().'index.php/master/grup_harga/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
			 	$id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/grup_harga/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/grup_harga/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}

	        $config['total_rows']   = $this->grup_hargamodel->num_grup_harga_row($id,$with);
	        $data['grup_hargadata'] = $this->grup_hargamodel->getgrup_hargaList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/grup_harga/viewgrup_hargalist', $data);
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
	     	$data['nama'] 	 = "";
	     	$data['kdbrg']	 = "";
	     	$data['nmbrg']	 = "";
	     	$data['jualm']	 = "";
	     	$data['jualg']	 = "";
	     	$data['plu']  	 = "";
	     	$data['tempall'] = "";
	    	$this->load->view('master/grup_harga/addgrup_harga',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function view_grup_harga($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 			= $this->uri->segment(4);
	    	$data['header'] = $this->grup_hargamodel->getHeader($id);
	    	$data['detail'] = $this->grup_hargamodel->getDetail($id);
	    	$data['edit'] 	= false;
	    	$this->load->view('master/grup_harga/vieweditgrup_harga', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_grup_harga($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 			= $this->uri->segment(4);
	    	$data['header'] = $this->grup_hargamodel->getHeader($id);
	    	$this->load->view('master/grup_harga/deletegrup_harga', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('grup_hargaheader', array('KdGrupHarga' => $id));
		$this->db->delete('grup_hargadetail', array('KdGrupHarga' => $id));
		redirect('/master/grup_harga/');
	}

    function edit_grup_harga($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 			= $this->uri->segment(4);
	    	$data['header'] = $this->grup_hargamodel->getHeader($id);
	    	$data['detail'] = $this->grup_hargamodel->getDetail($id);
	    	$data['edit'] 	= true;
	    	$this->load->view('master/grup_harga/vieweditgrup_harga', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }

    function save_grup_harga(){
     	$mylib = new globallib();
    	$id   		= $this->input->post('kode');
    	$nama 		= strtoupper(trim($this->input->post('nama')));
    	$temp 		= $this->input->post('tempall');
    	$how 		= $this->input->post('how');
    	$num  		= $this->grup_hargamodel->get_id($id);
    	$eachtemp	= explode("<.<",$temp);

    	$data = array(
           'Keterangan'  => $nama
        );
        $this->db->update('grup_hargaheader', $data, array('KdGrupHarga' => $id));

        for($a=1;$a<count($eachtemp);$a++)
        {
			$detailtemp = explode("*-*",$eachtemp[$a]);
			$pcode = strtoupper(trim($detailtemp[0]));
			$arrPCode[] = $pcode;
			$num = $this->grup_hargamodel->checkPCode($id,$pcode);
			if($num!=0)
			{
				$datadetail = array(
		           'HargaJual'   => $mylib->ubah_format_awal(trim($detailtemp[1])),
		           'KdPLU'  	 => strtoupper(trim($detailtemp[2])),
		           'AddDate'	 => date("Y-m-d")
		        );
		        $this->db->update('grup_hargadetail', $datadetail, array('KdGrupHarga' => $id,'PCode'=>$pcode));
			}
			else
			{
				$datadetail = array(
	               'KdGrupHarga' => $id,
	               'PCode'  	 => strtoupper(trim($detailtemp[0])),
	               'HargaJual'   => $mylib->ubah_format_awal(trim($detailtemp[1])),
	               'KdPLU'  	 => strtoupper(trim($detailtemp[2])),
	               'AddDate'	 => date("Y-m-d")
	            );
	            $this->db->insert('grup_hargadetail', $datadetail);
			}

		}
		$cek = $this->grup_hargamodel->getDetail($id);
		for($a=0;$a<count($cek);$a++)
		{
			$exist = false;
			for($c=0;$c<count($arrPCode);$c++)
			{
				if($cek[$a]['PCode']==$arrPCode[$c])
				{
					$exist = true;
					break;
				}
			}
			if(!$exist)
			{
				$this->db->delete('grup_hargadetail', array('KdGrupHarga' => $id,'PCode'=>$cek[$a]['PCode']));
			}
		}

        if($how=="save")
		{
    		redirect('/master/grup_harga/');
    	}
    	else
    	{
			redirect('/master/grup_harga/edit_grup_harga/'.$id);
		}
    }
    function save_new_grup_harga(){
     	$mylib 		= new globallib();
		$id   		= strtoupper(trim($this->input->post('kode')));
    	$nama 		= strtoupper(trim($this->input->post('nama')));
    	$temp 		= $this->input->post('tempall');
    	$num  		= $this->grup_hargamodel->get_id($id);
    	$eachtemp	= explode("<.<",$temp);
   	 	$detailtemp = explode("*-*",$eachtemp[1]);
   	 	$how 		= $this->input->post('how');
    	if($num!=0){
    	 	$rows			 = $this->grup_hargamodel->getDetailItem($detailtemp[0]);
			$data['id']		 = $this->input->post('kode');
			$data['nama']	 = $this->input->post('nama');
			$data['kdbrg'] 	 = $detailtemp[0];
			$data['jualg'] 	 = $detailtemp[1];
			$data['nmbrg']   = $rows->NamaStruk;
			$data['jualm']   = $mylib->ubah_format($rows->HargaJual);
			$data['plu']     = $detailtemp[2];
			$data['tempall'] = "";
			$data['msg']     = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/grup_harga/addgrup_harga', $data);
		}
		else{
		 	$data = array(
               'KdGrupHarga' => $id,
               'Keterangan'  => $nama
            );
            $datadetail = array(
               'KdGrupHarga' => $id,
               'PCode'  	 => strtoupper(trim($detailtemp[0])),
               'HargaJual'   => $mylib->ubah_format_awal(trim($detailtemp[1])),
               'KdPLU'  	 => strtoupper(trim($detailtemp[2])),
               'AddDate'	 => date("Y-m-d")
            );
            $this->db->insert('grup_hargaheader', $data);
            $this->db->insert('grup_hargadetail', $datadetail);
            if($how=="save")
			{
	    		redirect('/master/grup_harga/');
	    	}
	    	else
	    	{
				redirect('/master/grup_harga/edit_grup_harga/'.$id);
			}
		}
	}
	
	function getPCode()
	{
	 	$mylib = new globallib();
		$pcode = strtoupper(trim($this->input->post('pcode')));
		$num   = $this->grup_hargamodel->CheckItem($pcode);
		if($num==0)
		{
			$str = "*-*0.00";
		}
		else
		{
			$data = $this->grup_hargamodel->getDetailItem($pcode);
			$str  = $data->NamaStruk."*-*".$mylib->ubah_format($data->HargaJual);
		}
		echo $str;
	}
	
	function DetailItemForSales($PCode)
	{
		$this->grup_hargamodel->DetailItemForSales($PCode);
	}
}
?>