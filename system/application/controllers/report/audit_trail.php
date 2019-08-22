<?php
class audit_trail extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->library('report_lib');
        $this->load->model('report/audit_trail_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $data['listbarang'] = $this->audit_trail_model->getBarang();
            $tgl = $this->audit_trail_model->getDate();
            $data['track'] = $mylib->print_track();
            $tanggal = $this->input->post("alt_date");
            $tahun = substr($tanggal, 3, 4);
            $bulan = substr($tanggal, 0, 2);
            $data['tanggal'] = $bulan . "-" . $tahun;
            $data['tahun'] = $tahun;
            $data['bulan'] = $bulan;
            $pcode = trim(strtoupper($this->input->post("kd1")));

            $wherebrg = "and KodeBarang= '$pcode'";

            $bulankemarin = date("m", mktime(0, 0, 0, $bulan - 1, 1, $tahun));
            $tahunkemarin = date("Y", mktime(0, 0, 0, $bulan - 1, 1, $tahun));
            $field = "QtyAkhir" . $bulankemarin;
            $fieldawal = "(QtyAwal" . $bulan . ") as Awal";
            $fieldawal0 = "Awal";

            if (!empty($tanggal)) {
                // echo "masuk";
                $data['detail'] = $this->audit_trail_model->getDetail($wherebrg, $bulan, $tahun, $fieldawal);
            } else {
                //  echo "tidak";
            }
            $data['content'] = 'report/audit_trail/views';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function getBarangByName() {
        $type = $this->input->post('type');
        $name = $this->input->post('name_startsWith');
        if (strlen($name) == 13) {
            $query = $this->audit_trail_model->getBarangBarcode($type, $name);
            $barcode = true;
        } else {
            $query = $this->audit_trail_model->getBarangName($type, $name);
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