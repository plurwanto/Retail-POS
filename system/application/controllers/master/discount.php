<?php
class discount extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/discountmodel');
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
            $data['track'] = $mylib->print_track();
            $data['data'] = $this->discountmodel->getdiscountList();
            $data['content'] = 'master/discount/viewdiscountlist';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
//            $data['msg'] = "";
//            $data['id'] = "";
//            $data['nama'] = "";
//            $data['mjenis'] = array("R" => "Reguler", "P" => "Promosi");
//            $data['jenis'] = "";
//            $data['mrup'] = array("P" => "Persentase", "R" => "Rupiah", "B" => "Barang");
//            $data['rup'] = "";
//            $data['hitung'] = "";
//            $data['period1'] = "";
//            $data['period2'] = "";
//            $data['mbeban'] = array("M" => "Marketing", "S" => "Sales", "L" => "Lain - Lain");
//            $data['beban'] = array("", "", "");
//            $data['mrek'] = $this->discountmodel->getRekening();
//            $data['rek'] = array("", "", "");
//            $data['persen'] = array("", "", "");
//            $data['nilai'] = "";
//            $data['mhadiah'] = array("S" => "Barang Yang Sama", "B" => "Salah Satu Dari Kode Barang");
//            $data['hadiah'] = "";
//            $data['judul'] = array("Divisi" => array("KdDivisi", "NamaDivisi", "divisi"), "Sub Divisi" => array("KdSubDivisi", "NamaSubDivisi", "subdivisi"), "Kategori" => array("KdKategori", "NamaKategori", "kategori"), "Sub Kategori" => array("KdSubKategori", "NamaSubKategori", "subkategori"), "Brand" => array("KdBrand", "NamaBrand", "brand"), "Sub Brand" => array("KdSubBrand", "NamaSubBrand", "subbrand"), "Supplier" => array("KdSupplier", "Keterangan", "supplier"), "Size" => array("KdSize", "NamaSize", "size"), "Sub Size" => array("KdSubSize", "Ukuran", "subsize"), "Barang" => array("PCode", "NamaStruk", "masterbarang"), "Grup Store" => array("KdGrupStore", "Keterangan", "grup_store"), "Tipe Store" => array("KdTipeStore", "Keterangan", "tipe_store"), "Sub Tipe Store" => array("KdSubTipeStore", "Keterangan", "sub_tipe_store"), "Channel" => array("KdChannel", "Keterangan", "channel"), "Area" => array("KdArea", "Keterangan", "area"), "Sub Area" => array("KdSubArea", "Keterangan", "subarea"), "Store" => array("KdStore", "NamaStore", "masterstore"));
//            $data['opr'] = array("NIL", ">=", ">", "=", "<=", "<");
//            $data['mcampur'] = array("Y" => "Ya", "T" => "Tidak");
            $data['kode'] = $this->autoNumber('KodeDisc', 'discount_header');
            $data['dtcustomer'] = $this->discountmodel->getCustomer();
            $data['mstbarang'] = $this->discountmodel->getItemBarang();
            $data['content'] = 'master/discount/adddiscount';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_new_discount() {
        $mylib = new globallib();
        $kode = $this->input->post('kode');
        $nama = strtoupper(trim($this->input->post('nama')));
        $jenis = $this->input->post('jenis');
        $rup = $this->input->post('rup');
        //$persen1 = ($rup == 'P' ? $persen1 = '100' : $persen1 = '0');
        $hitung = $this->input->post('hitung');
        $perio1 = $mylib->ubah_tanggal($this->input->post('periode1'));
        $perio2 = $mylib->ubah_tanggal($this->input->post('periode2'));
        if ($rup == "P") {
            $persen1 = "100";
            $nilai = trim($this->input->post("nilai_persen"));
            $this->form_validation->set_rules('nilai_persen', 'nilai_persen', 'trim|is_natural_no_zero');
        } elseif ($rup == "R") {
            $persen1 = "0";
            $nilai = trim($this->input->post("nilai_rup"));
            $this->form_validation->set_rules('nilai_rup', 'nilai_rup', 'trim|is_natural_no_zero');
        }
        $nilai1 = trim($this->input->post('nilai1'));
        $kdcustomer = $this->input->post('customer');
        $barang1 = $this->input->post('barang');
//           print_r($_POST);
//         die();
        $this->_validate();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            $data_header = array(
                'KodeDisc' => $kode,
                'NamaDisc' => $nama,
                'Jenis' => $jenis,
                'RupBar' => $rup,
                'Perhitungan' => $hitung,
                'Period1' => $perio1,
                'Period2' => $perio2,
                'Persen1' => $persen1,
                'Nilai' => $nilai,
                'AddDate' => date('Y-m-d'),
                'KdCustomer' => $kdcustomer
            );
            $this->db->insert('discount_header', $data_header);

            foreach ($barang1 as $value) {
                $data_detail = array(
                    'KodeDisc' => $kode,
                    'Jenis' => $jenis,
                    'List' => $value,
                    'Status' => 'P',
                    'Opr1' => '>=',
                    'Nilai1' => $nilai1
                );
                $this->db->insert('discount_detail', $data_detail);
            }

            $arr_val = array('success' => true);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Berhasil di Simpan </div>');
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($arr_val);
    }

    function _validate() {
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
        $this->form_validation->set_rules('rup', 'rup', 'trim|required');
        $this->form_validation->set_rules('barang[]', 'barang', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus Isi');
        $this->form_validation->set_message('is_natural_no_zero', '* Tidak Boleh 0');
    }

    function autoNumber($id, $table) {
        $query = 'SELECT MAX(' . $id . ') as max_id FROM ' . $table . ' ORDER BY ' . $id;
        // echo $query;
        $result = mysql_query($query);
        $data = mysql_fetch_array($result);
        if (count($data['max_id']) > 0) {
            $id_max = $data['max_id'];
        } else {
            $id_max = 0;
        }
        $sort_num = substr($id_max, 0, 1);
        //$sort_num++;
        //$new_code = sprintf("%0s", $sort_num);
        $new_code = $id_max + 1;
        return $new_code;
    }

    function view_discount($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewarea'] = $this->areamodel->getDetail($id);
            $data['edit'] = false;
            $this->load->view('master/area/vieweditarea', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_area($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("del");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewarea'] = $this->areamodel->getDetail($id);
            $this->load->view('master/area/deletearea', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_This() {
        $id = $this->input->post('kode');
        $this->db->delete('area', array('KdArea' => $id));
        redirect('/master/area/');
    }

    function edit_area($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("edit");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewarea'] = $this->areamodel->getDetail($id);
            $data['edit'] = true;
            $this->load->view('master/area/vieweditarea', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_area() {
        $id = $this->input->post('kode');
        $nama = strtoupper(trim($this->input->post('nama')));
        $data = array(
            'Keterangan' => $nama,
            'EditDate' => date("Y-m-d")
        );
        $this->db->update('area', $data, array('KdArea' => $id));
        redirect('/master/area/');
    }

    function save_new_discount_OLD() {
        $nama = strtoupper(trim($this->input->post('nama')));
        $jenis = $this->input->post('nama');
        $rup = $this->input->post('rup');
        $hitung = $this->input->post('hitung');
        $perio1 = $this->input->post('periode1');
        $perio2 = $this->input->post('periode2');
        if ($perio1 == '') {
            $periode1 = "0000-00-00";
        } else {
            $periode1 = $mylib->ubah_tanggal($perio1);
        }
        if ($perio2 == '') {
            $periode2 = "0000-00-00";
        } else {
            $periode2 = $mylib->ubah_tanggal($perio2);
        }
        $beban1 = $this->input->post('beban1');
        $beban2 = $this->input->post('beban2');
        $beban3 = $this->input->post('beban3');
        $rek1 = $this->input->post('rek1');
        $rek2 = $this->input->post('rek2');
        $rek3 = $this->input->post('rek3');
        $persen1 = trim($this->input->post('persen1'));
        $persen2 = trim($this->input->post('persen2'));
        $persen3 = trim($this->input->post('persen3'));
        $nilai = trim($this->input->post("nilai"));
        $baranghadiah = trim($this->input->post('brg'));

        $data = array(
            'NamaDisc' => $nama,
            'Jenis' => $jenis,
            'RupBar' => $rup,
            'Perhitungan' => $hitung,
            'Period1' => $periode1,
            'Period2' => $periode2,
            'Beban' => $beban1,
            'NoRekening' => $rek1,
            'Persen1' => $persen1,
            'Beban2' => $beban2,
            'NoRekening2' => $rek2,
            'Persen2' => $persen2,
            'Beban3' => $beban3,
            'NoRekening3' => $rek3,
            'Persen3' => $persen3,
            'Nilai' => $nilai,
            'AddDate' => date("Y-m-d")
        );
        $this->db->insert('discount_header', $data);
        for ($i = 0; $i < count($baranghadiah); $i++) {
            $brg = $_POST['brg'][$i];
            $data = array(
                'KodeDisc' => $nama,
                'Jenis' => "00",
                'List' => $brg,
                'Status' => "",
                'Opr1' => "",
                'Nilai1' => "0",
                'Opr2' => "",
                'Nilai2' => "0",
                'Campur' => "0",
                'AddDate' => date("Y-m-d")
            );
        }
        $this->db->insert('discount_detail', $data);
        for ($a = 0; $a < 19; $a++) {
            $namaopr = "opr1" . $a;
            $namaopr2 = "opr2" . $a;
            $namanil = "nil1" . $a;
            $namanil2 = "nil2" . $a;
            $campuran = "campur" . $a;
            $pilih = "sel" . $a;
            $pilih1 = "kec" . $a;
            $sel0 = trim($this->input->post($pilih));
            $sel1 = trim($this->input->post($pilih1));
            $opr1 = trim($this->input->post($namaopr));
            $opr2 = trim($this->input->post($namaopr2));
            $nil1 = trim($this->input->post($namanil));
            $nil2 = trim($this->input->post($namanil2));
            $campur = trim($this->input->post($campuran));
            $jmljenis = $a + 1;
            if (strlen($jmljenis) == 1) {
                $jenis = "0" . $jmljenis;
            } else {
                $jenis = $jmljenis;
            }
            if ($nil1 == '')
                $nil1 = 0;
            if ($nil2 == '')
                $nil2 = 0;

            for ($i = 0; $i < count($sel0); $i++) {
                $p = $sel0[$i]; // status 1 untuk list, 0 untuk kecuali
                $data = array(
                    'KodeDisc' => $nama,
                    'Jenis' => $jenis,
                    'List' => $p,
                    'Status' => "P",
                    'Opr1' => $opr1,
                    'Nilai1' => $nil1,
                    'Opr2' => $opr2,
                    'Nilai2' => $nil1,
                    'Campur' => $campur,
                    'AddDate' => date("Y-m-d"),
                    'EditDate' => "0000-00-00"
                );
                $this->db->insert('discount_detail', $data);
            }
            for ($i = 0; $i < count($sel1); $i++) {
                $q = $sel1[$i];
                $data = array(
                    'KodeDisc' => $nama,
                    'Jenis' => $jenis,
                    'List' => $q,
                    'Status' => "K",
                    'Opr1' => $opr1,
                    'Nilai1' => $nil1,
                    'Opr2' => $opr2,
                    'Nilai2' => $nil1,
                    'Campur' => $campur,
                    'AddDate' => date("Y-m-d"),
                    'EditDate' => "0000-00-00"
                );
                $this->db->insert('discount_detail', $data); //sampe sini
            }
        }
        redirect('/master/discount/');
    }

    function getItemHadiah() {
        $pcode = $this->input->post("pcode");
        $num = $this->discountmodel->HadiahExist($pcode);
        $data1 = "";
        if ($num == 0) {
            $data1 = "empty";
        }
        echo $data1;
    }

    function cekNama() {
        $nama = $this->input->post("nama");
        $num = $this->discountmodel->get_id($nama);
        echo $num;
    }

}

?>