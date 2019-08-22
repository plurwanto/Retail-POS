<?php
class report_omzet extends Model
{
	function __construct()
	{
        parent::Model();
    }
	
	function detail($Tgltrans,$kondisi)
	{
            if($kondisi!="00"){
                $brand = "and KdBrand='$kondisi'";// buat semua brand
            }else{
                $brand = "";
            }
            $sql = "
                    SELECT  s.KdBrand,NamaBrand,QBarang,QNetto FROM
                    (SELECT KdBrand,SUM(t.Qty)AS QBarang ,SUM(t.Netto) AS QNetto
                    FROM Masterbarang m, transaksi_detail t
                    WHERE t.PCode=m.PCode $Tgltrans $brand GROUP BY m.KdBrand )s
                    INNER JOIN
                    (SELECT KdBrand,NamaBrand FROM brand)b ON s.KdBrand=b.KdBrand;
               ";
		$qry = $this->db->query($sql);//echo $sql;//die();
        $row = $qry->result_array();
        
        return $row;
	}
	
	function transaksi($NoStruk,$Tgltrans,$kondisi)
	{
		$sql = "select a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,a.TotalItem,a.TotalNilai,a.KdCustomer,d.Keterangan,d.Jenis from transaksi_header a, transaksi_detail_bayar d, customer c
				where a.Status = '1' and a.NoStruk=d.NoStruk $NoStruk $Tgltrans $kondisi group by NoStruk";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
	}
	
	function barang($NoStruk,$Tgltrans,$kondisi)
	{
		$sql = "SELECT a.PCode,a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,SUM(a.Qty) as Qty,a.Harga,SUM(a.Netto) as Netto,b.NamaLengkap FROM transaksi_detail a, masterbarang b, transaksi_detail_bayar d
				where a.Status = '1' and a.PCode=b.PCode and a.NoStruk=d.NoStruk $NoStruk $Tgltrans $kondisi group by a.PCode";
		$qry = $this->db->query($sql);//echo $sql;//die();
                $row = $qry->result_array();
            return $row;
	}
	
	function bayar($NoStruk,$Tgltrans,$kondisi)
	{
		$sql = "SELECT d.NoStruk,a.Tanggal,d.Jenis,d.NomorKKredit,d.NomorKDebet,d.NomorVoucher,d.Keterangan,d.NilaiTunai,d.NilaiKredit,d.NilaiDebet,d.NilaiVoucher,a.TotalBayar,a.TotalNilai,a.Kembali FROM transaksi_detail_bayar d, transaksi_header a
				where a.Status = '1' and a.NoStruk=d.NoStruk $NoStruk $Tgltrans $kondisi order by d.NoStruk";
		$qry = $this->db->query($sql);
                $row = $qry->result_array();
            return $row;
	}

        function getBrand(){
                $sql = "SELECT KdBrand,NamaBrand AS Nama FROM brand ORDER BY KdBrand";
            return $this->getArrayResult($sql);
        }

        function getArrayResult($sql)
	{
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function NumResult($sql)
	{
		$qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
}
?>