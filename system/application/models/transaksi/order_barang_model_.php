<?php
class order_barang_model extends Model
{
	function __construct()
	{
        parent::Model();
    }
	
	function detail()
	{
		$sql = "select a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,a.PCode,a.Qty,a.Harga,a.Netto,b.NamaStruk,c.TotalNilai from transaksi_detail a, masterbarang b, transaksi_header c 
				where a.Status = '0' and a.PCode=b.PCode and a.NoStruk=c.NoStruk order by a.NoStruk asc";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
	}
	
	function transaksi()
	{
		$sql = "select NoStruk,Tanggal,KdStore,NoKassa,Kasir,TotalItem,TotalNilai from transaksi_header
				where Status = '0' order by NoStruk asc";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
	}
	
	function barang()
	{
		$sql = "SELECT a.PCode,a.NoStruk,a.Tanggal,a.KdStore,a.NoKassa,a.Kasir,SUM(a.Qty) as Qty,a.Harga,SUM(a.Netto) as Netto,b.NamaStruk FROM transaksi_detail a, masterbarang b
				where a.Status = '0' and a.PCode=b.PCode group by PCode";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
	}
		
}
?>