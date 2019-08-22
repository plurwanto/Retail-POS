<?php
class retur_barang extends Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/retur_barangmodel');
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
			$config['base_url']       = base_url().'index.php/transaksi/retur_barang/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$flag1					  = "";
			if($with!=""){
                            if($id!=""&&$with!=""){
                                $config['base_url']     = base_url().'index.php/transaksi/retur_barang/index/'.$with."/".$id."/";
                                $page 					= $this->uri->segment(6);
                                $config['uri_segment']  = 6;
                            }
                            else{
                                $page ="";
                            }
			}
			else{
				if($this->uri->segment(5)!=""){
					$with 			= $this->uri->segment(4);
				 	$id 			= $this->uri->segment(5);
					if($with=="TglDokumen"||$with=="TglPengiriman")
					{
						$id = $mylib->ubah_tanggal($id);
					}
				 	$config['base_url']     = base_url().'index.php/transaksi/retur_barang/index/'.$with."/".$id."/";
					$page 			= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
		$data['header']		= array("No Dokumen","Tanggal","Supplier","Qty Item","Total Harga","Keterangan");
	        $config['total_rows']	= $this->retur_barangmodel->num_retur_row(addslashes($id),$with);
	        $data['data']	= $this->retur_barangmodel->getreturList($config['per_page'],$page,addslashes($id),$with);
	        $data['track']  = $mylib->print_track();
			$this->pagination->initialize($config);
	        $this->load->view('transaksi/retur_barang/retur_baranglist', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }

    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
			$aplikasi = $this->retur_barangmodel->getDate();
//			$data['mperusahaan'] = $this->retur_barangmodel->getPerusahaan();
			$data['aplikasi'] = $aplikasi;
			$data['mkontak'] = $this->retur_barangmodel->getKontak();
	    	$this->load->view('transaksi/retur_barang/add_retur_barang',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
	function carikontak()
	{
		$sumber = $this->input->post('sumber');
		$aplikasi = $this->retur_barangmodel->getDate();
		if($sumber=="M"||$sumber=="O"){
			if($aplikasi->DefaultContactOrder==""){
				$with = "";
			}
			else
			{
				$with = "where KdTipeContact='".$aplikasi->DefaultContactOrder."'";
			}
		}
		
		$mkontak = $this->retur_barangmodel->getKontak($with);
		$str = "";
		for($m=0;$m<count($mkontak);$m++)
		{
			$str .= "<option value='".$mkontak[$m]['KdContact']."'>".$mkontak[$m]['Nama']."</option>";
		}
		echo $str;
	}

    function delete_retur(){
            $mylib = new globallib();
                $id = $this->input->post('kode');
		$header = $this->retur_barangmodel->getSumber($id);
		$user = $this->session->userdata('userid');
		$tgl2 = $this->session->userdata('Tanggal_Trans');
                $tgl  = $mylib->ubah_tanggal($tgl2);
		$getHeader = $this->retur_barangmodel->getHeader($id);
		$getDetail= $this->retur_barangmodel->getDetailDel($id);
		$tahun = substr($getHeader->TglDokumen,6,4);
		$lastNo = $this->retur_barangmodel->getNewNo($tahun);
		$NoDelete = $id;
		if((int)$lastNo->Noretur == (int)$NoDelete + 1){
			$this->db->update("setup_no",array("Noretur"=>$NoDelete[1]),array("Tahun"=>$tahun));
		}
		$this->retur_barangmodel->locktables('trans_retur_detail,trans_retur_header');
		if($header->SumberOrder=="O")
		{
			$this->db->update('trans_order_barang_header', array("Flagretur"=>"T"),array('NoDokumen'=>$header->NoOrder));
			$this->db->update('trans_order_barang_detail', array("Flagretur"=>"T"),array('NoDokumen'=>$header->NoOrder));
		}
                $bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk  = "QtyMasuk".$bulan;
		$fieldakhir  = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;

                for($s=0;$s<count($getDetail);){

                        $pcodebarang  = $getDetail[$s]['PCode'];
                        $qtyretur    = $getDetail[$s]['Qtyretur'];
                        $stokawal = $this->retur_barangmodel->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyretur),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyretur)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$id,"KodeBarang"=>$pcodebarang));

                        $s++;
                }
		$this->db->delete('trans_retur_detail',array('NoDokumen' => $id."D"));
		$this->db->delete('trans_retur_header',array('NoDokumen' => $id."D"));
		$this->db->update('trans_retur_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->db->update('trans_retur_header',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->retur_barangmodel->unlocktables();

	}
	function cetak()
	{
		$data = $this->varCetak();
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_RB', $data);
	}
	function printThis()
	{
		$data = $this->varCetak();
		$id = $this->uri->segment(4);
		$data['fileName2'] = "retur_barang.sss";
		$data['fontstyle'] = chr(27).chr(80);
		$data['nfontstyle'] = "";
		$data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);
                $data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);
		$data['string1'] = "     Dibuat Oleh,                    Disetujui Oleh,";
		$data['string2'] = "(                     )         (                      )";
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer_RB', $data);
	}
	function varCetak()
	{
		$this->load->library('printreportlib');
		$mylib = new printreportlib();
		$id = $this->uri->segment(4);
		$header	 = $this->retur_barangmodel->getHeader($id);
		$data['header']	 = $header;
		$detail	 = $this->retur_barangmodel->getDetailForPrint($id);
		$data['judul1'] = array("No Retur","Tanggal Retur","Keterangan");
		$data['niljudul1'] = array("R".$header->NoDokumen,$header->TglDokumen,stripslashes($header->Keterangan));
		$data['judul2'] = array("Supplier","","");
		$data['niljudul2'] = array($header->KdSupplier." - ".stripslashes($header->supplier),$header->Alamat1,$header->Alamat2."".$header->Telepon);
		$tambahan_judul = "";
		$data['judullap'] = "RETUR BARANG".$tambahan_judul;
		$data['url'] = "retur_barang/printThis/".$id;
		$data['colspan_line'] = 4;
		$data['tipe_judul_detail'] = array("normal","normal","kanan","normal","kanan","kanan");
		$data['judul_detail'] = array("Kode","Nama Barang","Qty","","Harga","Total");
		$data['panjang_kertas'] = 30;
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
//                print_r($detail);
                 $sum_netto = 0;
		for($m=0;$m<count($detail);$m++)
		{
                    $netto = ($detail[$m]['Qtyretur'] * $detail[$m]['HargaBeliAkhir']);
    //			$hasil = $mylib->findSatuanQtyCetak($detail[$m]['Qtyretur'],$detail[$m]['KonversiBesarKecil'],$detail[$m]['KonversiTengahKecil']);
			unset($list_detail);
			$counterRow++;
			$list_detail[] = stripslashes($detail[$m]['PCode']);
			$list_detail[] = stripslashes($detail[$m]['NamaInitial']);
			$list_detail[] = $detail[$m]['Qtyretur'];
			$list_detail[] = "pcs";
			$list_detail[] = number_format($detail[$m]['HargaBeliAkhir'],'','','.');
			$list_detail[] = number_format($netto,'','','.');
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
                        $sum_netto = $sum_netto + ($netto);
		}
                $data['judul_netto']=array("Total","PPN 10%","Nett Retur");
                $data['isi_netto']=array(number_format($sum_netto,'',',','.'),number_format(($sum_netto * 0.1),'',',','.'),number_format($sum_netto + ($sum_netto * 0.1),'',',','.'));
		$data['detail'][] = $detail_page;
		$data['max_field_len'] = $max_field_len;
		$data['banyakBarang'] = $counterRow;
		return $data;
	}
    function edit_retur($id){
     	$mylib = new globallib();
	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
            $id = $this->uri->segment(4);
            $data['header']	 = $this->retur_barangmodel->getHeader($id);
            $data['detail']	 = $this->retur_barangmodel->getDetail($id);
           
            $this->load->view('transaksi/retur_barang/edit_retur_barang', $data);
    	}else{
            $this->load->view('denied');
	}
    }
    
    function getPCode(){
		$kode = $this->input->post('pcode');
		$tgl = $this->input->post('tgl');
                $bulan = substr($tgl,3,2);
		$tahun = substr($tgl,6,4);
		$fieldmasuk = "QtyMasuk".$bulan;
		$fieldakhir = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
                $detail = $this->retur_barangmodel->getPCodeDet($kode,$fieldakhir,$tahun);
//                print_r($detail);
                if(!empty($detail)){
			$nilai = $detail->NamaInitial."*&^%".$detail->PCode."*&^%".$detail->HargaBeliAkhir;
                }
		else
		{
			$nilai = "";
		}
		echo $nilai;
	}
	
	function getRealPCode()
	{
		$kode = $this->input->post('pcode');
		if(strlen($kode)==13)
		{
			$mylib = new globallib();
			$hasil = $mylib->findBarcode($kode);
                        print_r($hasil);die();
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
			$valpcode = $this->retur_barangmodel->ifPCodeBarcode($kode);
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

	function save_new_retur(){

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
			$no = $this->insertNewHeader($flag,$mylib->ubah_tanggal($tgl),$ket,$user,$kontak);
		}
		else
		{
			$this->updateHeader($flag,$no,$ket,$user,$mylib->ubah_tanggal($tgl));
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
                $mylib->Updateheadernya($no,"trans_retur_header","trans_retur_detail",substr($tgl,6,4),"QtyRetur","HargaBeliAkhir");
		redirect('/transaksi/retur_barang/');
	}
   
        function insertNewHeader($flag,$tgl,$ket,$user,$kontak)
	{
		$this->retur_barangmodel->locktables('trans_retur_header');
		$new_no = $this->retur_barangmodel->getNewNo(substr($tgl,0,4));
		$no = $new_no->Noretur;
		$this->db->update('setup_no', array("Noretur"=>(int)$new_no->Noretur+1),array("Tahun"=>substr($tgl,0,4)));
		$data = array(
			'NoDokumen'	=> $no,
			'TglDokumen'    => $tgl,
			'Keterangan'    => $ket ,
			'KdSupplier'    => $kontak,
			'AddDate'       => $tgl,
			'AddUser'       => $user
		);
		$this->db->insert('trans_retur_header', $data);
		$this->retur_barangmodel->unlocktables();
		return $no;
	}

        function updateHeader($flag,$no,$ket,$user,$tgl)
	{
		$tgl = $this->session->userdata('Tanggal_Trans');
		$this->retur_barangmodel->locktables('trans_retur_header,trans_retur_detail');
		$data = array(
			'Keterangan'    => $ket ,
		);
		if($flag=="edit")
		{
			$data['EditDate'] = $tgl;
			$data['EditUser'] = $user;
			$this->db->update('trans_retur_detail', array('EditDate'=> $tgl,'EditUser'=>$user), array('NoDokumen' => $no));
		}
		$this->db->update('trans_retur_header', $data, array('NoDokumen' => $no));
		$this->retur_barangmodel->unlocktables();
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
//                    echo "ok";die();
				$cekdulu = $this->retur_barangmodel->cekPast($no,$pcode);
//				echo $qty." ".$cekdulu->QtyOpname;die();
				if($qty!=$cekdulu->Qtyretur or $hrg!=$cekdulu->HargaBeliAkhir)
				{
//                                echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|".$cekdulu->Qtyretur;
					$this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->Qtyretur);
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
						$this->db->update("trans_retur_detail",$data,array("NoDokumen"=>$no));
					}
				}
			}
			else
			{
				$cekdulu = $this->retur_barangmodel->cekPast($no,$pcodesave);
//                                print_r($cekdulu);die();
                                //$pcodebarang_dulu = $this->retur_barangmodel->ifPCodeBarcode($pcodesave);
                                if(!empty($pcodesave)){ // jika barang baru
                                    $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->Qtyretur);
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
		$this->updateStok($pcode,$tahun,$qty,$fieldmasuk,$fieldkeluar,$fieldakhir);
                $this->insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl);
		$this->insertMutasi($no,$tgl,$pcode,$ket,$qty,$hrg,$user);
	}
	function updateStok($pcodebarang,$tahun,$qtyretur,$fieldmasuk,$fieldkeluar,$fieldakhir)
	{
		if($qtyretur!=0){
                        $stokawal = $this->retur_barangmodel->CekStock($fieldkeluar,$fieldakhir,$pcodebarang,$tahun);
                            if(!empty($stokawal)){// jika ada di table stock
				$data = array(
					$fieldkeluar => (int)$stokawal->$fieldkeluar + (int)abs($qtyretur),
					$fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyretur)
				);
				$this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                            }
		}
	}
        function insertMutasi($no,$tgl,$pcode,$ket,$qty,$hrg,$user)
	{
		if($qty!=0){
			
				$jenismutasi = "O";// keluar

				$dataekonomis = array(
					'KdTransaksi'   => "RB",//RB = Retur Barang
					'NoTransaksi'   => $no,
					'Tanggal'       => $tgl,
					'KodeBarang'    => $pcode,
					'Qty'           => abs($qty) ,
					'Nilai'         => abs($hrg) ,
					'Jenis'         => $jenismutasi ,
					'Kasir'         =>$user,
					'Keterangan'    =>$ket
				);
				$this->db->insert('mutasi', $dataekonomis);
		}
	}
        function deleteAll($flag,$no,$tgl,$pcode,$pcodebarang,$qtyretur)
	{//echo $tgl;die();
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk  = "QtyMasuk".$bulan;
		$fieldakhir  = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
		if($qtyretur!=0){
                        $stokawal = $this->retur_barangmodel->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyretur),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyretur)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$no,"KodeBarang"=>$pcodebarang));
		}
                if($flag!="del"){
                    if($pcode!=$pcodebarang){
                        $this->db->delete("trans_retur_detail",array("NoDokumen"=>$no,"PCode"=>$pcodebarang));
                    }else{
                        $this->db->delete("trans_retur_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
                    }
                }
	}
        function insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl)
	{
	
		$tgltrans = $this->session->userdata('Tanggal_Trans');
		$this->retur_barangmodel->locktables('trans_retur_detail');
		
//			$detail_ada = $this->retur_barangmodel->cekDetail($pcode,$no);
//			if(count($detail_ada)!=0&&$detail_ada->FlagDelete=="Y"){
//                                $this->db->delete("trans_retur_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
//			}

                            $data = array(
                                    'NoDokumen'     => $no,
                                    'PCode'         => $pcode,
                                    'QtyRetur'      => $qty ,
                                    'HargaBeliAkhir'=> $hrg,
                                    'AddDate'       => $tgl,
                                    'AddUser'       => $user
                            );
                $this->db->insert('trans_retur_detail', $data);
                if($flag=="edit"){
			$data = array(
                                'EditDate'      => $tgltrans,
				'EditUser'      => $user
			);
                    $this->db->update('trans_retur_detail', $data, array('NoDokumen' => $no,'PCode'=>$pcode));
		}
		
		$this->retur_barangmodel->unlocktables();
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
		$this->retur_barangmodel->locktables('trans_retur_detail');
//                no:no,tgl:tgl,pcode:pcode,pcodesave:pcodesave,qty:qty
                $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$qty);
//		$this->db->update('trans_retur_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user), array('NoDokumen' => $id,'PCode'=>$pcode));
		$this->retur_barangmodel->unlocktables();
	}
}
?>