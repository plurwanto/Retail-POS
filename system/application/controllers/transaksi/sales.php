<?php
class sales extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('transaksi/sales_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        $nm = $mylib->getUser();
        if ($sign == "Y") {
            $tgl = $this->sales_model->aplikasi();
            $data['msg'] = "";
            $data['sales_temp'] = $this->sales_model->sales_temp($nm);
            $data['sales_temp_count'] = $this->sales_model->sales_temp_count($nm);
            $data['TotalNetto'] = $this->sales_model->TotalNetto($nm);
            $data['TotalItem'] = $this->sales_model->TotalItem($nm);
            $data['SubTotal'] = $this->sales_model->SubTotal($nm);
            $data['TotalDisc'] = $this->sales_model->TotalDisc($nm);
            $data['diskonaktif'] = $this->sales_model->CekDiskonAktif($tgl[0]['TglTrans']);

//$q			= $this->no_struk();print_r($q);die();
            $data['struk'] = $this->no_struk($nm);
            $data['store'] = $this->sales_model->aplikasi();
            $data['NoKassa'] = $this->sales_model->cekidkassa($_SERVER['REMOTE_ADDR']); // cek no kasir
//					print_r($data['sales_temp']);
            $data['content'] = 'transaksi/sales/tampil';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function insert_temporary() {
        $tgl = $this->sales_model->aplikasi();
        $tgldok = $tgl[0]['TglTrans'];
        $EditFlg = $this->input->post('EditFlg');
        $NoUrut = $this->input->post('NoUrut');
        $kdbrg = $this->input->post('kdbrg1');
        $jumlah = $this->input->post('qty1');
        $harganya = $this->input->post('jualmtanpaformat1');
        $totalnya = $this->input->post('nettotanpaformat1');
        $Struk = $this->input->post('no');
        $disc = $this->input->post('disk1');
        $kassa = $this->input->post('kassa');
        $kasir = $this->input->post('kasir');
        $store = $this->input->post('store');
        $kdcustomer = $this->input->post('pelanggan');
        $kdgrupbarang = $this->input->post('kdgrupbarang');
        // echo "<pre>";print_r($_POST);echo "</pre>";die();
        if ($EditFlg == 1 && $jumlah == 0) {
            $this->sales_model->DeleteRecord($kdbrg, $kasir);
        } else {
            $sql = "select NoUrut+1 as NoUrut from sales_temp order by AutoID desc limit 1";
            $qry = $this->db->query($sql);
            $row = $qry->result_array();

            if ($qry->num_rows() == 0) {
                $NoUrut = 1;
            } else {
                $NoUrut = $row[0]['NoUrut'];
            }
        }

        $hasil = $this->sales_model->sales_temp_cek($kdbrg, $kasir);

        if (!empty($dis_potongan)) {
            ($dis_potongan != 0 or $dis_potongan != "") ? $hrg = $harganya - ($harganya * $dis_potongan) : $hrg = $harganya;
        } else {
            $dis_potongan = 0;
            $hrg = $harganya;
        }

        if ($hasil == 0) {
            $data = array(
                'NoUrut' => $NoUrut,
                'NoStruk' => $Struk,
                'KodeBarang' => $kdbrg,
                'Qty' => $jumlah,
                'disc' => '0',
                'Harga' => $harganya,
                'Netto' => $hrg,
                //'Netto'        => $totalnya,
                'NoKassa' => $kassa,
                'Kasir' => $kasir,
                'Tanggal' => $tgldok,
                'Waktu' => date('H:i:s'),
                'KdStore' => $store,
                'Status' => 1
            );

            $this->db->insert('sales_temp', $data);
        } else {
            $this->sales_model->sales_temp_add($jumlah, $kdbrg, $Struk, $EditFlg, $kasir, $dis_potongan);
        }
        $this->CekBonusPromo($Struk, $kasir, $kdcustomer);
        $this->index();
        // redirect('/transaksi/sales/');
    }

    // update by purwanto on april 17
    function CekBonusPromo($struk, $kasir, $kdcustomer) {
        $dataR = $this->sales_model->ambilDataTemp($struk, $kasir);
        $grupbarang = $this->sales_model->cekBonus_by_grupbrg($struk, $kasir);
        $num = $grupbarang->num_rows();
        $result = $grupbarang->result_array();
        if (!empty($result)){
            $totalbaris = $result[0]['totalbaris'];
            $totalqty = number_format($result[0]['TotalQty']);
        }
        $kelipatan = array('2', '4', '6', '8', '10', '12', '14', '16', '18', '20'); //testing dgn qty = 2;
        $kelipatan2 = array('3', '5', '7', '9', '11', '13', '15', '17', '19', '21'); //testing dgn qty = 3;
        //echo $num;
        foreach ($dataR as $b) {
            $cekDapatBonus = $this->sales_model->CekBonus($b['KodeBarang'], $b['Tanggal']);
            //echo "<pre>";print_r($b['KodeBarang']);"</pre>";
            //  echo "<pre>";print_r($grupbarang);"</pre>";
            foreach ($cekDapatBonus as $dp) {
                if ($dp['KdCustomer'] == "") {
                    if ($dp['RupBar'] == "P") { //persentase
                        if (!empty($dp['Opr1'])) {
                            if ($b['TTL'] >= $dp['Nilai1']) {
                                $dis_potongan = $b['Qty'] * $b['Harga'] * $dp['Nilai'] / 100;  //jika kelipatan
                            } else {
                                $dis_potongan = 0;
                            }
                        } else {
                            $dis_potongan = $dp['Nilai'];
                        }
                    } else if ($dp['RupBar'] == "R") {  //rupiah
                        if ($dp['Jenis'] == "Q") { //minimal pembelian by quantity, requestnya orang marketing yg cerewet :D
                            if ($dp['Perhitungan'] == "Y") { //berlaku kelipatan
                                if (in_array($b['Qty'], $kelipatan)) {
                                    $dis_potongan = $b['Qty'] * $dp['Nilai'];
                                } else if (in_array($totalqty, $kelipatan)) {
                                    $dis_potongan = $b['Qty'] * $dp['Nilai'];
//                                } else if (in_array($totalqty, $kelipatan2)) {
//                                    $dis_potongan = 0;
                                } else {
                                    $dis_potongan = ($b['Qty'] * $dp['Nilai']) - $dp['Nilai'];
                                }
                            } else {
                                if ($b['Qty'] >= $dp['Nilai1']) {
                                    $dis_potongan = $b['Qty'] * $dp['Nilai'];
                                } else {
                                    $dis_potongan = 0;
                                }
                            }
                        } else if ($dp['Jenis'] == "H") {
                            if ($b['TTL'] >= $dp['Nilai1']) {
                                $dis_potongan = $b['Qty'] * $dp['Nilai'];
                            } else {
                                $dis_potongan = 0;
                            }
                        }
                    } else {
                        $dis_potongan = 0;
                    }
                    //   $arr_val['diskon_customer'] = true;
                } else if ($dp['KdCustomer'] == $kdcustomer) {
                    if ($dp['RupBar'] == "P") { //persentase
                        if (!empty($dp['Opr1'])) {
                            if ($b['TTL'] >= $dp['Nilai1']) {
                                $dis_potongan = $b['Qty'] * $b['Harga'] * $dp['Nilai'] / 100;  //jika kelipatan
                            } else {
                                $dis_potongan = 0;
                            }
                        } else {
                            $dis_potongan = $dp['Nilai'];
                        }
                    } else if ($dp['RupBar'] == "R") {  //rupiah
                        if ($b['TTL'] >= $dp['Nilai1']) {
                            $dis_potongan = $b['Qty'] * $dp['Nilai'];
                        } else {
                            $dis_potongan = 0;
                        }
                    } else {
                        $dis_potongan = 0;
                    }
                    //     $arr_val['diskon_customer'] = true;
                } else {
                    $dis_potongan = 0;
                    //   $arr_val['diskon_customer'] = false;
                }

                $this->db->update('sales_temp', array('RupBar' => $dp['RupBar'], 'ketPromo' => $dp['Nilai'],
                    'Disc' => $dis_potongan, 'Netto' => ($b['Qty'] * $b['Harga']) - $dis_potongan), array('KodeBarang' => $b['KodeBarang'], "NoStruk" => $struk, 'Kasir' => $kasir));
            }
        }
    }

    function LastRecord() {
        $mylib = new globallib();
        $nm = $mylib->getUser();
        $this->sales_model->LastRecord(1, $nm);
    }

    function CustomerView($pelanggan) {
        $this->sales_model->customer($pelanggan);
    }

    function VoucherCustomer($id_voucher) {
        $this->sales_model->voucher($id_voucher);
    }

    function EditRecord($NoUrut) {
        $this->sales_model->EditRecord($NoUrut);
    }

    function save_trans() {
        //print_r($_POST);die();
//		$nostruk            = $this->input->post('confirm_struk');
        $nokassa = $this->input->post('confirm_kassa');
        $totalnya = $this->input->post('total_biaya');
        $id_customer = $this->input->post('id_customer');
        $nama_customer = $this->input->post('nama_customer');
        $gender_customer = $this->input->post('gender_customer');
        $tgl_customer = $this->input->post('tgl_customer');

        $id_kredit = $this->input->post('id_kredit');
        $id_debet = $this->input->post('id_debet');
        $id_voucher = $this->input->post('id_voucher');

        $cash = $this->input->post('cash_bayar');
        $kredit = $this->input->post('kredit_bayar');
        $debet = $this->input->post('debet_bayar');
        $voucher = $this->input->post('voucher_bayar');

        $gudang = $this->input->post('gudang');
        $total_bayar = $this->input->post('total_bayar_hide');
        $kembali = $this->input->post('cash_kembali');
        $apl = $this->sales_model->aplikasi();
        $bulan = substr($apl[0]['TglTrans'], 5, 2);
        $tahun = substr($apl[0]['TglTrans'], 0, 4);

        $this->save_trans_header($cash, $kredit, $debet, $voucher, $total_bayar, $id_customer, $nama_customer, $gender_customer, $tgl_customer, $nokassa, $id_kredit, $id_debet, $id_voucher, $bulan, $tahun, $gudang);


        $this->index();
        redirect('/transaksi/sales/');
    }

    function save_trans_header($cash, $kredit, $debet, $voucher, $total_bayar, $id_customer, $nama_customer, $gender_customer, $tgl_customer, $nokassa, $id_kredit, $id_debet, $id_voucher, $bulan, $tahun, $gudang) {
        //ambil nomor terakhir di aplikasi
//            echo $gudang."Biji";die();
        $no = $this->sales_model->ambil_No($tahun);
        $this->db->update('setup_no', array('no_struk' => $no + 1), array("Tahun" => $tahun));

        $mylib = new globallib();
        $nm = $mylib->getUser();
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->sales_model->do_simpan_header($no, $nm, $gudang); // simpan trans header
        $this->sales_model->do_simpan_detail($no, $nm, $gudang); // simpan trans detail
        $this->sales_model->do_simpan_mutasi($no, $nm, $gudang); // simpan trans mutasi
        $this->do_update_stock($no, $nm, $bulan, $tahun); // update stock

        $this->sales_model->clear_trans($nm); // hapus temp
        $this->sales_model->bayar_trans($no, $cash, $kredit, $debet, $voucher, $total_bayar, $id_customer, $nama_customer, $gender_customer, $tgl_customer);
        $this->sales_model->save_trans_bayar($no, $nokassa, $nama_customer, $id_kredit, $kredit, $id_debet, $debet, $id_voucher, $voucher, $cash, $gudang);
        //$ttl 	=	$this->sales_model->do_hitung_bonus($no);
        //$ttl; die();
        $this->db->update('transaksi_header', array('Status' => '1'), array("NoStruk" => $no));
        $this->db->update('transaksi_detail', array('Status' => '1'), array("NoStruk" => $no));

        /* Insert to log
         */
        $datalog = array(
            'nostruk' => $no,
            'kasir' => $nm,
            'status' => '1',
            'ip' => $ip
        );
        $this->db->insert('log_nostruk', $datalog);

        $this->cetak($no);
    }

    function do_update_stock($no, $nm, $bulan, $tahun) {
        $detail = $this->sales_model->sales_temp($nm);
        for ($x = 0; $x < count($detail); $x++) {
            /* cek terlebih dahulu table stock klo belum ada create baru */
            $cekS = $this->sales_model->cekStock($detail[$x]['KodeBarang'], $tahun);
//            print_r($cekS);
            $fieldakhir = "QtyAkhir" . $bulan;
            $fieldkeluar = "QtyKeluar" . $bulan;
            if (empty($cekS)) {// buat caru
                $data = array(
                    'Tahun' => $tahun,
                    'KodeBarang' => $detail[$x]['KodeBarang'],
                    $fieldkeluar => $detail[$x]['Qty'],
                    $fieldakhir => ($detail[$x]['Qty'] * -1)
                );
                $this->db->insert('stock', $data);
            } else {
                $dataK = array(
                    $fieldkeluar => $cekS[0][$fieldkeluar] + $detail[$x]['Qty'],
                    $fieldakhir => $cekS[0][$fieldakhir] - $detail[$x]['Qty']
                );
                $this->db->update('stock', $dataK, array("Tahun" => $tahun, "KodeBarang" => $detail[$x]['KodeBarang']));
            }
        }
    }

    function clear_trans() {
        $mylib = new globallib();
        $nostruk = $this->input->post('nostruk');
        $btl = $this->uri->segment(4);
        if (empty($btl)) {
            $this->sales_model->save_trans_header($nostruk);
            $this->sales_model->save_trans($nostruk);
            $this->sales_model->clear_trans($nostruk);
        } else {
            $nm = $mylib->getUser();
            $this->sales_model->clear_kasir($nm);
        }
        $this->index();
        redirect('/transaksi/sales/');
    }

    function no_struk($user) {
        //cek nomor di sales temp
        $cek_temp = $this->sales_model->sales_temp($user);
        $temp = $this->sales_model->no_struk_temp();
        if (!empty($cek_temp)) {
            $z = $cek_temp[0]['NoStruk'];
        } else {
            if (empty($temp)) {
                $b = $this->sales_model->no_struk();
                $z = $b[0]['NoStruk'] + 1;
            } else {
                $z = $temp[0]['NoStruk'] + 1;
            }
        }
        return $z;
    }

    function cekkode() {
        $kd = $this->input->post('PCode');
        if (strlen($kd) != 6) {
            $cekbarcode = $this->sales_model->cekBarcode($kd);
        } else {
            $cek = $this->sales_model->cekPCode($kd);
        }

        if (!empty($cek)) {
            echo $hasil = "ok";
        } else {
            echo $hasil = "tidak";
        }
//            echo $kd;die();
        return $hasil;
    }

    function cetak($no) {
        // $no	= $this->uri->segment(4);

        $printer = $this->sales_model->NamaPrinter($_SERVER['REMOTE_ADDR']);

        $data['ip'] = $printer[0]['ip'];
        $data['nm_printer'] = $printer[0]['nm_printer'];
        $data['store'] = $this->sales_model->aplikasi();
        $data['header'] = $this->sales_model->all_trans($no);
        $data['detail'] = $this->sales_model->det_trans($no);

//                        $data['ip']    = "\\\\".."\LQ-2170s";
//		$this->load->view('transaksi/sales/cetak',$data); // jika untuk tes
        $this->load->view('transaksi/sales/cetak_transaksi', $data); // jika ada printernya
    }

}

?>