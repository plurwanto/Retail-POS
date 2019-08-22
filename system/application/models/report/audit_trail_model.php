<?php
class Audit_trail_model extends Model {

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

    function getBarang() {
        $sql = "select PCode,NamaLengkap from masterbarang order by PCode";
        return $this->getArrayResult($sql);
    }

    function getNamaBarang($pcode) {
        $sql = "select PCode,NamaLengkap from masterbarang where PCode='$pcode'";
        return $this->getRow($sql);
    }

    function getTahun() {
        $sql = "select distinct Tahun from stock order by Tahun";
        return $this->getArrayResult($sql);
    }

    function getPCode($pcode) {
        $sql = "select PCode from masterbarang where PCode='$pcode'";
        return $this->NumResult($sql);
    }

    function getDate() {
        $sql = "select TglTrans from aplikasi";
        return $this->getRow($sql);
    }

    function getDetail($wherebrg, $bulan, $tahun, $fieldawal) {
        $sql = "
                SELECT i.*,s.awal FROM
                (SELECT NoMutasi,NoTransaksi,NoKassa,DATE_FORMAT(Tanggal,'%d-%m-%Y') AS Tanggal,Jenis,KdTransaksi,Qty,Nilai,Kasir,KodeBarang
                FROM mutasi
                WHERE MONTH(Tanggal)='$bulan' AND YEAR(Tanggal)='$tahun'
                $wherebrg ORDER BY Tanggal,NoMutasi,NoKassa)i
                LEFT JOIN
                (SELECT $fieldawal,KodeBarang FROM stock WHERE Tahun='$tahun')s ON s.KodeBarang=i.KodeBarang ;
                    ";
        //echo $sql;
        return $this->getArrayResult($sql);
    }

    function getRekap($wherebrg, $wherelokasi, $bulan, $tahun) {
        $sql = "SELECT al.*,Nama FROM (
					SELECT KdTransaksi,r.KdLokasi,NoTransaksi,Tanggal,JenisMutasi,Qty,r.Keterangan,Counter,Jenis,
					IF(KdTransaksi='DP',trans_terima_header.KdContact,trans_periksa_header.KdContact) as KdContact FROM (
						SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,
						JenisMutasi,SUM(Qty) as Qty,Keterangan,Counter,Jenis,UrutTrans From
							(SELECT KdTransaksi,'---' as KdLokasi,NoTransaksi,DATE_FORMAT(TglTransaksi,'%d-%m-%Y') AS Tanggal,
							JenisMutasi,Qty,Keterangan,NoPenerimaan,0 AS Counter,'Terima' AS Jenis,
							IF(KdTransaksi='DP',1,IF(KdTransaksi='CK',2,3)) AS UrutTrans
							FROM mutasi_terima
							WHERE month(TglTransaksi)='$bulan' and year(TglTransaksi)='$tahun' $wherebrg $wherelokasi
							ORDER BY Tanggal,UrutTrans,NoTransaksi)a1
						GROUP BY Tanggal,UrutTrans,NoTransaksi,KdTransaksi)r
					LEFT JOIN trans_periksa_header on r.notransaksi=trans_periksa_header.nodokumen
					LEFT JOIN trans_terima_header on r.notransaksi=trans_terima_header.nodokumen
					UNION
					SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,JenisMutasi,Qty,k.Keterangan,Counter,Jenis,
					IF(KdTransaksi='ST',trans_simpan_header.KdContact,trans_periksa_header.KdContact) as KdContact FROM (
					    SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,JenisMutasi,
						SUM(Qty) as Qty,Keterangan,Counter,Jenis,UrutTrans From
							(SELECT DISTINCT KdTransaksi,'---' as KdLokasi,NoTransaksi,DATE_FORMAT(TglTransaksi,'%d-%m-%Y') AS Tanggal,
							JenisMutasi,Qty,Keterangan,NoPenerimaan,Counter,'Periksa' AS Jenis,
							IF(KdTransaksi='CK',1,IF(KdTransaksi='ST',2,3)) AS UrutTrans
							FROM mutasi_periksa
							WHERE month(TglTransaksi)='$bulan' and year(TglTransaksi)='$tahun' $wherebrg $wherelokasi
							ORDER BY Tanggal,UrutTrans,NoTransaksi,Counter)a2
						GROUP BY Tanggal,UrutTrans,NoTransaksi,KdTransaksi
						)k
					LEFT JOIN trans_periksa_header on k.notransaksi=trans_periksa_header.nodokumen
					LEFT JOIN trans_simpan_header on k.notransaksi=trans_simpan_header.nodokumen
					
					LEFT JOIN trans_ambil_header on q.nobukti=trans_ambil_header.nodokumen
					LEFT JOIN trans_lainlain_header on q.nobukti=trans_lainlain_header.nodokumen
				)al
				LEFT JOIN contact on al.KdContact=contact.KdContact
				WHERE Qty<>0";

