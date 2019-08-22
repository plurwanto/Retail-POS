<?php
class Rterima_model extends Model {

    function __construct() {
        parent::Model();
    }

    function detail($Tgltrans) {
        $sql = "SELECT date_format(h.TglDokumen,'%d-%m-%Y') as Tanggal,date_format(h.TglTerima,'%d-%m-%Y') as TglTerima,
                    h.NoDokumen,h.`Keterangan`,NoPO,
                    d.PCode,QtyTerima,QtyHargaTerima,NamaLengkap FROM(
                    SELECT * FROM trans_terima_detail WHERE FlagDelete='T' ORDER BY PCode
                    ) d
                    LEFT JOIN
                    (SELECT * FROM(
                                    SELECT PCode, NamaLengkap FROM masterbarang 
                                    UNION 
                                    SELECT PCode, NamaLengkap FROM masterbarang_old
                                    )AS tbl1
                    GROUP BY PCode		
                    ) masterb
                    ON masterb.PCode = d.PCode
                    INNER JOIN trans_terima_header h ON h.`NoDokumen`=d.NoDokumen AND
                    $Tgltrans
                    ORDER BY NoDokumen,PCode               
               ";
        //echo $sql;
        $query = $this->db->query($sql);
        if ($query->num_rows > 0)
            return $query->result_array();
    }

    function transaksi($Tgltrans) {
        $sql = "select NoDokumen,if(SumberOrder='O','Order',if(SumberOrder='M','Manual','Sisa Pengiriman')) as SumberOrder
              	,date_format(TglDokumen,'%d-%m-%Y') as Tanggal,date_format(TglTerima,'%d-%m-%Y') as TglTerima
					,NoOrder,NoPO,kontak.Nama,terima.Keterangan
			from
			(
			select * from trans_terima_header where FlagDelete= 'T' AND $Tgltrans order by NoDokumen desc
			) as terima
			left join
			(
			select KdSupplier,NamaSupplier as Nama from supplier
			) kontak
			on kontak.KdSupplier = terima.KdSupplier";
        $query = $this->db->query($sql);
        if ($query->num_rows > 0)
            return $query->result_array();
    }

    function getBrand() {
        $sql = "SELECT KdBrand,NamaBrand AS Nama FROM brand ORDER BY KdBrand";
        return $this->getArrayResult($sql);
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

}

?>