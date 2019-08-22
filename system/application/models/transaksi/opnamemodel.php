<?php
class Opnamemodel extends Model {

    var $table = 'trans_opname_detail';

    function __construct() {
        parent::__construct();
    }

    function getList() {
        $query = $this->db->query("SELECT * FROM trans_opname_header ORDER BY NoDokumen*1 DESC");
        return $query->result_array();
    }

    function getBarangName($kode, $name, $tahun, $field) {
//        $sql = "
//                    SELECT CONCAT(KodeBarang,' ',NamaLengkap) AS PCode, KodeBarang AS PCode1,NamaLengkap,$field,HargaJual FROM
//                    (SELECT $field,KodeBarang FROM stock WHERE KodeBarang LIKE '$name%' AND Tahun='$tahun')a
//                     INNER JOIN masterbarang b
//                     ON a.KodeBarang = b.PCode OR NamaLengkap LIKE '%$name%' LIMIT 0,10";
        $sql = "SELECT CONCAT(a.KodeBarang,' ',b.NamaLengkap) AS PCode, a.KodeBarang AS PCode1,b.NamaLengkap, a.$field,b.HargaJual 
                    FROM stock a INNER JOIN masterbarang b
                    ON a.KodeBarang = b.PCode   
                    WHERE a.Tahun='$tahun' AND (b.PCode LIKE '$name%' OR b.NamaLengkap LIKE '$name%') LIMIT 0,10";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function getBarangBarcode($kode, $name, $tahun, $field) {
        $sql = "SELECT CONCAT(a.KodeBarang,' ',b.NamaLengkap) AS PCode, a.KodeBarang AS PCode1,b.NamaLengkap, a.$field,b.HargaJual 
                    FROM stock a INNER JOIN masterbarang b
                    ON a.KodeBarang = b.PCode   
                    WHERE a.Tahun='$tahun' AND b.BarCode = '$name'";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function get_datatables($no) {
        $sql = "SELECT a.NoDokumen,a.PCode, b.NamaLengkap, a.QtyKomputer,a.QtyOpname,a.Selisih 
                    FROM trans_opname_detail a INNER JOIN
                    masterbarang b 
                    ON b.PCode = a.PCode
                    WHERE a.NoDokumen='$no' ORDER BY a.AddDate DESC";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function get_detail_by_id($no, $pcode) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('PCode', $pcode);
        $query = $this->db->get('trans_opname_detail');
        return $query;
    }

