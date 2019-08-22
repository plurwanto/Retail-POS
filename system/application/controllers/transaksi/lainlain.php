<?php
class lainlain extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/lainlain_model');
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
            $data['tglTrans'] = $this->lainlain_model->getDate();
            $data['data'] = $this->lainlain_model->getList();
            $data['track'] = $mylib->print_track();
            $data['content'] = "transaksi/lainlain/lainlain_list";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function ajax_list($no) {
        $list = $this->lainlain_model->get_datatables($no);
        $data = array();
        foreach ($list as $value) {
            $row = array();
            $row['NoDokumen'] = $value['NoDokumen'];
            $row['PCode'] = $value['PCode'];
            $row['NamaLengkap'] = $value['NamaLengkap'];
            $row['QtyPcs'] = $value['QtyPcs'];
            $row['Harga'] = $value['Harga'];
            $row['Netto'] = $value['Netto'];
            $data[] = $row;
        }
        echo json_encode($data);
    }

    function ajax_add() {
        $mylib = new globallib();
        $user = $this->session->userdata('userid');
        $aplikasi = $this->lainlain_model->getDate();
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $tipe = $this->input->post('tipe');
        $cndn = $this->input->post('cndn');
        $counter = $this->input->post('counter');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $flag = $this->input->post('flag');

        $pcode0 = $this->input->post('itemNo_0');
        $pcodesave0 = $this->input->post('pcodesave');
        $harga0 = $this->input->post('harga_0');
        $qty0 = $this->input->post('qty_0');
        $total0 = $this->input->post('total_0');
        $adddateapl = date('Y-m-d H:i:s');
        $adddatestok = $tgl . " " . date('H:i:s');

        $this->_validate();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            if ($no == "") { //proses insert di awal baris pertama dan nodokume masih kosong
                $new_no = $this->lainlain_model->getNewNo(substr($tgl, 0, 4));
                $no = $new_no->NoLainLain;
                $this->db->update('setup_no', array("NoLainLain" => (int) $no + 1), array("Tahun" => substr($tgl, 0, 4)));

                $data_header = array(
                    'NoDokumen' => $no,
                    'TglDokumen' => $tgl,
                    'KdPerusahaan' => "01",
                    'Tipe' => $tipe,
                    'KdCNDN' => $cndn,
                    'KdCounter' => $counter,
                    'KdContact' => '0',
                    'Keterangan' => $ket,
                    'AddDate' => $tgl,
                    'AddUser' => $user
                );
                $data_detail = array(
                    'NoDokumen' => $no,
                    'PCode' => $pcode0,
                    'KdCounter' => $counter,
                    'QtyPcs' => $qty0,
                    'Harga' => $harga0,
                    'Netto' => $total0,
                    'TglStockSimpan' => $adddatestok,
                    'TglAplikasiStokSimpan' => $adddateapl,
                    'AddDate' => $tgl,
                    'AddUser' => $user
                );

                $this->db->insert('trans_lainlain_header', $data_header);
                $this->db->insert('trans_lainlain_detail', $data_detail);
            } else {  //proses insert di baris kedua dan nodokumen sudah ada
                $query = $this->lainlain_model->get_detail_by_id($no, $pcode0);
                if ($query->num_rows() > 0) {
                    $this->db->update('trans_lainlain_detail', array('QtyPcs' => $qty0, 'Harga' => $harga0, 'Netto' => $total0, 'TglStockSimpan' => date('Y-m-d H:i:s'), 'TglAplikasiStokSimpan' => date('Y-m-d H:i:s'), 'EditUser' => $user), array('NoDokumen' => $no, 'PCode' => $pcode0));
                } else {
                    $data_detail = array(
                        'NoDokumen' => $no,
                        'PCode' => $pcode0,
                        'KdCounter' => $counter,
                        'QtyPcs' => $qty0,
                        'Harga' => $harga0,
                        'Netto' => $total0,
                        'TglAplikasiStokSimpan' => date('Y-m-d H:i:s'),
                        'AddUser' => $user
                    );
                    $this->db->insert('trans_lainlain_detail', $data_detail);
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
        $this->lainlain_model->delete_by_id($no, $pcode);
        echo json_encode(array('success' => true));
    }

    function _validate() {
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|required');
        $this->form_validation->set_rules('cndn', 'cndn', 'trim|required');
        $this->form_validation->set_rules('counter', 'counter', 'trim|required');
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_rules('itemNo_0', 'itemNo_0', 'trim|required');
        $this->form_validation->set_rules('qty_0', 'qty_0', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
        $this->form_validation->set_message('is_natural_no_zero', '* Tidak Boleh 0');
    }

    function _validate_proses() {
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
    }

    function getBarangByName() {
        $type = $this->input->post('type');
        $name = $this->input->post('name_startsWith');
        $tanggal = $this->session->userdata('Tanggal_Trans');
        if (strlen($name) == 13) {
            $query = $this->lainlain_model->getBarangBarcode($type, $name);
            $barcode = true;
        } else {
            $query = $this->lainlain_model->getBarangName($type, $name);
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
        $flag = $this->input->post('flag');
        $user = $this->session->userdata('username');
        $aplikasi = $this->lainlain_model->getDate();
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $tipe = $this->input->post('tipe');
        $cndn = $this->input->post('cndn');
        $counter = $this->input->post('counter');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        //echo "<pre>";print_r($_POST);echo "</pre>";die();
        $this->_validate_proses();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            if ($tipe == "M") {
                $jenismutasi = "I";
                $kodetransaksi = "TL";
            }
            if ($tipe == "K") {
                $jenismutasi = "O";
                $kodetransaksi = "KL";
            }

            if ($flag == "edit") {
                $bulan = substr($tgl, 5, 2);
                $tahun = substr($tgl, 0, 4);
                $fieldmasuk = "QtyMasuk" . $bulan;
                $fieldakhir = "QtyAkhir" . $bulan;
                $fieldkeluar = "QtyKeluar" . $bulan;

                $this->db->delete('mutasi', array("KdTransaksi" => $kodetransaksi, "NoTransaksi" => $no));

                $cekTrx = "SELECT DISTINCT(Jenis) FROM mutasi m WHERE YEAR(m.`Tanggal`)='$tahun' AND MONTH(m.`Tanggal`)='$bulan'";
                $nil = $this->getArrayResult($cekTrx);
                if (!empty($nil)) {
                    $this->hapus_stock_dahulu($bulan, $tahun);
                    $this->hitung_stock_awal($bulan, $tahun);
                    for ($ak = 0; $ak < count($nil);) {
                        if ($nil[$ak]['Jenis'] == "I") {
                            //hitung stock masuk
                            $this->hitungTerima($bulan, $tahun);
                        } else {
                            //hitung stock keluar;
//                        echo "keluar";
                            $this->hitungKeluar($bulan, $tahun);
                        }
                        $ak++;
                    }
                    $this->hitung_ulang_stock($bulan, $tahun); // hitung ulang stock
                }
            }

            $list = $this->lainlain_model->get_lainlain_by_id($no);

            foreach ($list as $value) {
                $pcode = $value['PCode'];
                $harga = $value['Harga'];
                $qty = $value['QtyPcs'];

                if ($tipe == "M") {
                    $stokawal = $this->lainlain_model->CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun);
                    if (!empty($stokawal)) {
                        $data = array(
                            $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) $qty,
                            $fieldakhir => (int) $stokawal->$fieldakhir + (int) $qty
                        );
                        //  $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode, "Gudang" => $counter));
                        $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                    } else {
                        $data = array(
                            'Tahun' => $tahun,
                            // 'Gudang' => $counter,
                            'KodeBarang' => $pcode,
                            $fieldmasuk => $qty,
                            $fieldakhir => $qty
                        );
                        $this->db->insert('stock', $data);
                    }
                } elseif ($tipe == "K") {
                    $stokawal = $this->lainlain_model->StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun);
                    if (!empty($stokawal)) {
                        $data = array(
                            $fieldkeluar => (int) $stokawal->$fieldkeluar + (int) $qty,
                            $fieldakhir => (int) $stokawal->$fieldakhir - (int) $qty
                        );
                        //$this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode, "Gudang" => $counter));
                        $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                    } else {
                        $data = array(
                            'Tahun' => $tahun,
                            'Gudang' => $counter,
                            'KodeBarang' => $pcode,
                            $fieldkeluar => $qty,
                            $fieldakhir => $qty * -1
                        );
                        $this->db->insert('stock', $data);
                    }
                }

                $dataekonomis = array(
                    'KdTransaksi' => $kodetransaksi,
                    'NoTransaksi' => $no,
                    'Tanggal' => $tgl,
                    'Kasir' => $user,
                    'KodeBarang' => $pcode,
                    'Gudang' => $counter,
                    'Qty' => $qty,
                    'Nilai' => $harga,
                    'Jenis' => $jenismutasi,
                    'Keterangan' => $ket
                );
                $this->db->insert('mutasi', $dataekonomis);
            }

            $arr_val = array('success' => true);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Transaksi Lain Lain berhasil di proses </div>');
            redirect('transaksi/lainlain');
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($arr_val);
    }

    function hapus_stock_dahulu($bulan, $tahun) {

        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;

        $data = array(
            $fieldmasuk => 0,
            $fieldkeluar => 0,
            $fieldakhir => 0
        );
        $this->db->update('stock', $data, array("Tahun" => $tahun));
    }

    function hitung_ulang_stock($bulan, $tahun) {// stock akhir
        $fielAwal = "QtyAwal" . $bulan;
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $sqlNil = "SELECT KodeBarang,($fielAwal + $fieldmasuk) - $fieldkeluar AS QtyAkhir FROM stock WHERE `Tahun` = '$tahun'";
        $NilAkhir = $this->getArrayResult($sqlNil);
        for ($a = 0; $a < count($NilAkhir);) {
            $data = array(
                $fieldakhir => $NilAkhir[$a]['QtyAkhir']
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $NilAkhir[$a]['KodeBarang']));
            $a++;
        }
//            $this->db->update($sql);
    }

    function hitung_stock_awal($bulan, $tahun) {// stock akhir
        if ($bulan == '01') {
            $blnlama = "12";
            $tahunlama = $tahun - 1;
        } else {
            $blnlama = $bulan - 1;
            if (strlen($blnlama) == 1) {
                $blnlama = "0" . $blnlama;
            }
            $tahunlama = $tahun;
        }
        $fielAwal = "QtyAwal" . $bulan;
        $fieldakhir = "QtyAkhir" . $blnlama;
        $qtylama = "select KodeBarang,$fieldakhir as QtyAkhir FROM stock where Tahun='$tahunlama'";
        $NilAkhir = $this->getArrayResult($qtylama);
        for ($a = 0; $a < count($NilAkhir);) {
            $data = array(
                $fielAwal => $NilAkhir[$a]['QtyAkhir']
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $NilAkhir[$a]['KodeBarang']));
            $a++;
        }
