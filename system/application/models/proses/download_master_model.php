<?php

class download_master_model extends Model {

    function __construct() {
        parent::__construct();
    }

    //model
    function upload_csv_id() { // untuk download manual
         error_reporting(0);
        //ini alamat untuk tempat copy file CSV
        //  $pathDta	= set_realpath(APPPATH."downloadmail");
//     $target_path ="D:/BackupOmah";
        //$target = set_realpath(APPPATH . "downloadmail");
        $path = $this->upload_config['upload_path'] = APPPATH . 'downloadmail/';
        $target_path = $path . basename($_FILES['filename']['name']);
        if (move_uploaded_file($_FILES['filename']['tmp_name'], $target_path)) {
            
        } else {
            echo "Gagal Download Master Barang";
            //redirect("admin/setting_csv");
        }
        //echo $target_path;die();
        // ini script untuk mengimport data CSV ke MySQL
        $filename = $target_path;
        $handle = fopen("$filename", "r");
        // echo $handle;die();
        $bt = 0;
        $this->db->query('DELETE FROM masterbarang');
        
        while (($data = fgetcsv($handle, 4906, ";")) !== FALSE) {
//            echo "<pre>";
//            echo count($data[2]);
//            echo "</pre>";
            // die();
            switch ($data[1]) {
                case "masterbarang" :
                    $det = $data[2];
                    $gd = $data[0];
                    // echo "barang";
                    if ($det != "data tidak ditemukan") {
                        $this->do_simpan_barang($det, $gd);
                    }
                    break;
//                case "transaksi_detail" :
//                    $det = $data[2];
//                    $gd = $data[0];
//                    if ($det != "data tidak ditemukan") {
//                        $this->do_simpan_detail($det, $gd);
//                    }
//                    break;
//                case "trans_terima_header" :
//                    $det = $data[2];
//                    $gd = $data[0];
//                    if ($det != "data tidak ditemukan") {
//                        $this->do_terima_header($det, $gd);
//                    }
//                    break;
//                case "trans_terima_detail" :
//                    $det = $data[2];
//                    $gd = $data[0];
//                    if ($det != "data tidak ditemukan") {
//                        $this->do_terima_detail($det, $gd);
//                    }
//                    break;
            }
            $bt++;
        }
        fclose($handle);

        return true;
    }

    function do_simpan_barang($det, $gd) { // simpan master barang
        $da = explode("#", $det);
        //echo $da[0];
        //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
        $cek = $this->getRow("SELECT * FROM masterbarang WHERE PCode = '$da[0]'");
       //  echo "<pre>";print_r($cek);echo "</pre>";//die();
        //echo count($cek);
        if (!empty($cek)) {
            //  echo "oke";die();
//            if ($da[16] == 2) {
//                $this->db->update('transaksi_header', array("Status" => 2), array("NoStruk" => $da[2], "Gudang" => $gd));
//            } else {
//                $this->db->delete('transaksi_header', array("NoStruk" => $da[2], "Gudang" => $gd));
//            }
       // $this->db->where('PCode', $da[0]);
        }

        $data = array(
            'PCode' => $da[0],
            'NamaStruk' => $da[1],
            'NamaLengkap' => $da[2],
            'NamaInitial' => $da[3],
            'HargaJual' => $da[4],
            'KdGrupHarga' => $da[5],
            'FlagHarga' => $da[6],
            'HargaBeliAkhir' => $da[7],
            'HargaBeliRata' => $da[8],
            'KdSatuan' => $da[9],
            'ParentCode' => $da[10],
            'Konversi' => $da[11],
            'KdSupplier' => $da[12],
            'KdPrincipal' => $da[13],
            'Status' => $da[14],
            'MinOrder' => $da[15],
            'KdDivisi' => $da[16],
            'KdSubDivisi' => $da[17],
            'KdKategori' => $da[18],
            'KdSubKategori' => $da[19],
            'KdBrand' => $da[20],
            'KdSubBrand' => $da[21],
            'KdDepartemen' => $da[22],
            'KdKelas' => $da[23],
            'KdType' => $da[24],
            'KdSize' => $da[25],
            'KdSubSize' => $da[26],
            'KdKemasan' => $da[27],
            'AddDate' => $da[28],
            'EditDate' => $da[29],
            'BarCode' => $da[30],
            'KdGrupBarang' => $da[31],
            'FlagAktif' => $da[32]
        );
     
            $this->db->insert('masterbarang', $data);
            
    }

