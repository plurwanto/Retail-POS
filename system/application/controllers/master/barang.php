<?php

class barang extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/barangmodel');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'form'));
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);
            $data['barangdata'] = $this->barangmodel->getbarangList();
            $data['track'] = $mylib->print_track();
            //   $data['header'] = array("Kode Barang", "Barcode", "Nama Struk", "Nama Lengkap", "Nama Initial", "Divisi", "Sub Divisi", "Brand", "Sub Brand", "Kelas Produk", "Tipe Produk", "Kemasan", "Size", "Sub Size", "Harga Jual", "Satuan", "Parent", "Konversi", "Supplier", "Status", "Min Order");
//	        $data['header']		  = array("Kode Barang","Barcode","Nama Struk","Nama Lengkap","Nama Initial","Divisi","Sub Divisi","Kategori","Sub Kategori","Brand","Sub Brand","Departemen","Kelas Produk","Tipe Produk","Kemasan","Produk Tag","Size","Sub Size","Harga Jual","Grup Harga","Flag Harga","Satuan","Parent","Konversi","Supplier","Principal","Status","Min Order");
            $data['content'] = "master/barang/viewbaranglist";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        $data['track'] = $mylib->print_track();
        if ($sign == "Y") {
            $data['msg'] = "";
            $data['id'] = "";
            $data['barcode'] = "";
            $data['nama'] = "";
            $data['nlengkap'] = "";
            $data['ninitial'] = "";
            $data['hjual'] = "";
            $data['konv'] = "";
            $data['minimum'] = "";
            $data['mdivisi'] = $this->barangmodel->getDivisi();
            $data['divisi'] = "";
            $data['subdiv'] = "";
            $data['mkategori'] = $this->barangmodel->getKategori();
            $data['kategori'] = "";
            $data['subkat'] = "";
            $data['mbrand'] = $this->barangmodel->getBrand();
            $data['brand'] = "";
            $data['subbrand'] = "";
            $data['msize'] = $this->barangmodel->getSize();
            $data['size'] = "";
            $data['subsize'] = "";
            $data['mdept'] = $this->barangmodel->getDept();
            $data['dept'] = "";
            $data['mclass'] = $this->barangmodel->getKelas();
            $data['class'] = "";
            $data['mtipe'] = $this->barangmodel->getTipe();
            $data['tipe'] = "";
            $data['mkemasan'] = $this->barangmodel->getKemasan();
            $data['kemasan'] = "";
            $data['msupplier'] = $this->barangmodel->getSupplier();
            $data['supplier'] = "";
            $data['mprincipal'] = $this->barangmodel->getPrincipal();
            $data['principal'] = "";
            $data['msatuan'] = $this->barangmodel->getSatuan();
            $data['satuan'] = "";
            $data['mgrup'] = $this->barangmodel->getGrup();
            $data['grup'] = "";
            $data['mparent'] = $this->barangmodel->getParent();
            $data['parent'] = "";
            $data['mflag'] = array("HJ" => "Harga Jual", "GH" => "Grup Harga");
            $data['flag'] = "";
            $data['mstatus'] = array("Normal", "Konsinyasi");
            $data['status'] = "";
            $data['content'] = "master/barang/addbarang";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_barang($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("del");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewbarang'] = $this->barangmodel->getDetail($id);
            $this->load->view('master/barang/deletebarang', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_This() {
        $id = $this->input->post('kode');
        $this->db->delete('masterbarang', array('PCode' => $id));
        redirect('/master/barang/');
    }

    function edit_barang($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("edit");
        $data['track'] = $mylib->print_track();
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $value = $this->barangmodel->getDetail($id);
            $data = $this->isi_data("true", "edit", $id, $value->NamaStruk, $value->NamaLengkap, $value->NamaInitial, $value->HargaJual, $value->MinOrder, $value->KdDivisi, $value->KdSubDivisi, $value->KdKategori, $value->KdSubKategori, $value->KdBrand, $value->KdSubBrand, $value->KdSize, $value->KdSubSize, $value->KdDepartemen, $value->KdKelas, $value->KdType, $value->KdKemasan, $value->KdSupplier, $value->KdPrincipal, $value->KdSatuan, $value->KdGrupHarga, $value->ParentCode, $value->FlagHarga, $value->Status, $value->Konversi, $value->BarCode);
            $data['content'] = "master/barang/vieweditbarang";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function view_barang($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        $data['track'] = $mylib->print_track();
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $value = $this->barangmodel->getDetail($id);
            $data = $this->isi_data("false", "edit", $id, $value->NamaStruk, $value->NamaLengkap, $value->NamaInitial, $value->HargaJual, $value->MinOrder, $value->KdDivisi, $value->KdSubDivisi, $value->KdKategori, $value->KdSubKategori, $value->KdBrand, $value->KdSubBrand, $value->KdSize, $value->KdSubSize, $value->KdDepartemen, $value->KdKelas, $value->KdType, $value->KdKemasan, $value->KdSupplier, $value->KdPrincipal, $value->KdSatuan, $value->KdGrupHarga, $value->ParentCode, $value->FlagHarga, $value->Status, $value->Konversi, $value->BarCode);
            $data['content'] = "master/barang/vieweditbarang";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_barang() {
        $id = strtoupper(trim($this->input->post('kode')));
        $nama = strtoupper(trim($this->input->post('nama')));
        $nlengkap = strtoupper(trim($this->input->post('nlengkap')));
        $ninitial = strtoupper(trim($this->input->post('ninitial')));
        $hjual = $this->input->post('hjual');
        $konv = $this->input->post('konv');
        $barcode = $this->input->post('barcode');
        $minimum = $this->input->post('minimum');
        $divisi = $this->input->post('divisi');
        $subdiv = $this->input->post('subdivisi');
        //$kategori = $this->input->post('kategori');
        //$subkat = $this->input->post('subkategori');
        $brand = $this->input->post('brand');
        $subbrand = $this->input->post('subbrand');
        $size = $this->input->post('size');
        $subsize = $this->input->post('subsize');
        //$dept = $this->input->post('departemen');
        $class = $this->input->post('kelas');
        $tipe = $this->input->post('tipe');
        //$tag = $this->input->post('tag');
        $kemasan = $this->input->post('kemasan');
        $supplier = $this->input->post('supplier');
        //$principal = $this->input->post('principal');
        $satuan = $this->input->post('satuan');
        $parent = $this->input->post('parents');
        //$grup = $this->input->post('grup');
        //$flag = $this->input->post('flag');
        $status = $this->input->post('status');
        
        $this->form_validation->set_rules('kode', 'Kode Barang', 'trim|required');
        $this->_validate();
        $this->form_validation->set_error_delimiters('<div class="text-danger" role="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus isi');
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            $data = array(
                'NamaStruk' => $nama,
                'Barcode' => $barcode,
                'NamaLengkap' => $nlengkap,
                'NamaInitial' => $ninitial,
                'HargaJual' => $hjual,
               // 'KdGrupHarga' => $grup,
               // 'FlagHarga' => $flag,
                'KdSatuan' => $satuan,
                'ParentCode' => $parent,
                'Konversi' => $konv,
                'KdSupplier' => $supplier,
               // 'KdPrincipal' => $principal,
                'Status' => $status,
                'MinOrder' => $minimum,
                'KdDivisi' => $divisi,
                'KdSubDivisi' => $subdiv,
             //   'KdKategori' => $kategori,
            //    'KdSubKategori' => $subkat,
                'KdBrand' => $brand,
                'KdSubBrand' => $subbrand,
               // 'KdDepartemen' => $dept,
                'KdKelas' => $class,
                'KdType' => $tipe,
                'KdSize' => $size,
                'KdSubSize' => $subsize,
                'KdKemasan' => $kemasan,
                'EditDate' => date("Y-m-d")
            );
            $this->db->update('masterbarang', $data, array('PCode' => $id));
            $pesan = " Berhasil Di Update";
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i>' . $pesan . '</div>');
            $arr_val['success']= true;
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($arr_val);
    }

    function save_new_barang() {
        $id = strtoupper(trim($this->input->post('kode')));
        $nama = strtoupper(trim($this->input->post('nama')));
        $nlengkap = strtoupper(trim($this->input->post('nlengkap')));
        $ninitial = strtoupper(trim($this->input->post('ninitial')));
        $hjual = $this->input->post('hjual');
        $konv = $this->input->post('konv');
        $barcode = $this->input->post('barcode');
        $minimum = $this->input->post('minimum');
        $divisi = $this->input->post('divisi');
        $subdiv = $this->input->post('subdivisi');
        $kategori = $this->input->post('kategori');
        $subkat = $this->input->post('subkategori');
        $brand = $this->input->post('brand');
        $subbrand = $this->input->post('subbrand');
        $size = $this->input->post('size');
        $subsize = $this->input->post('subsize');
        $dept = $this->input->post('departemen');
        $class = $this->input->post('kelas');
        $tipe = $this->input->post('tipe');
        //$tag = $this->input->post('tag');
        $kemasan = $this->input->post('kemasan');
        $supplier = $this->input->post('supplier');
        $principal = $this->input->post('principal');
        $satuan = $this->input->post('satuan');
        $parent = $this->input->post('parents');
        $grup = $this->input->post('grup');
        $flag = $this->input->post('flag');
        $status = $this->input->post('status');
        // $num = $this->barangmodel->get_id($id);
        
        $this->form_validation->set_rules('kode', 'Kode Barang', 'trim|required|callback_kode_check');
        $this->_validate();
        $this->form_validation->set_error_delimiters('<div class="text-danger" role="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus isi');
        $arr_val = array('success' => false, 'messages' => array());
        
        if ($this->form_validation->run()) {

            $data = array(
                'PCode' => $id,
                'BarCode' => $barcode,
                'NamaStruk' => $nama,
                'NamaLengkap' => $nlengkap,
                'NamaInitial' => $ninitial,
                'HargaJual' => $hjual,
                'KdGrupHarga' => $grup,
                'FlagHarga' => $flag,
                'KdSatuan' => $satuan,
                'ParentCode' => $parent,
                'Konversi' => $konv,
                'KdSupplier' => $supplier,
                'KdPrincipal' => $principal,
                'Status' => $status,
                'MinOrder' => $minimum,
                'KdDivisi' => $divisi,
                'KdSubDivisi' => $subdiv,
                'KdKategori' => $kategori,
                'KdSubKategori' => $subkat,
                'KdBrand' => $brand,
                'KdSubBrand' => $subbrand,
                'KdDepartemen' => $dept,
                'KdKelas' => $class,
                'KdType' => $tipe,
                'KdSize' => $size,
                'KdSubSize' => $subsize,
                'KdKemasan' => $kemasan,
                'AddDate' => date("Y-m-d")
            );
            $this->db->insert('masterbarang', $data);
            $pesan = " Berhasil Di Simpan";
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i>' . $pesan . '</div>');

            $arr_val['success'] = true;
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($arr_val);
    }

    function _validate() {
        $this->form_validation->set_rules('nama', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('nlengkap', 'nlengkap', 'trim|required');
        $this->form_validation->set_rules('ninitial', 'ninitial', 'trim|required');
        $this->form_validation->set_rules('divisi', 'divisi', 'trim|required');
        $this->form_validation->set_rules('subdivisi', 'divisi', 'trim|required');
        $this->form_validation->set_rules('brand', 'brand', 'trim|required');
        $this->form_validation->set_rules('subbrand', 'subbrand', 'trim|required');
        $this->form_validation->set_rules('size', 'size', 'trim|required');
        $this->form_validation->set_rules('subsize', 'subsize', 'trim|required');
        $this->form_validation->set_rules('kemasan', 'kemasan', 'trim|required');
//        $this->form_validation->set_rules('departemen', 'departemen', 'trim|required');
        $this->form_validation->set_rules('kelas', 'kelas', 'trim|required');
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|required');
        $this->form_validation->set_rules('supplier', 'supplier', 'trim|required');
//        $this->form_validation->set_rules('principal', 'principal', 'trim|required');
        $this->form_validation->set_rules('satuan', 'satuan', 'trim|required');
        $this->form_validation->set_rules('hjual', 'hjual', 'trim|required');
        $this->form_validation->set_rules('konv', 'konv', 'trim|required');
        $this->form_validation->set_rules('minimum', 'minimum', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');

        
    }

    function kode_check($id) {
        $id = strtoupper(trim($this->input->post('kode')));
        $data = $this->barangmodel->get_id($id);

        foreach ($data as $value) {
            $dt = trim($value->PCode);
            if ($id == $dt) {
                $this->form_validation->set_message("kode_check", "<div class='text-danger' role='alert'>* Kode Barang Sudah Ada </div>");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    function getSubDivisiBy($divisi) {
        $tmp = '';
        $data = $this->barangmodel->getSubDivBy($divisi);
        if (!empty($data)) {
            $tmp .= "<option value=''>Pilih</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row['KdSubDivisi'] . "'>" . $row['NamaSubDivisi'] . "</option>";
            }
        } else {
            $tmp .= "<option value=''>Pilih</option>";
        }
        die($tmp);
    }

    function getSubKategoriBy() {
        $kategori = $this->input->post("kategori");
        $data = $this->barangmodel->getSubKatBy($kategori);
        for ($a = 0; $a < count($data); $a++) {
            echo "<option value='" . $data[$a]['KdSubKategori'] . "'>" . $data[$a]['NamaSubKategori'] . "</option>";
        }
    }

    function getSubBrandBy($brand) {
        $tmp = '';
        $data = $this->barangmodel->getSubBrandBy($brand);
        if (!empty($data)) {
            $tmp .= "<option value=''>Pilih</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row['KdSubBrand'] . "'>" . $row['NamaSubBrand'] . "</option>";
            }
        } else {
            $tmp .= "<option value=''>Pilih</option>";
        }
        die($tmp);
    }

    function getSubSizeBy($size) {
        $tmp = '';
        $data = $this->barangmodel->getSubSizeBy($size);
        if (!empty($data)) {
            $tmp .= "<option value=''>Pilih</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row['KdSubSize'] . "'>" . $row['Ukuran'] . "</option>";
            }
        } else {
            $tmp .= "<option value=''>Pilih</option>";
        }
        die($tmp);
    }

    function isi_data($msg, $namaarray, $id, $nama, $nlengkap, $ninitial, $hjual, $minimum, $divisi, $subdiv, $kategori, $subkat, $brand, $subbrand, $size, $subsize, $dept, $class, $tipe, $kemasan, $supplier, $principal, $satuan, $grup, $parent, $flag, $status, $konv, $barcode) {
        $data[$namaarray] = $msg;
        $data['id'] = $id;
        $data['nama'] = $nama;
        $data['nlengkap'] = $nlengkap;
        $data['ninitial'] = $ninitial;
        $data['hjual'] = $hjual;
        $data['konv'] = $konv;
        $data['barcode'] = $barcode;
        $data['minimum'] = $minimum;
        $data['mdivisi'] = $this->barangmodel->getDivisi();
        $data['divisi'] = $divisi;
        $subdivtemp = $this->barangmodel->getSubDivBy($divisi);
        $subdivlagi = "";
        for ($s = 0; $s < count($subdivtemp); $s++) {
            $select = "";
            if ($subdivtemp[$s]['KdSubDivisi'] == $subdiv) {
                $select = "selected";
            }
            $subdivlagi .= "<option " . $select . " value='" . $subdivtemp[$s]['KdSubDivisi'] . "'>" . $subdivtemp[$s]['NamaSubDivisi'] . "</option>";
        }
        $data['subdiv'] = $subdivlagi;

        $data['mkategori'] = $this->barangmodel->getKategori();
        $data['kategori'] = $kategori;
        $subkattemp = $this->barangmodel->getSubKatBy($kategori);
        $subkatlagi = "";
        for ($s = 0; $s < count($subkattemp); $s++) {
            $select = "";
            if ($subkattemp[$s]['KdSubKategori'] == $subkat) {
                $select = "selected";
            }
            $subkatlagi .= "<option " . $select . " value='" . $subkattemp[$s]['KdSubKategori'] . "'>" . $subkattemp[$s]['NamaSubKategori'] . "</option>";
        }
        $data['subkat'] = $subkatlagi;

        $data['mbrand'] = $this->barangmodel->getBrand();
        $data['brand'] = $brand;
        $subbrandtemp = $this->barangmodel->getSubBrandBy($brand);
        $subbrandlagi = "";
        for ($s = 0; $s < count($subbrandtemp); $s++) {
            $select = "";
            if ($subbrandtemp[$s]['KdSubBrand'] == $subbrand) {
                $select = "selected";
            }
            $subbrandlagi .= "<option " . $select . " value='" . $subbrandtemp[$s]['KdSubBrand'] . "'>" . $subbrandtemp[$s]['NamaSubBrand'] . "</option>";
        }
        $data['subbrand'] = $subbrandlagi;

        $data['msize'] = $this->barangmodel->getSize();
        $data['size'] = $size;
        $subsizetemp = $this->barangmodel->getSubSizeBy($size);
        $subsizelagi = "";
        for ($s = 0; $s < count($subsizetemp); $s++) {
            $select = "";
            if ($subsizetemp[$s]['KdSubSize'] == $subsize) {
                $select = "selected";
            }
            $subsizelagi .= "<option " . $select . " value='" . $subsizetemp[$s]['KdSubSize'] . "'>" . $subsizetemp[$s]['Ukuran'] . "</option>";
        }
        $data['subsize'] = $subsizelagi;
        $data['mdept'] = $this->barangmodel->getDept();
        $data['dept'] = $dept;
        $data['mclass'] = $this->barangmodel->getKelas();
        $data['class'] = $class;
        $data['mtipe'] = $this->barangmodel->getTipe();
        $data['tipe'] = $tipe;
        $data['mkemasan'] = $this->barangmodel->getKemasan();
        $data['kemasan'] = $kemasan;
        $data['msupplier'] = $this->barangmodel->getSupplier();
        $data['supplier'] = $supplier;
        $data['mprincipal'] = $this->barangmodel->getPrincipal();
        $data['principal'] = $principal;
        $data['msatuan'] = $this->barangmodel->getSatuan();
        $data['satuan'] = $satuan;
        $data['mgrup'] = $this->barangmodel->getGrup();
        $data['grup'] = $grup;
        $data['mparent'] = $this->barangmodel->getParent();
        $data['parent'] = $parent;
        $data['mflag'] = array("HJ" => "Harga Jual", "GH" => "Grup Harga");
        $data['flag'] = $flag;
        $data['mstatus'] = array("Normal", "Konsinyasi");
        $data['status'] = $status;
        return $data;
    }

    function toExcelAll() {
        $query['dataexcel'] = $this->getExcelAll();
        $this->load->view('master/barang/toexcelallbarang', $query);
    }

    //query for get all data
    function getExcelAll() {
        $sql = "select b.PCode,Barcode,NamaStruk,NamaLengkap,NamaInitial,HargaJual,
			if(isNULL(NamaGrupHarga),'No Grup',NamaGrupHarga) as NamaGrupHarga,if(FlagHarga='HJ','HargaJual','Grup Harga') as FlagHarga,
			Satuan,if(isNULL(Parent),'No Parent',Parent) as Parent,Konversi,NamaSupplier,Principal,Status,MinOrder 
			,NamaDivisi,NamaSubDivisi,NamaKategori,NamaSubKategori,NamaBrand,NamaSubBrand,Departemen,NamaKelas,NamaType
			,NamaKemasan,NamaSize,Ukuran
			from
			(
			select * from masterbarang order by PCode
			) as b
			left join
			(
			select KdGrupHarga,Keterangan as NamaGrupHarga from grup_hargaheader
			) as grup
			on grup.KdGrupHarga=b.KdGrupHarga
			left join
			(
			select NamaSatuan,keterangan as Satuan from satuan
			) as satuan
			on satuan.NamaSatuan = b.KdSatuan
			left join
			(
			select PCode,NamaStruk as Parent from masterbarang
			) as parent
			on parent.PCode = b.ParentCode
			left join
			(
			select KdSupplier,Keterangan as NamaSupplier from supplier
			) as supplier
			on supplier.KdSupplier  = b.KdSupplier
			left join
			(
			select KdPrincipal,Keterangan as Principal from principal
			) as princ
			on princ.KdPrincipal = b.KdPrincipal
			left join
			(
			select KdDivisi,NamaDivisi from divisi 
			) as divisi
			on divisi.KdDivisi = b.KdDivisi
			left join
			(
			select KdSubDivisi,NamaSubDivisi from subdivisi 
			)as subdivisi
			on subdivisi.KdSubDivisi = b.KdSubDivisi
			left join
			(
			select KdKategori,NamaKategori from kategori 
			) as kategori
			on kategori.KdKategori = b.KdKategori
			left join
			(
			select KdSubKategori,NamaSubKategori from subkategori 
			)as subkategori
			on subkategori.KdSubKategori = b.KdSubKategori
			left join
			(
			select KdBrand,NamaBrand from brand 
			) as brand
			on brand.KdBrand = b.KdBrand
			left join
			(
			select KdSubBrand,NamaSubBrand from subbrand 
			)as subbrand
			on subbrand.KdSubBrand = b.KdSubBrand
			left join
			(
			select KdDepartemen,Keterangan as Departemen from departemen 
			)as departemen
			on departemen.KdDepartemen = b.KdDepartemen
			left join
			(
			select KdKelas,NamaKelas from kelasproduk 
			)as kelasproduk
			on kelasproduk.KdKelas = b.KdKelas
			left join
			(
			select KdType,NamaType from tipeproduk 
			)as tipeproduk
			on tipeproduk.KdType = b.KdType
			left join
			(
			select KdKemasan,NamaKemasan from kemasan 
			)as kemasan
			on kemasan.KdKemasan = b.KdKemasan
			left join
			(
			select KdSize,NamaSize from size 
			)as size
			on size.KdSize = b.KdSize
			left join
			(
			select KdSubSize,Ukuran from subsize 
			)as subsize
			on subsize.KdSubSize = b.KdSubSize";

        $getData = $this->db->query($sql);
        if ($getData->num_rows() > 0)
            return $getData->result_array();
        else
            return null;
    }

}

?>