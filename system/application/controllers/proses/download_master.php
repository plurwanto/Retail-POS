<?php

class download_master extends Controller {//update stock berdasarkan mutasi

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/download_master_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['msg'] = "";
            if ($this->input->post()) {
                $data['tgl_1'] = $this->input->post('tgl_1');
                $data['tgl_2'] = $this->input->post('tgl_2');
            }
            $data['tglawal'] = date('d-m-Y');
            $data['tgl_2'] = date('d-m-Y');
            $this->load->view('proses/upload_data/upload_master', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function upload_csv() {
//    echo "OK"; die();
        $eksekusi = $this->download_master_model->upload_csv_id();
        if ($eksekusi == true) {
            $this->session->set_flashdata('msg', 'Data Berhasil di Masukan');
            redirect('proses/download_master/');
        } else {
            $this->session->set_flashdata('msg', 'Data Gagal di Masukan');
            redirect('proses/download_master/');
        }
    }

    function doThis() {
        $tgl2 = $this->input->post("tgl");
        $mylib = new globallib();
        //
//           $tgl2 = $this->session->userdata('Tanggal_Trans');
        $tgl = $mylib->ubah_tanggal($tgl2);
        $bulan = substr($tgl, 5, 2);
        $tahun = substr($tgl, 0, 4);
        $fieldmasuk = "QtyMasuk" . $bulan;
        $fieldakhir = "QtyAkhir" . $bulan;
        $fieldkeluar = "QtyKeluar" . $bulan;

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
        echo "Update Stock Bulan $bulan Tahun $tahun Berhasil";
//            print_r($nil);
//            die();
        return;
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

    function cabang() {
        $kdC = $this->kirim_data_model->FindCabang();
//            print_r($kdC);
        $csv_terminated = "\n";
        $csv_separator = "|";
        $batasjudul = "##";
        $no = $kdC->KdCabang;
        $kd = "KdCabang=$no$batasjudul$csv_terminated";
        return $kd;
    }

    function getArrayResult($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

}

?>