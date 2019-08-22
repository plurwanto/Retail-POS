<?php
class tutup_hari extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('proses/tutup_hari_model');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['msg'] = "";
            $last_update_query = $this->tutup_hari_model->getLastDate();
            $data['tanggal'] = $last_update_query;
            $data['content'] = 'proses/tutup_hari';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function doThis() {
        error_reporting(0);
        $mylib = new globallib();
        $id = $this->session->userdata('userid');
        $tgl = $this->tutup_hari_model->getLastDate(); //echo $tgl->TglTrans;die();
        $tglkomp = date('Y-m-d');
        $tahun = substr($tgl->TglTrans, 0, 4);
//                $this->cetak($tgl->TglTrans,$tahun); // cetak rekap harian hanya untuk counter
        $this->db->update("aplikasi", array("FlagTutupHari" => "Y"), array("Tahun" => $tahun));
        $usergantung = $this->tutup_hari_model->FindUser($id);
        $nextdates = $this->getNewDate($tahun, 1);
        $nextdates2 = $this->getNewDate($tahun, -1);
        $bulanini = substr($nextdates->TglTrans, 5, 2);
        $tahunini = substr($nextdates->TglTrans, 0, 4);
        $bulanbesok = substr($nextdates->nextdate, 5, 2);
        $tahunbesok = substr($nextdates->nextdate, 0, 4);
        $session_name = $this->session->userdata('username');
        $tanggalbaru = $mylib->ubah_tanggal($nextdates->TglTrans);
        $validTrans = "";
        if ($bulanini == $bulanbesok) {
            $validTrans = "";
        } else {
            $order = $this->tutup_hari_model->cekOrder();

            if ($order != 0) {
                $validTrans .= "<font color = 'red'><b> Masih Ada Order Barang Yang Belum Diproses</b></font><br>";
            }
        }

        if (!empty($usergantung)) {
            $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="fa fa-lock"></i> <b> Proses Tutup Hari Tidak Bisa Dilakukan
					Karena Masih Ada User Yang Mengakses Program<br>Gunakan Menu Hapus User Gantung
					Pada Menu Proses Untuk Menghapus User Gantung
					</b> </div><br>';
            $success = false;
        }

        $validTrans = "";
        if ($validTrans != "") {
            $msg = $validTrans;
        }

        if (empty($usergantung) && $validTrans == "") {
            $fieldakhir_ini = "QtyAkhir" . $bulanini;
            $fieldakhir_besok = "QtyAkhir" . $bulanbesok;
            $fieldawal_besok = "QtyAwal" . $bulanbesok;
//                        echo $nextdates->TglTrans;die();
            if ($nextdates2->nextdate >= $tglkomp) {
                $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"><i class="fa fa-times"></i></button>'
                        . '<i class="fa fa-lock"></i> <b> Proses Tutup Hari Tidak Bisa Dilakukan Ke Tanggal '.$nextdates->nextdate2.'
					,Karena Tanggal melebihi dari tanggal sekarang
					</b> </div><br>';
                $success = false;
            } else {

                if ($tahunini != $tahunbesok) {
                    $getAplikasi = $this->tutup_hari_model->getAllAplikasi($tahunini);
                    $getSetupPerusahaan = $this->tutup_hari_model->getSetupNo($tahunini);
                    $prefix = substr($tahunbesok, 2, 2);
                    $new_no = $prefix . "000001";
                    $data = array(
                        'Tahun' => $tahunbesok,
                        'KdCabang' => $getAplikasi->KdCabang,
                        'NoKassa' => $getAplikasi->NoKassa,
                        'KdGU' => $getAplikasi->KdGU,
                        'KdAdm' => $getAplikasi->KdAdm,
                        'PjgKdOutlet' => $getAplikasi->PjgKdOutlet,
                        'NoAdjust' => $new_no,
                        'NoOpname' => $new_no,
                        'NoOrder' => $new_no,
                        'NoFG' => $new_no,
                        'NoWrite' => $new_no,
                        'KdSO' => $getAplikasi->KdSO,
                        'KdPL' => $getAplikasi->KdPL,
                        'TglTrans' => $nextdates->nextdate,
                        'NamaPT' => $getAplikasi->NamaPT,
                        'Alamat1PT' => $getAplikasi->Alamat1PT,
                        'Alamat2PT' => $getAplikasi->Alamat2PT,
                        'TelpPT' => $getAplikasi->TelpPT,
                        'LamaPass' => $getAplikasi->LamaPass,
                        'NPWP' => $getAplikasi->NPWP,
                        'KdMutasi' => $getAplikasi->KdMutasi,
                        'KdPengeluaran' => $getAplikasi->KdPengeluaran,
                        'KdPenerimaanLain' => $getAplikasi->KdPenerimaanLain,
                        'KdDespatch' => $getAplikasi->KdDespatch,
                        'KdRetur' => $getAplikasi->KdRetur,
                        'KdAdjust' => $getAplikasi->KdAdjust,
                        'KdOpname' => $getAplikasi->KdOpname,
                        'KdPemusnahanBS' => $getAplikasi->KdPemusnahanBS,
                        'FolderSqlDump' => $getAplikasi->FolderSqlDump,
                        'PathAplikasi' => $getAplikasi->PathAplikasi,
                        'PPN' => $getAplikasi->PPN,
                        'MaxCD' => $getAplikasi->MaxCD,
                        'TglPAP' => $getAplikasi->TglPAP,
                        'TtdTax' => $getAplikasi->TtdTax,
                        'JbtTax' => $getAplikasi->JbtTax,
                        'NoASP' => $getAplikasi->NoASP,
                        'NoPajakF' => $getAplikasi->NoPajakF,
                        'NoPajakR' => $getAplikasi->NoPajakR,
                        'FlagLimitKredit' => $getAplikasi->FlagLimitKredit,
                        'FlagLimitFaktur' => $getAplikasi->FlagLimitFaktur,
                        'ParameterUmurPiutang' => $getAplikasi->ParameterUmurPiutang,
                        'SistemPosting' => $getAplikasi->SistemPosting,
                        'PostingPersediaanPerGudang' => $getAplikasi->PostingPersediaanPerGudang,
                        'PostingPersediaanPerBarang' => $getAplikasi->PostingPersediaanPerBarang,
                        'PostingPersediaanBSPisah' => $getAplikasi->PostingPersediaanBSPisah,
                        'PostingPiutangPerLangganan' => $getAplikasi->PostingPiutangPerLangganan,
                        'PostingJualHppBrg' => $getAplikasi->PostingJualHppBrg,
                        'FlagTutupHari' => $getAplikasi->FlagTutupHari,
                        'FlagTutupBulan' => $getAplikasi->FlagTutupBulan,
                        'SabtuMasuk' => $getAplikasi->SabtuMasuk,
                        'AddDate' => $nextdates->TglTrans,
                        'EditDate' => "0000-00-00",
                    );
                    //$this->db->insert("aplikasi",$data);
                    // echo "aplikasi ok<br>";

                    for ($sp = 0; $sp < count($getSetupPerusahaan); $sp++) {
                        $data2 = array(
                            'Tahun' => $tahunbesok,
                            'no_struk' => $new_no,
                            'NoLainLain' => $new_no,
                            'NoTerima' => $new_no,
                            'NoRetur' => $new_no,
                            'NoOrder' => $new_no,
                        );
                        $this->db->insert("setup_no", $data2);
                    }
                    //echo "setup no ok<br>";

                    $stoksimpan = $this->tutup_hari_model->getStokSimpan($tahunini, $fieldakhir_ini);
                    for ($s = 0; $s < count($stoksimpan); $s++) {
                        $data = array(
                            "Tahun" => $tahunbesok,
                            "Gudang" => $stoksimpan[$s]['Gudang'],
                            "KodeBarang" => $stoksimpan[$s]['KodeBarang'],
                            $fieldawal_besok => $stoksimpan[$s][$fieldakhir_ini],
                            $fieldakhir_besok => $stoksimpan[$s][$fieldakhir_ini]
                        );
                        $this->db->insert("stock", $data);
                    }
                    //echo "stock ok<br>";
                    // $this->db->update("aplikasi", array("FlagTutupHari" => "T"), array("Tahun" => $tahunini));
                    $this->db->update("aplikasi", array("FlagTutupHari" => "T", "TglTrans" => $nextdates->nextdate, "Tahun" => $tahunbesok), array("Tahun" => $tahunini));
                } else {
                    if ($bulanini != $bulanbesok) {
                        $this->db->query("update stock set $fieldawal_besok=$fieldakhir_ini,$fieldakhir_besok=$fieldakhir_ini where Tahun='$tahunini'");
                        $this->db->update("aplikasi", array("FlagTutupHari" => "T", "TglTrans" => $nextdates->nextdate), array("Tahun" => $tahunini));
                    } else {
                        $this->db->update("aplikasi", array("FlagTutupHari" => "T", "TglTrans" => $nextdates->nextdate), array("Tahun" => $tahunini));
                    }
                }
                $success = true;
                /* $sessiondata = array(
                  'Tanggal_Trans' => $nextdates->nextdate
                  );
                  $this->session->set_userdata($sessiondata); */
                $this->session->set_userdata('Tanggal_Trans', $nextdates->nextdate2);
                $tanggalbaru = $mylib->ubah_tanggal($nextdates->nextdate);
                $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                        . '<i class="fa fa-unlock"></i>  Proses Tutup Hari Berhasil <br>'
                        . 'Kasir: ' . $session_name . '<br>'
                        . 'Tanggal: ' . $tanggalbaru
                        . '</div>');
            }
        }

        $cek_perubahan = $this->tutup_hari_model->getCekTglPerubahan();
        $tgl_berubah = $mylib->ubah_tanggal($cek_perubahan[0]['TglPerubahan']);

        if ($tgl_berubah == $tanggalbaru) {
            $detail_berubah = $this->tutup_hari_model->getDetailTglPerubahan($cek_perubahan[0]['TglPerubahan']);
            foreach ($detail_berubah as $value) {
                $tgl_detail = $value['TglPerubahan'];
                $pcode = $value['PCode'];
                $h_lama = $value['HargaLama'];
                $h_baru = $value['HargaBaru'];

                $this->db->update('masterbarang', array('HargaJual' => $h_baru, 'EditDate' => $tgl_detail), array('PCode' => $pcode));
            }
            $this->db->update('Perubahan_harga_header', array('StatusPerubahan' => 'D'), array('TglPerubahan' => $cek_perubahan[0]['TglPerubahan']));
        }

        $this->db->update("aplikasi", array("FlagTutupHari" => "T"), array("Tahun" => $tahun));
        $head_sess = "$session_name/" . $tanggalbaru . "";
        echo $msg . "&&&" . $head_sess . "&&&" . $success;
    }

    function getNewDate($tahun, $interval) {
        $apl = $this->tutup_hari_model->getAllAplikasi($tahun);
        $nextdates = $this->tutup_hari_model->FindNextDate($tahun, $interval);
        $bulanbesok = substr($nextdates->nextdate, 5, 2);
        $tahunbesok = substr($nextdates->nextdate, 0, 4);
        $tanggalbesok = substr($nextdates->nextdate, 8, 2);
        $hari = date("w", mktime(0, 0, 0, $bulanbesok, $tanggalbesok, $tahunbesok));
        $sabtumasuk = $apl->SabtuMasuk;
//		if($sabtumasuk=="N"&&$hari==6)  // 0 = minggu, 1 = senin
//		{
//			$interval++;
//			$nextdates = $this->getNewDate($tahun,$interval);
//		}
//		if($hari==0)
//		{
//			$interval++;
//			$nextdates = $this->getNewDate($tahun,$interval);
//		}
        return $nextdates;
    }

    function cetak($tgl, $tahun) {
        // $no	= $this->uri->segment(4);

        $printer = $this->tutup_hari_model->NamaPrinter($_SERVER['REMOTE_ADDR']);

        $data['ip'] = $printer[0]['ip'];
        $data['nm_printer'] = $printer[0]['nm_printer'];
        $data['store'] = $this->tutup_hari_model->aplikasi();
        $data['header'] = $this->tutup_hari_model->all_trans($tgl);
//print_r($data['header']);
//			$data['detail']		= $this->tutup_hari_model->det_trans($no);
//die();
//                        $data['ip']    = "\\\\".."\LQ-2170s";

        if (!empty($data['header'])) {
//		$this->load->view('proses/cetak_tutup',$data); // jika untuk tes
            $this->load->view('proses/cetak_transaksi', $data); // jika ada printernya
        }
    }

}

?>