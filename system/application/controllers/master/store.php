<?php
class store extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/storemodel');
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
			$config['base_url']       = base_url().'index.php/master/store/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/store/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/store/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->storemodel->num_store_row($id,$with);
	        $data['storedata']    = $this->storemodel->getstoreList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Store","Nama Store","DC","Tipe Store","Sub Tipe Store","Grup Harga","Grup Store","Klasifikasi Store","Channel","Kode POS","PIC","Luas Store","Luas All");
	        $this->pagination->initialize($config);
	        $this->load->view('master/store/viewstorelist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
     		$data['msg']	   = "";
	     	$data['id']		   = "";
	     	$data['nama']	   = "";
	     	$data['pic']  	   = "";
	     	$data['mdc'] 	   = $this->storemodel->getDC();
	     	$data['dc']  	   = "";
	     	$data['mtipe']     = $this->storemodel->getTipe();
	     	$data['tipe']      = "";
	     	$data['subtipe']   = "";
	     	$data['gruph']	   = "";
	     	$data['mgrups']    = $this->storemodel->getGrupStore();
	     	$data['grups']     = "";
	     	$data['mklas']	   = $this->storemodel->getKlasifikasi();
	     	$data['klas']	   = "";
	     	$data['mchannel']  = $this->storemodel->getChannel();
	     	$data['channel']   = "";
	     	$data['marea']     = $this->storemodel->getArea();
	     	$data['area']      = "";
	     	$data['subarea']   = "";
	     	$data['kodepos']   = "";
	     	$data['panjang']   = "";
	     	$data['lebar']     = "";
	     	$data['panjangAll']= "";
	     	$data['lebarAll']  = "";
	    	$this->load->view('master/store/addstore',$data);	    	
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 			   = $this->uri->segment(4);
	    	$data['viewstore'] = $this->storemodel->getDetail($id);
	    	$this->load->view('master/store/deletestore', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('masterstore', array('KdStore' => $id));
		redirect('/master/store/');	
	}
    
    function edit_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
			$id    = $this->uri->segment(4);
	    	$value = $this->storemodel->getDetail($id);
	    	$data  = $this->isi_data("true","edit",$id,$value->NamaStore,$value->KdGrupHarga,$value->KdDC,$value->KdTipeStore,$value->KdSubTipeStore,$value->KdGrupStore,$value->KdKlasifikasi,$value->KdChannel,$value->KdArea,$value->KdSubArea,$value->KodePos,$value->PIC,$value->PanjangStore,$value->LebarStore,$value->PanjangAll,$value->LebarAll);
	    	$this->load->view('master/store/vieweditstore', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_store($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id    = $this->uri->segment(4);
	    	$value = $this->storemodel->getDetail($id);
	    	$data  = $this->isi_data("false","edit",$id,$value->NamaStore,$value->KdGrupHarga,$value->KdDC,$value->KdTipeStore,$value->KdSubTipeStore,$value->KdGrupStore,$value->KdKlasifikasi,$value->KdChannel,$value->KdArea,$value->KdSubArea,$value->KodePos,$value->PIC,$value->PanjangStore,$value->LebarStore,$value->PanjangAll,$value->LebarAll);
	    	$this->load->view('master/store/vieweditstore', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_store(){
    	$id 	 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));     	
    	$dc 		= $this->input->post('dc');
    	$tipe 		= $this->input->post('tipe');
    	$subtipe 	= $this->input->post('subtipe');
    	$gruph 		= $this->input->post('gruph');
    	$grups 		= $this->input->post('grups');
    	$klasifikasi= $this->input->post('klasifikasi');
    	$channel 	= $this->input->post('channel');
    	$area 		= $this->input->post('area');
    	$subarea	= $this->input->post('subarea');
    	$kodepos	= $this->input->post('kodepos');
    	$pic 		= $this->input->post('pic');
    	$panjang	= $this->input->post('panjang');
    	$lebar		= $this->input->post('lebar');
    	$panjangAll	= $this->input->post('panjangAll');
    	$lebarAll	= $this->input->post('lebarAll');
    	$data = array(
				'NamaStore' 	=> $nama,				
				'KdDC'			=> $dc,
				'KdTipeStore'	=> $tipe,
				'KdSubTipeStore'=> $subtipe,
				'KdGrupHarga' 	=> $gruph,
				'KdGrupStore'	=> $grups,
				'KdKlasifikasi'	=> $klasifikasi,
				'KdChannel'		=> $channel,
				'KdArea'		=> $area,
				'KdSubArea'		=> $subarea,
				'KodePos'		=> $kodepos,
				'PIC'			=> $pic,
				'PanjangStore'	=> $panjang,
				'LebarStore'	=> $lebar,
				'PanjangAll'	=> $panjangAll,
				'LebarAll'		=> $lebarAll,			
				'AddDate'		=> date("Y-m-d")
            );
		$this->db->update('masterstore', $data, array('KdStore' => $id));
    	redirect('/master/store/');
    }
    function save_new_store(){
		$id 	 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));     	
    	$dc 		= $this->input->post('dc');
    	$tipe 		= $this->input->post('tipe');
    	$subtipe 	= $this->input->post('subtipe');
    	$gruph 		= $this->input->post('gruph');
    	$grups 		= $this->input->post('grups');
    	$klasifikasi= $this->input->post('klasifikasi');
    	$channel 	= $this->input->post('channel');
    	$area 		= $this->input->post('area');
    	$subarea	= $this->input->post('subarea');
    	$kodepos	= $this->input->post('kodepos');
    	$pic 		= $this->input->post('pic');
    	$panjang	= $this->input->post('panjang');
    	$lebar		= $this->input->post('lebar');
    	$panjangAll	= $this->input->post('panjangAll');
    	$lebarAll	= $this->input->post('lebarAll');
    	$num 	    = $this->storemodel->get_id($id);   	
    	if($num == 0)
		{
		 	$data = array(
				'KdStore' 		=> $id,
				'NamaStore' 	=> $nama,
				'KdGrupHarga' 	=> $gruph,
				'KdDC'			=> $dc,
				'KdTipeStore'	=> $tipe,
				'KdSubTipeStore'=> $subtipe,
				'KdGrupStore'	=> $grups,
				'KdKlasifikasi'	=> $klasifikasi,
				'KdChannel'		=> $channel,
				'KdArea'		=> $area,
				'KdSubArea'		=> $subarea,
				'KodePos'		=> $kodepos,
				'PIC'			=> $pic,
				'PanjangStore'	=> $panjang,
				'LebarStore'	=> $lebar,
				'PanjangAll'	=> $panjangAll,
				'LebarAll'		=> $lebarAll,
				'AddDate'		=> date("Y-m-d")
            );
            $this->db->insert('masterstore', $data); 
			redirect('master/store');
		}
		else
		{
		 	$msg 	   = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
	     	$id		   = $this->input->post('kode');
	     	$nama	   = $this->input->post('nama');	     	
	     	$namaarray = "msg";
		 	$data 	   = $this->isi_data($msg,$namaarray,$id,$nama,$gruph,$dc,$tipe,$subtipe,$grups,$klasifikasi,$channel,$area,$subarea,$kodepos,$pic,$panjang,$lebar,$panjangAll,$lebarAll);
			$this->load->view('master/store/addstore', $data);
		}
	}
	
	function getSubTipeBy()
	{
		$tipe = $this->input->post("tipe");
		$data = $this->storemodel->getSubTipeBy($tipe);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubTipeStore']."'>".$data[$a]['Keterangan']."</option>";
		}
	}
	function getSubAreaBy()
	{
		$area = $this->input->post("area");
		$data = $this->storemodel->getSubAreaBy($area);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubArea']."'>".$data[$a]['Keterangan']."</option>";
		}
	}
	function getKodePosBy()
	{
		$subarea  = $this->input->post("subarea");
		$data     = $this->storemodel->getKodePosBy($subarea);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KodePos']."'>".$data[$a]['KodePos']."</option>";
		}
	}
	function getGrupHargaBy()
	{
		$subtipe  = $this->input->post("subtipe");
		$data     = $this->storemodel->getGrupHargaBy($subtipe);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdGrupHarga']."'>".$data[$a]['Keterangan']."</option>";
		}
	}
	

	function isi_data($msg,$namaarray,$id,$nama,$gruph,$dc,$tipe,$subtipe,$grups,$klasifikasi,$channel,$area,$subarea,$kodepos,$pic,$panjang,$lebar,$panjangAll,$lebarAll)
	{
		$data[$namaarray]  = $msg;
     	$data['id']		   = $id;
     	$data['nama']	   = $nama;
     	$data['pic']  	   = $pic;
     	$data['panjang']   = $panjang;
     	$data['lebar']     = $lebar;
     	$data['panjangAll']= $panjangAll;
     	$data['lebarAll']  = $lebarAll;
     	$data['mgrups']    = $this->storemodel->getGrupStore();
     	$data['grups']     = $grups;
     	$data['mklas']	   = $this->storemodel->getKlasifikasi();
     	$data['klas']	   = $klasifikasi;
     	$data['mchannel']  = $this->storemodel->getChannel();
     	$data['channel']   = $channel;     	
     	$data['mdc'] 	   = $this->storemodel->getDC();
     	$data['dc']  	   = $dc;
     	$data['mtipe']     = $this->storemodel->getTipe();
     	$data['tipe']      = $tipe;
     	$subtipetemp	   = $this->storemodel->getSubTipeBy($tipe);
		$subtipelagi	   = "";
		for($s=0;$s<count($subtipetemp);$s++)
		{
		 	$select = "";
		 	if($subtipetemp[$s]['KdSubTipeStore']==$subtipe)
			{
				$select		= "selected";
			}
			$subtipelagi   .= "<option ".$select." value='".$subtipetemp[$s]['KdSubTipeStore']."'>".$subtipetemp[$s]['Keterangan']."</option>";
		}
     	$data['subtipe']    = $subtipelagi;
		
		$gruphtemp	   	    = $this->storemodel->getGrupHargaBy($subtipe);
		$gruphlagi	   	    = "";
		for($s=0;$s<count($gruphtemp);$s++)
		{
		 	$select = "";
		 	if($gruphtemp[$s]['KdGrupHarga']==$gruph)
			{
				$select 	= "selected";
			}
			$gruphlagi     .= "<option ".$select." value='".$gruphtemp[$s]['KdGrupHarga']."'>".$gruphtemp[$s]['Keterangan']."</option>";
		}
		$data['gruph']	    = $gruphlagi;;
     	$data['marea']      = $this->storemodel->getArea();
     	$data['area']       = $area;
     	$subareatemp	   = $this->storemodel->getSubAreaBy($area);
		$subarealagi	   = "";
		for($s=0;$s<count($subareatemp);$s++)
		{
		 	$select = "";
		 	if($subareatemp[$s]['KdSubArea']==$subarea)
			{
				$select		= "selected";
			}
			$subarealagi   .= "<option ".$select." value='".$subareatemp[$s]['KdSubArea']."'>".$subareatemp[$s]['Keterangan']."</option>";
		}
     	$data['subarea']   = $subarealagi;
     	$kodepostemp	   = $this->storemodel->getKodePosBy($subarea);
		$kodeposlagi	   = "";
		for($s=0;$s<count($kodepostemp);$s++)
		{
		 	$select = "";
		 	if($kodepostemp[$s]['KodePos']==$kodepos)
			{
				$select		= "selected";
			}
			$kodeposlagi   .= "<option ".$select." value='".$kodepostemp[$s]['KodePos']."'>".$kodepostemp[$s]['KodePos']."</option>";
		}
     	$data['kodepos']    = $kodeposlagi;     
     	return $data;
	}
}
?>