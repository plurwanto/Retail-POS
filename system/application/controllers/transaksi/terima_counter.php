<?php
class terima_counter extends Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/terima_countermodel');
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
			$config['base_url']       = base_url().'index.php/transaksi/terima_counter/index/';
			$page					  = $this->uri->segment(4);
			$config['uri_segment']    = 4;
			$flag1					  = "";
			if($with!=""){
                            if($id!=""&&$with!=""){
                                $config['base_url']     = base_url().'index.php/transaksi/terima_counter/index/'.$with."/".$id."/";
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
				 	$config['base_url']     = base_url().'index.php/transaksi/terima_counter/index/'.$with."/".$id."/";
					$page 					= $this->uri->segment(6);
					$config['uri_segment']  = 6;
				}
			}
		$data['header']		= array("No Dokumen","Tanggal","Counter","Keterangan");
	        $config['total_rows']	= $this->terima_countermodel->num_terima_row(addslashes($id),$with);
	        $data['data']	= $this->terima_countermodel->getkirimList($config['per_page'],$page,addslashes($id),$with);
	        $data['track']  = $mylib->print_track();
                $this->pagination->initialize($config);
	        $this->load->view('transaksi/terima_counter/list_terima_counter', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }

    function add_new(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y"){
			$aplikasi = $this->terima_countermodel->getDate();
//			$data['mperusahaan'] = $this->terima_countermodel->getPerusahaan();
			$data['aplikasi'] = $aplikasi;
			$data['mcounter'] = $this->terima_countermodel->getKontak();
	    	$this->load->view('transaksi/terima_counter/add_terima_counter',$data);
    	}
		else{
			$this->load->view('denied');
		}
    }
	function carikontak()
	{
		$sumber = $this->input->post('sumber');
		$aplikasi = $this->terima_countermodel->getDate();
		if($sumber=="M"||$sumber=="O"){
			if($aplikasi->DefaultContactOrder==""){
				$with = "";
			}
			else
			{
				$with = "where KdTipeContact='".$aplikasi->DefaultContactOrder."'";
			}
		}
		
		$mkontak = $this->terima_countermodel->getKontak($with);
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
		$header = $this->terima_countermodel->getSumber($id);
		$user = $this->session->userdata('userid');
		$tgl2 = $this->session->userdata('Tanggal_Trans');
                $tgl  = $mylib->ubah_tanggal($tgl2);
		$getHeader = $this->terima_countermodel->getHeader($id);
		$getDetail= $this->terima_countermodel->getDetailDel($id);
		$tahun = substr($getHeader->TglDokumen,6,4);
		$lastNo = $this->terima_countermodel->getNewNo($tahun);
		$NoDelete = $id;
		if((int)$lastNo->NoTerima == (int)$NoDelete + 1){
			$this->db->update("setup_no",array("NoTerima"=>$NoDelete[1]),array("Tahun"=>$tahun));
		}
		$this->terima_countermodel->locktables('trans_terima_counter_detail,trans_terima_header');
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
                        $stokawal = $this->terima_countermodel->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyterima),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyterima)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$id,"KodeBarang"=>$pcodebarang));

                        $s++;
                }
		$this->db->delete('trans_terima_counter_detail',array('NoDokumen' => $id."D"));
		$this->db->delete('trans_terima_header',array('NoDokumen' => $id."D"));
		$this->db->update('trans_terima_counter_detail',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->db->update('trans_terima_header',array("FlagDelete" => "Y","DeleteDate"=>$tgl,"DeleteUser"=>$user,"NoDokumen"=>$id."D"), array('NoDokumen' => $id));
		$this->terima_countermodel->unlocktables();

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
		$data['fileName2'] = "terima_counter.sss";
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
		$header	 = $this->terima_countermodel->getHeader($id);
//                print_r($header);
		$data['header']	 = $header;
		$detail	 = $this->terima_countermodel->getDetailForPrint($id);
//                print_r($detail);
//		$data['pt'] = $this->terima_countermodel->getAlmPerusahaan($header->KdPerusahaan);
		$data['judul1'] = array("NoPengiriman","Tanggal Terima","Keterangan");
		$data['niljudul1'] = array($header->NoDokumen,$header->Tanggal,stripslashes($header->Keterangan));
		$data['judul2'] = array("Counter");
		$data['niljudul2'] = array($header->KdCounter." - ".stripslashes($header->Nama));
		$tambahan_judul = "";
		$data['judullap'] = "Terima Barang dari Counter".$tambahan_judul;
		$data['url'] = "terima_counter/printThis/".$id;
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
			$list_detail[] = stripslashes($detail[$m]['NamaLengkap']);
			$list_detail[] = $detail[$m]['QtyPcs'];
			$list_detail[] = "pcs";
			$list_detail[] = $detail[$m]['Harga'];
			$list_detail[] = number_format(($detail[$m]['QtyPcs'] * $detail[$m]['Harga']),'','','.');
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
		 $netto = $detail[$m]['QtyPcs'] * $detail[$m]['Harga'];
		 $sum_netto = $sum_netto + ($netto);
		}
                $data['judul_netto']=array("Total","PPN 10%","Nett ");
                $data['isi_netto']=array(number_format($sum_netto,'',',','.'),number_format(($sum_netto * 0.1),'',',','.'),number_format($sum_netto + ($sum_netto * 0.1),'',',','.'));
		$data['detail'][] = $detail_page;
		$data['max_field_len'] = $max_field_len;
		$data['banyakBarang'] = $counterRow;
		return $data;
	}
    function edit_kirim($id){
     	$mylib = new globallib();
	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y"){
            $id = $this->uri->segment(4);
            $data['header']	 = $this->terima_countermodel->getHeader($id);
            $data['detail']	 = $this->terima_countermodel->getDetail($id);
            $data['mcounter']    = $this->terima_countermodel->getKontak();
           
            $this->load->view('transaksi/terima_counter/e_terima_counter', $data);
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
			$valpcode = $this->terima_countermodel->ifPCodeBarcode($kode);
		}
		if(count($valpcode)!=0)
		{
			$pcode = $valpcode->PCode;
			$detail = $this->terima_countermodel->getPCodeDet($pcode);
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
			$hasil = $this->terima_countermodel->getOrder($order,$perusahaan);
		}
		if($kirim!="")
		{
			$hasil = $this->terima_countermodel->getKirim($kirim,$perusahaan);
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
			$valpcode = $this->terima_countermodel->ifPCodeBarcode($kode);
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

	function save_new(){

//            print_r($_POST);
//            die();
            	$mylib  = new globallib();
		$user   = $this->session->userdata('userid');
		$flag   = $this->input->post('flag');
		$no   = $this->input->post('nodok');
		$tgl    = $this->input->post('tgl');
		$kdcounter = $this->input->post('counter');
		$ket    = trim(strtoupper(addslashes($this->input->post('ket'))));
		$pcode1 = $this->input->post('pcode');
		$qty1   = $this->input->post('qty');
		$hrg1 = $this->input->post('hrg');
		$pcodesave1  = $this->input->post('savepcode');
		if($no=="")
		{
			$counter = "1";
			$no = $this->insertNewHeader($flag,$mylib->ubah_tanggal($tgl),$ket,$kdcounter,$user);
		}
		else
		{
			$counter = $this->updateHeader($flag,$no,$ket,$mylib->ubah_tanggal($tgl),$noorder,$user,$NoPO);
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
                /* update sisa order jika order bukan manual*/
              
		redirect('/transaksi/terima_counter/');
	}
   

        function insertNewHeader($flag,$tgl,$ket,$kdcounter,$user)
	{
		$this->terima_countermodel->locktables('setup_per_perusahaan,trans_terima_counter_header');
		$new_no = $this->terima_countermodel->getNewNo(substr($tgl,0,4));
		$noOK   = $new_no->NoTerimaCounter;
                $kd     = split ("-", $noOK);
                $nextNo =(int)$kd[1];
                $nobaru = $kd[0]."-".($nextNo+1);
		$this->db->update('setup_no', array("NoTerimaCounter"=>$nobaru),array("Tahun"=>substr($tgl,0,4)));
		$data = array(
			'NoDokumen'	=> $noOK,
			'TglDokumen'    => $tgl,
			'KdCounter'     => $kdcounter,
			'Keterangan'    => $ket ,
			'AddDate'       => $tgl,
			'AddUser'       => $user
		);
		$this->db->insert('trans_terima_counter_header', $data);
		$this->terima_countermodel->unlocktables();
		return $noOK;
	}

        function updateHeader($flag,$no,$ket,$tglterima,$sumber,$noorder,$user,$nopo)
	{
		$tgl = $this->session->userdata('Tanggal_Trans');
		$this->terima_countermodel->locktables('trans_terima_header,trans_terima_counter_detail');
		$data = array(
			'Keterangan'    => $ket ,
			'TglTerima'     => $tglterima,
			'SumberOrder'   => $sumber,
			'NoOrder'       => $noorder,
                        'NoPO'          => $nopo,
		);
		if($flag=="edit")
		{
			$data['EditDate'] = $tgl;
			$data['EditUser'] = $user;
			$this->db->update('trans_terima_counter_detail', array('EditDate'=> $tgl,'EditUser'=>$user), array('NoDokumen' => $no));
		}
		$this->db->update('trans_terima_header', $data, array('NoDokumen' => $no));
		$this->terima_countermodel->unlocktables();
	}

        function InsertAllDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$pcodesave,$ket)
	{
//            echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|qty:".$qty;die();
		if($flag=="add")
		{
                        $this->doAll($flag,$no,$pcode,$qty,$hrg,$user,$tgl,$ket,$pcodesave);
		}
		else
		{
			if($pcodesave==$pcode)//jika hanya qty yg berubah
			{
//                    echo "ok";die();
				$cekdulu = $this->terima_countermodel->cekPast($no,$pcode);
//				echo $qty." ".$cekdulu->QtyOpname;die();
				if($qty!=$cekdulu->QtyTerima or $hrg!=$cekdulu->QtyHargaTerima)
				{
//                                echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|".$cekdulu->QtyTerima;
					$this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->QtyTerima);
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
						$this->db->update("trans_terima_counter_detail",$data,array("NoDokumen"=>$no));
					}
				}
			}
			else
			{
				$cekdulu = $this->terima_countermodel->cekPast($no,$pcodesave);
//                                print_r($cekdulu);die();
                                //$pcodebarang_dulu = $this->terima_countermodel->ifPCodeBarcode($pcodesave);
                                if(!empty($pcodesave)){ // jika barang baru
                                    $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$cekdulu->QtyTerima);
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
	function updateStok($pcodebarang,$tahun,$qtyterima,$fieldmasuk,$fieldkeluar,$fieldakhir)
	{
		if($qtyterima!=0){
                        $stokawal = $this->terima_countermodel->CekStock($fieldkeluar,$fieldakhir,$pcodebarang,$tahun);
                            if(!empty($stokawal)){// jika ada di table stock
				$data = array(
					$fieldkeluar => (int)$stokawal->$fieldkeluar + (int)abs($qtyterima),
					$fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyterima)
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
        function insertMutasi($no,$tgl,$pcode,$ket,$qty,$hrg,$user)
	{
             $kd     = split ("-", $no);

		if($qty!=0){
			
				$jenismutasi = "I";

				$dataekonomis = array(
					'KdTransaksi'   => $kd[0],//Terima dari Counter
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
                        $stokawal = $this->terima_countermodel->CekStock($fieldmasuk,$fieldakhir,$pcodebarang,$tahun);
                        $data = array(
                                $fieldmasuk => (int)$stokawal->$fieldmasuk - (int)abs($qtyterima),
                                $fieldakhir => (int)$stokawal->$fieldakhir - (int)abs($qtyterima)
                        );
                        $this->db->update('stock', $data,array("Tahun"=>$tahun,"KodeBarang"=>$pcodebarang));
                        $this->db->delete("mutasi",array("KdTransaksi"=>"T","NoTransaksi"=>$no,"KodeBarang"=>$pcodebarang));
		}
                if($flag!="del"){
                    if($pcode!=$pcodebarang){
                        $this->db->delete("trans_terima_counter_detail",array("NoDokumen"=>$no,"PCode"=>$pcodebarang));
                    }else{
                        $this->db->delete("trans_terima_counter_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
                    }
                }
	}
        function insertDetail($flag,$no,$pcode,$qty,$hrg,$user,$tgl)
	{
	
		$tgltrans = $this->session->userdata('Tanggal_Trans');
		$this->terima_countermodel->locktables('trans_terima_counter_detail');
		
               $data = array(
                                    'NoDokumen'     => $no,
                                    'PCode'         => $pcode,
                                    'QtyPcs'        => $qty ,
                                    'Harga'         => $hrg,
                                    'Netto'         => ($qty * $hrg),
                                    'AddDate'       => $tgl,
                                    'AddUser'       => $user
                            );
                $this->db->insert('trans_terima_counter_detail', $data);
                if($flag=="edit"){
			$data = array(
                                'EditDate'      => $tgltrans,
				'EditUser'      => $user
			);
                    $this->db->update('trans_terima_counter_detail', $data, array('NoDokumen' => $no,'PCode'=>$pcode));
		}
		
		$this->terima_countermodel->unlocktables();
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
		$this->terima_countermodel->locktables('trans_terima_counter_detail');
                $this->deleteAll($flag,$no,$tgl,$pcode,$pcodesave,$qty);
		$this->terima_countermodel->unlocktables();
	}

}
?>