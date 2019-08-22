<?php
class return_void extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('transaksi/void_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);
            $tanggal = $this->void_model->getDate();
            $tahun = substr($tanggal->TglTrans2, 0, 4);
            $data['tanggal'] = $tanggal->TglTrans3;
            $data['data'] = $this->void_model->getList($tahun);
            $data['track'] = $mylib->print_track();
            $data['content'] = 'transaksi/void/list';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_edit_void() {
//            echo "<pre>";print_r($_POST);echo "</pre>";
        $mylib = new globallib();
        $user = $this->session->userdata('userid');
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $flag = $this->input->post('flag');
        $pcode1 = $this->input->post('pcode');
        $qty1 = $this->input->post('qty');
        $hrg1 = $this->input->post('harga');
        $nil1 = $this->input->post('netto');


        $this->updateHeader($flag, $no, $ket, $user);
        //hapus detail lama dan update stock
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $this->deleteAll($no, $tahun, $fieldmasuk, $fieldkeluar, $fieldakhir); //update dan delete
        for ($x = 0; $x < count($pcode1); $x++) {
            if ($pcode1[$x] != "") {
                $pcode = strtoupper(addslashes($pcode1[$x]));
                $qty = trim($qty1[$x]);
                $hrg = trim($hrg1[$x]);
                $nil = trim($nil1[$x]);
                $this->InsertAllDetail($no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket);
            }
        }
        $this->void_model->update_total_beli($no);
        redirect('/transaksi/return_void/');
    }

    function deleteAll($no, $tahun, $fieldmasuk, $fieldkeluar, $fieldakhir) {
//            print $fieldmasuk."/".$fieldakhir."/".$pcode."/".$tahun."/".$fieldkeluar;
//           echo $tipe.$qty;
        $this->void_model->locktables('stock,mutasi,transaksi_detail');

        $lama = $this->void_model->getdatalama($no);
//                print_r($lama);
//                echo count($lama);
        $a = 1;
        for ($x = 0; $x < count($lama); $x++) {
//                    echo $x;die();
            $pcode = $lama[$x]['PCode'];
            $qty = $lama[$x]['Qty'];
            $kdtransaksi = "R";
            $stokawal = $this->void_model->CekKeluar($fieldkeluar, $fieldakhir, $pcode, $tahun);
//                    print_r($stokawal);
//                    echo $stokawal[0][$fieldkeluar];
            $data = array(
                $fieldkeluar => (int) $stokawal[0][$fieldkeluar] - (int) $qty,
                $fieldakhir => (int) $stokawal[0][$fieldakhir] + (int) $qty
            );
//                die();
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
            $this->db->delete('transaksi_detail', array("NoStruk" => $no, "PCode" => $pcode));
            $this->db->delete('mutasi', array("KdTransaksi" => $kdtransaksi, "NoTransaksi" => $no, "KodeBarang" => $pcode));
            $a = $a + 1;
        }
        $this->void_model->unlocktables();
    }

    function edit_void() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['header'] = $this->void_model->getHeader($id);
            $data['detail'] = $this->void_model->getDetail($id);
//                        print_r($data['detail']);
            $data['ket'] = "";
            $data['session_tgl'] = $this->session->userdata('Tanggal_Trans');
            $this->load->view('transaksi/void/editvoid', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function updateHeader($flag, $no, $ket, $user) {
        $this->void_model->locktables('trans_lainlain_header');
//		$tgltrans = $this->session->userdata('Tanggal_Trans');
        $adddateapl = date('Y-m-d H:i:s');
        $data = array(
            'Keterangan' => $ket,
            'EditDate' => $adddateapl,
            'EditUser' => $user
        );
        $this->db->update('transaksi_header', $data, array("NoStruk" => $no));
        $this->void_model->unlocktables();
    }

    function InsertAllDetail($no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket) { //echo $qty."/".$qtypcs."/".$qtydisplay."/".$flag."/".$pcodesave."=".$pcode;
        /* insert to mutasi
         * Update/insert stock
         * insert to transaksi_detail
         */
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $adddateapl = date('Y-m-d H:i:s');
        $adddatestok = $tgl . " " . date('H:i:s');

        $this->updateStokKeluar($pcode, $qty, $fieldkeluar, $fieldakhir, $tahun);
        $this->insertTransDetail($no, $pcode, $qty, $hrg, $nil, $user, $tgl, $adddateapl, $adddatestok);
        $this->insertMutasi($no, $pcode, $qty, $hrg, $user, $tgl, $ket);
    }

    function insertTransDetail($no, $pcode, $qty, $hrg, $nil, $user, $tgl, $adddateapl, $adddatestok) {//print "detailnya";
        $this->void_model->locktables('transaksi_detail');
        $mylib = new globallib();
        $nm = $mylib->getUser();
        $ip = $_SERVER['REMOTE_ADDR'];
        $kassa = $mylib->detailkassa($ip);
//                print_r($kassa);
        $data = array(
            'NoKassa' => $kassa->id_kassa,
            'NoStruk' => $no,
            'PCode' => $pcode,
            'Qty' => $qty,
            'Harga' => $hrg,
            'Netto' => $nil,
            'Status' => 1,
            'Tanggal' => $tgl,
            'Waktu' => date('H:i:s'),
            'Kasir' => $nm
        );

        $this->db->insert('transaksi_detail', $data);
        $this->void_model->unlocktables();
    }

    function insertMutasi($no, $pcode, $qty, $hrg, $user, $tgl, $ket) {
        $this->void_model->locktables('mutasi');
        $jenismutasi = "O";
        $kodetransaksi = "R";
        $dataekonomis = array(
            'KdTransaksi' => $kodetransaksi,
            'NoTransaksi' => $no,
            'Tanggal' => $tgl,
            'KodeBarang' => $pcode,
            'Qty' => $qty,
            'Nilai' => $hrg,
            'Jenis' => $jenismutasi,
            'Keterangan' => $ket
        );
        $this->db->insert('mutasi', $dataekonomis);
        $this->void_model->unlocktables();
    }

    function updateStokKeluar($pcode, $qty, $fieldkeluar, $fieldakhir, $tahun) {
        $this->void_model->locktables('stock');
        $stokawal = $this->void_model->CekKeluar($fieldkeluar, $fieldakhir, $pcode, $tahun);
        $data = array(
            $fieldkeluar => (int) $stokawal[0][$fieldkeluar] + (int) $qty,
            $fieldakhir => (int) $stokawal[0][$fieldakhir] - (int) $qty
        );
        $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
        $this->void_model->unlocktables();
    }

    function ajax_edit($id) {
        $dataHeader = $this->void_model->getHeader3($id);
        echo json_encode($dataHeader);
    }

    function ajax_update() {
        $this->void_model->locktables('stock,mutasi');
        $no = $this->input->post('kode');
        $ket = $this->input->post('ket');
     
        $lama = $this->void_model->getdatalama($no);
        for ($x = 0; $x < count($lama); $x++) {
            $pcode = $lama[$x]['PCode'];
            $qty = $lama[$x]['Qty'];

            $mylib = new globallib();
            $header = $this->void_model->getHeader($no);
            $tgl = $mylib->ubah_tanggal($header->TglDokumen);
            $bulan = substr($tgl, 5, 2);
            $tahun = substr($tgl, 0, 4);
            $fieldmasuk = "QtyMasuk" . $bulan;
            $fieldakhir = "QtyAkhir" . $bulan;
            $fieldkeluar = "QtyKeluar" . $bulan;

            $stokawal = $this->void_model->StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun);
            $data = array(
                $fieldkeluar => (int) $stokawal->$fieldkeluar - (int) $qty,
                $fieldakhir => (int) $stokawal->$fieldakhir + (int) $qty
            );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                $this->db->delete('mutasi', array("KdTransaksi" => 'R', "NoTransaksi" => $no, "KodeBarang" => $pcode));
        }
        /* Udate status 2 untuk delete           */
        $data = array('Status' => 2,
            'Keterangan' => $ket
        );
        $this->db->update('transaksi_header', $data, array("NoStruk" => $no));
        $this->db->update('transaksi_detail', $data, array("NoStruk" => $no));
        $this->db->update('transaksi_detail_bayar', $data, array("NoStruk" => $no));
        $this->void_model->unlocktables();
        echo json_encode(array("status" => TRUE));
    }

    function cetak() {
        $data = $this->varCetak();
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_struk', $data);
    }

    function versistruk() {
        $data = $this->varCetak();
        $no = $this->uri->segment(4);
        $printer = $this->void_model->NamaPrinter($_SERVER['REMOTE_ADDR']);

        $data['ip'] = $printer[0]['ip'];
        $data['nm_printer'] = $printer[0]['nm_printer'];
        $data['store'] = $this->void_model->aplikasi();
        $data['header'] = $this->void_model->getHeader2($no);
        $data['detail'] = $this->void_model->getDetailForPrint($no);

        if (!empty($data['header'])) {
            //	$this->load->view('proses/cetak_tutup',$data); // jika untuk tes
            $this->load->view('transaksi/void/cetak_transaksi', $data); // jika ada printernya
        }
    }

    function printThis() {
        $data = $this->varCetak();
        $id = $this->uri->segment(4);
        $data['fileName2'] = "sales.sss";
        $data['fontstyle'] = chr(27) . chr(80);
        $data['nfontstyle'] = "";
        $data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['string1'] = "     Dibuat Oleh,                    Disetujui Oleh,";
        $data['string2'] = "(                     )         (                      )";
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer', $data);
    }

    function varCetak() {
        $this->load->library('printreportlib');
        $printreport = new printreportlib();
        $id = $this->uri->segment(4);
        $header = $this->void_model->getHeader($id);
        $data['header'] = $header;
        $detail = $this->void_model->getDetailForPrint($id);
        $data['judul1'] = array("NoStruk", "Kasir");
        $data['niljudul1'] = array($header->NoStruk, $header->Kasir);
        $data['judul2'] = array("TglTransaksi");
        $data['niljudul2'] = array($header->TglDokumen);
        $data['judullap'] = "Transaksi Penjualan";
        $data ['url2'] = "return_void/versistruk/" . $id;
        $data ['colspan_line'] = 4;
        $data['tipe_judul_detail'] = array("normal", "normal", "kanan", "kanan", "kanan", "kanan");
        $data['judul_detail'] = array("Kode", "Nama Barang", "Qty", "Harga", "Diskon", "Netto");
        $data['panjang_kertas'] = 33;
        $jmlh_baris_lain = 21;
        $data['panjang_per_hal'] = (int) $data['panjang_kertas'] - (int) $jmlh_baris_lain;
        $jml_baris_detail = count($detail) + $this->void_model->getCountDetail($id);
        if ($data['panjang_per_hal'] != 0) {
            $data['tot_hal'] = ceil((int) count($detail) / (int) $data['panjang_per_hal']);
        } else {
            $data['tot_hal'] = 1;
        }
        $list_detail = array();
        $detail_page = array();
        $counterRow = 0;
        $bayar = 0;
        $max_field_len = array(0, 0, 0, 0, 0, 0);
        for ($m = 0; $m < count($detail); $m++) {

            $net = ($detail[$m]['Netto']);
            $bayar = $bayar + $net;
            unset($list_detail);
            $counterRow++;
            $list_detail[] = stripslashes($detail[$m]['PCode']);
            $list_detail[] = stripslashes($detail[$m]['NamaStruk']);
            $list_detail[] = $detail[$m]['Qty'];
            $list_detail[] = $detail[$m]['Harga'];
            $list_detail[] = $detail[$m]['Disc1'];
            $list_detail[] = $detail[$m]['Netto'];
            $detail_page[] = $list_detail;
            $max_field_len = $printreport->get_max_field_len($max_field_len, $list_detail);
//                        echo $data['panjang_per_hal'];die();
            if ($data['panjang_per_hal'] != 0) {
                if (((int) $m + 1) % $data['panjang_per_hal'] == 0) {
                    $data['detail'][] = $detail_page;
                    if ($m != count($detail) - 1) {
                        unset($detail_page);
                    }
                }
            }
        }
//                print_r($list_detail);
        $data['judul_netto'] = array("Total");
        $data['isi_netto'] = array(number_format($bayar, 0, ',', '.'));
        $data['detail'][] = $detail_page;
        $data['max_field_len'] = $max_field_len;
        $data['banyakBarang'] = $counterRow;
        $data['bayar'] = $bayar;
        return $data;
    }

}

?>