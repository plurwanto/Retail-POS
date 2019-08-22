<?php
class report_model extends Model
{
	function __construct()
	{
        parent::Model();
    }
	
	function detail($NoStruk,$Tgltrans,$kondisi,$cs)
	{
            $sql = "
                SELECT DISTINCT z.*, b.NamaLengkap,c.TotalNilai,c.Waktu,
                c.KdCustomer,c.NamaCustomer,
                DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(c.TglLahir, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(c.TglLahir, '00-%m-%d')) AS umur  FROM(
                SELECT NoStruk,Tanggal,KdStore,NoKassa,Kasir,PCode,Qty,Harga,Netto,Disc1 
                FROM transaksi_detail a
                WHERE  Status = '1'
                $NoStruk $Tgltrans) z
                INNER JOIN masterbarang b ON b.PCode=z.PCode
                INNER JOIN transaksi_header c ON z.NoStruk=c.NoStruk $cs
                INNER JOIN transaksi_detail_bayar d ON c.NoStruk=d.NoStruk $kondisi
                ORDER BY NoStruk DESC";
		$qry = $this->db->query($sql);//echo $sql;//die();
        $row = $qry->result_array();
        
        return $row;
	}
	
	function transaksi($NoStruk,$Tgltrans,$kondisi,$cs)
	{
		$sql = "select a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,a.TotalItem,a.TotalNilai,a.KdCustomer,d.Keterangan,d.Jenis,a.Waktu,a.Discount
                        from transaksi_header a, transaksi_detail_bayar d, customer c
                        where a.Status = '1' and a.NoStruk=d.NoStruk $NoStruk $Tgltrans $kondisi $cs group by a.NoStruk";
		$qry = $this->db->query($sql);
                $row = $qry->result_array();
            return $row;
	}
	
	function barang($NoStruk,$Tgltrans,$kondisi,$cs)
	{
		$sql = "
                        SELECT a.PCode,a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,Qty,a.Harga,Netto,mb.NamaLengkap,KdCustomer,NamaCustomer
                        FROM (
                        SELECT PCode,d.NoStruk,d.Tanggal,d.KdStore,d.NoKassa,d.Kasir,SUM(Qty) AS Qty,Harga,SUM(Netto)
                        AS Netto,KdCustomer,NamaCustomer FROM transaksi_detail d,transaksi_header h
                        WHERE d.STATUS='1' AND d.NoStruk=h.NoStruk $NoStruk $Tgltrans $kondisi $cs
                        GROUP BY PCode
                        )a
                        INNER JOIN masterbarang mb ON a.PCode=mb.PCode";
//                        INNER JOIN transaksi_detail_bayar db ON a.NoStruk=db.NoStruk
//                    ";
		$qry = $this->db->query($sql);//echo $sql;die();
        $row = $qry->result_array();
        
        return $row;
	}
         function getCustomer(){
                $sql = "SELECT KdCustomer,Nama FROM customer ORDER BY KdCustomer";
            return $this->getArrayResult($sql);
        }

        function getArrayResult($sql)
	{
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function bayar($NoStruk,$Tgltrans,$kondisi,$cs)
	{
		$sql = "SELECT d.NoStruk,a.Tanggal,d.Jenis,d.NomorKKredit,d.NomorKDebet,d.NomorVoucher,d.Keterangan,d.NilaiTunai,d.NilaiKredit,d.NilaiDebet,d.NilaiVoucher,a.TotalBayar,a.TotalNilai,a.Kembali FROM transaksi_detail_bayar d, transaksi_header a
				where a.Status = '1' and a.NoStruk=d.NoStruk $NoStruk $Tgltrans $kondisi $cs order by d.NoStruk";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
	}
		
}
?>