//            $this->db->update($sql);
    }

    function hitungKeluar($bulan, $tahun) {
        $sql = "SELECT SUM(Qty) as ttl,KodeBarang FROM mutasi WHERE Jenis='O' and YEAR(Tanggal)='$tahun' AND MONTH(Tanggal)='$bulan' GROUP BY KodeBarang";
        $nil = $this->getArrayResult($sql);
        $fieldkeluar = "QtyKeluar" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;

        for ($a = 0; $a < count($nil);) {
            $sql2 = "select * from stock where Tahun='$tahun'and KodeBarang='" . $nil[$a]['KodeBarang'] . "'";
            $dt = $this->getArrayResult($sql2);
            if (!empty($dt)) {
                $data = array(
                    $fieldkeluar => $fieldkeluar + $nil[$a]['ttl']
                );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $nil[$a]['KodeBarang']));
            } else {
                $this->tambah_stock($nil[$a]['KodeBarang'], $tahun, $nil[$a]['ttl'], $fieldkeluar, $fieldakhir);
            }
            $a++;
        }
    }

    function hitungTerima($bulan, $tahun) {
        $sql = "SELECT SUM(Qty) as ttl,KodeBarang FROM mutasi WHERE Jenis='I' and YEAR(Tanggal)='$tahun' AND MONTH(Tanggal)='$bulan' GROUP BY KodeBarang";
        $nil = $this->getArrayResult($sql);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        for ($a = 0; $a < count($nil);) {
            $sql2 = "select * from stock where Tahun='$tahun'and KodeBarang='" . $nil[$a]['KodeBarang'] . "'";
            $dt = $this->getArrayResult($sql2);
            if (!empty($dt)) {
                $data = array(
                    $fieldmasuk => $fieldmasuk + $nil[$a]['ttl']
                );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $nil[$a]['KodeBarang']));
            } else {
                $this->tambah_stock($nil[$a]['KodeBarang'], $tahun, $nil[$a]['ttl'], $fieldmasuk, $fieldakhir);
            }
            $a++;
        }
    }

    function tambah_stock($pcodebarang, $tahun, $qtyterima, $field, $fieldakhir) {
        $sql = "SELECT KdGU FROM aplikasi ";
        $dt = $this->getArrayResult($sql);
        $dat = array(
            'Tahun' => $tahun,
            'Gudang' => $dt[0]['KdGU'],
            'KodeBarang' => $pcodebarang,
            $field => $qtyterima,
            $fieldakhir => $qtyterima
        );
        $this->db->insert('stock', $dat);
    }

    function getArrayResult($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
            $data['tanggal'] = $this->lainlain_model->getDate();
            $data['mcounter'] = $this->lainlain_model->getCounter();
            $data['mcndn'] = $this->lainlain_model->getCNDN();
            $data['content'] = "transaksi/lainlain/lainlain_add";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function edit_lainlain() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['mcounter'] = $this->lainlain_model->getCounter();
            $data['mcndn'] = $this->lainlain_model->getCNDN();
            $data['header'] = $this->lainlain_model->getHeader($id);
            $data['detail'] = $this->lainlain_model->getDetail($id);
            // $data['session_tgl'] = $this->session->userdata('Tanggal_Trans');
            $data['content'] = "transaksi/lainlain/lainlain_edit";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function print_barcode() {
        $id = $this->uri->segment(4);
        $data = $this->varBarcode($id);
        $data['NoDokumen'] = $id;
        $this->load->view('transaksi/cetak_transaksi/print_barcode', $data);
    }

    function printThisBarcode() {
        $id = $this->uri->segment(4);
        $data = $this->varBarcode($id);
        $data['fileName2'] = "barcode_simpan.ccc";
        $qtycetak = $this->lainlain_model->getQtyCetak($id);
        for ($m = 0; $m < count($qtycetak); $m++) {
            $qtyctk = $qtycetak[$m]['QtyCetak'];
            $data['QtyCetak'][] = $qtyctk;
        }
//		$data['fileName2'] = "barcode_lain.sss";
        $this->load->view('transaksi/cetak_transaksi/print_barcode_printer', $data);
    }

    function varBarcode($id) {
        $det = $this->lainlain_model->getBasedBarcode($id);
//                print_r($det);
        $getIdPerusahaan = $this->lainlain_model->getDate();
        $perusahaan = $getIdPerusahaan->DefaultKodePerusahaan;
        $this->load->library('printreportlib');
        $printreport = new printreportlib();
        $mylib = new globallib();
        for ($s = 0; $s < count($det); $s++) {
            $nonya = $det[$s]['NoDokumen'];
            $nopenerimaan = $perusahaan . "8" . $nonya;
            $nilai = $printreport->findSatuanQtyCetak($det[$s]['QtyKonversi'], $det[$s]['KonversiBesarKecil'], $det[$s]['KonversiTengahKecil'], "B", "T", "K");
            $nilai_exp = explode(" ", $nilai);
            for ($m = 0; $m < count($nilai_exp); $m++) {
                $qty = $nilai_exp[$m];
                $m++;
                $satuan = $nilai_exp[$m];
                if ($satuan == "B") {
                    $kode_sat = "1";
                } else if ($satuan == "T") {
                    $kode_sat = "2";
                } else if ($satuan == "K") {
                    $kode_sat = "3";
                }
                if (strlen($det[$s]['Counter']) == 1) {
                    $det[$s]['Counter'] = "0" . $det[$s]['Counter'];
                }
                $asaldata = "1";
                $barcode = $kode_sat . $asaldata . $nopenerimaan . $det[$s]['Counter'];
                $data['Barcode'][] = $barcode;
                $data['Qty'][] = $qty;
                if (strlen($det[$s]['PCode']) == 13) {
                    $hasil = $mylib->findStructureBarcode($det[$s]['PCode'], "PCode", "distinct");
                    $pcode_hasil = $hasil['nilai'];
                    $properties_barang = $this->lainlain_model->getPCodeName($pcode_hasil[0]['PCode']);
                    $pcode = $pcode_hasil[0]['PCode'];
                } else {
                    $properties_barang = $this->lainlain_model->getPCodeName($det[$s]['PCode']);
                    $pcode = $det[$s]['PCode'];
                }
                $cari_attr_lot = $mylib->getLot("L", $det[$s]['NoDokumen'], $pcode, $det[$s]['Counter'], "2");
                $data['Attr'][] = substr($cari_attr_lot->NilAttr, strlen($cari_attr_lot->NilAttr) - 6, 6);
//                                                                print_r($cari_attr_lot);
                if ($satuan == "B") {
                    $data['Satuan'][] = $properties_barang->SatuanBesar;
                } else if ($satuan == "T") {
                    $data['Satuan'][] = $properties_barang->SatuanTengah;
                } else if ($satuan == "K") {
                    $data['Satuan'][] = $properties_barang->SatuanKecil;
                }
                $data['Nama'][] = $properties_barang->NamaLengkap;
                $data['noterima'][] = $det[$s]['NoDokumen'];
                $data['asaldata'][] = "L";
                $data['counter'][] = $det[$s]['Counter'];
                $data['pcode'][] = $pcode;
                $data['konversibesarkecil'][] = $det[$s]['KonversiBesarKecil'];
                $data['konversitengahkecil'][] = $det[$s]['KonversiTengahKecil'];
            }
        }
        $data['url'] = "transaksi/lainlain/printThisBarcode/" . $id;
        return $data;
    }

    function cetak() {
        $data = $this->varCetak();
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_lain', $data);
    }

    function printThis() {
        $data = $this->varCetak();
        $id = $this->uri->segment(4);
        $data['fileName2'] = "lainlain.sss";
        $data['fontstyle'] = chr(27) . chr(80);
        $data['nfontstyle'] = "";
        $data['spasienter'] = "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['pindah_hal'] = "\r\n\r\n\r\n\r\n\r\n" . chr(27) . chr(48) . "\r\n" . chr(27) . chr(50);
        $data['string1'] = "     Dibuat Oleh,                     Disetujui Oleh,";
        $data['string2'] = "(                     )         (                      )";
        $this->load->view('transaksi/cetak_transaksi/cetak_transaksi_printer_lain', $data);
    }

    function varCetak() {
        $this->load->library('printreportlib');
        $mylib = new globallib();
        $printreport = new printreportlib();
        $id = $this->uri->segment(4);
        $header = $this->lainlain_model->getHeader($id);
        $data['header'] = $header;
        $detail = $this->lainlain_model->getDetailForPrint($id);
        $data['judul1'] = array("NoDokumen", "TglDokumen", "Keterangan");
        $data['niljudul1'] = array($header->NoDokumen, $header->TglDokumen, stripslashes($header->Keterangan));
        $data['judul2'] = "";
        $data['niljudul2'] = "";
        $data['judullap'] = $header->NamaKeterangan . " LAIN LAIN";
        $data['colspan_line'] = 4;
        $data['url'] = "lainlain/printThis/" . $id;
        $data['tipe_judul_detail'] = array("normal", "normal", "kanan", "normal", "kanan", "kanan");
        $data['judul_detail'] = array("Kode", "Nama Barang", "Qty", "", "Harga", "Total");
        $data['panjang_kertas'] = 30;
        $jmlh_baris_lain = 19;
        $data['panjang_per_hal'] = (int) $data['panjang_kertas'] - (int) $jmlh_baris_lain;
        $jml_baris_detail = count($detail) + $this->lainlain_model->getCountDetail($id);
        if ($data['panjang_per_hal'] == 0) {
            $data['tot_hal'] = 1;
        } else {
            $data['tot_hal'] = ceil((int) $jml_baris_detail / (int) $data['panjang_per_hal']);
        }
        $list_detail = array();
        $detail_attr = array();
        $list_detail_attr = array();
        $detail_page = array();
        $new_array = array();
        $counterBaris = 0;
        $counterRow = 0;
        $max_field_len = array(0, 0, 0, 0, 0, 0);
        $sum_netto = 0;
//                print_r($detail);
        for ($m = 0; $m < count($detail); $m++) {
//			$attr = $this->lainlain_model->getDetailAttrCetak($id,$detail[$m]['PCode'],$detail[$m]['Counter']);
            unset($list_detail);
            $counterRow++;
            $list_detail[] = stripslashes($detail[$m]['PCode']);
            $list_detail[] = stripslashes($detail[$m]['NamaInitial']);
            $list_detail[] = number_format($detail[$m]['QtyPcs'], '', '', '.');
            $list_detail[] = "pcs";
            $list_detail[] = number_format($detail[$m]['Harga'], '', '', '.');
            $list_detail[] = number_format(($detail[$m]['QtyPcs']) * ($detail[$m]['Harga']), '', '', '.');
            $detail_page[] = $list_detail;
            $max_field_len = $printreport->get_max_field_len($max_field_len, $list_detail);
            if ($data['panjang_per_hal'] != 0) {
                if (((int) $m + 1) % $data['panjang_per_hal'] == 0) {
                    $data['detail'][] = $detail_page;
                    if ($m != count($detail) - 1) {
                        unset($detail_page);
                    }
                }
            }
            $netto = $detail[$m]['QtyPcs'] * $detail[$m]['Harga'];
            $sum_netto = $sum_netto + ($netto);
        }
        $data['judul_netto'] = array("Total", "PPN 10%", "Nett");
        $data['isi_netto'] = array(number_format($sum_netto, '', ',', '.'), number_format(($sum_netto * 0.1), '', ',', '.'), number_format($sum_netto + ($sum_netto * 0.1), '', ',', '.'));
        $data['detail'][] = $detail_page;
        $data['max_field_len'] = $max_field_len;
        $data['banyakBarang'] = $counterRow;
        return $data;
    }

    function getPCode() {
        $mylib = new globallib();
        $kode = $this->input->post('pcode');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $jml = "";
        $noterima = "";
        $counter = "";
        $asaldata = "";

        $valpcode = $kode;
        if (count($valpcode) != 0) {
            $pcode = $valpcode;
//			$jenis_kode = $valpcode->Jenis;

            $detail = $this->lainlain_model->getPCodeDet($pcode);
            $nilai = $detail->NamaInitial . "*&^%" . $detail->HargaJual . "*&^%" . $detail->NamaSatuan . "*&^%" . $detail->PCode;
        } else {
            $nilai = "";
        }
        echo $nilai;
    }

    function getstringstock() {
        $pcodebarang = $this->input->post('pcodebarang');
        $pickingmethod = $this->input->post('pickmethod');
        $qtypcs = $this->input->post('qtypcs');
        $tgltrans = $this->session->userdata('Tanggal_Trans');
        $tahun = substr($tgltrans, 0, 4);
        $bulan = substr($tgltrans, 5, 2);
        $strstok = $this->getStok($pcodebarang, $pickingmethod, $tahun, $bulan, $qtypcs);
        if ($strstok != "picking method not defined") {
            $ambil_total = explode("@", $strstok);
            $string_stok = $ambil_total[0];
            if ($ambil_total[1] >= $qtypcs) {
                $qtyterambil = $qtypcs;
            } else {
                $qtyterambil = $ambil_total[1];
            }
            $str = "##" . $qtyterambil . "##" . $ambil_total[1] . "##" . $ambil_total[2] . "**" . $string_stok;
        } else {
            $str = $strstok;
        }
        echo $str;
    }

    function save_new_lainlain_old() {
//            echo "<pre>";print_r($_POST);echo "</pre>";//die();
        $mylib = new globallib();
        $user = $this->session->userdata('userid');
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $tipe = $this->input->post('hidetipe');
        $kontak = $this->input->post('kontak');
        $cndn = $this->input->post('cndn');
        $counter = $this->input->post('counter');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $flag = $this->input->post('flag');
        $pcode1 = $this->input->post('pcode');
        $qty1 = $this->input->post('qty');
        $hrg1 = $this->input->post('hrg');
        $nil1 = $this->input->post('nil');
        $qtypcs1 = $this->input->post('qtypcs');
        $qtydisplay1 = $this->input->post('qtydisplay');
        if ($flag == "add") {
            $no = $this->InsertHeader($no, $tgl, $tipe, $kontak, $ket, $user, $cndn, $counter);
        } else {
            $this->updateHeader($flag, $no, $ket, $user, $cndn, $counter);
            //hapus detail lama dan update stock
            $bulan = substr($tgl, 5, 2);
            $tahun = substr($tgl, 0, 4);
            $fieldmasuk = "QtyMasuk" . $bulan;
            $fieldakhir = "QtyAkhir" . $bulan;
            $fieldkeluar = "QtyKeluar" . $bulan;
            $this->deleteAll($tipe, $no, $tahun, $fieldmasuk, $fieldkeluar, $fieldakhir); //update dan delete
        }
        for ($x = 0; $x < count($pcode1); $x++) {
            if ($pcode1[$x] != "") {
                $pcode = strtoupper(addslashes($pcode1[$x]));
                $qty = trim($qty1[$x]);
                $hrg = trim($hrg1[$x]);
                $nil = trim($nil1[$x]);
                $this->InsertAllDetail($flag, $tipe, $no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket, $counter);
            }
        }
        redirect('/transaksi/lainlain/');
    }

    function InsertHeader($no, $tgl, $tipe, $kontak, $ket, $user, $cndn, $counter) {
        $this->lainlain_model->locktables('setup_no,trans_lainlain_header');
        $new_no = $this->lainlain_model->getNewNo(substr($tgl, 0, 4));
        $no = $new_no->NoLainLain;
        $this->db->update('setup_no', array("NoLainLain" => (int) $no + 1), array("Tahun" => substr($tgl, 0, 4)));
        if ($tipe == "masuk") {
            $tipe = "M";
        } else if ($tipe == "keluar") {
            $tipe = "K";
        }
        $data = array(
            'NoDokumen' => $no,
            'TglDokumen' => $tgl,
            'KdPerusahaan' => "01",
            'Tipe' => $tipe,
            'KdCNDN' => $cndn,
            'KdCounter' => $counter,
            'KdContact' => $kontak,
            'Keterangan' => $ket,
            'AddDate' => $tgl,
            'AddUser' => $user
        );
        $this->db->insert('trans_lainlain_header', $data);
        $this->lainlain_model->unlocktables();
        return $no;
    }

    function updateHeader($flag, $no, $ket, $user, $cndn, $counter) {
        $this->lainlain_model->locktables('trans_lainlain_header');
        $tgltrans = $this->session->userdata('Tanggal_Trans');
        $data = array(
            'Keterangan' => $ket,
        );
        if ($flag == "edit") {
            $data['EditDate'] = $tgltrans;
            $data['EditUser'] = $user;
        }
        $this->db->update('trans_lainlain_header', $data, array("NoDokumen" => $no));
        $this->lainlain_model->unlocktables();
    }

    function InsertAllDetail($flag, $tipe, $no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket, $counter) { //echo $qty."/".$qtypcs."/".$qtydisplay."/".$flag."/".$pcodesave."=".$pcode;
        /* insert to mutasi
         * Update/insert stock
         * insert to trans_lainlain_detail
         */
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $adddateapl = date('Y-m-d H:i:s');
        $adddatestok = $tgl . " " . date('H:i:s');

        //$lokasi_dulu = $this->lainlain_model->getPastLocation($no,$pcodesave,$counter);
        $this->doAll($flag, $tipe, $no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket, $fieldmasuk, $fieldkeluar, $fieldakhir, $adddateapl, $adddatestok, $tahun, $counter);
    }

    function doAll($flag, $tipe, $no, $pcode, $qty, $hrg, $nil, $user, $tgl, $ket, $fieldmasuk, $fieldkeluar, $fieldakhir, $adddateapl, $adddatestok, $tahun, $counter) {
        if ($tipe == "masuk") {
            $this->updateStokMasuk($no, $pcode, $qty, $hrg, $fieldmasuk, $fieldakhir, $tahun, $adddateapl, $adddatestok, $counter);
        } else if ($tipe == "keluar") {//echo "keluar";
            $this->updateStokKeluar($pcode, $qty, $fieldkeluar, $fieldakhir, $tahun, $counter);
        }
//            }
        $this->insertTransDetail($flag, $no, $tipe, $pcode, $qty, $hrg, $nil, $user, $tgl, $adddateapl, $adddatestok, $counter);
        $this->insertMutasi($no, $tipe, $pcode, $qty, $hrg, $user, $tgl, $ket, $counter);
    }

    function updateStokMasuk($no, $pcode, $qty, $hrg, $fieldmasuk, $fieldakhir, $tahun, $adddateapl, $adddatestok, $counter) {
        $this->lainlain_model->locktables('stock,stock_detail');
        $stokawal = $this->lainlain_model->CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun);
        if (!empty($stokawal)) {
            $data = array(
                $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) $qty,
                $fieldakhir => (int) $stokawal->$fieldakhir + (int) $qty
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode, "Gudang" => $counter));
        } else {
            $data = array(
                'Tahun' => $tahun,
                'Gudang' => $counter,
                'KodeBarang' => $pcode,
                $fieldmasuk => $qty,
                $fieldakhir => $qty
            );
            $this->db->insert('stock', $data);
        }
        $this->lainlain_model->unlocktables();
    }

    function updateStokKeluar($pcode, $qty, $fieldkeluar, $fieldakhir, $tahun, $counter) {
//            print $fieldkeluar."/".$fieldakhir."/".$pcodebarang."/".$tahun."/".$lokasi."/".$qtypcs;
        $this->lainlain_model->locktables('stock');
        $stokawal = $this->lainlain_model->StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun);
        if (!empty($stokawal)) {
            $data = array(
                $fieldkeluar => (int) $stokawal->$fieldkeluar + (int) $qty,
                $fieldakhir => (int) $stokawal->$fieldakhir - (int) $qty
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode, "Gudang" => $counter));
        } else {
            $data = array(
                'Tahun' => $tahun,
                'Gudang' => $counter,
                'KodeBarang' => $pcode,
                $fieldkeluar => $qty,
                $fieldakhir => $qty * -1
            );
            $this->db->insert('stock', $data);
        }
        $this->lainlain_model->unlocktables();
    }

    function insertTransDetail($flag, $no, $tipe, $pcode, $qty, $hrg, $nil, $user, $tgl, $adddateapl, $adddatestok, $counter) {//print "detailnya";
        $this->lainlain_model->locktables('trans_lainlain_detail');

        $data = array(
            'NoDokumen' => $no,
            'PCode' => $pcode,
            'KdCounter' => $counter,
            'QtyPcs' => $qty,
            'Harga' => $hrg,
            'Netto' => $nil,
            'TglStockSimpan' => $adddatestok,
            'TglAplikasiStokSimpan' => $adddateapl,
            'AddDate' => $tgl,
            'AddUser' => $user
        );
        if ($flag == "edit") {
            $tgltrans = $this->session->userdata('Tanggal_Trans');
            $data['EditDate'] = $tgltrans;
            $data['EditUser'] = $user;
        }
        $this->db->insert('trans_lainlain_detail', $data);
        $this->lainlain_model->unlocktables();
    }

    function insertMutasi($no, $tipe, $pcode, $qty, $hrg, $user, $tgl, $ket, $counter) {
        $this->lainlain_model->locktables('mutasi');
        if ($tipe == "masuk") {
            $jenismutasi = "I";
            $kodetransaksi = "TL";
        }
        if ($tipe == "keluar") {
            $jenismutasi = "O";
            $kodetransaksi = "KL";
        }
        $dataekonomis = array(
            'KdTransaksi' => $kodetransaksi,
            'NoTransaksi' => $no,
            'Tanggal' => $tgl,
            'Kasir' => $user,
            'KodeBarang' => $pcode,
            'Gudang' => $counter,
            'Qty' => $qty,
            'Nilai' => $hrg,
            'Jenis' => $jenismutasi,
            'Keterangan' => $ket
        );
        $this->db->insert('mutasi', $dataekonomis);
        $this->lainlain_model->unlocktables();
    }

    function deleteAll($tipe, $no, $tahun, $fieldmasuk, $fieldkeluar, $fieldakhir) {//print $fieldmasuk."/".$fieldakhir."/".$pcode."/".$tahun."/".$lokasi;
//           echo $tipe.$qty;
        $this->lainlain_model->unlocktables();

        $lama = $this->lainlain_model->getdatalama($no);
//                print_r($lama);
        for ($x = 0; $x < count($lama); $x++) {
            $pcode = $lama->PCode;
            $qty = $lama->QtyPcs;
//		$this->lainlain_model->locktables('stock,mutasi,trans_lainlain_detail');
            if ($tipe == "masuk") {
                $kdtransaksi = "TL";
                $stokawal = $this->lainlain_model->CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun);
//                        print_r($stokawal);
                $data = array(
                    $fieldmasuk => (int) $stokawal->$fieldmasuk - (int) $qty,
                    $fieldakhir => (int) $stokawal->$fieldakhir - (int) $qty
                );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
            } else if ($tipe == "keluar") {
                $kdtransaksi = "KL";
                $stokawal = $this->lainlain_model->StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun);
                $data = array(
                    $fieldkeluar => (int) $stokawal->$fieldkeluar - (int) $qty,
                    $fieldakhir => (int) $stokawal->$fieldakhir + (int) $qty
                );
                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $lama->PCode));
            }
            $this->db->delete('trans_lainlain_detail', array("NoDokumen" => $no, "PCode" => $pcode));
            $this->db->delete('mutasi', array("KdTransaksi" => $kdtransaksi, "NoTransaksi" => $no, "KodeBarang" => $pcode));
        }
        $this->lainlain_model->unlocktables();
    }

    function delete_itemnya($tipe, $no, $pcode, $pcodebarang, $lokasi, $qty, $counter, $tahun, $noterima, $asaldata, $counterterima, $fieldmasuk, $fieldkeluar, $fieldakhir) {//print $fieldmasuk."/".$fieldakhir."/".$pcode."/".$tahun."/".$lokasi;
        $this->lainlain_model->locktables('stock,stock_detail,mutasi,trans_lainlain_detail');
        if ($tipe == "masuk") {
            $kdtransaksi = "TL";
            $stokawal = $this->lainlain_model->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
            $data = array(
                $fieldmasuk => (int) $stokawal->$fieldmasuk - (int) $qty,
                $fieldakhir => (int) $stokawal->$fieldakhir - (int) $qty
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "PCode" => $pcodebarang));
        } else if ($tipe == "keluar") {
            $kdtransaksi = "KL";
            $stokawal = $this->lainlain_model->StockKeluarAwal($fieldkeluar, $fieldakhir, $pcodebarang, $tahun);
            $data = array(
                $fieldkeluar => (int) $stokawal->$fieldkeluar - (int) $qty,
                $fieldakhir => (int) $stokawal->$fieldakhir + (int) $qty
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "PCode" => $pcodebarang));
        }
        $this->db->delete('mutasi', array("KdTransaksi" => $kdtransaksi, "NoTransaksi" => $no, "KodeBarang" => $pcodebarang, 'Counter' => $counter));
        $this->lainlain_model->unlocktables();
    }

    function delete_item() {
        $no = $this->input->post('kode');
        $mylib = new globallib();
//            $no = $this->uri->segment(4);
        $header = $this->lainlain_model->getHeader($no);
        $detail = $this->lainlain_model->getDetail($no);
//                print_r($detail);
        $tipe = $header->Tipe;
        $tgl = $mylib->ubah_tanggal($header->TglDokumen);
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        for ($x = 0; $x < count($detail); $x++) {
            $pcode = $detail[$x]['PCode'];
            $lokasi_dulu = $this->lainlain_model->getPastLocation($no, $pcode, $counter);
//                    print_r($lokasi_dulu);
            $this->delete_itemnya($tipe, $no, $pcode, $pcode, $lokasi_dulu->QtyPcs, $counter, $tahun, $lokasi_dulu->NoPenerimaan, $lokasi_dulu->AsalData, $lokasi_dulu->CounterStockPenerimaan, $fieldmasuk, $fieldkeluar, $fieldakhir);
        }
        $datanya = array(
            'NoDokumen' => $no . "D",
            'FlagDelete' => "Y"
        );
        $this->db->update('trans_lainlain_header', $datanya, array("NoDokumen" => $no));
//                $this->index();
    }

    function save_cetak_attr() {
        $no = $this->input->post('noterima');
        $qtyctk = $this->input->post('qtycetak');
        $attr_temp = explode("~", $qtyctk);
        $data = $this->varBarcode($no);
        $this->db->delete('trans_simpan_cetak', array("NoDokumen" => $no));
        for ($k = 0; $k < count($attr_temp) - 1; $k++) {
            $qty = $attr_temp[$k];
            $this->db->insert('trans_simpan_cetak', array("qtycetak" => $qty, "NoDokumen" => $no, "Counter" => $k));
        }
    }

}

?>
