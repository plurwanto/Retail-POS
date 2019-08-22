<?php

class proses_opname extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('transaksi/opnamemodel');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);
            $id = $this->input->post('stSearchingKey');
            $id2 = $this->input->post('date1');
            $with = $this->input->post('searchby');
            if ($with == "TglDokumen") {
                $id = $mylib->ubah_tanggal($id2);
            }
            $this->load->library('pagination');

            $config['full_tag_open'] = '<div class="pagination">';
            $config['full_tag_close'] = '</div>';
            $config['cur_tag_open'] = '<span class="current">';
            $config['cur_tag_close'] = '</span>';
            $config['per_page'] = '14';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['num_links'] = 2;
            $config['base_url'] = base_url() . 'index.php/transaksi/proses_opname/index/';
            $page = $this->uri->segment(4);
            $config['uri_segment'] = 4;
            $flag1 = "";
            if ($with != "") {
                if ($id != "" && $with != "") {
                    $config['base_url'] = base_url() . 'index.php/transaksi/proses_opname/index/' . $with . "/" . $id . "/";
                    $page = $this->uri->segment(6);
                    $config['uri_segment'] = 6;
                } else {
                    $page = "";
                }
            } else {
                if ($this->uri->segment(5) != "") {
                    $with = $this->uri->segment(4);
                    $id = $this->uri->segment(5);
                    if ($with == "TglDokumen") {
                        $id = $mylib->ubah_tanggal($id);
                    }
                    $config['base_url'] = base_url() . 'index.php/transaksi/proses_opname/index/' . $with . "/" . $id . "/";
                    $page = $this->uri->segment(6);
                    $config['uri_segment'] = 6;
                }
            }
            $data['header'] = array("No Dokumen", "Tanggal", "Keterangan");
            $config['total_rows'] = $this->opnamemodel->num_row(addslashes($id), $with);
            $data['data'] = $this->opnamemodel->getList($config['per_page'], $page, addslashes(trim($id)), $with);
            $tanggal = $this->opnamemodel->getDate();
            $data['tanggal'] = $tanggal->TglTrans2;
            $data['track'] = $mylib->print_track();
            $this->pagination->initialize($config);
            $this->load->view('transaksi/opname/proses_opname_list', $data);
        } else {
            $this->load->view('denied');
        }
    }

