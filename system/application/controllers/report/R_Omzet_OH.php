    <?php
class R_Omzet_OH extends Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/Omzet_all_model');
    }

    function index(){
//        print_r($_POST);
        //print_r($_GET);
//        die();
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
	{
            $segs 		  = $this->uri->segment_array();
            $arr 		  = "index.php/".$segs[1]."/".$segs[2]."/";
            $data['link']         = $mylib->restrictLink($arr);
            $id 		  = $this->input->post('stSearchingKey');
            $tglnya 		  = $this->input->post('tglawal');
            $bulan = substr($tglnya,3,2);
            $tahun = substr($tglnya,6,4);//die();

	        $config['full_tag_open']  = '<div class="pagination">';
	        $config['full_tag_close'] = '</div>';
	        $config['cur_tag_open']   = '<span class="current">';
	        $config['cur_tag_close']  = '</span>';
	        $config['per_page']       = '14';
	        $config['first_link'] 	  = 'First';
	        $config['last_link'] 	  = 'Last';
	        $config['num_links']  	  = 2;
			$config['base_url']       = base_url().'index.php/transaksi/penerimaan_barang/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$flag1					  = "";
			
               $aplikasi = $this->Omzet_all_model->getDate();

               $ap =$mylib->ubah_tanggal($aplikasi->TglTrans);
               $dateskrg = date("Y-m-d");//echo"||";
               if($ap!=$dateskrg){
                    $data['ubahuser'] = $ap ;
                }
                else {
                    $data['ubahuser'] = $ap ;
                }
//         ech      $selisihtgl = $ap - $dateskrg;
//                die();
		$data['header']		= array("Kode Counter","Nama Counter","ITM","STD","SPD","DSC","VOU","NET","APC","Last trans");
	    $data['data']	= $this->Omzet_all_model->getterimaList($bulan,$tahun);
	        $data['track']  = $mylib->print_track();
            $data['baseurl']= base_url();

	        $this->load->view('report/omzet_all/view_omzet_all', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }

    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
			$aplikasi = $this->Omzet_all_model->getDate();
//			$data['mperusahaan'] = $this->Omzet_all_model->getPerusahaan();
			$data['aplikasi'] = $aplikasi;
			$data['mkontak'] = $this->Omzet_all_model->getKontak();
	    	$this->load->view('transaksi/terima_barang/add_terima_barang',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
	function carikontak()
	{
		$sumber = $this->input->post('sumber');
		$aplikasi = $this->Omzet_all_model->getDate();
		if($sumber=="M"||$sumber=="O"){
			if($aplikasi->DefaultContactOrder==""){
				$with = "";
			}
			else
			{
				$with = "where KdTipeContact='".$aplikasi->DefaultContactOrder."'";
			}
		}
		
		$mkontak = $this->Omzet_all_model->getKontak($with);
		$str = "";
		for($m=0;$m<count($mkontak);$m++)
		{
			$str .= "<option value='".$mkontak[$m]['KdContact']."'>".$mkontak[$m]['Nama']."</option>";
		}
		echo $str;
	}

    function delete_penerimaan(){
            $mylib = new globallib();
                $id = $this->input->post('kode');
		$header = $this->Omzet_all_model->getSumber($id);
		$user = $this->session->userdata('userid');
		$tgl2 = $this->session->userdata('Tanggal_Trans');
                $tgl  = $mylib->ubah_tanggal($tgl2);
		$getHeader = $this->Omzet_all_model->getHeader($id);
		$getDetail= $this->Omzet_all_model->getDetailDel($id);
		$tahun = substr($getHeader->TglDokumen,6,4);
		$lastNo = $this->Omzet_all_model->getNewNo($tahun);
		$NoDelete = $id;
		if((int)$lastNo->NoTerima == (int)$NoDelete + 1){
			$this->db->update("setup_no",array("NoTerima"=>$NoDelete[1]),array("Tahun"=>$tahun));
		}
		$this->Omzet_all_model->locktables('trans_terima_detail,trans_terima_header');
		if($header->SumberOrder=="O")
		{
			$this->db->update('trans_order_barang_header', array("FlagPenerimaan"=>"T"),array('NoDokumen'=>$header->NoOrder));
			$this->db->update('trans_order_barang_detail', array("FlagPenerimaan"=>"T"),array('NoDokumen'=>$header->NoOrder));
		}
                $bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk  = "QtyMasuk".$bulan;
		$fieldakhir  = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;

                for($s=0;$s<count($getDetail);){

                        $pcodebarang  = $getDetail[$s]['PCode'];
                        $qtyterima    = $getDetail[$s]['QtyTerima'];
                        $stokawal = $this->Omzet_all_model->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyterima),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyterima)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$id,"KodeBarang"=>$pcodebarang));

                        $s++;
                }
		$this->db->delete('trans_terima_detail',array('NoDokumen' => $id."D"));
		$this->db->delete('trans_terima_header',array('NoDokumen' => $id."D"));
		$this->db->update('trans_terima_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->db->update('trans_terima_header',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->Omzet_all_model->unlocktables();

	}
	function cetak()
	{
		$data = $this->varCetak();
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_terima', $data);
	}
	function printThis()
	{
		$data = $this->varCetak();
		$id = $this->uri->segment(4);
		$data['fileName2'] = "terima_barang.sss";
		$data['fontstyle'] = chr(27).chr(80);
		$data['nfontstyle'] = "";
                $data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);
		$data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n".chr(27).chr(48)."\r\n".chr(27).chr(50);
		$data['string1'] = "     Dibuat Oleh,                    Disetujui Oleh,";
		$data['string2'] = "(                     )         (                      )";
		$this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer_so', $data);// jika mau di ubah pastikan cetakan yg lain sama
	}
	function varCetak()
	{
		$this->load->library('printreportlib');
		$mylib = new printreportlib();
		$id = $this->uri->segment(4);
		$header	 = $this->Omzet_all_model->getHeader($id);
//                print_r($header);
		$data['header']	 = $header;
		$detail	 = $this->Omzet_all_model->getDetailForPrint($id);
//		$data['pt'] = $this->Omzet_all_model->getAlmPerusahaan($header->KdPerusahaan);
		$data['judul1'] = array("NoPenerimaan","Tanggal Terima","Keterangan");
		$data['niljudul1'] = array($header->NoDokumen,$header->TglTerima,stripslashes($header->Keterangan));
		$data['judul2'] = array("No Order","Supplier");
		$data['niljudul2'] = array(stripslashes($header->NoOrder),$header->KdSupplier." - ".stripslashes($header->supplier));
		$tambahan_judul = "";
		$data['judullap'] = "PENERIMAAN BARANG".$tambahan_judul;
		$data['url'] = "penerimaan_barang/printThis/".$id;
		$data['colspan_line'] = 4;
		$data['tipe_judul_detail'] = array("normal","normal","kanan","normal","kanan","kanan");
		$data['judul_detail'] = array("Kode","Nama Barang","Qty","","Harga ","Total");
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
                $sum_netto  = 0;
//                print_r($detail);
		for($m=0;$m<count($detail);$m++)
		{
//			$hasil = $mylib->findSatuanQtyCetak($detail[$m]['QtyTerima'],$detail[$m]['KonversiBesarKecil'],$detail[$m]['KonversiTengahKecil']);
			unset($list_detail);
			$counterRow++;
			$list_detail[] = stripslashes($detail[$m]['PCode']);
			$list_detail[] = stripslashes($detail[$m]['NamaInitial']);
			$list_detail[] = $detail[$m]['QtyTerima'];
			$list_detail[] = "pcs";
			$list_detail[] = number_format($detail[$m]['QtyHargaTerima'],'','','.');
			$list_detail[] = number_format(($detail[$m]['QtyTerima'] * $detail[$m]['QtyHargaTerima']),'','','.');
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
		 $netto = $detail[$m]['QtyTerima'] * $detail[$m]['QtyHargaTerima'];
		 $sum_netto = $sum_netto + ($netto);
		}
                $data['judul_netto']=array("Total","PPN 10%","Nett ");
                $data['isi_netto']=array(number_format($sum_netto,'',',','.'),number_format(($sum_netto * 0.1),'',',','.'),number_format($sum_netto + ($sum_netto * 0.1),'',',','.'));
		$data['detail'][] = $detail_page;
		$data['max_field_len'] = $max_field_len;
		$data['banyakBarang'] = $counterRow;
		return $data;
	}
    function edit_penerimaan($id){
     	$mylib = new globallib();
	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
            $id = $this->uri->segment(4);
            $data['header']	 = $this->Omzet_all_model->getHeader($id);
            $data['detail']	 = $this->Omzet_all_model->getDetail($id);
           
            $this->load->view('transaksi/terima_barang/edit_terima_barang', $data);
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
			$valpcode = $this->Omzet_all_model->ifPCodeBarcode($kode);
		}
		if(count($valpcode)!=0)
		{
			$pcode = $valpcode->PCode;
			$detail = $this->Omzet_all_model->getPCodeDet($pcode);
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
			$hasil = $this->Omzet_all_model->getOrder($order,$perusahaan);
		}
		if($kirim!="")
		{
			$hasil = $this->Omzet_all_model->getKirim($kirim,$perusahaan);
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
			$valpcode = $this->Omzet_all_model->ifPCodeBarcode($kode);
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

	function save_new_terima(){

//            print_r($_POST);
//            die();
            	$mylib  = new globallib();
		$sbr = $this->input->post('sumber');
		$tglterima  = $this->input->post('tglterima');
		$user   = $this->session->userdata('userid');
		$flag   = $this->input->post('flag');
		$no     = $this->input->post('nodok');
		$NoPO   = $this->input->post('nopo');
		$gudang = $this->input->post('gudang');
		$tgl    = $this->input->post('tgl');
		$kontak = $this->input->post('supplier');
		$noorder= trim(strtoupper(addslashes($this->input->post('noorder'))));
		$ket    = trim(strtoupper(addslashes($this->input->post('ket'))));
		$pcode1 = $this->input->post('pcode');
		$qty1   = $this->input->post('qty');
		$hrg1 = $this->input->post('hrg');
		$pcodesave1  = $this->input->post('savepcode');
		if($no=="")
		{
			$counter = "1";
			$no = $this->insertNewHeader($flag,$mylib->ubah_tanggal($tgl),$ket,$mylib->ubah_tanggal($tglterima),$sbr,$noorder,$user,$kontak,$NoPO,$gudang);
		}
		else
		{
			$counter = $this->updateHeader($flag,$no,$ket,$mylib->ubah_tanggal($tglterima),$sbr,$noorder,$user,$NoPO);
		}
		for($x=0;$x<count($pcode1);$x++)
		{
			$pcode = strtoupper(addslashes(trim($pcode1[$x])));
			$qty = trim($qty1[$x]);
			$hrg = trim($hrg1[$x]);
			$pcodesave = $pcodesave1[$x];
			if($pcode!=""){
				$this->InsertAllDetail($flag,$no,$pcode,$qty,$hrg,$user,$mylib->ubah_tanggal($tgl),$pcodesave,$ket,$gudang);
			}
		}
                /* update sisa order jika order bukan manual*/
                if($sbr !="M"){
                    $this->updateSisaOrder($no,$noorder);
                }
		redirect('/transaksi/penerimaan_barang/');
	}

        function insertNewHeader($flag,$tgl,$ket,$tglterima,$sumber,$noorder,$user,$kontak,$NoPO,$gudang)
	{
		$this->Omzet_all_model->locktables('setup_per_perusahaan,trans_terima_header');
		$new_no = $this->Omzet_all_model->getNewNo(substr($tgl,0,4));
		$no = $new_no->NoTerima;
		$this->db->update('setup_no', array("NoTerima"=>(int)$new_no->NoTerima+1),array("Tahun"=>substr($tgl,0,4)));
		$data = array(
                        'Gudang'        => $gudang,
			'NoDokumen'	=> $no,
			'TglDokumen'    => $tgl,
			'TglTerima'     => $tglterima,
			'NoOrder'       => $noorder,
			'SumberOrder'   => $sumber,
			'NoPO'          => $NoPO,
			'Keterangan'    => $ket ,
			'KdSupplier'    => $kontak,
			'AddDate'       => $tgl,
			'AddUser'       => $user
		);
		$this->db->insert('trans_terima_header', $data);
		$this->Omzet_all_model->unlocktables();
		return $no;
	}

        function updateHeader($flag,$no,$ket,$tglterima,$sumber,$noorder,$user,$nopo)
	{
		$tgl = $this->session->userdata('Tanggal_Trans');
		$this->Omzet_all_model->locktables('trans_terima_header,trans_terima_detail');
		$data = array(
			'Keterangan'    => $ket ,
			'TglTerima'     => $tglterima,
//			'SumberOrder'   => $sumber,
			'NoOrder'       => $noorder,
                        'NoPO'          => $nopo,
		);
//		if($flag=="edit")
//		{
//			$data['EditDate'] = $tgl;
//			$data['EditUser'] = $user;
//			$this->db->update('trans_terima_detail', array('EditDate'=> $tgl,'EditUser'=>$user), array('NoDokumen' => $no));
//		}
		$this->db->update('trans_terima_header', $data, array('NoDokumen' => $no));
		$this->Omzet_all_model->unlocktables();
	}

        function InsertAllDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$pcodesave,$ket,$gudang)
	{
            //echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|qty:".$qty; //die();
		if($flag=="add")
		{
                        $this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave,$gudang);
		}
		else
		{
			if($pcodesave==$pcode)//jika hanya qty yg berubah
			{
//                    echo "ok";die();
				$cekdulu = $this->Omzet_all_model->cekPast($no,$pcode);
//				echo $qty." ".$cekdulu->QtyOpname;die();
				if($qty!=$cekdulu->QtyTerima or $hrg!=$cekdulu->QtyHargaTerima)
				{
//                                echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|".$cekdulu->QtyTerima;
					$this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->QtyTerima);
					$this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave,$gudang);
				}
				else
				{
					if($flag=="edit")
					{
                                            $tgltrans = $this->session->userdata('Tanggal_Trans');
						$data = array(
							'EditDate' => $tgl,
							'EditUser' => $user
						);
						$this->db->update("trans_terima_detail",$data,array("NoDokumen"=>$no));
					}
				}
			}
			else
			{
				$cekdulu = $this->Omzet_all_model->cekPast($no,$pcodesave);
//                                print_r($cekdulu);die();
                                //$pcodebarang_dulu = $this->Omzet_all_model->ifPCodeBarcode($pcodesave);
                                if(!empty($pcodesave)){ // jika barang baru
                                    $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->QtyTerima);
                                }
				$this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave,$gudang);
			}
		}
	}
                    
        function doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave,$gudang)
	{
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk = "QtyMasuk".$bulan;
		$fieldakhir = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
		$this->updateStok($pcode,$tahun,$qty,$fieldmasuk,$fieldkeluar,$fieldakhir);
                $this->insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$gudang);
		$this->insertMutasi($no,$tgl,$pcode,$ket,$qty,$hrg,$user,$gudang);
	}
	function updateStok($pcodebarang,$tahun,$qtyterima,$fieldmasuk,$fieldkeluar,$fieldakhir)
	{
		if($qtyterima!=0){
                        $stokawal = $this->Omzet_all_model->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                            if(!empty($stokawal)){// jika ada di table stock
				$data = array(
					$fieldmasuk => (int)$stokawal->$fieldmasuk + (int)abs($qtyterima),
					$fieldakhir => (int)$stokawal->$fieldakhir + (int)abs($qtyterima)
				);
				$this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                            }else{
                                $dat = array(
                                        'Tahun'     => $tahun,
                                        'Gudang'    => '00',
                                        'KodeBarang'=> $pcodebarang,
                                        $fieldmasuk => $qtyterima,
                                        $fieldakhir => $qtyterima
                                );
                                $this->db->insert('stock',$dat);
                            }
		}
	}
        
        function insertMutasi($no,$tgl,$pcode,$ket,$qty,$hrg,$user,$gudang)
	{
		if($qty!=0){
			
				$jenismutasi = "I";

				$dataekonomis = array(
                                        'Gudang'        => $gudang,
					'KdTransaksi'   => "T",//Terima
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
        function deleteAll($flag,$no,$tgl,$pcode,$pcodebarang,$qtyterima)
	{//echo $tgl;die();
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		$fieldmasuk  = "QtyMasuk".$bulan;
		$fieldakhir  = "QtyAkhir".$bulan;
		$fieldkeluar = "QtyKeluar".$bulan;
		if($qtyterima!=0){
                        $stokawal = $this->Omzet_all_model->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyterima),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyterima)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$no,"KodeBarang"=>$pcodebarang));
		}
                if($flag!="del"){
                    if($pcode!=$pcodebarang){
                        $this->db->delete("trans_terima_detail",array("NoDokumen"=>$no,"PCode"=>$pcodebarang));
                    }else{
                        $this->db->delete("trans_terima_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
                    }
                }
	}
        function insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$gudang)
	{
	
		$tgltrans = $this->session->userdata('Tanggal_Trans');
		$this->Omzet_all_model->locktables('trans_terima_detail');
		

                            $data = array(
                                    'Gudang'        => $gudang,
                                    'NoDokumen'     => $no,
                                    'PCode'         => $pcode,
                                    'QtyTerima'     => $qty ,
                                    'QtyHargaTerima'=> $hrg,
                                    'AddDate'       => $tgl,
                                    'AddUser'       => $user
                            );
                $this->db->insert('trans_terima_detail', $data);
                if($flag=="edit"){
			$data = array(
                                'EditDate'      => $tgl,
				'EditUser'      => $user
			);
                    $this->db->update('trans_terima_detail', $data, array('NoDokumen' => $no,'PCode'=>$pcode));
		}
		
		$this->Omzet_all_model->unlocktables();
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
		$this->Omzet_all_model->locktables('trans_terima_detail');
                $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$qty);
		$this->Omzet_all_model->unlocktables();
	}

        function updateSisaOrder($no,$noorder){
            $det = $this->globalmodel->getQuery("SELECT PCode,QtyTerima FROM trans_terima_detail WHERE NoDokumen='$no' order by PCode");
//            echo "<pre>".print_r($det)."</pre><br>";
//            echo $det[0]['PCode'];
            for ($a=0;$a<count($det);$a++){
                //update detail order
                    $qakhir = $this->globalmodel->getField("select QtyKonfTerima from trans_order_detail where NoDokumen = '".$noorder."' and PCode = '".$det[$a]['PCode']."'");
                    $par   ="trans_order_detail";
                    $data =array(
                            'QtyKonfTerima' => $qakhir->QtyKonfTerima + $det[$a]['QtyTerima']
                        );
//                        echo $a;
                    $where = "NoDokumen = '".$noorder."' and PCode = '".$det[$a]['PCode']."'";
                    $this->globalmodel->editData($par,$data,$where);
//                    echo $det[$a]['PCode'];
                }
             $ceksisa = $this->globalmodel->getQuery("SELECT SUM(QtyOrder-QtyKonfTerima)as hasil FROM trans_order_detail WHERE NoDokumen='$noorder'");
//             die();
             if ($ceksisa[0]['hasil']==0){
                 $up =  array('FlagKonfirmasi'=>'1');
                 $this->db->update('trans_order_header',$up , array('NoDokumen' => $noorder));
             }
        }

}
?>