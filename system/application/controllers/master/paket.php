<?php
class paket extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/paketmodel');
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
			$config['base_url']       = base_url().'index.php/master/paket/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$with 					  = $this->input->post('searchby');
			$id   					  = "";
			$flag1					  = "";
			if($with!=""){
		        $id = $this->input->post('stSearchingKey');
		        if($id!=""&&$with!=""){
					$config['base_url']     = base_url().'index.php/master/barang/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/master/barang/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
	        $config['total_rows'] = $this->paketmodel->num_barang_row($id,$with);
	        $data['barangdata']   = $this->paketmodel->getbarangList($config['per_page'],$page,$id,$with);
	        $data['header']		  = array("Kode Barang Paket","Barcode","Nama Paket","Isi Paket","Barcode detail","Nama Lengkap");
//	        $data['header']		  = array("Kode Barang","Barcode","Nama Struk","Nama Lengkap","Nama Initial","Divisi","Sub Divisi","Kategori","Sub Kategori","Brand","Sub Brand","Departemen","Kelas Produk","Tipe Produk","Kemasan","Produk Tag","Size","Sub Size","Harga Jual","Grup Harga","Flag Harga","Satuan","Parent","Konversi","Supplier","Principal","Status","Min Order");
	        $this->pagination->initialize($config);
	        $this->load->view('master/paket/viewbaranglist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    
    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
     		$data['msg']        = "";
	     	$data['id']         = "";
	     	$data['barcode']    = "";
	     	$data['nama']	   = "";
	     	$data['nlengkap']  = "";
	     	$data['ninitial']  = "";
	     	$data['hjual'] 	   = "";
	     	$data['konv'] 	   = "";
	     	$data['minimum']   = "";
	     	$data['mdivisi']   = $this->paketmodel->getDivisi();
	     	$data['divisi']	   = "";
	     	$data['subdiv']	   = "";
	     	$data['mkategori'] = $this->paketmodel->getKategori();
	     	$data['kategori']  = "";
	     	$data['subkat']	   = "";
	     	$data['mbrand']    = $this->paketmodel->getBrand();
	     	$data['brand']     = "";
	     	$data['subbrand']  = "";
	     	$data['msize'] 	   = $this->paketmodel->getSize();
	     	$data['size']  	   = "";
	     	$data['subsize']   = "";
	     	$data['mdept']	   = $this->paketmodel->getDept();
	     	$data['dept']	   = "";
	     	$data['mclass']	   = $this->paketmodel->getKelas();
	     	$data['class']	   = "";
	     	$data['mtipe']	   = $this->paketmodel->getTipe();
	     	$data['tipe']	   = "";
	     	$data['mkemasan']  = $this->paketmodel->getKemasan();
	     	$data['kemasan']   = "";
	     	$data['msupplier'] = $this->paketmodel->getSupplier();
	     	$data['supplier']  = "";
	     	$data['mprincipal']= $this->paketmodel->getPrincipal();
	     	$data['principal'] = "";
	     	$data['msatuan']   = $this->paketmodel->getSatuan();
	     	$data['satuan']    = "";
	     	$data['mgrup']     = $this->paketmodel->getGrup();
	     	$data['grup']      = "";
	     	$data['mparent']   = $this->paketmodel->getParent();
	     	$data['parent']    = "";
	     	$data['mflag']     = array("HJ"=>"Harga Jual","GH"=>"Grup Harga");
	     	$data['flag']      = "";
	     	$data['mstatus']   = array("Normal","Konsinyasi");
	     	$data['status']    = "";
	    	$this->load->view('master/barang/addbarang',$data);	    	
    	}
		else{
			$this->load->view('denied');
		}
    }

    function delete_barang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y"){
	     	$id 				= $this->uri->segment(4);
	    	$data['viewbarang'] = $this->paketmodel->getDetail($id);
	    	$this->load->view('master/barang/deletebarang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }    
    
    function delete_This(){
     	$id = $this->input->post('kode');
     	$this->db->delete('masterbarang', array('PCode' => $id));
		redirect('/master/barang/');	
	}
    
    function edit_barang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
                $id    = $this->uri->segment(4);
	    	$value = $this->paketmodel->getDetail($id);
	    	$data  = $this->isi_data("true","edit",$id,$value->NamaStruk,$value->NamaLengkap,$value->NamaInitial,$value->HargaJual,$value->MinOrder,$value->KdDivisi,$value->KdSubDivisi,$value->KdKategori,$value->KdSubKategori,$value->KdBrand,$value->KdSubBrand,$value->KdSize,$value->KdSubSize,$value->KdDepartemen,$value->KdKelas,$value->KdType,$value->KdKemasan,$value->KdSupplier,$value->KdPrincipal,$value->KdSatuan,$value->KdGrupHarga,$value->ParentCode,$value->FlagHarga,$value->Status,$value->Konversi,$value->BarCode);
	    	$this->load->view('master/barang/vieweditbarang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function view_barang($id){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("view");
    	if($sign=="Y"){
	     	$id    = $this->uri->segment(4);
	    	$value = $this->paketmodel->getDetail($id);
    	 	$data  = $this->isi_data("false","edit",$id,$value->NamaStruk,$value->NamaLengkap,$value->NamaInitial,$value->HargaJual,$value->MinOrder,$value->KdDivisi,$value->KdSubDivisi,$value->KdKategori,$value->KdSubKategori,$value->KdBrand,$value->KdSubBrand,$value->KdSize,$value->KdSubSize,$value->KdDepartemen,$value->KdKelas,$value->KdType,$value->KdKemasan,$value->KdSupplier,$value->KdPrincipal,$value->KdSatuan,$value->KdGrupHarga,$value->ParentCode,$value->FlagHarga,$value->Status,$value->Konversi,$value->BarCode);
	    	$this->load->view('master/barang/vieweditbarang', $data);
    	}
		else{
			$this->load->view('denied');
		}
    }
    
    function save_barang(){
    	$id 	 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));
    	$nlengkap 	= strtoupper(trim($this->input->post('nlengkap')));
    	$ninitial 	= strtoupper(trim($this->input->post('ninitial')));
    	$hjual 		= $this->input->post('hjual');
    	$konv 		= $this->input->post('konv');
    	$barcode	= $this->input->post('barcode');
    	$minimum 	= $this->input->post('minimum');
    	$divisi 	= $this->input->post('divisi');
    	$subdiv 	= $this->input->post('subdivisi');
    	$kategori 	= $this->input->post('kategori');
    	$subkat 	= $this->input->post('subkategori');
    	$brand 		= $this->input->post('brand');
    	$subbrand 	= $this->input->post('subbrand');
    	$size 		= $this->input->post('size');
    	$subsize 	= $this->input->post('subsize');
    	$dept		= $this->input->post('departemen');
    	$class		= $this->input->post('kelas');
    	$tipe		= $this->input->post('tipe');
    	$tag		= $this->input->post('tag');
    	$kemasan	= $this->input->post('kemasan');
    	$supplier	= $this->input->post('supplier');
    	$principal	= $this->input->post('principal');
    	$satuan		= $this->input->post('satuan');
    	$parent		= $this->input->post('parents');
    	$grup		= $this->input->post('grup');
    	$flag		= $this->input->post('flag');
    	$status		= $this->input->post('status');
    	$data = array(
				'NamaStruk' 	=> $nama,
				'Barcode' 	=> $barcode,
				'NamaLengkap' 	=> $nlengkap,
				'NamaInitial'	=> $ninitial,
				'HargaJual'		=> $hjual,
				'KdGrupHarga'	=> $grup,
				'FlagHarga'		=> $flag,
				'KdSatuan'		=> $satuan,
				'ParentCode'	=> $parent,
				'Konversi'		=> $konv,
				'KdSupplier'	=> $supplier,
				'KdPrincipal'	=> $principal,
				'Status'		=> $status,
				'MinOrder'		=> $minimum,
				'KdDivisi'		=> $divisi,
				'KdSubDivisi'	=> $subdiv,
				'KdKategori'	=> $kategori,
				'KdSubKategori'	=> $subkat,
				'KdBrand'		=> $brand,
				'KdSubBrand'	=> $subbrand,
				'KdDepartemen'	=> $dept,
				'KdKelas'		=> $class,
				'KdType'		=> $tipe,
				'KdSize'		=> $size,
				'KdSubSize'		=> $subsize,
				'KdKemasan'		=> $kemasan,
				'AddDate'		=> date("Y-m-d")
            );
		$this->db->update('masterbarang', $data, array('PCode' => $id));
    	redirect('/master/barang/');
    }
    function save_new_barang(){
		$id 	 	= strtoupper(trim($this->input->post('kode')));
    	$nama 	 	= strtoupper(trim($this->input->post('nama')));
    	$nlengkap 	= strtoupper(trim($this->input->post('nlengkap')));
    	$ninitial 	= strtoupper(trim($this->input->post('ninitial')));
    	$hjual 		= $this->input->post('hjual');
    	$konv 		= $this->input->post('konv');
    	$barcode        = $this->input->post('barcode');
    	$minimum 	= $this->input->post('minimum');
    	$divisi 	= $this->input->post('divisi');
    	$subdiv 	= $this->input->post('subdivisi');
    	$kategori 	= $this->input->post('kategori');
    	$subkat 	= $this->input->post('subkategori');
    	$brand 		= $this->input->post('brand');
    	$subbrand 	= $this->input->post('subbrand');
    	$size 		= $this->input->post('size');
    	$subsize 	= $this->input->post('subsize');
    	$dept		= $this->input->post('departemen');
    	$class		= $this->input->post('kelas');
    	$tipe		= $this->input->post('tipe');
    	$tag		= $this->input->post('tag');
    	$kemasan	= $this->input->post('kemasan');
    	$supplier	= $this->input->post('supplier');
    	$principal	= $this->input->post('principal');
    	$satuan		= $this->input->post('satuan');
    	$parent		= $this->input->post('parents');
    	$grup		= $this->input->post('grup');
    	$flag		= $this->input->post('flag');
    	$status		= $this->input->post('status');
    	$num 	    = $this->paketmodel->get_id($id);
    	if($num == 0)
		{
		 	$data = array(
				'PCode' 		=> $id,
				'BarCode' 		=> $barcode,
				'NamaStruk' 	=> $nama,
				'NamaLengkap' 	=> $nlengkap,
				'NamaInitial'	=> $ninitial,
				'HargaJual'		=> $hjual,
				'KdGrupHarga'	=> $grup,
				'FlagHarga'		=> $flag,
				'KdSatuan'		=> $satuan,
				'ParentCode'	=> $parent,
				'Konversi'		=> $konv,
				'KdSupplier'	=> $supplier,
				'KdPrincipal'	=> $principal,
				'Status'		=> $status,
				'MinOrder'		=> $minimum,
				'KdDivisi'		=> $divisi,
				'KdSubDivisi'	=> $subdiv,
				'KdKategori'	=> $kategori,
				'KdSubKategori'	=> $subkat,
				'KdBrand'		=> $brand,
				'KdSubBrand'	=> $subbrand,
				'KdDepartemen'	=> $dept,
				'KdKelas'		=> $class,
				'KdType'		=> $tipe,
				'KdSize'		=> $size,
				'KdSubSize'		=> $subsize,
				'KdKemasan'		=> $kemasan,
				'AddDate'		=> date("Y-m-d")
            );
            $this->db->insert('masterbarang', $data); 
			redirect('master/barang');
		}
		else
		{
		 	$msg 	   = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
	     	$id		   = $this->input->post('kode');
	     	$nama	   = $this->input->post('nama');
	     	$nlengkap  = $this->input->post('nlengkap');
	     	$ninitial  = $this->input->post('ninitial');
	     	$namaarray = "msg";
		 	$data 	   = $this->isi_data($msg,$namaarray,$id,$nama,$nlengkap,$ninitial,$hjual,$minimum,$divisi,$subdiv,$kategori,$subkat,$brand,$subbrand,$size,$subsize,$dept,$class,$tipe,$tag,$kemasan,$supplier,$principal,$satuan,$grup,$parent,$flag,$status,$konv,$barcode);
			$this->load->view('master/barang/addbarang', $data);
		}
	}
	
	function getSubDivisiBy()
	{
		$divisi = $this->input->post("divisi");
		$data   = $this->paketmodel->getSubDivBy($divisi);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubDivisi']."'>".$data[$a]['NamaSubDivisi']."</option>";
		}
	}
	function getSubKategoriBy()
	{
		$kategori = $this->input->post("kategori");
		$data     = $this->paketmodel->getSubKatBy($kategori);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubKategori']."'>".$data[$a]['NamaSubKategori']."</option>";
		}
	}
	function getSubBrandBy()
	{
		$brand  = $this->input->post("brand");
		$data   = $this->paketmodel->getSubBrandBy($brand);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubBrand']."'>".$data[$a]['NamaSubBrand']."</option>";
		}
	}
	function getSubSizeBy()
	{
		$size  = $this->input->post("size");
		$data   = $this->paketmodel->getSubSizeBy($size);
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['KdSubSize']."'>".$data[$a]['Ukuran']."</option>";
		}
	}
	function isi_data($msg,$namaarray,$id,$nama,$nlengkap,$ninitial,$hjual,$minimum,$divisi,$subdiv,$kategori,$subkat,$brand,$subbrand,$size,$subsize,$dept,$class,$tipe,$kemasan,$supplier,$principal,$satuan,$grup,$parent,$flag,$status,$konv,$barcode)
	{
            $data[$namaarray]  = $msg;
            $data['id']		   = $id;
            $data['nama']	   = $nama;
            $data['nlengkap']  = $nlengkap;
            $data['ninitial']  = $ninitial;
            $data['hjual'] 	   = $hjual;
     	$data['konv'] 	   = $konv;
        $data['barcode']   = $barcode;
     	$data['minimum']   = $minimum;
     	$data['mdivisi']   = $this->paketmodel->getDivisi();
     	$data['divisi']	   = $divisi;	     
     	$subdivtemp		   = $this->paketmodel->getSubDivBy($divisi);
		$subdivlagi		   = "";
		for($s=0;$s<count($subdivtemp);$s++)
		{
		 	$select = "";
		 	if($subdivtemp[$s]['KdSubDivisi']==$subdiv)
			{
				$select 	= "selected";	
			}
			$subdivlagi	.= "<option ".$select." value='".$subdivtemp[$s]['KdSubDivisi']."'>".$subdivtemp[$s]['NamaSubDivisi']."</option>";
		}
     	$data['subdiv'] 	= $subdivlagi;

     	$data['mkategori'] = $this->paketmodel->getKategori();
     	$data['kategori']  = $kategori;
     	$subkattemp		   = $this->paketmodel->getSubKatBy($kategori);
		$subkatlagi		   = "";
		for($s=0;$s<count($subkattemp);$s++)
		{
		 	$select = "";
		 	if($subkattemp[$s]['KdSubKategori']==$subkat)
			{
				$select 	= "selected";	
			}
			$subkatlagi	.= "<option ".$select." value='".$subkattemp[$s]['KdSubKategori']."'>".$subkattemp[$s]['NamaSubKategori']."</option>";
		}
     	$data['subkat'] 	= $subkatlagi;
     	
     	$data['mbrand']    = $this->paketmodel->getBrand();
     	$data['brand']     = $brand;
     	$subbrandtemp	   = $this->paketmodel->getSubBrandBy($brand);
		$subbrandlagi	   = "";
		for($s=0;$s<count($subbrandtemp);$s++)
		{
		 	$select = "";
		 	if($subbrandtemp[$s]['KdSubBrand']==$subbrand)
			{
				$select 	= "selected";	
			}
			$subbrandlagi	.= "<option ".$select." value='".$subbrandtemp[$s]['KdSubBrand']."'>".$subbrandtemp[$s]['NamaSubBrand']."</option>";
		}
     	$data['subbrand'] 	= $subbrandlagi;
     	
     	$data['msize'] 	   = $this->paketmodel->getSize();
     	$data['size']  	   = $size;    	
     	$subsizetemp	   = $this->paketmodel->getSubSizeBy($size);
		$subsizelagi	   = "";
		for($s=0;$s<count($subsizetemp);$s++)
		{
		 	$select = "";
		 	if($subsizetemp[$s]['KdSubSize']==$subsize)
			{
				$select 	= "selected";	
			}
			$subsizelagi	.= "<option ".$select." value='".$subsizetemp[$s]['KdSubSize']."'>".$subsizetemp[$s]['Ukuran']."</option>";
		}
     	$data['subsize'] 	= $subsizelagi;
     	$data['mdept']	   = $this->paketmodel->getDept();
     	$data['dept']	   = $dept;
     	$data['mclass']	   = $this->paketmodel->getKelas();
     	$data['class']	   = $class;
     	$data['mtipe']	   = $this->paketmodel->getTipe();
     	$data['tipe']	   = $tipe;
     	$data['mkemasan']  = $this->paketmodel->getKemasan();
     	$data['kemasan']   = $kemasan;
     	$data['msupplier'] = $this->paketmodel->getSupplier();
     	$data['supplier']  = $supplier;
     	$data['mprincipal']= $this->paketmodel->getPrincipal();
     	$data['principal'] = $principal;
     	$data['msatuan']   = $this->paketmodel->getSatuan();
     	$data['satuan']    = $satuan;
     	$data['mgrup']     = $this->paketmodel->getGrup();
     	$data['grup']      = $grup;
     	$data['mparent']   = $this->paketmodel->getParent();
     	$data['parent']    = $parent;
     	$data['mflag']     = array("HJ"=>"Harga Jual","GH"=>"Grup Harga");
     	$data['flag']      = $flag;
     	$data['mstatus']   = array("Normal","Konsinyasi");
     	$data['status']    = $status;
     	return $data;
	}
}
?>