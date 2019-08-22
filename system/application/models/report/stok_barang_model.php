<?php
class Stok_barang_model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    function getBarangName($kode, $name) {
        $sql = "SELECT CONCAT(PCode,' ',NamaLengkap) AS PCode, PCode AS PCode1,NamaLengkap
                    FROM masterbarang
                    WHERE (PCode LIKE '$name%' OR NamaLengkap LIKE '$name%') LIMIT 0,10";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function getBarangBarcode($kode, $name) {
        $sql = "SELECT CONCAT(PCode' 'NamaLengkap) AS PCode, a.PCode AS PCode1,NamaLengkap
                    FROM masterbarang
                    WHERE BarCode = '$name'";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function pil_lokasi($kode) {
        $sql = "select KdLokasi from lokasi where StatusLokasiKecil='Y' and KdLokasi like '$kode%' limit 0,20";
        return $this->getArrayResult($sql);
    }

    function getKodeLokasi($kode) {
        $sql = "select KdLokasi from lokasi where Keterangan = '$kode'";
        return $this->getRow($sql);
    }

    function getLokasi() {
        $sql = "select KdLokasi,Keterangan from lokasi where StatusLokasiKecil='Y' order by KdLokasi";
        return $this->getArrayResult($sql);
    }

    function getBarang() {
        $sql = "select PCode,NamaLengkap from masterbarang order by PCode";
        return $this->getArrayResult($sql);
    }

    function getNamaBarang($kode) {
        $sql = "select PCode,NamaLengkap from masterbarang where PCode='$kode'";
        return $this->getRow($sql);
    }

    function getPCode($pcode) {
        $sql = "select PCode from masterbarang where PCode='$pcode'";
        return $this->NumResult($sql);
    }

    function getDate() {
        $sql = "select TglTrans from aplikasi limit 0,1";
        return $this->getRow($sql);
    }

    function getDetailAttr($wherebrg, $wherelokasi, $fieldawal, $fieldmasuk, $fieldkeluar, $fieldakhir, $tahun, $tabel, $tambahan) {
        $sql = "SELECT o.*,NamaLengkap,NamaAttribute,TipeAttr FROM(
						SELECT PCode,$fieldawal,$fieldmasuk,$fieldkeluar,$fieldakhir,
						KdAttribute,NilAttr,NoPenerimaan,s.KdLokasi AS lokasinya,KonversiBesarKecil,KonversiTengahKecil,$tambahan 
						FROM $tabel s
						WHERE Tahun='$tahun' $wherebrg $wherelokasi 
						and ($fieldawal<>0 OR $fieldmasuk<>0 OR $fieldkeluar<>0 OR $fieldakhir<>0)
						ORDER BY KdLokasi,PCode,NoPenerimaan,$tambahan,s.Urutan
					) o
					LEFT JOIN
					(
						SELECT PCode,NamaLengkap FROM masterbarang
					)b
					ON b.PCode=o.PCode
					LEFT JOIN
					(
						SELECT KdAttribute,NamaAttribute,TipeAttr FROM attribute_barang
					)a
					ON a.KdAttribute=o.KdAttribute";

        return $this->getArrayResult($sql);
    }

    function getDetailnoAttr($wherebrg, $wherelokasi, $fieldawal, $fieldmasuk, $fieldkeluar, $fieldakhir, $tahun, $tabel, $tambahan) {
        $sql = "SELECT o.*,NamaLengkap,NamaAttribute,TipeAttr FROM(
						SELECT DISTINCT(CONCAT(NoPenerimaan,KdLokasi,Counter,AsalData)),PCode,$fieldawal,$fieldmasuk,$fieldkeluar,$fieldakhir,
						KdAttribute,NilAttr,NoPenerimaan,s.KdLokasi AS lokasinya,KonversiBesarKecil,KonversiTengahKecil,$tambahan 
						FROM $tabel s
						WHERE Tahun='$tahun' $wherebrg $wherelokasi 
						and ($fieldawal<>0 OR $fieldmasuk<>0 OR $fieldkeluar<>0 OR $fieldakhir<>0)
						ORDER BY KdLokasi,PCode,NoPenerimaan,$tambahan,s.Urutan
					) o
					LEFT JOIN
					(
						SELECT PCode,NamaLengkap FROM masterbarang
					)b
					ON b.PCode=o.PCode
					LEFT JOIN
					(
						SELECT KdAttribute,NamaAttribute,TipeAttr FROM attribute_barang
					)a
					ON a.KdAttribute=o.KdAttribute";

        return $this->getArrayResult($sql);
    }

    function getRekapTrans($wherebrg, $wherelokasi, $fieldawal, $fieldmasuk, $fieldkeluar, $fieldakhir, $tahun, $tabel) {
        $sql = "SELECT o.*,NamaLengkap FROM(
					SELECT KodeBarang,sum($fieldawal)as $fieldawal,
					sum($fieldmasuk) as $fieldmasuk,sum($fieldkeluar) as $fieldkeluar,sum($fieldakhir) as $fieldakhir 
					FROM $tabel s
					WHERE Tahun='$tahun' and KodeBarang<>''
					$wherebrg $wherelokasi
					group by KodeBarang
					ORDER BY KodeBarang
				) o
				LEFT JOIN
				(
					SELECT PCode,NamaLengkap FROM masterbarang
				)b
				ON b.PCode=o.KodeBarang";
        //echo $sql;
        return $this->getArrayResult($sql);
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