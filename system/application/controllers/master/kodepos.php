<?php
class kodepos extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/kodeposmodel');   
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
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
	        $config['num_links']  	  = 2;        
			$config['base_url']       = base_url().'index.php/master/kodepos/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id    = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/kodepos/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/kodepos/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->kodeposmodel->num_kodepos_row($id,$with);
	        $data['kodeposdata']  = $this->kodeposmodel->getkodeposList($config['per_page'],$page,$id,$with);
	        $this->pagination->initialize($config);
	        $this->load->view('master/kodepos/viewkodeposlist', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
	     	$data['msg']  		= "";
	     	$data['id']   		= "";
	     	$data['nama'] 		= "";
	     	$data['masterarea'] = $this->kodeposmodel->get_area();
	     	$data['area'] 	 	= "not selected";
	     	$data['subarea'] 	= "";
	    	$this->load->view('master/kodepos/addkodepos',$data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function view_kodepos($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewkodepos'] = $this->kodeposmodel->getDetail($id);
	    	$data['masterarea']  = $this->kodeposmodel->get_area();
	    	$area				 = $this->kodeposmodel->area($id);
	    	$subareatemp		 = $this->kodeposmodel->get_subs_area($area->KdArea);
	    	$subarealagi		 = "";
	    	if(count($subareatemp)==0)
			{
				$subarealagi =  "<option value=''>Tanpa Nama</option>";
			}
			else
			{
				for($s=0;$s<count($subareatemp);$s++)
				{
				 	$select = "";
				 	if($subareatemp[$s]['KdSubArea']==$data['viewkodepos']->KdSubArea)
					{
						$select 	= "selected";	
					}
					$subarealagi	.= "<option ".$select." value='".$subareatemp[$s]['KdSubArea']."'>".$subareatemp[$s]['Keterangan']."</option>";
				}
			}
			
			$data['subareatemp'] = $subarealagi;
	    	$data['edit'] 		 = false;
	    	$this->load->view('master/kodepos/vieweditkodepos', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_kodepos($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				 = $this->uri->segment(4);
	    	$data['viewkodepos'] = $this->kodeposmodel->getDetail($id);
	    	$this->load->view('master/kodepos/deletekodepos', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function delete_This(){
     	$id = $this->input->post('kode');
		$this->db->delete('kodepos', array('KodePos' => $id));
		redirect('/master/kodepos/');
	}
    
    function edit_kodepos($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id 				 = $this->uri->segment(4);
	    	$data['viewkodepos'] = $this->kodeposmodel->getDetail($id);
	    	$data['masterarea']  = $this->kodeposmodel->get_area();
	    	$area				 = $this->kodeposmodel->area($id);
	    	$subareatemp		 = $this->kodeposmodel->get_subs_area($area->KdArea);
	    	$subarealagi		 = "";
			if(count($subareatemp)==0)
			{
				$subarealagi =  "<option value=''>Tanpa Nama</option>";
			}
			else
			{
				for($s=0;$s<count($subareatemp);$s++)
				{
				 	$select = "";
				 	if($subareatemp[$s]['KdSubArea']==$data['viewkodepos']->KdSubArea)
					{
						$select 	= "selected";	
					}
					$subarealagi	.= "<option ".$select." value='".$subareatemp[$s]['KdSubArea']."'>".$subareatemp[$s]['Keterangan']."</option>";
				}
			}
			$data['subareatemp'] = $subarealagi;
	    	$data['edit'] 		 = true;
	    	$this->load->view('master/kodepos/vieweditkodepos', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function save_kodepos(){
    	$id   	 = $this->input->post('kode');
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$area 	 = $this->input->post('area');
    	$subarea = $this->input->post('subarea');
    	$data = array(
    		  'Keterangan'	=> $nama,
    		  'KdArea'		=> $area,
              'KdSubArea'	=> $subarea,
              'EditDate'	=> date("Y-m-d")
			);
		$this->db->update('kodepos', $data, array('KodePos' => $id));
    	redirect('/master/kodepos/');
    }
    function save_new_kodepos(){
		$id   	 = strtoupper(trim($this->input->post('kode')));
    	$nama 	 = strtoupper(trim($this->input->post('nama')));
    	$area 	 = $this->input->post('area');
    	$subarea = $this->input->post('subarea');
    	$num  	 = $this->kodeposmodel->get_id($id);
    	if($num!=0){
			$data['id']   		= $this->input->post('kode');
			$data['nama'] 		= $this->input->post('nama');
			$data['masterarea'] = $this->kodeposmodel->get_area();
			$subareatemp		= $this->kodeposmodel->get_subs_area($area);
			$subarealagi		= "";
			for($s=0;$s<count($subareatemp);$s++)
			{
			 	$select = "";
			 	if($subareatemp[$s]['KdSubArea']==$subarea)
				{
					$select 	= "selected";	
				}
				$subarealagi	.= "<option ".$select." value='".$subareatemp[$s]['KdSubArea']."'>".$subareatemp[$s]['Keterangan']."</option>";
			}
	     	$data['subarea'] 	= $subarealagi;
			$data['area'] 		= $area;
			$data['msg']  		= "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
			$this->load->view('master/kodepos/addkodepos', $data);
		}
		else{
		 	$data = array(
               'KodePos' 	 => $id ,
               'Keterangan'  => $nama ,
               'KdArea'		 => $area,
               'KdSubArea'	 => $subarea,
               'AddDate'     => date("Y-m-d")
            );
            $this->db->insert('kodepos', $data);
			redirect('/master/kodepos/');
		}
	}
	function sub_area()
	{	 
	 	$area = $this->input->post("area");
		$sub  = $this->kodeposmodel->get_subs_area($area);
		if(count($sub)==0)
		{
			echo "<option value=''>Tanpa Nama</option>";
		}
		else
		{
			for($a=0;$a<count($sub);$a++)
			{
				echo "<option value='".$sub[$a]['KdSubArea']."'>".$sub[$a]['Keterangan']."</option>";
			}
		}
	}
}
?>