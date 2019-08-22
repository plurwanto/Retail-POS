<?php
class stok_barang extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->library('report_lib');
        $this->load->model('report/stok_barang_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $mylib = new globallib();
            $tgl = $this->stok_barang_model->getDate();
            $tanggal = $this->input->post("alt_date");
            $kdbrg1 = $this->input->post("kdbrg1");
            $kdbrg2 = $this->input->post("kdbrg2");
            $tahun = substr($tanggal, 3, 4);
            $bulan = substr($tanggal, 0, 2); 
//            $thn = substr($tgl->TglTrans, 0, 4);
//            $bln = substr($tgl->TglTrans, 5, 2); 
//            $data['date'] = $bln."-".$thn; 
            
            $data['kdbrg1'] = $kdbrg1;
            $data['kdbrg2'] = $kdbrg2;
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;

            $tabel = "stock";
            $judul[] = "Rekap Stok Barang";
            $judul[] = "Bulan: $bulan || Tahun : $tahun";
            $wherebrg = "";
            $wherelokasi = "";
            $fieldawal = "QtyAwal" . $bulan;
            $fieldmasuk = "QtyMasuk" . $bulan;
            $fieldkeluar = "QtyKeluar" . $bulan;
            $fieldakhir = "QtyAkhir" . $bulan;
            $data['awal'] = $fieldawal;
            $data['masuk'] = $fieldmasuk;
            $data['keluar'] = $fieldkeluar;
            $data['akhir'] = $fieldakhir;

            $data['detail'] = "";
            if (!empty($kdbrg1)) {
                $wherebrg = "and KodeBarang between '$kdbrg1' and '$kdbrg2'";
                $brg1 = $this->stok_barang_model->getNamaBarang($kdbrg1)->NamaLengkap;
                $brg2 = $this->stok_barang_model->getNamaBarang($kdbrg2)->NamaLengkap;
                $judul[] = "Barang = $brg1 s/d $brg2";
            }
            if (!empty($tanggal)) {
                $data['display1'] = "";
                $data['detail'] = $this->stok_barang_model->getRekapTrans($wherebrg, $wherelokasi, $fieldawal, $fieldmasuk, $fieldkeluar, $fieldakhir, $tahun, $tabel);
            }
            //echo $bulan . "-" . $tahun;
            $data['track'] = $mylib->print_track();
            $data['display1'] = "display:none";
            $data['content'] = 'report/stok_barang/views';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

   function getBarangByName() {
        $type = $this->input->post('type');
        $name = $this->input->post('name_startsWith');
        if (strlen($name) == 13) {
            $query = $this->stok_barang_model->getBarangBarcode($type, $name);
            $barcode = true;
        } else {
            $query = $this->stok_barang_model->getBarangName($type, $name);
            $barcode = false;
        }

        $results = array();
        foreach ($query as $row) {
            $name = $row['PCode'] . '|' . $row['PCode1'] . '|' . $row['NamaLengkap'] . '|' . $barcode;
            array_push($results, $name);
        }

        echo json_encode($results);
        exit;
    }

}

?>