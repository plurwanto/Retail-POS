<?php

class Tutup_hari_model extends Model {

    function __construct() {
        parent::__construct();
    }

    function aplikasi() {
        $sql = "select * from aplikasi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function FindUser($id) {
        $sql = "Select Id,UserName from user where Active='Y' and Id<>'$id'";
        return $this->getArrayResult($sql);
    }

    function FindNextDate($tahun, $interval) {
        $sql = "SELECT TglTrans,DATE_FORMAT(DATE_ADD(TglTrans,INTERVAL $interval DAY),'%d-%m-%Y') AS nextdate2,
                        DATE_ADD(TglTrans,INTERVAL $interval DAY) AS nextdate FROM aplikasi WHERE Tahun='$tahun'";
        return $this->getRow($sql);
    }

    function cekOrder() {
        $sql = "SELECT * FROM sales_temp;";
        return $this->NumResult($sql);
    }

    function all_trans($tgl) {
        $sql = "SELECT Tanggal,Kasir,COUNT(NoStruk) as TTLStruk,SUM(TotalItem) as TotalItem,SUM(TotalNilai) as TotalNilai,
                    SUM(TotalBayar)as TotalBayar,SUM(Kembali) as Kembali,SUM(Tunai) as Tunai,SUM(KKredit) as KKredit,
SUM(KDebit)as KDebit,SUM(Voucher)as Voucher  FROM transaksi_header WHERE STATUS='1' AND Tanggal='$tgl' GROUP BY Kasir ";
//		$sql = "select * from transaksi_header where status='1' and Tanggal='$tgl' ORDER BY NoStruk DESC ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function NamaPrinter($id) {
        $sql = "SELECT * from kassa where ip='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getStokTerima($tahun, $field) {
        $sql = "select KodeBarang,Gudang,$field from stock where Tahun='$tahun'";
        return $this->getArrayResult($sql);
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

    function getCekTglPerubahan() {
        $this->db->where('StatusPerubahan', 'N');
        $query = $this->db->get('Perubahan_harga_header');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function getDetailTglPerubahan($tgl) {
        $this->db->where('TglPerubahan', $tgl);
        $query = $this->db->get('Perubahan_harga_detail');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

}

?>