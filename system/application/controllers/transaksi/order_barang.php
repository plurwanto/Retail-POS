<?php
class order_barang extends Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/order_barangmodel');
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
	{
            $segs 		  = $this->uri->segment_array();
            $arr 		  = "index.php/".$segs[1]."/".$segs[2]."/";
            $data['link']         = $mylib->restrictLink($arr);
            $id 		  = $this->input->post('stSearchingKey');
            $id2 		  = $this->input->post('date1');
	    $with 		  = $this->input->post('searchby');
            if($with=="TglDokumen"||$with=="TglPengiriman")
            {
                $id = $mylib->ubah_tanggal($id2);
            }
	        $this->load->library('pagination');

	        $config['full_tag_open']  = '<div class="pagination">';
	        $config['full_tag_close'] = '</div>';
	        $config['cur_tag_open']   = '<span class="current">';
	        $config['cur_tag_close']  = '</span>';
	        $config['per_page']       = '14';
	        $config['first_link'] 	  = 'First';
	        $config['last_link'] 	  = 'Last';
	        $config['num_links']  	  = 2;
			$config['base_url']       = base_url().'index.php/transaksi/order_barang/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$flag1					  = "";
			if($with!=""){
                            if($id!=""&&$with!=""){
                                $config['base_url']     = base_url().'index.php/transaksi/order_barang/index/'.$with."/".$id."/";
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
					if($with=="TglDokumen"||$with=="TglPengiriman")
					{
						$id = $mylib->ubah_tanggal($id);
					}
				 	$config['base_url']     = base_url().'index.php/transaksi/order_barang/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
		$data['header']		= array("No Dokumen","Tanggal","Supplier","Qty Item","Total Harga","Keterangan");
	        $config['total_rows']	= $this->order_barangmodel->num_order_row(addslashes($id),$with);
	        $data['data']	= $this->order_barangmodel->getorderList($config['per_page'],$page,addslashes($id),$with);
	        $data['track']  = $mylib->print_track();
			$this->pagination->initialize($config);
	        $this->load->view('transaksi/order_barang/order_baranglist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }

    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
			$aplikasi = $this->order_barangmodel->getDate();
//			$data['mperusahaan'] = $this->order_barangmodel->getPerusahaan();
			$data['aplikasi'] = $aplikasi;
			$data['mkontak'] = $this->order_barangmodel->getKontak();
	    	$this->load->view('transaksi/order_barang/add_order_barang',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
	function carikontak()
	{
		$sumber = $this->input->post('sumber');
		$aplikasi = $this->order_barangmodel->getDate();
		if($sumber=="M"||$sumber=="O"){
			if($aplikasi->DefaultContactOrder==""){
				$with = "";
			}
			else
			{
				$with = "where KdTipeContact='".$aplikasi->DefaultContactOrder."'";
			}
		}
		
		$mkontak = $this->order_barangmodel->getKontak($with);
		$str = "";
		for($m=0;$m<count($mkontak);$m++)
		{
			$str .= "<option value='".$mkontak[$m]['KdContact']."'>".$mkontak[$m]['Nama']."</option>";
		}
		echo $str;
	}

    function delete_order(){
            $mylib = new globallib();
                $id = $this->input->post('kode');
		$user = $this->session->userdata('userid');
		$tgl2 = $this->session->userdata('Tanggal_Trans');
                $tgl  = $mylib->ubah_tanggal($tgl2);
		$getHeader = $this->order_barangmodel->getHeader($id);
		$getDetail= $this->order_barangmodel->getDetailDel($id);
		$tahun = substr($getHeader->TglDokumen,6,4);
		$lastNo = $this->order_barangmodel->getNewNo($tahun);
		$NoDelete = $id;
		if((int)$lastNo->NoOrder == (int)$NoDelete + 1){
			$this->db->update("setup_no",array("NoOrder"=>$NoDelete[1]),array("Tahun"=>$tahun));
		}
		$this->order_barangmodel->locktables('trans_order_detail,trans_order_header');
                $bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		

                for($s=0;$s<count($getDetail);){

                        $pcodebarang  = $getDetail[$s]['PCode'];
                        $qtyorder     = $getDetail[$s]['QtyOrder'];

                        $s++;
                }
		$this->db->delete('trans_order_detail',array('NoDokumen' => $id."D"));
		$this->db->delete('trans_order_header',array('NoDokumen' => $id."D"));
		$this->db->update('trans_order_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->db->update('trans_order_header',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->order_barangmodel->unlocktables();

	}
	function cetak()
	{
		$data = $this->varCetak();
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_so', $data);
	}
	function printThis()
	{
		$data = $this->varCetak();
		$id = $this->uri->segment(4);
		$data['fileName2'] = "order_barang.sss";
		$data['fontstyle'] = chr(27).chr(80);
		$data['nfontstyle'] = "";
		$data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);
                $data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);// buat halaman baru
		$data['string1'] = "     Dibuat oleh,                    Disetujui oleh,";
		$data['string2'] = "(                     )         (                      )";
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer_so', $data);
	}
	function varCetak()
	{
		$this->load->library('printreportlib');
		$mylib = new printreportlib();
		$id = $this->uri->segment(4);
		$header	 = $this->order_barangmodel->getHeader($id);
//                print_r($header);
		$data['header']	 = $header;
		$detail	 = $this->order_barangmodel->getDetailForPrint($id);
//		$data['pt'] = $this->order_barangmodel->getAlmPerusahaan($header->KdSupplier);
//                print_r($data['pt']);die();
		$data['judul1'] = array("Supplier","Contact Person","Telp / Fax");
		$data['niljudul1'] = array($header->KdSupplier." - ".stripslashes($header->supplier),$header->ContactPrs,$header->Telepon."-".$header->NoFax);
		$data['judul2'] = array("PO Number","PO Date","Keterangan");
		$data['niljudul2'] = array("PO".$header->NoDokumen,$header->TglDokumen,stripslashes($header->Keterangan));
		$tambahan_judul = "";
		$data['judullap'] = "PURCHASE ORDER".$tambahan_judul;
		$data['url'] = "order_barang/printThis/".$id;
		$data['colspan_line'] = 4;
		$data['tipe_judul_detail'] = array("normal","normal","kanan","normal","kanan","kanan");
		$data['judul_detail'] = array("Kode","Nama Barang","Qty","","Harga","Total");
		$data['panjang_kertas'] = 33;
		$default_page_written = 21;
		$data['panjang_per_hal'] = (int)$data['panjang_kertas'] - (int)$default_page_written;
		if($data['panjang_per_hal']!=0){
			$data['tot_hal'] = ceil((int)count($detail)/ (int)$data['panjang_per_hal']);
		}
		else
		{
			$data['tot_hal'] = 1;
		}
		$list_detail = array();
		$detail_page = array();
		$counterRow = 0;
		$max_field_len = array(0,0,0,0,0,0);
                $sum_netto = 0;
		for($m=0;$m<count($detail);$m++)
		{
//			$hasil = $mylib->findSatuanQtyCetak($detail[$m]['Qtyorder'],$detail[$m]['KonversiBesarKecil'],$detail[$m]['KonversiTengahKecil']);
			unset($list_detail);
			$counterRow++;
			$list_detail[] = stripslashes($detail[$m]['PCode']);
			$list_detail[] = stripslashes($detail[$m]['NamaInitial']);
			$list_detail[] = stripslashes($detail[$m]['QtyOrder']);
			$list_detail[] = "pcs";
			$list_detail[] = number_format($detail[$m]['HargaOrder'],'',',','.');
			$list_detail[] = number_format(($detail[$m]['QtyOrder'] * $detail[$m]['HargaOrder']),'',',','.');
			$detail_page[] = $list_detail;
			$max_field_len = $mylib->get_max_field_len($max_field_len,$list_detail);
			if($data['panjang_per_hal']!=0){
				if(((int)$m+1) % $data['panjang_per_hal'] ==0){
					$data['detail'][] = $detail_page;
					if($m!=count($detail)-1){
						unset($detail_page);
					}
				}
			}
                        $netto = $detail[$m]['QtyOrder'] * $detail[$m]['HargaOrder'];
		 $sum_netto = $sum_netto + ($netto);
		}
                $data['judul_netto']=array("Total","PPN 10%","Nett ");
                $data['isi_netto']=array(number_format($sum_netto,'',',','.'),number_format(($sum_netto * 0.1),'',',','.'),number_format($sum_netto + ($sum_netto * 0.1),'',',','.'));
		$data['detail'][] = $detail_page;
		$data['max_field_len'] = $max_field_len;
		$data['banyakBarang'] = $counterRow;
		return $data;
	}
    function edit_order($id){
     	$mylib = new globallib();
	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
            $id = $this->uri->segment(4);
            $data['header']	 = $this->order_barangmodel->getHeader($id);
            $data['detail']	 = $this->order_barangmodel->getDetail($id);
           
            $this->load->view('transaksi/order_barang/edit_order_barang', $data);
    	}else{
            $this->load->view('denied');
	}
    }
    
    function getPCode(){
		$kode = $this->input->post('pcode');
		if(strlen($kode)==13)
		{
			$mylib = new globallib();
			$hasil = $mylib->findStructureBarcode($kode,"PCode","distinct");
			$pcode_hasil = $hasil['nilai'];
			$valpcode->PCode = $pcode_hasil[0]['PCode'];
		}
		else
		{
			$valpcode = $this->order_barangmodel->ifPCodeBarcode($kode);
		}
		if(count($valpcode)!=0)
		{
			$pcode = $valpcode->PCode;
			$detail = $this->order_barangmodel->getPCodeDet($pcode);
			$nilai = $detail->NamaInitial."*&^%".$detail->PCode."*&^%".$detail->HargaBeliAkhir;
                }
		else
		{
			$nilai = "";
		}
		echo $nilai;
	}
	function getsumber()
	{
		$order = $this->input->post('order');
		$kirim = $this->input->post('kirim');
		$perusahaan = $this->input->post('perusahaan');
		if($order!=""){
			$hasil = $this->order_barangmodel->getOrder($order,$perusahaan);
		}
		if($kirim!="")
		{
			$hasil = $this->order_barangmodel->getKirim($kirim,$perusahaan);
		}
		$str = "";
		$strsatuan = "";
		$kendaraan = "";
		for($s=count($hasil)-1;$s>=0;$s--)
		{
			if($kirim!="")
			{
				$kendaraan = $hasil[$s]['NoPolisi'];
				if( (int)$hasil[$s]['QtyPcs'] % (int)$hasil[$s]['KonversiBesarKecil']==0)
				{
					$hasil[$s]['QtyInput'] = (int)$hasil[$s]['QtyPcs'] / (int)$hasil[$s]['KonversiBesarKecil'];
					$hasil[$s]['Satuan'] = $hasil[$s]['KdSatuanBesar'];
				}
				else if( (int)$hasil[$s]['QtyPcs'] % (int)$hasil[$s]['KonversiTengahKecil']==0)
				{
					$hasil[$s]['QtyInput'] = (int)$hasil[$s]['QtyPcs'] / (int)$hasil[$s]['KonversiTengahKecil'];
					$hasil[$s]['Satuan'] = $hasil[$s]['KdSatuanTengah'];
				}
			}
			$str .= $hasil[$s]['PCode']."*&^%".$hasil[$s]['QtyInput']."*&^%".$hasil[$s]['QtyDisplay']."*&^%".$hasil[$s]['QtyPcs']."*&^%".$hasil[$s]['NamaInitial']."*&^%".$hasil[$s]['KonversiJualKecil']."*&^%".$hasil[$s]['KonversiBesarKecil']."*&^%".$hasil[$s]['KonversiTengahKecil']."*&^%".$hasil[$s]['KdSatuanJual']."*&^%".$hasil[$s]['NamaSatuan']."*&^%".$hasil[$s]['Satuan']."*&^%".$hasil[$s]['PCodeBarang']."*&^%".$hasil[$s]['KdContact']."*&^%".$hasil[$s]['jenis']."~";
			
		}
		if($kirim!="")
		{
			echo $kendaraan."^&&^".$str."+".$strsatuan;
		}
		else
		{
			echo $str."+".$strsatuan;
		}
	}
	function getRealPCode()
	{
		$kode = $this->input->post('pcode');
		if(strlen($kode)==13)
		{
			$mylib = new globallib();
			$hasil = $mylib->findStructureBarcode($kode,"PCode","distinct");
			$pcode_hasil = $hasil['nilai'];
			if(count($pcode_hasil)!=0)
			{
				$pcode = $pcode_hasil[0]['PCode'];
			}
			else
			{
				$pcode = "";
			}
		}
		else
		{
			$valpcode = $this->order_barangmodel->ifPCodeBarcode($kode);
			if(count($valpcode)!=0)
			{
				$pcode = $valpcode->PCode;
			}
			else
			{
				$pcode = "";
			}
		}
		echo $pcode;
	}

	function save_new_order(){

//            print_r($_POST);
//            die();
            	$mylib  = new globallib();
		$user   = $this->session->userdata('userid');
		$flag   = $this->input->post('flag');
		$no     = $this->input->post('nodok');
		$tgl    = $this->input->post('tgl');
		$kontak = $this->input->post('supplier');
		$ket    = trim(strtoupper(addslashes($this->input->post('ket'))));
		$pcode1 = $this->input->post('pcode');
		$qty1   = $this->input->post('qty');
		$hrg1 = $this->input->post('hrg');
		$pcodesave1  = $this->input->post('savepcode');
		if($no=="")
		{
			$counter = "1";
			$no = $this->insertNewHeader($flag,$mylib->ubah_tanggal($tgl),$ket,$user,$kontak);
		}
		else
		{
			$counter = $this->updateHeader($flag,$no,$ket,$user);
		}
		for($x=0;$x<count($pcode1);$x++)
		{
			$pcode = strtoupper(addslashes(trim($pcode1[$x])));
			$qty = trim($qty1[$x]);
			$hrg = trim($hrg1[$x]);
			$pcodesave = $pcodesave1[$x];
			if($pcode!=""){
				$this->InsertAllDetail($flag,$no,$pcode,$qty,$hrg,$user,$mylib->ubah_tanggal($tgl),$pcodesave,$ket);
			}
		}
                $mylib->Updateheadernya($no,"trans_order_header","trans_order_detail",substr($tgl,6,4),"QtyOrder","HargaOrder");

//                $this->order_barangmodel->updateHargaHeader($no);
		redirect('/transaksi/order_barang/');
	}
   

        function insertNewHeader($flag,$tgl,$ket,$user,$kontak)
	{
		$this->order_barangmodel->locktables('setup_per_perusahaan,trans_order_header');
		$new_no = $this->order_barangmodel->getNewNo(substr($tgl,0,4));
		$no = $new_no->NoOrder;
		$this->db->update('setup_no', array("NoOrder"=>(int)$new_no->NoOrder+1),array("Tahun"=>substr($tgl,0,4)));
		$data = array(
			'NoDokumen'	=> $no,
			'TglDokumen'    => $tgl,
			'Keterangan'    => $ket ,
			'KdSupplier'     => $kontak,
			'AddDate'       => $tgl,
			'AddUser'       => $user
		);
		$this->db->insert('trans_order_header', $data);
		$this->order_barangmodel->unlocktables();
		return $no;
	}

        function updateHeader($flag,$no,$ket,$user)
	{
		$tgl = $this->session->userdata('Tanggal_Trans');
		$this->order_barangmodel->locktables('trans_order_header,trans_order_detail');
		$data = array(
			'Keterangan'    => $ket ,
		);
		if($flag=="edit")
		{
			$data['EditDate'] = $tgl;
			$data['EditUser'] = $user;
			$this->db->update('trans_order_detail', array('EditDate'=> $tgl,'EditUser'=>$user), array('NoDokumen' => $no));
		}
		$this->db->update('trans_order_header', $data, array('NoDokumen' => $no));
		$this->order_barangmodel->unlocktables();
	}

        function InsertAllDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$pcodesave,$ket)
	{
//            echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|qty:".$qty;
		if($flag=="add")
		{
                        $this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave);
		}
		else
		{
			if($pcodesave==$pcode)//jika hanya qty yg berubah
			{
				$cekdulu = $this->order_barangmodel->cekPast($no,$pcode);
//				echo $qty." ".$cekdulu->QtyOpname;die();
				if($qty!=$cekdulu->QtyOrder or $hrg!=$cekdulu->HargaOrder)
				{
					$this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->QtyOrder);
					$this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave);
				}
				else
				{
					if($flag=="edit")
					{
						$tgltrans = $this->session->userdata('Tanggal_Trans');
						$data = array(
							'EditDate' => $tgltrans,
							'EditUser' => $user
						);
						$this->db->update("trans_order_detail",$data,array("NoDokumen"=>$no));
					}
				}
			}
			else
			{
				$cekdulu = $this->order_barangmodel->cekPast($no,$pcodesave);
//                                print_r($cekdulu);die();
                                //$pcodebarang_dulu = $this->order_barangmodel->ifPCodeBarcode($pcodesave);
                                if(!empty($pcodesave)){ // jika barang baru
                                    $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->Qtyorder);
                                }
				$this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave);
			}
		}
	}
                    
        function doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave)
	{
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk = "QtyMasuk".$bulan;
		$fieldakhir = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
                $this->insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl);
	}
	
        function deleteAll($flag,$no,$tgl,$pcode,$pcodebarang,$qtyorder)
	{//echo $tgl;die();
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk  = "QtyMasuk".$bulan;
		$fieldakhir  = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
		
                if($flag!="del"){
                    if($pcode!=$pcodebarang){
                        $this->db->delete("trans_order_detail",array("NoDokumen"=>$no,"PCode"=>$pcodebarang));
                    }else{
                        $this->db->delete("trans_order_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
                    }
                }
	}
        function insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl)
	{
	
		$tgltrans = $this->session->userdata('Tanggal_Trans');
		$this->order_barangmodel->locktables('trans_order_detail');
		
//			$detail_ada = $this->order_barangmodel->cekDetail($pcode,$no);
//			if(count($detail_ada)!=0&&$detail_ada->FlagDelete=="Y"){
//                                $this->db->delete("trans_order_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
//			}

                            $data = array(
                                    'NoDokumen'     => $no,
                                    'PCode'         => $pcode,
                                    'Qtyorder'      => $qty ,
                                    'HargaOrder'    => $hrg,
                                    'AddDate'       => $tgl,
                                    'AddUser'       => $user
                            );
                $this->db->insert('trans_order_detail', $data);
                if($flag=="edit"){
			$data = array(
                                'EditDate'      => $tgltrans,
				'EditUser'      => $user
			);
                    $this->db->update('trans_order_detail', $data, array('NoDokumen' => $no,'PCode'=>$pcode));
		}
		
		$this->order_barangmodel->unlocktables();
	}
	function delete_item()
	{
		$mylib = new globallib();
                $flag       = "edit";
		$no         = $this->input->post('no');
		$pcode      = $this->input->post('pcode');
		$pcodesave  = $this->input->post('pcodesave');
		$qty        = $this->input->post('qty');
		$user       = $this->session->userdata('userid');
		$tgl2        = $this->session->userdata('Tanggal_Trans');
                $mylib = new globallib();
                $tgl = $mylib->ubah_tanggal($tgl2);
		$this->order_barangmodel->locktables('trans_order_detail');
//                no:no,tgl:tgl,pcode:pcode,pcodesave:pcodesave,qty:qty
                $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$qty);
//		$this->db->update('trans_order_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user), array('NoDokumen' => $id,'PCode'=>$pcode));
		$this->order_barangmodel->unlocktables();
	}
}
?>