        return $this->getArrayResult($sql);
    }

    function getDetailAttr($wherebrg, $wherelokasi, $bulan, $tahun) {
        $sql = "SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,JenisMutasi,Qty,Keterangan,Counter,Jenis,KdAttribute,NilaiAttribute,'' AS NamaAttribute,'' AS TipeAttr FROM (
				SELECT KdTransaksi,KdLokasi,NoTransaksi,DATE_FORMAT(TglTransaksi,'%d-%m-%Y') AS Tanggal,JenisMutasi,Qty,Keterangan,0 AS Counter,'Terima' AS Jenis,
				IF(KdTransaksi='DP',1,IF(KdTransaksi='CK',2,3)) AS UrutTrans
				,'' AS KdAttribute,'' AS NilaiAttribute FROM mutasi_terima
				WHERE MONTH(TglTransaksi)='$bulan' AND YEAR(TglTransaksi)='$tahun' $wherebrg $wherelokasi
				ORDER BY Tanggal,UrutTrans,NoTransaksi
				)r
				UNION
				SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,JenisMutasi,Qty,Keterangan,Counter,Jenis,k.KdAttribute,NilaiAttribute,NamaAttribute,TipeAttr FROM (
				SELECT KdTransaksi,KdLokasi,NoTransaksi,DATE_FORMAT(TglTransaksi,'%d-%m-%Y') AS Tanggal,JenisMutasi,Qty,Keterangan,Counter,'Periksa' AS Jenis,
				IF(KdTransaksi='CK',1,IF(KdTransaksi='ST',2,3)) AS UrutTrans
				,KdAttribute,NilaiAttribute FROM mutasi_periksa
				WHERE MONTH(TglTransaksi)='$bulan' AND YEAR(TglTransaksi)='$tahun' $wherebrg $wherelokasi
				ORDER BY Tanggal,UrutTrans,NoTransaksi,Counter,UrutAttr
				) k
				LEFT JOIN
				(
					SELECT KdAttribute,NamaAttribute,TipeAttr FROM attribute_barang
				)a
				ON a.KdAttribute=k.KdAttribute


				UNION
				SELECT KdTransaksi,KdLokasi,NoTransaksi,Tanggal,JenisMutasi,Qty,Keterangan,Counter,Jenis,p.KdAttribute,NilaiAttribute,NamaAttribute,TipeAttr FROM (
				SELECT KdTransaksi,KdLokasi,NoTransaksi,DATE_FORMAT(TglTransaksi,'%d-%m-%Y') AS Tanggal,JenisMutasi,Qty,Keterangan,Counter,'Simpan' AS Jenis,
				IF(KdTransaksi='ST',1,IF(KdTransaksi='CST',2,IF(KdTransaksi='TO',3,IF(KdTransaksi='CTO',4,IF(KdTransaksi='SE',5,6))))) AS UrutTrans
				,KdAttribute,NilaiAttribute FROM mutasi_simpan
				WHERE (kdtransaksi NOT IN ('KM','TM') or left(notransaksi,1)<>'3') AND MONTH(TglTransaksi)='$bulan' AND YEAR(TglTransaksi)='$tahun' $wherebrg $wherelokasi
				ORDER BY Tanggal,UrutTrans,NoTransaksi,Counter,UrutAttr
				)p
				LEFT JOIN
				(
					SELECT KdAttribute,NamaAttribute,TipeAttr FROM attribute_barang
				)a
				ON a.KdAttribute=p.KdAttribute
";
        return $this->getArrayResult($sql);
    }

    function getsaldoSimpan($wherebrg, $field, $tahun) {
        $sql = "select $field from stock where Tahun='$tahun' $wherebrg ";
        return $this->getRow($sql);
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

    function pil_lokasi($kode) {
        $sql = "select KdLokasi from lokasi where StatusLokasiKecil='Y' and KdLokasi like '$kode%' limit 0,20";
        return $this->getArrayResult($sql);
    }

    function pil_barang($kode) {
        $sql = "select CONCAT(PCode,'#',NamaLengkap) as PCode from masterbarang where (PCode like '$kode%' or NamaLengkap like '$kode%') limit 0,20";
        return $this->getArrayResult($sql);
    }

}

?>