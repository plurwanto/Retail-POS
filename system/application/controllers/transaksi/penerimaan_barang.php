<?php
ob_start();
class penerimaan_barang extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/terima_barangmodel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);

            $aplikasi = $this->terima_barangmodel->getDate();

            $ap = $mylib->ubah_tanggal($aplikasi->TglTrans);
            $dateskrg = date("Y-m-d"); //echo"||";
            if ($ap != $dateskrg) {
                $data['ubahuser'] = $ap;
            } else {
                $data['ubahuser'] = $ap;
            }
            $data['data'] = $this->terima_barangmodel->getterimaList();
            $data['track'] = $mylib->print_track();
            $data['content'] = 'transaksi/terima_barang/terima_baranglist';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function ajax_list($no) {
        $list = $this->terima_barangmodel->get_datatables($no);
        $data = array();
        foreach ($list as $value) {
            $row = array();
            $row['NoDokumen'] = $value['NoDokumen'];
            $row['PCode'] = $value['PCode'];
            $row['NamaLengkap'] = $value['NamaLengkap'];
            $row['QtyTerima'] = $value['QtyTerima'];
            $row['QtyHargaTerima'] = $value['QtyHargaTerima'];
            $data[] = $row;
        }
        echo json_encode($data);
    }

    function ajax_add() {
        $mylib = new globallib();
        $user = $this->session->userdata('userid');
        $aplikasi = $this->terima_barangmodel->getDate();
        $gudang = $aplikasi->KdGU;
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $tglterima = $mylib->ubah_tanggal($this->input->post('tglterima'));
        $sumberorder = $this->input->post('sumber');
        $noorder = $this->input->post('noorder');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $supp = $this->input->post('supplier');
        $nopo = $this->input->post('nopo');
        $pcode0 = $this->input->post('itemNo_0');
        $pcodesave0 = $this->input->post('pcodesave');
        $harga0 = $this->input->post('harga_0');
        $qty0 = $this->input->post('qty_0');
        $total0 = $this->input->post('total_0');

        //$flag = $this->input->post('flag');
        $this->_validate();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            if ($no == "") { //proses insert di awal baris pertama dan nodokume masih kosong
                $new_no = $this->terima_barangmodel->getNewNo(substr($tgl, 0, 4));
                $no = $new_no->NoTerima;
                $this->db->update('setup_no', array("NoTerima" => (int) $no + 1), array("Tahun" => substr($tgl, 0, 4)));

                $data_header = array(
                    'Gudang' => $gudang,
                    'NoDokumen' => $no,
                    'TglDokumen' => $tgl,
                    'TglTerima' => $tglterima,
                    'SumberOrder' => $sumberorder,
                    'NoOrder' => $noorder,
                    'Keterangan' => $ket,
                    'KdSupplier' => $supp,
                    'NoPO' => $nopo,
                    'AddDate' => date('Y-m-d'),
                    'AddUser' => $user
                );
                $data_detail = array(
                    'Gudang' => $gudang,
                    'NoDokumen' => $no,
                    'PCode' => $pcode0,
                    'QtyTerima' => $qty0,
                    'QtyHargaTerima' => $harga0,
                    'AddDate' => date('Y-m-d H:i:s'),
                    'AddUser' => $user
                );
                $this->db->insert('trans_terima_header', $data_header);
                $this->db->insert('trans_terima_detail', $data_detail);
            } else {  //proses insert di baris kedua dan nodokumen sudah ada
                $query = $this->terima_barangmodel->get_detail_by_id($no, $pcode0);
                if ($query->num_rows() > 0) {
                    $arr_val['messages'] = array('<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                        . '<i class="glyphicon glyphicon-ok"></i>Kode Barang tersebut sudah pernah di input</div>');
                    $this->db->update('trans_terima_detail', array('QtyTerima' => $qty0, 'QtyHargaTerima' => $harga0, 'EditDate' => date('Y-m-d H:i:s'), 'EditUser' => $user), array('NoDokumen' => $no, 'PCode' => $pcode0));
                } else {
                    $data_detail = array(
                        'Gudang' => $gudang,
                        'NoDokumen' => $no,
                        'PCode' => $pcode0,
                        'QtyTerima' => $qty0,
                        'QtyHargaTerima' => $harga0,
                        'AddDate' => date('Y-m-d H:i:s'),
                        'AddUser' => $user
                    );
                    $this->db->insert('trans_terima_detail', $data_detail);
                }
            }
            $arr_val['nodokumen'] = $no;
            $arr_val['success'] = true;
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($arr_val);
    }

    function ajax_delete($no, $pcode) {
        $this->terima_barangmodel->delete_by_id($no, $pcode);
        echo json_encode(array('success' => true));
    }

    function ajax_delete_transaksi($no) {
        $user = $this->session->userdata('userid');
        $tgl = date('Y-m-d');
        $this->db->update('trans_terima_detail', array("FlagDelete" => "Y", "DeleteDate" => $tgl, "DeleteUser" => $user), array('NoDokumen' => $no));
        $this->db->update('trans_terima_header', array("FlagDelete" => "Y", "DeleteDate" => $tgl, "DeleteUser" => $user), array('NoDokumen' => $no));
        echo json_encode(array('success' => true));
    }

    function _validate() {
        $this->form_validation->set_rules('nopo', 'nopo', 'trim|required');
        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_rules('itemNo_0', 'itemNo_0', 'trim|required');
        $this->form_validation->set_rules('qty_0', 'qty_0', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
        $this->form_validation->set_message('is_natural_no_zero', '* Tidak Boleh 0');
    }

    function _validate_proses() {
        $this->form_validation->set_rules('nopo', 'nopo', 'trim|required');
        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
    }

    function getBarangByName() {
        $type = $this->input->post('type');
        $name = $this->input->post('name_startsWith');
        $tanggal = $this->session->userdata('Tanggal_Trans');
        if (strlen($name) == 13) {
            $query = $this->terima_barangmodel->getBarangBarcode($type, $name);
            $barcode = true;
        } else {
            $query = $this->terima_barangmodel->getBarangName($type, $name);
            $barcode = false;
        }

        $results = array();
        foreach ($query as $row) {
            $name = $row['PCode'] . '|' . $row['PCode1'] . '|' . $row['NamaLengkap'] . '|' . $row['HargaBeliAkhir'] . '|' . $barcode;
            array_push($results, $name);
        }

        echo json_encode($results);
        exit;
    }

    function ajax_save_proses() {
        $mylib = new globallib();
        $no = $this->input->post('nodok');
        $user = $this->session->userdata('username');
        $aplikasi = $this->terima_barangmodel->getDate();
        $gudang = $aplikasi->KdGU;
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $tglterima = $mylib->ubah_tanggal($this->input->post('tglterima'));
        $sumberorder = $this->input->post('sumber');
        $noorder = $this->input->post('noorder');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $supp = $this->input->post('supplier');
        $nopo = $this->input->post('nopo');
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;

        $this->_validate_proses();
        $arr_val = array('success' => false, 'messages' => array());
        if ($this->form_validation->run()) {
            $this->db->delete('mutasi', array('NoTransaksi' => $no, 'KdTransaksi' => 'T'));
            $list = $this->terima_barangmodel->get_terima_by_id($no);
            foreach ($list as $value) {
                $no = $value['NoDokumen'];
                $pcode = $value['PCode'];
                $harga = $value['QtyHargaTerima'];
                $qty = $value['QtyTerima'];

                $stokawal = $this->terima_barangmodel->CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun);
                if (!empty($stokawal)) {// jika ada di table stock
                    $data = array(
                        $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) abs($qty),
                        $fieldakhir => (int) $stokawal->$fieldakhir + (int) abs($qty)
                    );
                    $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                } else {
                    $dat = array(
                        'Tahun' => $tahun,
                        'Gudang' => '00',
                        'KodeBarang' => $pcode,
                        $fieldmasuk => $qty,
                        $fieldakhir => $qty
                    );
                    $this->db->insert('stock', $dat);
                }

                $dataekonomis = array(
                    'Gudang' => $gudang,
                    'KdTransaksi' => "T", //Terima
                    'NoTransaksi' => $no,
                    'Tanggal' => $tgl,
                    'KodeBarang' => $pcode,
                    'Qty' => abs($qty),
                    'Nilai' => abs($hrg),
                    'Jenis' => 'I',
                    'Kasir' => $user,
                    'Keterangan' => $ket
                );
                $this->db->insert('mutasi', $dataekonomis);
            }

            $this->db->update('trans_terima_header', array('Keterangan' => $ket, 'FlagProses' => '1'), array('NoDokumen' => $no));
            $arr_val = array('success' => true);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Transaksi Penerimaan Barang berhasil di proses </div>');
            redirect('transaksi/penerimaan_barang');
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($arr_val);
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['tanggal'] = $this->terima_barangmodel->getDate();
            $data['mkontak'] = $this->terima_barangmodel->getKontak();
            $data['content'] = 'transaksi/terima_barang/add_terima_barang';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function edit_penerimaan($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("edit");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $id = $this->uri->segment(4);
            $data['mkontak'] = $this->terima_barangmodel->getKontak();
            $data['header'] = $this->terima_barangmodel->getHeader($id);
            $data['detail'] = $this->terima_barangmodel->getDetail($id);
            $data['content'] = 'transaksi/terima_barang/edit_terima_barang';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function cetak() {
        $data = $this->varCetak();
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_terima', $data);
    }

    function printThis() {
        $data = $this->varCetak();
        $id = $this->uri->segment(4);
        $data['fileName2'] = "terima_barang.sss";
        $data['fontstyle'] = chr(27) . chr(80);
        $data['nfontstyle'] = "";
        $data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['string1'] = "     Dibuat Oleh,                    Disetujui Oleh,";
        $data['string2'] = "(                     )         (                      )";
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer_so', $data); // jika mau di ubah pastikan cetakan yg lain sama
    }

    function versistruk() {
        $data = $this->varCetak();
        $no = $this->uri->segment(4);
        $printer = $this->terima_barangmodel->NamaPrinter($_SERVER['REMOTE_ADDR']);

        $data['ip'] = $printer[0]['ip'];
        $data['nm_printer'] = $printer[0]['nm_printer'];
        $data['store'] = $this->terima_barangmodel->aplikasi();
        $data['header'] = $this->terima_barangmodel->getHeader($no);
        $data['detail'] = $this->terima_barangmodel->getDetailForPrint($no);
//print_r($data['header']);
//			$data['detail']		= $this->tutup_hari_model->det_trans($no);
//die();
//                        $data['ip']    = "\\\\".."\LQ-2170s";

        if (!empty($data['header'])) {
//		$this->load->view('proses/cetak_tutup',$data); // jika untuk tes
            $this->load->view('transaksi/terima_barang/cetak_strukterima', $data); // jika ada printernya
        }
    }

    function varCetak() {
        $this->load->library('printreportlib');
        $mylib = new printreportlib();
        $id = $this->uri->segment(4);
        $header = $this->terima_barangmodel->getHeader($id);
//                print_r($header);
        $data['header'] = $header;
        $detail = $this->terima_barangmodel->getDetailForPrint($id);
//		$data['pt'] = $this->terima_barangmodel->getAlmPerusahaan($header->KdPerusahaan);
        $data['judul1'] = array("NoPenerimaan", "Tanggal Terima", "Keterangan");
        $data['niljudul1'] = array($header->NoDokumen, $header->TglTerima, stripslashes($header->Keterangan));
        $data['judul2'] = array("No Order", "Supplier");
        $data['niljudul2'] = array(stripslashes($header->NoOrder), $header->KdSupplier . " - " . stripslashes($header->NamaSupplier));
        $tambahan_judul = "";
        $data['judullap'] = "PENERIMAAN BARANG" . $tambahan_judul;
        $data['url'] = "penerimaan_barang/printThis/" . $id;
        $data['url2'] = "penerimaan_barang/versistruk/" . $id; // untuk mesin kasir
        $data['colspan_line'] = 4;
        $data['tipe_judul_detail'] = array("normal", "normal", "kanan", "normal", "kanan", "kanan");
        $data['judul_detail'] = array("Kode", "Nama Barang", "Qty", "", "Harga ", "Total");
        $data['panjang_kertas'] = 33;
        $default_page_written = 21;
        $data['panjang_per_hal'] = (int) $data['panjang_kertas'] - (int) $default_page_written;
        if ($data['panjang_per_hal'] != 0) {
            $data['tot_hal'] = ceil((int) count($detail) / (int) $data['panjang_per_hal']);
        } else {
            $data['tot_hal'] = 1;
        }
        $list_detail = array();
        $detail_page = array();
        $counterRow = 0;
        $max_field_len = array(0, 0, 0, 0, 0, 0);
        $sum_netto = 0;
//                print_r($detail);
        for ($m = 0; $m < count($detail); $m++) {
//			$hasil = $mylib->findSatuanQtyCetak($detail[$m]['QtyTerima'],$detail[$m]['KonversiBesarKecil'],$detail[$m]['KonversiTengahKecil']);
            unset($list_detail);
            $counterRow++;
            $list_detail[] = stripslashes($detail[$m]['PCode']);
            $list_detail[] = stripslashes($detail[$m]['NamaInitial']);
            $list_detail[] = $detail[$m]['QtyTerima'];
            $list_detail[] = "pcs";
            $list_detail[] = number_format($detail[$m]['QtyHargaTerima'], 0, '', '.');
            $list_detail[] = number_format(($detail[$m]['QtyTerima'] * $detail[$m]['QtyHargaTerima']), 0, '', '.');
            $detail_page[] = $list_detail;
            $max_field_len = $mylib->get_max_field_len($max_field_len, $list_detail);
            if ($data['panjang_per_hal'] != 0) {
                if (((int) $m + 1) % $data['panjang_per_hal'] == 0) {
                    $data['detail'][] = $detail_page;
                    if ($m != count($detail) - 1) {
                        unset($detail_page);
                    }
                }
            }
            $netto = $detail[$m]['QtyTerima'] * $detail[$m]['QtyHargaTerima'];
            $sum_netto = $sum_netto + ($netto);
        }
        $data['judul_netto'] = array("Total", "PPN 10%", "Nett ");
        $data['isi_netto'] = array(number_format($sum_netto, 0, ',', '.'), number_format(($sum_netto * 0.1), 0, ',', '.'), number_format($sum_netto + ($sum_netto * 0.1), 0, ',', '.'));
        $data['detail'][] = $detail_page;
        $data['max_field_len'] = $max_field_len;
        $data['banyakBarang'] = $counterRow;
        return $data;
    }

    function getPCode() {
        $kode = $this->input->post('pcode');
        if (strlen($kode) == 13) {
            $mylib = new globallib();
            $hasil = $mylib->findBarcode($kode);
            $pcode = $hasil;
        } else {
            $valpcode = $this->terima_barangmodel->ifPCodeBarcode($kode);
            $pcode = $valpcode->PCode;
        }
        if (!empty($pcode)) {
            $detail = $this->terima_barangmodel->getPCodeDet($pcode);
            $nilai = $detail->NamaInitial . "*&^%" . $detail->PCode . "*&^%" . $detail->HargaBeliAkhir;
        } else {
            $nilai = "";
        }
        echo $nilai;
    }

    function getsumber() {
        $order = $this->input->post('order');
        $kirim = $this->input->post('kirim');
        $perusahaan = $this->input->post('perusahaan');
        if ($order != "") {
            $hasil = $this->terima_barangmodel->getOrder($order, $perusahaan);
        }
        if ($kirim != "") {
            $hasil = $this->terima_barangmodel->getKirim($kirim, $perusahaan);
        }
        $str = "";
        $strsatuan = "";
        $kendaraan = "";
        for ($s = count($hasil) - 1; $s >= 0; $s--) {
            if ($kirim != "") {
                $kendaraan = $hasil[$s]['NoPolisi'];
                if ((int) $hasil[$s]['QtyPcs'] % (int) $hasil[$s]['KonversiBesarKecil'] == 0) {
                    $hasil[$s]['QtyInput'] = (int) $hasil[$s]['QtyPcs'] / (int) $hasil[$s]['KonversiBesarKecil'];
                    $hasil[$s]['Satuan'] = $hasil[$s]['KdSatuanBesar'];
                } else if ((int) $hasil[$s]['QtyPcs'] % (int) $hasil[$s]['KonversiTengahKecil'] == 0) {
                    $hasil[$s]['QtyInput'] = (int) $hasil[$s]['QtyPcs'] / (int) $hasil[$s]['KonversiTengahKecil'];
                    $hasil[$s]['Satuan'] = $hasil[$s]['KdSatuanTengah'];
                }
            }
            $str .= $hasil[$s]['PCode'] . "*&^%" . $hasil[$s]['QtyInput'] . "*&^%" . $hasil[$s]['QtyDisplay'] . "*&^%" . $hasil[$s]['QtyPcs'] . "*&^%" . $hasil[$s]['NamaInitial'] . "*&^%" . $hasil[$s]['KonversiJualKecil'] . "*&^%" . $hasil[$s]['KonversiBesarKecil'] . "*&^%" . $hasil[$s]['KonversiTengahKecil'] . "*&^%" . $hasil[$s]['KdSatuanJual'] . "*&^%" . $hasil[$s]['NamaSatuan'] . "*&^%" . $hasil[$s]['Satuan'] . "*&^%" . $hasil[$s]['PCodeBarang'] . "*&^%" . $hasil[$s]['KdContact'] . "*&^%" . $hasil[$s]['jenis'] . "~";
        }
        if ($kirim != "") {
            echo $kendaraan . "^&&^" . $str . "+" . $strsatuan;
        } else {
            echo $str . "+" . $strsatuan;
        }
    }

    function getRealPCode() {
        $kode = $this->input->post('pcode');
        if (strlen($kode) == 13) {
            $mylib = new globallib();
            $hasil = $mylib->findBarcode($kode);
            $pcode = $hasil;
        } else {
            $valpcode = $this->terima_barangmodel->ifPCodeBarcode($kode);
            if (count($valpcode) != 0) {
                $pcode = $valpcode->PCode;
            } else {
                $pcode = "";
            }
        }
        echo $pcode;
    }

    function save_new_terima() {

//            print_r($_POST);
//            die();
        $mylib = new globallib();
        $sbr = $this->input->post('sumber');
        $tglterima = $this->input->post('tglterima');
        $user = $this->session->userdata('userid');
        $flag = $this->input->post('flag');
        $no = $this->input->post('nodok');
        $NoPO = $this->input->post('nopo');
        $gudang = $this->input->post('gudang');
        $tgl = $this->input->post('tgl');
        $kontak = $this->input->post('supplier');
        $noorder = trim(strtoupper(addslashes($this->input->post('noorder'))));
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $pcode1 = $this->input->post('pcode');
        $qty1 = $this->input->post('qty');
        $hrg1 = $this->input->post('hrg');
        $pcodesave1 = $this->input->post('savepcode');
        if ($no == "") {
            $counter = "1";
            $no = $this->insertNewHeader($flag, $mylib->ubah_tanggal($tgl), $ket, $mylib->ubah_tanggal($tglterima), $sbr, $noorder, $user, $kontak, $NoPO, $gudang);
        } else {
            $counter = $this->updateHeader($flag, $no, $ket, $mylib->ubah_tanggal($tglterima), $sbr, $noorder, $user, $NoPO);
        }
        for ($x = 0; $x < count($pcode1); $x++) {
            $pcode = strtoupper(addslashes(trim($pcode1[$x])));
            $qty = trim($qty1[$x]);
            $hrg = trim($hrg1[$x]);
            $pcodesave = $pcodesave1[$x];
            if ($pcode != "") {
                $this->InsertAllDetail($flag, $no, $pcode, $qty, $hrg, $user, $mylib->ubah_tanggal($tgl), $pcodesave, $ket, $gudang);
            }
        }
        /* update sisa order jika order bukan manual */
        if ($sbr != "M") {
            $this->updateSisaOrder($no, $noorder);
        }
        redirect('/transaksi/penerimaan_barang/');
    }

    function insertNewHeader($flag, $tgl, $ket, $tglterima, $sumber, $noorder, $user, $kontak, $NoPO, $gudang) {
        $this->terima_barangmodel->locktables('setup_per_perusahaan,trans_terima_header');
        $new_no = $this->terima_barangmodel->getNewNo(substr($tgl, 0, 4));
        $no = $new_no->NoTerima;
        $this->db->update('setup_no', array("NoTerima" => (int) $new_no->NoTerima + 1), array("Tahun" => substr($tgl, 0, 4)));
        $data = array(
            'Gudang' => $gudang,
            'NoDokumen' => $no,
            'TglDokumen' => $tgl,
            'TglTerima' => $tglterima,
            'NoOrder' => $noorder,
            'SumberOrder' => $sumber,
            'NoPO' => $NoPO,
            'Keterangan' => $ket,
            'KdSupplier' => $kontak,
            'AddDate' => $tgl,
            'AddUser' => $user
        );
        $this->db->insert('trans_terima_header', $data);
        $this->terima_barangmodel->unlocktables();
        return $no;
    }

    function updateHeader($flag, $no, $ket, $tglterima, $sumber, $noorder, $user, $nopo) {
        $tgl = $this->session->userdata('Tanggal_Trans');
        $this->terima_barangmodel->locktables('trans_terima_header,trans_terima_detail');
        $data = array(
            'Keterangan' => $ket,
            'TglTerima' => $tglterima,
//			'SumberOrder'   => $sumber,
            'NoOrder' => $noorder,
            'NoPO' => $nopo,
        );
//		if($flag=="edit")
//		{
//			$data['EditDate'] = $tgl;
//			$data['EditUser'] = $user;
//			$this->db->update('trans_terima_detail', array('EditDate'=> $tgl,'EditUser'=>$user), array('NoDokumen' => $no));
//		}
        $this->db->update('trans_terima_header', $data, array('NoDokumen' => $no));
        $this->terima_barangmodel->unlocktables();
    }

    function InsertAllDetail($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $pcodesave, $ket, $gudang) {
        //echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|qty:".$qty; //die();
        if ($flag == "add") {
            $this->doAll($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $ket, $pcodesave, $gudang);
        } else {
            if ($pcodesave == $pcode) {//jika hanya qty yg berubah
//                    echo "ok";die();
                $cekdulu = $this->terima_barangmodel->cekPast($no, $pcode);
//				echo $qty." ".$cekdulu->QtyOpname;die();
                if ($qty != $cekdulu->QtyTerima or $hrg != $cekdulu->QtyHargaTerima) {
//                                echo $no."|".$tgl."|PCode=".$pcode."|save".$pcodesave."|".$cekdulu->QtyTerima;
                    $this->deleteAll($flag, $no, $tgl, $pcode, $pcodesave, $cekdulu->QtyTerima);
                    $this->doAll($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $ket, $pcodesave, $gudang);
                } else {
                    if ($flag == "edit") {
                        $tgltrans = $this->session->userdata('Tanggal_Trans');
                        $data = array(
                            'EditDate' => $tgl,
                            'EditUser' => $user
                        );
                        $this->db->update("trans_terima_detail", $data, array("NoDokumen" => $no));
                    }
                }
            } else {
                $cekdulu = $this->terima_barangmodel->cekPast($no, $pcodesave);
//                                print_r($cekdulu);die();
                //$pcodebarang_dulu = $this->terima_barangmodel->ifPCodeBarcode($pcodesave);
                if (!empty($pcodesave)) { // jika barang baru
                    $this->deleteAll($flag, $no, $tgl, $pcode, $pcodesave, $cekdulu->QtyTerima);
                }
                $this->doAll($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $ket, $pcodesave, $gudang);
            }
        }
    }

    function doAll($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $ket, $pcodesave, $gudang) {
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $this->updateStok($pcode, $tahun, $qty, $fieldmasuk, $fieldkeluar, $fieldakhir);
        $this->insertDetail($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $gudang);
        $this->insertMutasi($no, $tgl, $pcode, $ket, $qty, $hrg, $user, $gudang);
    }

    function updateStok($pcodebarang, $tahun, $qtyterima, $fieldmasuk, $fieldkeluar, $fieldakhir) {
        if ($qtyterima != 0) {
            $stokawal = $this->terima_barangmodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
            if (!empty($stokawal)) {// jika ada di table stock
                $data = array(
                    $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) abs($qtyterima),
                    $fieldakhir => (int) $stokawal->$fieldakhir + (int) abs($qtyterima)
                );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
            } else {
                $dat = array(
                    'Tahun' => $tahun,
                    'Gudang' => '00',
                    'KodeBarang' => $pcodebarang,
                    $fieldmasuk => $qtyterima,
                    $fieldakhir => $qtyterima
                );
                $this->db->insert('stock', $dat);
            }
        }
    }

    function insertMutasi($no, $tgl, $pcode, $ket, $qty, $hrg, $user, $gudang) {
        if ($qty != 0) {

            $jenismutasi = "I";

            $dataekonomis = array(
                'Gudang' => $gudang,
                'KdTransaksi' => "T", //Terima
                'NoTransaksi' => $no,
                'Tanggal' => $tgl,
                'KodeBarang' => $pcode,
                'Qty' => abs($qty),
                'Nilai' => abs($hrg),
                'Jenis' => $jenismutasi,
                'Kasir' => $user,
                'Keterangan' => $ket
            );
            $this->db->insert('mutasi', $dataekonomis);
        }
    }

    function deleteAll($flag, $no, $tgl, $pcode, $pcodebarang, $qtyterima) {//echo $tgl;die();
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        if ($qtyterima != 0) {
            $stokawal = $this->terima_barangmodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
            $data = array(
                $fieldmasuk => (int) $stokawal->$fieldmasuk - (int) abs($qtyterima),
                $fieldakhir => (int) $stokawal->$fieldakhir - (int) abs($qtyterima)
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
            $this->db->delete("mutasi", array("KdTransaksi" => "T", "NoTransaksi" => $no, "KodeBarang" => $pcodebarang));
        }
        if ($flag != "del") {
            if ($pcode != $pcodebarang) {
                $this->db->delete("trans_terima_detail", array("NoDokumen" => $no, "PCode" => $pcodebarang));
            } else {
                $this->db->delete("trans_terima_detail", array("NoDokumen" => $no, "PCode" => $pcode));
            }
        }
    }

    function insertDetail($flag, $no, $pcode, $qty, $hrg, $user, $tgl, $gudang) {

        $tgltrans = $this->session->userdata('Tanggal_Trans');
        $this->terima_barangmodel->locktables('trans_terima_detail');

//			$detail_ada = $this->terima_barangmodel->cekDetail($pcode,$no);
//			if(count($detail_ada)!=0&&$detail_ada->FlagDelete=="Y"){
//                                $this->db->delete("trans_terima_detail",array("NoDokumen"=>$no,"PCode"=>$pcode));
//			}

        $data = array(
            'Gudang' => $gudang,
            'NoDokumen' => $no,
            'PCode' => $pcode,
            'QtyTerima' => $qty,
            'QtyHargaTerima' => $hrg,
            'AddDate' => $tgl,
            'AddUser' => $user
        );
        $this->db->insert('trans_terima_detail', $data);
        if ($flag == "edit") {
            $data = array(
                'EditDate' => $tgl,
                'EditUser' => $user
            );
            $this->db->update('trans_terima_detail', $data, array('NoDokumen' => $no, 'PCode' => $pcode));
        }

        $this->terima_barangmodel->unlocktables();
    }

    function delete_item() {
        $mylib = new globallib();
        $flag = "edit";
        $no = $this->input->post('no');
        $pcode = $this->input->post('pcode');
        $pcodesave = $this->input->post('pcodesave');
        $qty = $this->input->post('qty');
        $user = $this->session->userdata('userid');
        $tgl2 = $this->session->userdata('Tanggal_Trans');
        $mylib = new globallib();
        $tgl = $mylib->ubah_tanggal($tgl2);
        $this->terima_barangmodel->locktables('trans_terima_detail');
        $this->deleteAll($flag, $no, $tgl, $pcode, $pcodesave, $qty);
        $this->terima_barangmodel->unlocktables();
    }

    function updateSisaOrder($no, $noorder) {
        $det = $this->globalmodel->getQuery("SELECT PCode,QtyTerima FROM trans_terima_detail WHERE NoDokumen='$no' order by PCode");
//            echo "<pre>".print_r($det)."</pre><br>";
//            echo $det[0]['PCode'];
        for ($a = 0; $a < count($det); $a++) {
            //update detail order
            $qakhir = $this->globalmodel->getField("select QtyKonfTerima from trans_order_detail where NoDokumen = '" . $noorder . "' and PCode = '" . $det[$a]['PCode'] . "'");
            $par = "trans_order_detail";
            $data = array(
                'QtyKonfTerima' => $qakhir->QtyKonfTerima + $det[$a]['QtyTerima']
            );
//                        echo $a;
            $where = "NoDokumen = '" . $noorder . "' and PCode = '" . $det[$a]['PCode'] . "'";
            $this->globalmodel->editData($par, $data, $where);
//                    echo $det[$a]['PCode'];
        }
        $ceksisa = $this->globalmodel->getQuery("SELECT SUM(QtyOrder-QtyKonfTerima)as hasil FROM trans_order_detail WHERE NoDokumen='$noorder'");
//             die();
        if ($ceksisa[0]['hasil'] == 0) {
            $up = array('FlagKonfirmasi' => '1');
            $this->db->update('trans_order_header', $up, array('NoDokumen' => $noorder));
        }
    }
    
    function carikontak() {
        $sumber = $this->input->post('sumber');
        $aplikasi = $this->terima_barangmodel->getDate();
        if ($sumber == "M" || $sumber == "O") {
            if ($aplikasi->DefaultContactOrder == "") {
                $with = "";
            } else {
                $with = "where KdTipeContact='" . $aplikasi->DefaultContactOrder . "'";
            }
        }

        $mkontak = $this->terima_barangmodel->getKontak($with);
        $str = "";
        for ($m = 0; $m < count($mkontak); $m++) {
            $str .= "<option value='" . $mkontak[$m]['KdContact'] . "'>" . $mkontak[$m]['Nama'] . "</option>";
        }
        echo $str;
    }

//    function delete_penerimaan() {
//        $mylib = new globallib();
//        $id = $this->input->post('kode');
//        $header = $this->terima_barangmodel->getSumber($id);
//        $user = $this->session->userdata('userid');
//        $tgl2 = $this->session->userdata('Tanggal_Trans');
//        $tgl = $mylib->ubah_tanggal($tgl2);
//        $getHeader = $this->terima_barangmodel->getHeader($id);
//        $getDetail = $this->terima_barangmodel->getDetailDel($id);
//        $tahun = substr($getHeader->TglDokumen, 6, 4);
//        $lastNo = $this->terima_barangmodel->getNewNo($tahun);
//        $NoDelete = $id;
//        if ((int) $lastNo->NoTerima == (int) $NoDelete + 1) {
//            $this->db->update("setup_no", array("NoTerima" => $NoDelete), array("Tahun" => $tahun));
//        }
//        $this->terima_barangmodel->locktables('trans_terima_detail,trans_terima_header');
//        if ($header->SumberOrder == "O") {
//            $this->db->update('trans_order_barang_header', array("FlagPenerimaan" => "T"), array('NoDokumen' => $header->NoOrder));
//            $this->db->update('trans_order_barang_detail', array("FlagPenerimaan" => "T"), array('NoDokumen' => $header->NoOrder));
//        }
//        $bulan = substr($tgl, 5, 2);
//        $tahun = substr($tgl, 0, 4);
//        $fieldmasuk = "QtyMasuk" . $bulan;
//        $fieldakhir = "QtyAkhir" . $bulan;
//        $fieldkeluar = "QtyKeluar" . $bulan;
//
//        for ($s = 0; $s < count($getDetail);) {
//
//            $pcodebarang = $getDetail[$s]['PCode'];
//            $qtyterima = $getDetail[$s]['QtyTerima'];
//            $stokawal = $this->terima_barangmodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
//            $data = array(
//                $fieldmasuk => (int) $stokawal->$fieldmasuk - (int) abs($qtyterima),
//                $fieldakhir => (int) $stokawal->$fieldakhir - (int) abs($qtyterima)
//            );
//            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
//            $this->db->delete("mutasi", array("KdTransaksi" => "T", "NoTransaksi" => $id, "KodeBarang" => $pcodebarang));
//
//            $s++;
//        }
//        $this->db->delete('trans_terima_detail', array('NoDokumen' => $id . "D"));
//        $this->db->delete('trans_terima_header', array('NoDokumen' => $id . "D"));
//        $this->db->update('trans_terima_detail', array("FlagDelete" => "Y", "DeleteDate" => $tgl, "DeleteUser" => $user, "NoDokumen" => $id . "D"), array('NoDokumen' => $id));
//        $this->db->update('trans_terima_header', array("FlagDelete" => "Y", "DeleteDate" => $tgl, "DeleteUser" => $user, "NoDokumen" => $id . "D"), array('NoDokumen' => $id));
//        $this->terima_barangmodel->unlocktables();
//    }


}

?>