    function download_auto($pathbaca, $nmattc) { // untuk download auto
        // ini script untuk mengimport data CSV ke MySQL
        $filename = $pathbaca . $nmattc;
        // print_r($filename) or die();
        $handle = fopen($filename, "r") or die("Direktori Failed");
        //print_r($handle);
//         $word = explode(";",fgets($handle, 4096));
        $bt = 0;
//     while (($data = fgetcsv($handle, 4906, ";")) !== FALSE)
        while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
            //echo "<pre>";print_r($data[2]);echo "</pre>";die();
            switch ($data[1]) {
                case "transaksi_header" :
                    $det = $data[2];
                    $gd = $data[0];
                    if ($det != "data tidak ditemukan") {
                        $this->do_simpan_header($det, $gd);
                    }
                    break;
                case "transaksi_detail" :
                    $det = $data[2];
                    $gd = $data[0];
                    if ($det != "data tidak ditemukan") {
                        $this->do_simpan_detail($det, $gd);
                    }
                    break;
                case "trans_terima_header" :
                    $det = $data[2];
                    $gd = $data[0];
                    if ($det != "data tidak ditemukan") {
                        $this->do_terima_header($det, $gd);
                    }
                    break;
                case "trans_terima_detail" :
                    $det = $data[2];
                    $gd = $data[0];
                    if ($det != "data tidak ditemukan") {
                        $this->do_terima_detail($det, $gd);
                    }
                    break;
            }
            //$bt++;
        }
        fclose($handle);
        return true;
    }

    function sumOmzet($kdgudang, $tglawal, $tglakhir) {
        $sql = "SELECT Tanggal,Gudang, SUM(TotalNilai) AS TotalNilai FROM transaksi_header WHERE Gudang='$kdgudang' AND Tanggal BETWEEN '$tglawal' AND '$tglakhir' GROUP BY Tanggal,Gudang";
        //echo $sql;
        // return $this->getRow($sql);
        return $this->db->query($sql);
    }

    function cekRekapOmzet($kdgudang, $tgl) {
        $sql = "SELECT Gudang,Tanggal,Omzet,Terbayar,Status FROM rekap_omzet WHERE Gudang='$kdgudang' AND Tanggal ='$tgl'";
        //echo $sql;
        return $this->getRow($sql);
    }

    function getReports($sDateStart, $sDateEnd) {

        $csv_terminated = "\n";
        $csv_separator = "|";
        $csv_enclosed = '"';
        $csv_escaped = "\"";
        $schema_insert = "";
        $out = '';

        $aResults = $this->db
                ->select('*')
                ->from('transaksi_header')
                ->where('Tanggal >=', $sDateStart)
                ->where('Tanggal <=', $sDateEnd)
                ->get();


        $aFields = $aResults->list_fields();
        $out .= 'Transaksi_header' . $csv_separator . $csv_terminated;
        /* header  field */
        /*
          foreach ( $aFields as $sField ) {

          $l = $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes( $sField ) ) . $csv_enclosed;
          $schema_insert .= $l;
          $schema_insert .= $csv_separator;
          }

          $out .= $schema_insert . $csv_terminated; */
        /* end header */

        // Format the data
        foreach ($aResults->result_array() as $aResult) {
            $schema_insert = '';
            foreach ($aFields as $sField) {
                $schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $aResult[$sField]) . $csv_enclosed . $csv_separator;
            }
            $out .= $schema_insert;
            $out .= $csv_terminated;
        }
        return $out;
    }

    function getReportsDetail($tabel, $kondisi, $sDateStart, $sDateEnd, $csv_terminated, $csv_separator, $csv_enclosed, $csv_escaped, $schema_insert, $out, $batasjudul) {
//        $sql = $this->db->query("select * from $tabel where $kondisi between $sDateStart and $sDateEnd") ;
        $wh = "$kondisi between '$sDateStart' and '$sDateEnd'";
        $aResults = $this->db
                ->select('*')
                ->from($tabel)
                ->where($wh)
                ->get();

        $aFields = $aResults->list_fields();
        $out .= $tabel . $batasjudul . $csv_terminated;


        // Format the data
        foreach ($aResults->result_array() as $aResult) {
            $schema_insert = '';
            foreach ($aFields as $sField) {
                $schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $aResult[$sField]) . $csv_enclosed . $csv_separator;
            }
            $out .= $schema_insert;
            $out .= $csv_terminated;
        }
        return $out;
    }

    function do_terima_header($det, $gd) { // simpan terima header
        $da = explode("#", $det);
        //echo "<pre>";print_r($da);echo "</pre>";die();
        //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
        $cek = $this->getRow("select * from trans_terima_header where NoDokumen = '$da[1]' and Gudang='$gd'");
        if (!empty($cek)) {
            $this->db->delete('trans_terima_header', array("NoDokumen" => $da[1], "Gudang" => $gd));
        }
        $data = array(
            'Gudang' => $da[0],
            'NoDokumen' => $da[1],
            'TglDokumen' => $da[2],
            'TglTerima' => $da[3],
            'SumberOrder' => $da[4],
            'NoOrder' => $da[5],
            'Keterangan' => $da[6],
            'KdSupplier' => $da[7],
            'AddDate' => $da[8],
            'AddUser' => $da[9],
            'EditDate' => $da[10],
            'EditUser' => $da[11],
            'DeleteDate' => $da[12],
            'DeleteUser' => $da[13],
            'FlagDelete' => $da[14],
            'NoPO' => $da[15],
        );
        $this->db->insert('trans_terima_header', $data);
    }

    function do_terima_detail($det, $gd) { // simpan terima detail
        $da = explode("#", $det);
        //echo "<pre>";print_r($da);echo "</pre>";die();
        //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
        $cek = $this->getRow("select * from trans_terima_detail where NoDokumen = '$da[1]' and Gudang='$gd'");
        if (!empty($cek)) {
            $this->db->delete('trans_terima_detail', array("NoDokumen" => $da[1], "Gudang" => $gd, "PCode" => $da[2]));
        }
        $data = array(
            'Gudang' => $da[0],
            'NoDokumen' => $da[1],
            'PCode' => $da[2],
            'QtyTerima' => $da[3],
            'QtyHargaTerima' => $da[4],
            'AddDate' => $da[5],
            'AddUser' => $da[6],
            'EditDate' => $da[7],
            'EditUser' => $da[8],
            'DeleteDate' => $da[9],
            'DeleteUser' => $da[10],
            'FlagDelete' => $da[11],
        );
        $this->db->insert('trans_terima_detail', $data);
    }

    function do_simpan_header($det, $gd) { // simpan transaksi header
        $da = explode("#", $det);
        //echo "<pre>";print_r($da);echo "</pre>";die();
        //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
        $cek = $this->getRow("select * from transaksi_header where NoStruk = '$da[2]' and Gudang='$gd'");
        if (!empty($cek)) {
//             echo "oke";
            if ($da[16] == 2) {
                $this->db->update('transaksi_header', array("Status" => 2), array("NoStruk" => $da[2], "Gudang" => $gd));
            } else {
                $this->db->delete('transaksi_header', array("NoStruk" => $da[2], "Gudang" => $gd));
            }
            $this->db->delete('transaksi_detail', array("NoStruk" => $da[2], "Gudang" => $gd));
        }
        $data = array(
            'NoKassa' => $da[0],
            'Gudang' => $gd,
            'NoStruk' => $da[2],
            'Tanggal' => $da[3],
            'Waktu' => $da[4],
            'Kasir' => $da[5],
            'KdStore' => $da[6],
            'TotalItem' => $da[7],
            'TotalNilai' => $da[8],
            'TotalBayar' => $da[9],
            'Kembali' => $da[10],
            'Tunai' => $da[11],
            'KKredit' => $da[12],
            'KDebit' => $da[13],
            'Voucher' => $da[14],
            'Discount' => $da[15],
            'Status' => $da[16],
            'KdCustomer' => $da[17],
            'NamaCustomer' => $da[18],
            'Gender' => $da[19],
            'TglLahir' => $da[20],
            'Keterangan' => $da[21],
            'EditDate' => $da[22],
            'EditUser' => $da[23]
        );
        if ($da[16] == 1) {
            $this->db->insert('transaksi_header', $data);
        }
    }

    function do_simpan_detail($det, $gd) {
        $da = explode("#", $det);
//         echo "<pre>";print_r($da);echo "</pre>";die();
        //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
        $cek = $this->getRow("select * from transaksi_detail where NoStruk = '$da[2]' and Gudang='$gd'");
        if (!empty($cek)) {
            $this->db->delete('transaksi_detail', array("NoStruk" => $da[2], "Gudang" => $gd, "PCode" => $da[7]));
        }
        $data = array(
            'NoKassa' => $da[0],
            'Gudang' => $gd,
            'NoStruk' => $da[2],
            'Tanggal' => $da[3],
            'Waktu' => $da[4],
            'Kasir' => $da[5],
            'KdStore' => $da[6],
            'PCode' => $da[7],
            'Qty' => $da[8],
            'Harga' => $da[9],
            'Ketentuan1' => $da[10],
            'Disc1' => $da[11],
            'Jenis1' => $da[12],
            'Ketentuan2' => $da[13],
            'Disc2' => $da[14],
            'Jenis2' => $da[15],
            'Ketentuan3' => $da[16],
            'Disc3' => $da[17],
            'Jenis3' => $da[18],
            'Ketentuan4' => $da[19],
            'Disc4' => $da[20],
            'Jenis4' => $da[21],
            'Netto' => $da[22],
            'Hpp' => $da[23],
            'Status' => $da[24],
            'Keterangan' => $da[25]
        );
        $this->db->insert('transaksi_detail', $data); //die();
    }

    function getDetailTrx($tabelD, $tabelH, $kondisi, $tgl, $sDateStart, $sDateEnd, $csv_terminated, $csv_separator, $csv_enclosed, $csv_escaped, $schema_insert, $out, $batasjudul) {
        $out .= $tabelD . $batasjudul . $csv_terminated;
        $dt = "select $tabelD.* FROM $tabelD
               INNER JOIN $tabelH ON 
               $tabelH.$kondisi=$tabelD.$kondisi 
               AND $tabelH.TglDokumen BETWEEN '$sDateStart' AND '$sDateEnd'";
        $nil = $this->getRow($dt);

//        fro
//        print_r($nil);
        $aList = $this->db
                ->select('*')
                ->from($tabelD)
                ->get();

        $aFields = $aList->list_fields();
        /* header  field */
        $lstD = '';
        $by = '';
        if (!empty($nil)) {

            for ($y = 0; $y < count($aFields);) {
                $lstD .= $aFields[$y] . ",";
//                $l = $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes( $sField ) ) . $csv_enclosed;
//                $schema_insert .= $l;
//                $schema_insert .= $csv_separator;
                $by .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $nil->$aFields[$y]) . $csv_enclosed . $csv_separator;
//            $by .=$nil->$aFields[$y];
                $y++;
            }
        } else {
            $by = "data tidak ditemukan";
        }
//        echo $lstD; 
        $out .= $by;

        $out .= $csv_terminated;

//        $aFields = $aResults->list_fields();


        return $out;
    }

    function FindCabang() {
        $sql = "SELECT KdCabang,KdGU FROM aplikasi";
        return $this->getRow($sql);
    }

    function getStokSimpan($tahun, $field) {
        $sql = "select KodeBarang,Gudang,$field from stock where Tahun='$tahun'";
        return $this->getArrayResult($sql);
    }

    function getAllAplikasi($tahun) {
        $sql = "select * from aplikasi where Tahun='$tahun'"; //echo $sql;die();
        return $this->getRow($sql);
    }

    function getSetupNo($tahun) {
        $sql = "select * from setup_no where Tahun='$tahun'";
        return $this->getArrayResult($sql);
    }

    function locktables($table) {
        $this->db->simple_query("LOCK TABLES $table");
    }

    function unlocktables() {
        $this->db->simple_query("UNLOCK TABLES");
    }

    function getRow($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getArrayResult($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function NumResult($sql) {
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getLastDate() {
        $sql = "select TglTrans,Tahun from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
        return $this->getRow($sql);
    }

}

?>