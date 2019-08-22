<?php
class Mutasi_paketmodel extends Model {
	
    function __construct(){
        parent::__construct();
    }

    function getterimaList($num, $offset,$id,$with)
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
			SELECT id_mutasi,Tanggal,MPCode,Qty,Jenis_mutasi,Keterangan,b.`NamaLengkap` FROM
                        (SELECT id_mutasi,date_format(Tanggal,'%d-%m-%Y') as Tanggal,MPCode,Qty,if(Jenis_mutasi='B','Buat Paket','Pecah Paket') as Jenis_mutasi,
                        Keterangan FROM `mutasi_paket`)m
                        INNER JOIN masterbarang b ON b.`PCode`=m.MPCode
            ";
		return $this->getArrayResult($sql);
    }
    
    function num_terima_row($id,$with){
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
		$sql = "SELECT NoDokumen FROM trans_terima_header where FlagDelete='T' $clause";
        return $this->NumResult($sql);
	}

	function getDate(){
            $sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans,KdGU from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
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
		$sql = "SELECT SumberOrder,NoOrder,TglDokumen from trans_terima_header where NoDokumen='$no'";
		return $this->getRow($sql);
	}
   
    function getPCode($id){
		$sql = "SELECT NamaInitial,Konversi FROM masterbarang b
		Where PCode='$id'";
		return $this->getRow($sql);
	}
	function getAlmPerusahaan($id)
	{
		$sql = "SELECT Nama,Alamat1,Alamat2,Kota from perusahaan
				where KdPerusahaan='$id'";
		return $this->getRow($sql);
	}
	function getSatuan($pcode)
	{
		$sql = "SELECT KdSatuanBesar,(select keterangan from satuan where NamaSatuan=KdSatuanBesar) as NamaBesar,
KdSatuanTengah,(select keterangan from satuan where NamaSatuan=KdSatuanTengah) as NamaTengah,
KdSatuanKecil ,(select keterangan from satuan where NamaSatuan=KdSatuanKecil) as NamaKecil 
		from masterbarang where PCode='$pcode'";
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
	function getKirim($kirim,$perusahaan)
	{
		$sql = "
		select NoPolisi,brg.PCode,QtyInput,QtyDisplay,QtyPcs,brg.KonversiJualKecil,brg.KonversiBesarKecil,brg.KonversiTengahKecil,
		brg.KdSatuanJual,KdSatuanBesar,KdSatuanKecil,KdSatuanTengah,NamaInitial,
		if(Satuan=b.KdSatuanBesar,'B',if(Satuan=b.KdSatuanTengah,'T','K'))as jenis,
		Satuan,KdContact ,if(brg.PCode=b.PCode,'pcode','bar') as jenis_satuan,brg.PCode as PCode,
		keterangan as NamaSatuan,KdContact from(
			select d.PCode,QtyKonfirm as QtyInput,QtyDisplayKonfirm as QtyDisplay,QtyPcsKonfirm as QtyPcs,d.KonversiJualKecil,d.KonversiBesarKecil,d.KonversiTengahKecil,
			d.KdSatuanJual,d.Satuan,KdContact ,h.KdKendaraan
			from trans_kirim_detail d,trans_kirim_header h,trans_order_taking_header o
			where h.NoDokumen='$kirim' and o.KdPerusahaan='$perusahaan' and h.NoDokumen=d.NoDokumen and d.NoOrder = o.NoDokumen
			and d.FlagBalik='Y' and d.FlagTerima='T' and h.FlagDelete='T' and d.FlagDelete='T' and month(h.TglDokumen)=(select month(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1) and year(h.TglDokumen)=(select year(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1)
			order by PCode desc
		)brg
		left join
		(
			select PCode,NamaLengkap as NamaInitial,KdSatuanBesar,KdSatuanKecil,KdSatuanTengah
			from masterbarang
		)b
		on b.PCode = brg.PCode
		left join
		(
		select NamaSatuan,Keterangan from satuan
		)s
		on s.NamaSatuan=brg.KdSatuanJual
		left join kendaraan
		on kendaraan.KdKendaraan=brg.KdKendaraan";
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
		$sql = "SELECT NoMutasiPaket FROM setup_no where Tahun='$tahun'";
		return $this->getRow($sql);
	}
	function cekPast($no,$pcode)
	{
		$sql = "select distinct QtyTerima,QtyHargaTerima
		from trans_terima_detail
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
	function Getdetail($id)
	{
		$sql = "
                    select * from barang_paket Where MPCode='$id'
		";
        return $this->getArrayResult($sql);
	}
	function getDetailDel($id)
	{
		$sql = "
		select d.PCode,QtyTerima,QtyHargaTerima,NamaInitial from(
		SELECT * from trans_terima_detail Where NoDokumen='$id' order by PCode
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
                        SELECT b.MPCode,DPcode,(m.Qty * b.Qty) AS jumlah,NamaLengkap  FROM (
                        SELECT MPCode,Qty FROM mutasi_paket WHERE id_mutasi='14000029')m
                        INNER JOIN barang_paket b ON b.`MPCode`=m.MPCode
                        INNER JOIN masterbarang mb ON b.`DPcode`=mb.`PCode`
                        ORDER BY b.`DPcode`
		";
        return $this->getArrayResult($sql);
	}
	function getHeader($id)
	{
		$sql = "
                        SELECT id_mutasi,Tanggal,MPCode,Qty,Jenis_mutasi,Keterangan,b.`NamaLengkap` FROM
                        (SELECT id_mutasi,date_format(Tanggal,'%d-%m-%Y') as Tanggal,MPCode,Qty,if(Jenis_mutasi='B','Buat Paket','Pecah Paket') as Jenis_mutasi,
                        Keterangan FROM `mutasi_paket` where id_mutasi='$id')m
                        INNER JOIN masterbarang b ON b.`PCode`=m.MPCode
                        ";
//            print $sql;
        return $this->getRow($sql);
	}
	function cekDetail($pcode,$id)
	{
		$sql = "select FlagDelete from trans_terima_detail where NoDokumen='$id' and PCode='$pcode'";
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