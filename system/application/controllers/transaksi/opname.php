<?php
ob_start();
class opname extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/opnamemodel');
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

            $data['data'] = $this->opnamemodel->getList();
            $tanggal = $this->opnamemodel->getDate();
            $data['tanggal'] = $tanggal->TglTrans2;
            $data['track'] = $mylib->print_track();
            $data['content'] = 'transaksi/opname/opname_list';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function ajax_list($no) {
        $list = $this->opnamemodel->get_datatables($no);
        $data = array();
        foreach ($list as $value) {
            $row = array();
            $row['NoDokumen'] = $value['NoDokumen'];
            $row['PCode'] = $value['PCode'];
            $row['NamaLengkap'] = $value['NamaLengkap'];
            $row['QtyKomputer'] = $value['QtyKomputer'];
            $row['QtyOpname'] = $value['QtyOpname'];
            $row['Selisih'] = $value['Selisih'];
            $data[] = $row;
        }

        //  $output = array("NoDokumen" => $row['NoDokumen'], 'detail' => array($data));
        echo json_encode($data);
    }

    function ajax_add() {
        $mylib = new globallib();
        $user = $this->session->userdata('userid');
        $no = $this->input->post('nodok');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $pcode0 = $this->input->post('itemNo_0');
        $pcodesave0 = $this->input->post('pcodesave');
        $qtykom0 = $this->input->post('qty_Komp_0');
        $qty0 = $this->input->post('qty_Opname_0');
        $sisa0 = $this->input->post('sisa_0');
        $HJ0 = $this->input->post('HJ_0');
        $selisih = (int) $qty0 - (int) $qtykom0;
        if ($selisih < 0) {
            $HJ0 = "-" . $HJ0;
        } else {
            $HJ0 = $HJ0;
        }

        //$flag = $this->input->post('flag');
        $this->_validate();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            if ($no == "") { //proses insert di awal baris pertama dan nodokume masih kosong
                $new_no = $this->opnamemodel->getNewNo(substr($tgl, 0, 4));
                $no = $new_no->NoOpname;
                $this->db->update('aplikasi', array("NoOpname" => (int) $no + 1), array("Tahun" => substr($tgl, 0, 4)));

                $data_header = array(
                    'NoDokumen' => $no,
                    'TglDokumen' => $tgl,
                    'Keterangan' => $ket,
                    'AddDate' => date('Y-m-d'),
                    'AddUser' => $user
                );
                $data_detail = array(
                    'NoDokumen' => $no,
                    'PCode' => $pcode0,
                    'QtyKomputer' => $qtykom0,
                    'QtyOpname' => $qty0,
                    'Selisih' => $sisa0,
                    'HJualItem' => $HJ0,
                    'AddDate' => date('Y-m-d H:i:s'),
                    'AddUser' => $user
                );
                $this->db->insert('trans_opname_header', $data_header);
                $this->db->insert('trans_opname_detail', $data_detail);
            } else {  //proses insert di baris kedua dan nodokumen sudah ada
                $this->db->update('trans_opname_header', array('Keterangan' => $ket), array('NoDokumen' => $no));
                $query = $this->opnamemodel->get_detail_by_id($no, $pcode0);
                if ($query->num_rows() > 0) {
                    $arr_val['messages'] = array('<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                        . '<i class="glyphicon glyphicon-ok"></i>Kode Barang tersebut sudah pernah di input</div>');
                    $this->db->update('trans_opname_detail', array('QtyOpname' => $qty0, 'Selisih' => $sisa0, 'HJualItem' => $HJ0, 'EditDate' => date('Y-m-d H:i:s')), array('NoDokumen' => $no, 'PCode' => $pcode0));
                } else {
                    $data_detail = array(
                        'NoDokumen' => $no,
                        'PCode' => $pcode0,
                        'QtyKomputer' => $qtykom0,
                        'QtyOpname' => $qty0,
                        'Selisih' => $sisa0,
                        'HJualItem' => $HJ0,
                        'AddDate' => date('Y-m-d H:i:s'),
                        'AddUser' => $user
                    );
                    $this->db->insert('trans_opname_detail', $data_detail);
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
        $this->opnamemodel->delete_by_id($no, $pcode);
        echo json_encode(array('success' => true));
    }

    function _validate() {
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_rules('itemNo_0', 'itemNo_0', 'trim|required');
        $this->form_validation->set_rules('qty_Opname_0', 'qty_Opname_0', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
    }

    function _validate_proses() {
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
    }

    function stock_check($id) {
        $dtstok = $this->opnamemodel->get_kdoutlet($id);
        foreach ($dtstok->result() as $row) {
            $dt = $row->KdOutlet;
            if ($id == $dt) {
                $this->validation->set_message('kdoutlet_check', '<font color=red>* Kode Outlet Sudah Ada</font>');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    function getBarangByName() {
        $type = $this->input->post('type');
        $name = $this->input->post('name_startsWith');
        $tanggal = $this->session->userdata('Tanggal_Trans');
        $bulan = substr($tanggal, 3, 2);
        $tahun = substr($tanggal, 6, 4);
        $field = "QtyAkhir" . $bulan;
        if (strlen($name) == 13) {
            $query = $this->opnamemodel->getBarangBarcode($type, $name, $tahun, $field);
            $barcode = true;
        } else {
            $query = $this->opnamemodel->getBarangName($type, $name, $tahun, $field);
            $barcode = false;
        }

        $results = array();
        foreach ($query as $row) {
            $name = $row['PCode'] . '|' . $row['PCode1'] . '|' . $row['NamaLengkap'] . '|' . $row['QtyAkhir' . $bulan] . '|' . $row['HargaJual'] . '|' . $barcode;
            array_push($results, $name);
        }

        echo json_encode($results);
        exit;
    }

    function ajax_save_proses() {
        $mylib = new globallib();
        $no = $this->input->post('nodok');
        $user = $this->session->userdata('username');
        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;

        $this->_validate_proses();
        $arr_val = array('success' => false, 'messages' => array());

        ///cek smua pcode yg ada di table stok
        $list_stock = $this->opnamemodel->getlistStock($tahun, $fieldakhir);
        $list_opname = $this->opnamemodel->get_datatables($no);

        $pcode_opname = array();
        $pcode_stock = array();
        foreach ($list_opname as $opname) {
            $pcode_opname[] = $opname['PCode'];
        }
        foreach ($list_stock as $stock) {
            $pcode_stock[] = $stock['KodeBarang'];
        }
        $result = array_diff($pcode_stock, $pcode_opname);

        $pcode_sisa = $sep = '';
        foreach ($result as $key => $value) {
            $pcode_sisa .= $sep . $value;
            $sep = ', ';
        }

        //jika pcode yg di opname detail sudah memenuhi dgn kode barang di stock maka eksekusi...
        if (empty($result)) {
            if ($this->form_validation->run()) {
                $list = $this->opnamemodel->get_selisih_by_id($no);
                foreach ($list as $value) {
                    $no = $value['NoDokumen'];
                    $pcode = $value['PCode'];
                    $qtykomp = $value['QtyKomputer'];
                    $qtyopname = $value['QtyOpname'];
                    $selisih = $value['Selisih'];
                    $HJ = $value['HJualItem'];
                    //$jenismutasi = ($selisih > 0 ? $jenismutasi = "O" : $jenismutasi = "I");
                    if ($selisih < 0) {
                        $jenismutasi = "O";
                        $stokawal = $this->opnamemodel->CekStock($fieldkeluar, $fieldakhir, $pcode, $tahun);
                        $data = array(
                            $fieldkeluar => (int) $stokawal->$fieldkeluar + (int) abs($selisih),
                            $fieldakhir => (int) $stokawal->$fieldakhir - (int) abs($selisih)
                        );
                        $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                    } else {
                        $jenismutasi = "I";
                        $stokawal = $this->opnamemodel->CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun);
                        $data = array(
                            $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) $selisih,
                            $fieldakhir => (int) $stokawal->$fieldakhir + (int) $selisih
                        );
                        $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcode));
                    }

                    $dataekonomis = array(
                        'KdTransaksi' => "OP",
                        'NoTransaksi' => $no,
                        'Tanggal' => $tgl,
                        'KodeBarang' => $pcode,
                        'Qty' => abs($selisih),
                        'Jenis' => $jenismutasi,
                        'Nilai' => abs($HJ),
                        'Kasir' => $user,
                        'Keterangan' => $ket
                    );
                    $this->db->insert('mutasi', $dataekonomis);
                }
                $this->db->update('trans_opname_header', array('Keterangan' => $ket, 'FlagProses' => '1'), array('NoDokumen' => $no));
                $arr_val = array('success' => true);
                $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                        . '<i class="glyphicon glyphicon-ok"></i> Opaname berhasil di proses </div>');
                // redirect('transaksi/opname');
            } else {
                foreach ($_POST as $key => $value) {
                    $arr_val['messages'][$key] = form_error($key);
                }
            }
            redirect('transaksi/opname');
        } else {
            $this->session->set_flashdata('msg_proses', '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="ace-icon fa fa-times"></i> <b>Kode Barang Yang Belum di input</b><br>' . $pcode_sisa . '</div>');
            redirect('transaksi/opname/edit_opname/' . $no . '/');
        }

        echo json_encode($arr_val);
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['save_methode'] = 'add';
            $data['tanggal'] = $this->opnamemodel->getDate();
            $data['content'] = 'transaksi/opname/opname_add';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function edit_opname($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $id = $this->uri->segment(4);
            $data['header'] = $this->opnamemodel->getHeader($id);
            $data['detail'] = $this->opnamemodel->get_datatables($id);
            $data['content'] = 'transaksi/opname/opname_edit';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }
    
    function export() {
        $no = $this->uri->segment(4);
        $data['header'] = $this->opnamemodel->getHeader($no);
        $data['detail'] = $this->opnamemodel->getDetailForPrint($no);
        //$data['content'] = 'transaksi/opname/opname_export';
        $this->load->view('transaksi/opname/opname_export', $data);
    }

    function cetak() {
        $data = $this->varCetak();
        $this->load->view('transaksi/cetak_transaksi/cetak_opname', $data);
    }

    function versistruk() {
        $data = $this->varCetak();
        $no = $this->uri->segment(4);
        $printer = $this->opnamemodel->NamaPrinter($_SERVER['REMOTE_ADDR']);

        $data['ip'] = $printer[0]['ip'];
        $data['nm_printer'] = $printer[0]['nm_printer'];
        $data['store'] = $this->opnamemodel->aplikasi();
        $data['header'] = $this->opnamemodel->getHeader($no);
        $data['detail'] = $this->opnamemodel->getDetailForPrint($no);
//print_r($data['header']);
        //			$data['detail']		= $this->tutup_hari_model->det_trans($no);
//die();
//                        $data['ip']    = "\\\\".."\LQ-2170s";

        if (!empty($data['header'])) {
//		$this->load->view('proses/cetak_tutup',$data); // jika untuk tes
            $this->load->view('transaksi/opname/cetak_strukopname', $data); // jika ada printernya
        }
    }

    function printThis() {
        $data = $this->varCetak();
        $id = $this->uri->segment(4);
        $data['fileName2'] = "opname.sss";
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
        $header = $this->opnamemodel->getHeader($id);
        $data['header'] = $header;
        $detail = $this->opnamemodel->getDetailForPrint($id);
        $data['judul1'] = array("NoOpname", "Keterangan");
        $data['niljudul1'] = array($header->NoDokumen, stripslashes($header->Keterangan));
        $data['judul2'] = array("TglOpname");
        $data['niljudul2'] = array($header->TglDokumen);
        $data['judullap'] = "OPNAME";
        //$data['url'] = "opname/printThis/" . $id;
        $data ['url2'] = "opname/versistruk/" . $id;
        $data ['colspan_line'] = 4;
        $data['tipe_judul_detail'] = array("normal", "normal", "kanan", "kanan", "kanan", "kanan");
        $data['judul_detail'] = array("Kode", "Nama Barang", "QtyKomputer", "QtyOpname", "QtySelisih", "Harga");
        $data['panjang_kertas'] = 33;
        $jmlh_baris_lain = 21;
        $data['panjang_per_hal'] = (int) $data['panjang_kertas'] - (int) $jmlh_baris_lain;
        $jml_baris_detail = count($detail) + $this->opnamemodel->getCountDetail($id);
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
            $hasilkom = $detail[$m]['QtyKomputer'];
            $hasilopn = $detail[$m]['QtyOpname'];
//$hasilselisih   = abs($detail[$m]['Selisih']);
            $hasilselisih = $detail[$m]['Selisih'];
            if ($detail[$m]['Selisih'] == 0) {
                $hasilselisih = "" . $hasilselisih;
                //$net = ($detail[$m]['HJ']);
                $net = 0;
            } elseif ($detail[$m]['Selisih'] < 0) {
                $net = "-" . ($detail[$m]['Netto']);
            } elseif ($detail[$m]['Selisih'] > 0) {
                $net = ($detail[$m]['Netto']);
            }
            $bayar = $bayar + $net;
            unset($list_detail);
            $counterRow++;
            $list_detail[] = stripslashes($detail[$m]['PCode']);
            $list_detail[] = stripslashes($detail[$m]['NamaInitial']);
            $list_detail[] = $hasilkom;
            $list_detail[] = $hasilopn;
            $list_detail[] = $hasilselisih;
            $list_detail[] = $net;
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
        $data['detail'][] = $detail_page;
        $data['max_field_len'] = $max_field_len;
        $data['banyakBarang'] = $counterRow;
        $data['bayar'] = $bayar;
        return $data;
    }

//    function getRealPCode() {
//        $kode = $this->input->post('pcode');
//        if (strlen($kode) == 13) {
//            $mylib = new globallib();
//            $hasil = $mylib->findBarcode($kode);
//            $pcode = $hasil;
//        } else {
//            $valpcode = $this->opnamemodel->ifPCodeBarcode($kode);
//            if (count($valpcode) != 0) {
//                $pcode = $valpcode->PCode;
//            } else {
//                $pcode = "";
//            }
//        }
//        echo $pcode;
//    }
//
//    function getPCode() {
//        $kode = $this->input->post('pcode');
//        if (strlen($kode) == 13) {
//            $mylib = new globallib();
//            $mylib = new globallib();
//            $hasil = $mylib->findBarcode($kode);
//            $pcode = $hasil;
//        } else {
//            $valpcode = $this->opnamemodel->ifPCodeBarcode($kode);
//            $pcode = $valpcode->PCode;
//        }
//        if (!empty($pcode)) {
//
//
//            $tanggal = $this->session->userdata('Tanggal_Trans');
//            $bulan = substr($tanggal, 3, 2);
//            $tahun = substr($tanggal, 6, 4);
//            $field = "QtyAkhir" . $bulan;
//            $detail = $this->opnamemodel->getPCodeDet($pcode, $tahun, $field);
////                                print $detail->QtyAkhir06;
//            $nilai = $detail->$field . "||" . $detail->NamaLengkap . "||" . $pcode . "||" . $detail->HargaJual;
//        } else {
//            $nilai = "";
//        }
//        echo $nilai;
//    }
//
//    function getlistBarang() {
//        $tanggal = $this->session->userdata('Tanggal_Trans');
//        $bulan = substr($tanggal, 3, 2);
//        $tahun = substr($tanggal, 6, 4);
//        $field = "QtyAkhir" . $bulan;
//        $detail = $this->opnamemodel->getlistStock($tahun, $field);
//        $nilai = "";
//        for ($a = 0; $a < count($detail); $a++) {
//            $nilai .= $detail[$a]['Qty'] . "||" . $detail [$a]['NamaLengkap'] . "||" . $detail [$a]['KodeBarang'] . "||" . $detail [$a]['HargaJual'] . "**";
//        }
//        echo count($detail) . "##" . $nilai;
//    }
//
//    function save_new_opname() {
//        $mylib = new globallib();
//        $user = $this->session->userdata('userid');
////$user = $mylib->getUser();
//        $no = $this->input->post('nodok');
//        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
//        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
//        $pcode1 = $this->input->post('pcode');
//        $pcodesave1 = $this->input->post('pcodesave');
//        $qtykom1 = $this->input->post('qtykom');
//        $qty1 = $this->input->post('qty');
//        $sisa1 = $this->input->post('sisa');
//        $HJ1 = $this->input->post('HJ');
//        $flag = $this->input->post('flag');
////$this->db->trans_start();
//        if ($no == "") {
//// $no = $this->InsertHeader($no, $tgl, $ket, $user);
//        } else {
//            $this->updateHeader($flag, $no, $ket, $user);
//        }
//        for ($x = 0; $x < count($pcode1); $x++) {
//            if ($pcode1[$x] != "") {
//                $pcode = strtoupper(addslashes(trim($pcode1[$x])));
//                $qtykom = $qtykom1[$x];
//                $qty = trim($qty1[$x]);
//                $sisa = $sisa1[$x];
//                $HJ = $HJ1[$x];
//                $pcodesave = $pcodesave1[$x];
//// $this->InsertAllDetail($flag, $no, $pcode, $pcodesave, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket);
//            }
//        }
//        echo json_encode(array("status" => true));
////$this->db->trans_complete();
////   redirect("/transaksi/opname/");
//    }
//
//    function save_new_item() {
//        $mylib = new globallib();
//        $user = $this->session->userdata('userid');
//        $no = $this->input->post('no');
//        $tgl = $mylib->ubah_tanggal($this->input->post('tgl'));
//        $lokasi = $this->input->post("lokasi");
//        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
//        $pcode = strtoupper(addslashes(trim($this->input->post('pcode'))));
//        $satuankom = $this->input->post("satuankom");
//        $qtykom = trim($this->input->post('qtykom'));
//        $satuanop = $this->input->post("satuanop");
//        $qty = trim($this->input->post('qty'));
//        $counter = trim($this->input->post('counter'));
//        $qtypcsop = $this->input->post('qtypcsop');
//        $qtypcskom = $this->input->post('qtypcskom');
//        $konverbk = $this->input->post('konverbk');
//        $konvertk = $this->input->post('konvertk');
//        $attr = $this->input->post('attr');
//        $pcodesave = $this->input->post('savepcode');
//        $pcodebarang = $this->input->post('pcodebarang');
//        $noterima = $this->input->post('noterima');
//        $counterterima = $this->input->post('counterterima');
//        $asaldata = $this->input->post('asaldata');
//        $flag = $this->input->post('flag');
//        $this->db->trans_start();
//        if ($no == "") {
//            $no = $this->InsertHeader($no, $tgl, $lokasi, $ket, $user);
//        } else {
//            $this->updateHeader($flag, $no, $ket, $user);
//        }
//        $counternew = $this->InsertAllDetail($flag, $no, $pcode, $pcodesave, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket);
//        $data = array
//            (
//            'FlagUsed' => "S",
//        );
//        if ($asaldata == "O") {
//            $this->db->update('trans_simpan_header', $data, array("NoPenerimaan" => $noterima));
//        } else if ($asaldata == "L") {
//            $this->db->update('trans_lainlain_header', $data, array("NoDokumen" => $noterima));
//        }
//        $this->db->trans_complete();
//        echo $no . "**" . $counternew;
//    }
//
//    function InsertHeader($no, $tgl, $ket, $user) {
//        $this->opnamemodel->locktables('aplikasi,trans_opname_header');
//        $new_no = $this->opnamemodel->getNewNo(substr($tgl, 0, 4));
//        $no = $new_no->NoOpname;
//        $this->db->update('aplikasi', array("NoOpname" => (int) $no + 1), array("Tahun" => substr($tgl, 0, 4)));
//        $data = array(
//            'NoDokumen' => $no,
//            'TglDokumen' => $tgl,
//            'Keterangan' => $ket,
//            'AddDate' => date('Y-m-d'), 'AddUser' => $user
//        );
//        $this->db->insert('trans_opname_header', $data);
//        $this->opnamemodel->unlocktables();
//        return $no;
//    }
//
//    function updateHeader($flag, $no, $ket, $user) {
//        $this->opnamemodel->locktables('trans_opname_header');
//        $tgltrans = $this->session->userdata('Tanggal_Trans');
//        $data = array('Keterangan' => $ket,
//        );
//        if ($flag == "edit") {
//            $data['EditDate'] = $tgltrans;
//            $data['EditUser'] = $user;
//        }
//        $this->db->update('trans_opname_header', $data, array("NoDokumen" => $no));
//        $this->opnamemodel->unlocktables();
//    }
//
//    function InsertAllDetail($flag, $no, $pcode, $pcodesave, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket) {
//        if ($flag == "add") {
//            $counternew = $this->doAll($flag, $no, $pcode, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket);
//        } else {
//            if ($pcodesave == $pcode) {//ubah qty opname
//                $cekdulu = $this->opnamemodel->cekPast($no, $pcode);
////				echo $qty." ".$cekdulu->QtyOpname;die();
//                if ($qty != $cekdulu->QtyOpname) {
//                    $this->deleteAll($no, $tgl, $pcode, $pcodesave, $cekdulu->Selisih);
//                    $this->doAll($flag, $no, $pcode, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket);
//                } else {
//                    if ($flag == "edit") {
//                        $tgltrans = $this->session->userdata('Tanggal_Trans');
//                        $data = array(
//                            'EditDate' => $tgltrans,
//                            'EditUser' => $user
//                        );
//                        $this->db->update("trans_opname_detail", $data, array("NoDokumen" => $no));
//                    }
//                }
//            } else {
//                $cekdulu = $this->opnamemodel->cekPast($no, $pcodesave);
////                                print_r($cekdulu);die();
////$pcodebarang_dulu = $this->opnamemodel->ifPCodeBarcode($pcodesave);
//                $this->deleteAll($no, $tgl, $pcode, $pcodesave, $cekdulu->Selisih);
//                $this->doAll($flag, $no, $pcode, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket);
//            }
//        }
//    }
//
//    function doAll($flag, $no, $pcode, $qtykom, $qty, $sisa, $HJ, $user, $tgl, $ket) {
//        $bulan = substr($tgl, 5, 2);
//        $tahun = substr($tgl, 0, 4);
//        $fieldmasuk = "QtyMasuk" . $bulan;
//        $fieldakhir = "QtyAkhir" . $bulan;
//        $fieldkeluar = "QtyKeluar" . $bulan;
////$selisih = (int)$qtykom - (int)$qty;
//        $selisih = (int) $qty - (int) $qtykom;
//        if ($selisih < 0) {
//            $HJ = "-" . $HJ;
//        } else {
//            $HJ = $HJ;
//        }
//        $this->updateStok($pcode, $tahun, $selisih, $fieldmasuk, $fieldkeluar, $fieldakhir);
//        $this->insertTransDetail($flag, $no, $pcode, $qtykom, $qty, $selisih, $HJ, $user, $tgl);
//        $this->insertMutasi($no, $tgl, $pcode, $ket, $selisih, $user, $HJ);
//    }
//
//    function updateStok($pcodebarang, $tahun, $selisih, $fieldmasuk, $fieldkeluar, $fieldakhir) {
//        if ($selisih != 0) {
//            if ($selisih > 0) {
//                $stokawal = $this->opnamemodel->CekStock($fieldkeluar, $fieldakhir, $pcodebarang, $tahun);
//                $data = array($fieldkeluar => (int) $stokawal->$fieldkeluar + (int) $selisih,
//                    $fieldakhir => (int) $stokawal->$fieldakhir - (int) $selisih
//                );
//                $this->db->update('stock', $data, array("Tahun" =>
//                    $tahun, "KodeBarang" => $pcodebarang));
//            } else {
//                $stokawal = $this->opnamemodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun)
//
//                ;
//                $data = array(
//                    $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) abs($selisih),
//                    $fieldakhir => (int) $stokawal->$fieldakhir + (int) abs($selisih)
//                );
//                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
//            }
//        }
//    }
//
//    function insertTransDetail($flag, $no, $pcode, $qtykom, $qty, $selisih, $HJ, $user, $tgl) {
//        $this->opnamemodel->locktables('trans_opname_detail');
//
//        $data = array("NoDokumen" => $no,
//            "PCode" => $pcode,
//            "QtyKomputer" => $qtykom,
//            "QtyOpname" => $qty,
//            "Selisih" => $selisih,
//            "HJualItem" => $HJ,
//            "AddDate" => $tgl,
//            "AddUser" => $user,
//        );
//        if ($flag == "edit") {
//            $tgltrans = $this->session->userdata('Tanggal_Trans');
//            $data['EditDate'] = $tgltrans;
//            $data[
//                    'EditUser'] = $user;
//        }
//        $this->db->insert("trans_opname_detail", $data);
//
//        $this->opnamemodel->unlocktables();
//    }
//
//    function insertMutasi($no, $tgl, $pcode, $ket, $selisih, $user, $HJ) {
//        if ($selisih != 0) {
//            if ($selisih > 0) {
//                $jenismutasi = "O";
//            } else {
//                $jenismutasi = "I";
//            }
//
//            $dataekonomis = array(
//                'KdTransaksi' => "OP"
//                ,
//                'NoTransaksi' => $no,
//                'Tanggal' => $tgl,
//                'KodeBarang' => $pcode,
//                'Qty' => abs($selisih),
//                'Jenis' => $jenismutasi,
//                'Nilai' => abs($HJ),
//                'Kasir' => $user,
//                'Keterangan' => $ket
//            );
//            $this->db->insert('mutasi', $dataekonomis);
//        }
//    }
//
//    function deleteAll($no, $tgl, $pcode, $pcodebarang, $selisih) {//echo $tgl;die();
//        $bulan = substr($tgl, 5, 2);
//        $tahun = substr($tgl, 0, 4);
//        $fieldmasuk = "QtyMasuk" . $bulan;
//        $fieldakhir = "QtyAkhir" . $bulan;
//        $fieldkeluar = "QtyKeluar" . $bulan;
//        if ($selisih != 0) {
//            if ($selisih > 0) {
//                $stokawal = $this->opnamemodel->CekStock($fieldkeluar, $fieldakhir, $pcodebarang, $tahun);
//                $data = array(
//                    $fieldkeluar => (int) $stokawal->$fieldkeluar - (int) $selisih,
//                    $fieldakhir => (int) $stokawal->$fieldakhir + (int) $selisih
//                );
//                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
//            } else {
//                $stokawal = $this->opnamemodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
//                $data = array(
//                    $fieldmasuk => (int) $stokawal->$fieldmasuk - (int) abs($selisih),
//                    $fieldakhir => (int) $stokawal->$fieldakhir - (int) abs($selisih)
//                );
//                $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
//            }
//            $this->db->delete("mutasi", array("KdTransaksi" => "OP", "NoTransaksi" => $no, "KodeBarang" => $pcodebarang));
//        }
//        if ($pcode != $pcodebarang) {
//            $this->db->delete("trans_opname_detail", array("NoDokumen" => $no, "PCode" => $pcodebarang));
//        }
//    }
}

?>
   