//    function add_new() {
//        $mylib = new globallib();
//        $sign = $mylib->getAllowList("add");
//        if ($sign == "Y") {
//            $data['tanggal'] = $this->opnamemodel->getDate();
//            $data['lokasi'] = $this->opnamemodel->getLokasi();
//            $this->load->view('transaksi/opname/opname_add', $data);
//        } else {
//            $this->load->view('denied');
//        }
//    }
//
    function proses_edit_opname($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['header'] = $this->opnamemodel->getHeader($id);
            $data['detail'] = $this->opnamemodel->getDetail($id);
            $this->load->view('transaksi/opname/proses_opname_edit', $data);
        } else {
            $this->load->view('denied');
        }
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
        $data['url'] = "opname/printThis/" . $id;
        $data['url2'] = "opname/versistruk/" . $id;
        $data['colspan_line'] = 4;
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
            $hasilselisih = abs($detail[$m]['Selisih']);
            $net = ($detail[$m]['Netto']);
            $bayar = $bayar + $net;
            if ($detail[$m]['Selisih'] < 0) {
                $hasilselisih = "" . $hasilselisih;
            }
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

    function getRealPCode() {
        $kode = $this->input->post('pcode');
        if (strlen($kode) == 13) {
            $mylib = new globallib();
            $hasil = $mylib->findBarcode($kode);
            $pcode = $hasil;
        } else {
            $valpcode = $this->opnamemodel->ifPCodeBarcode($kode);
            if (count($valpcode) != 0) {
                $pcode = $valpcode->PCode;
            } else {
                $pcode = "";
            }
        }
        echo $pcode;
    }

    function getPCode() {
        $kode = $this->input->post('pcode');
        if (strlen($kode) == 13) {
            $mylib = new globallib();
            $mylib = new globallib();
            $hasil = $mylib->findBarcode($kode);
            $pcode = $hasil;
        } else {
            $valpcode = $this->opnamemodel->ifPCodeBarcode($kode);
            $pcode = $valpcode->PCode;
        }
        if (!empty($pcode)) {


            $tanggal = $this->session->userdata('Tanggal_Trans');
            $bulan = substr($tanggal, 3, 2);
            $tahun = substr($tanggal, 6, 4);
            $field = "QtyAkhir" . $bulan;
            $detail = $this->opnamemodel->getPCodeDet($pcode, $tahun, $field);
//                                print $detail->QtyAkhir06;
            $nilai = $detail->$field . "||" . $detail->NamaLengkap . "||" . $pcode . "||" . $detail->HargaJual;
        } else {
            $nilai = "";
        }
        echo $nilai;
    }

    function ProsesToOpname() {
        $mylib = new globallib();
        $id = $this->input->post('nodok');
        $tgl2 = $this->session->userdata('Tanggal_Trans');
        $tgl = $mylib->ubah_tanggal($tgl2);
        $user = $this->session->userdata('userid');
        $ket = trim(strtoupper(addslashes($this->input->post('ket'))));
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;
        $detail = $this->opnamemodel->getDetailSelisih($id);

        foreach ($detail as $s) {
            $no = $s['NoDokumen'];
            $pcode = $s['PCode'];
            $selisih = $s['Selisih'];
            $HJ = $s['HJualItem'];

            if ($selisih > 0) {
                $jenismutasi = "O";
            } else {
                $jenismutasi = "I";
            }

            $this->insertMutasi($no, $jenismutasi, $tgl, $pcode, $selisih, $HJ, $user, $ket);
            $this->updateStok($pcode, $tahun, $selisih, $fieldmasuk, $fieldkeluar, $fieldakhir);
        }

        if ($no != 0) {
            $flagupdate = array(
                'FlagProses' => "1");
        }
        $this->db->update('trans_opname_header', $flagupdate, array("NoDokumen" => $no));
        redirect('transaksi/proses_opname/');
    }

    function insertMutasi($no, $jenismutasi, $tgl, $pcode, $selisih, $HJ, $user, $ket) {
        $dataekonomis = array(
            'KdTransaksi' => "OP",
            'NoTransaksi' => $no,
            'Jenis' => $jenismutasi,
            'Tanggal' => $tgl,
            'KodeBarang' => $pcode,
            'Qty' => abs($selisih),
            'Nilai' => $HJ,
            'Kasir' => $user,
            'Keterangan' => $ket
        );
        $this->db->insert('mutasi', $dataekonomis);
    }

    function updateStok($pcodebarang, $tahun, $selisih, $fieldmasuk, $fieldkeluar, $fieldakhir) {
        //  if ($selisih != 0) {
        if ($selisih > 0) {
            $stokawal = $this->opnamemodel->CekStock($fieldkeluar, $fieldakhir, $pcodebarang, $tahun);
            $data = array(
                $fieldkeluar => (int) $stokawal->$fieldkeluar + (int) $selisih,
                $fieldakhir => (int) $stokawal->$fieldakhir - (int) $selisih
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
        } else {
            $stokawal = $this->opnamemodel->CekStock($fieldmasuk, $fieldakhir, $pcodebarang, $tahun);
            $data = array(
                $fieldmasuk => (int) $stokawal->$fieldmasuk + (int) abs($selisih),
                $fieldakhir => (int) $stokawal->$fieldakhir + (int) abs($selisih)
            );
            $this->db->update('stock', $data, array("Tahun" => $tahun, "KodeBarang" => $pcodebarang));
        }
        // }
    }


}

?>