    function get_selisih_by_id($no) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('Selisih !=', '0');
        $query = $this->db->get('trans_opname_detail');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function delete_by_id($no, $pcode) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('PCode', $pcode);
        $this->db->delete($this->table);
    }
    
    function getHeader($id) {
        $this->db->where('NoDokumen', $id);
        $query = $this->db->get('trans_opname_header');
        return $query->row();
    }
    
    function getDate() {
        $sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans,TglTrans as TglTrans2 from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
        return $this->getRow($sql);
    }

    function num_row($id, $with) {
        $clause = "";
        if ($id != '') {
            if ($with == "NoDokumen") {
                $clause = " where $with like '%$id%'";
            } else {
                $clause = " where $with = '$id'";
            }
        }
        $sql = "SELECT NoDokumen FROM trans_opname_header $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getLokasi() {
        $sql = "select KdLokasi,Keterangan from lokasi order by KdLokasi";
        return $this->getArrayResult($sql);
    }

    function ifPCodeBarcode($id) {
        $bar = substr($id, 0, 10);
        $sql = "SELECT PCode FROM masterbarang Where PCode='$id'";
        return $this->getRow($sql);
    }

    function cekShareLokasi($kode) {
        $sql = "select KodeBarang from stock where KodeBarang='$kode' ";
        return $this->NumResult($sql);
    }

    function getPCodeDet($pcode, $tahun, $field) {
        $sql = "
                    SELECT $field,NamaLengkap,HargaJual FROM
                    (SELECT $field,KodeBarang FROM stock WHERE KodeBarang='$pcode' AND Tahun='$tahun')a
                     INNER JOIN masterbarang b
                     ON a.KodeBarang = b.PCode";
        return $this->getRow($sql);
    }

    function getNewNo($tahun) {
        $sql = "select NoOpname from aplikasi where Tahun='$tahun'";
        return $this->getRow($sql);
    }

    function CekStock($field, $fieldakhir, $pcode, $tahun) {
        $sql = "select $field,$fieldakhir from stock
		where Tahun='$tahun'
		and KodeBarang='$pcode'";
        return $this->getRow($sql);
    }

    function cekPast($no, $pcode) {
        $sql = "select distinct QtyKomputer,QtyOpname,Selisih
		from trans_opname_detail
		where NoDokumen='$no' and PCode='$pcode'";
        return $this->getRow($sql);
    }

    function FindLastCounter($no) {
        $sql = "select distinct Counter
		from trans_opname_detail
		where NoDokumen='$no' order by Counter desc limit 0,1";
        return $this->getRow($sql);
    }

    function getDetail_old($id) {
        $sql = "
		select d.*,NamaInitial from(
		SELECT distinct NoDokumen,PCode,QtyKomputer,QtyOpname,Selisih
		from trans_opname_detail 
		Where NoDokumen='$id'
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial
			from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
    }

    function getDetailSelisih($id) {
        $sql = "
		select d.*,NamaInitial from(
		SELECT distinct NoDokumen,PCode,QtyKomputer,QtyOpname,Selisih,HJualItem
		from trans_opname_detail 
		Where NoDokumen='$id' And Selisih <> 0
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial
			from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
    }

    function getlistStock($thn, $field) {
        $sql = "
			SELECT Qty,KodeBarang,NamaLengkap,HargaJual FROM
			(SELECT $field AS Qty, KodeBarang FROM stock WHERE Tahun='$thn' ORDER BY KodeBarang)s
			INNER JOIN masterbarang m ON m.`PCode`=s.KodeBarang
		";
        return $this->getArrayResult($sql);
    }

    function getDetailForPrint($id) {
        $sql = "
		select d.*,NamaInitial,HJ,(HJ * Selisih) AS Netto from(
		SELECT distinct PCode,QtyKomputer,QtyOpname,Selisih,HJualItem AS HJ
		from trans_opname_detail 
		Where NoDokumen='$id'
		) d
		left join
		(
			select PCode,NamaStruk as NamaInitial
			from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
    }

    function getCountDetail($id) {
        $sql = "SELECT NoDokumen FROM trans_opname_detail where NoDokumen='$id'";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function NamaPrinter($id) {
        $sql = "SELECT * from kassa where ip='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function aplikasi() {
        $sql = "select * from aplikasi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
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

    function getDetailAttrCetak($id, $pcode, $counter) {
        $sql = "
		select NamaAttribute,Nilai,TipeAttr from
		(
			select KdAttribute,NilAttribute as Nilai from trans_opname_detail 
			Where NoDokumen='$id' and PCode='$pcode' and Counter='$counter'
		)d 
		left join
		(
			select KdAttribute,TipeAttr,NamaAttribute from attribute_barang 
		)a
		on a.KdAttribute=d.KdAttribute";
        return $this->getArrayResult($sql);
    }

    function getSatuan($pcode) {
        $sql = "SELECT KdSatuanBesar,(select keterangan from satuan where NamaSatuan=KdSatuanBesar) as NamaBesar,
KdSatuanTengah,(select keterangan from satuan where NamaSatuan=KdSatuanTengah) as NamaTengah,
KdSatuanKecil ,(select keterangan from satuan where NamaSatuan=KdSatuanKecil) as NamaKecil 
		from masterbarang where PCode='$pcode'";
        return $this->getRow($sql);
    }

    function getDetailAttr($id, $pcode, $counter) {
        $sql = "
			select KdAttribute,NilAttribute from trans_opname_detail 
			Where NoDokumen='$id' and PCode='$pcode' and Counter='$counter'";
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
        $row = $qry->num_rows();
        $qry->free_result();
        return $row;
    }

}

?>