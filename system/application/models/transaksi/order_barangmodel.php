<?php
class Order_barangmodel extends Model {
	
    function __construct(){
        parent::__construct();
    }

    function getOrderList($num, $offset,$id,$with)
	{
	 	if($offset !=''){
			$offset = $offset;
		}            
        else{
        	$offset = 0;
        }
		$clause="";
		if($id!=""){
			if($with=="NoDokumen"){
				$clause = "and $with like '%$id%'";
			}
			else
			{
				$clause = "and $with = '$id'";
			}
		}
    	$sql = "
			select NoDokumen,date_format(TglDokumen,'%d-%m-%Y') as Tanggal,kontak.Nama,o.Keterangan,Bruto,CountQty,FlagKonfirmasi
			from
			(
			select * from trans_order_header where FlagDelete= 'T' $clause order by NoDokumen desc Limit $offset,$num
			) as o
			left join
			(
			select KdSupplier,NamaSupplier as Nama from supplier
			) kontak
			on kontak.KdSupplier = o.KdSupplier";
		return $this->getArrayResult($sql);
    }
    
    function num_Order_row($id,$with){
     	$clause="";
     	if($id!=''){
			if($with=="NoDokumen"){
				$clause = "and $with like '%$id%'";
			}
			else
			{
				$clause = "and $with = '$id'";
			}
		}
		$sql = "SELECT NoDokumen FROM trans_order_header where FlagDelete='T' $clause";
        return $this->NumResult($sql);
	}

	function getDate(){
    	$sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
		return $this->getRow($sql);
    }
	function getPerusahaan()
	{
		$sql = "select KdPerusahaan,Nama from perusahaan";
		return $this->getArrayResult($sql);
	}
        function ifPCodeBarcode($id){
		$bar = substr($id,0,10);
		$sql = "SELECT PCode FROM masterbarang Where PCode='$id'";
		return $this->getRow($sql);
	}

       	function getKontak(){
                $sql = "SELECT KdSupplier,NamaSupplier as Nama from supplier order by KdSupplier";
		return $this->getArrayResult($sql);
        }
	
	function getSumber($no)
	{
		$sql = "SELECT SumberOrder,NoOrder,TglDokumen from trans_order_header where NoDokumen='$no'";
		return $this->getRow($sql);
	}
   
    function getPCode($id){
		$sql = "SELECT NamaInitial,Konversi FROM masterbarang b
		Where PCode='$id'";
		return $this->getRow($sql);
	}
	function getAlmPerusahaan($id)
	{
		$sql = "SELECT Keterangan AS Nama,Alamat1,Alamat2,Kota FROM supplier
				WHERE KdSupplier='$id'";
		return $this->getRow($sql);
	}
	
	function getOrder($order,$perusahaan)
	{
		$sql = "
		select brg.*,keterangan as NamaSatuan,KdContact from(
			select d.*,NamaLengkap as NamaInitial,KdContact 
			from trans_order_barang_detail d,masterbarang b,trans_order_barang_header h
			where h.NoDokumen='$order' and KdPerusahaan='$perusahaan' and d.FlagDelete='T' and h.FlagDelete='T' and h.NoDokumen=d.NoDokumen
			and d.FlagPenerimaan='T' and month(TglDokumen)=(select month(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1) and year(TglDokumen)=(select year(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1)
			and b.PCode = d.PCode
			order by d.PCode desc
		)brg
		left join
		(
		select NamaSatuan,Keterangan from satuan
		)s
		on s.NamaSatuan=brg.KdSatuanJual";
		return $this->getArrayResult($sql);
	}
	
	function cekShare($kode,$kontak)
	{
		$sql = "select h.KdKetentuan
				from ketentuan_simpan_detail d,ketentuan_simpan h
				where PCode='$kode' and h.KdKetentuan=d.KdKetentuan and Pilihan='K' and h.KdContact<>'$kontak'
				and SharePCode='N';";
        return $this->NumResult($sql);
	}
	function getPCodeDet($kode)
	{
		$sql = "
                        SELECT PCode,NamaLengkap as NamaInitial,HargaBeliAkhir
                        FROM masterbarang Where PCode='$kode'
                        ";
            return $this->getRow($sql);
	}
	function getNewNo($tahun)
	{
		$sql = "SELECT NoOrder FROM setup_no where Tahun='$tahun'";
		return $this->getRow($sql);
	}
	function cekPast($no,$pcode)
	{
		$sql = "select distinct QtyOrder,HargaOrder
		from trans_order_detail
		where NoDokumen='$no' and PCode='$pcode'";
        return $this->getRow($sql);
	}

        function CekStock($field,$fieldakhir,$pcode,$tahun)
	{
		$sql = "select $field,$fieldakhir from stock
		where Tahun='$tahun'
		and KodeBarang='$pcode'";
        return $this->getRow($sql);
	}
	function getDetail($id)
	{
		$sql = "
		select d.PCode,QtyOrder,HargaOrder,NamaInitial from(
		SELECT * from trans_order_detail Where NoDokumen='$id' and FlagDelete='T' order by PCode
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
	}
	function getDetailDel($id)
	{
		$sql = "
		select d.PCode,QtyOrder,HargaOrder,NamaInitial from(
		SELECT * from trans_order_detail Where NoDokumen='$id' order by PCode
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
	}
	function getDetailForPrint($id)
	{
		$sql = "
		select d.PCode,QtyOrder,HargaOrder,NamaInitial from(
		SELECT * from trans_order_detail Where NoDokumen='$id' and FlagDelete='T' order by PCode
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
	function getHeader($id)
	{
		$sql = "SELECT NoDokumen,date_format(TglDokumen,'%d-%m-%Y') as TglDokumen,
                h.Keterangan,h.keterangan,c.NamaSupplier as supplier,h.KdSupplier,c.ContactPrs,c.Telepon,c.NoFax
		from trans_order_header h,supplier c
		Where NoDokumen='$id' and FlagDelete='T' and c.KdSupplier = h.KdSupplier";
//            print $sql;
        return $this->getRow($sql);
	}
	function cekDetail($pcode,$id)
	{
		$sql = "select FlagDelete from trans_order_detail where NoDokumen='$id' and PCode='$pcode'";
		return $this->getRow($sql);
	}
	function locktables($table)
	{
		$this->db->simple_query("LOCK TABLES $table");
	}
	function unlocktables()
	{
		$this->db->simple_query("UNLOCK TABLES");
	}
	function getRow($sql)
	{
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
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