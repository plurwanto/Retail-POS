<?php
class Ordermodel extends Model
{
	function __construct(){
        parent::__construct();
    }

    function getorderList($num, $offset,$id,$with)
	{
	 	if($offset !=''){
			$offset = $offset;
		}
        else{
        	$offset = 0;
        }
		$clause="";
		if($id!=""){
			$clause = " and $with like '%$id%'";
		}
    	$sql = "
		SELECT h.*,COUNT(d.PCode) as Jmlh, s.keterangan AS nmsuplier
		from trans_order_detail d,trans_order_header h,supplier s
		where h.NoDokumen=d.NoDokumen
		and d.FlagDelete='T' and h.FlagKonfirmasi=0 and h.FlagDelete='T' AND s.KdSupplier=h.KdSupplier
                and month(TglDokumen)=(select month(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1) and year(TglDokumen)=(select year(TglTrans) from aplikasi ORDER BY Tahun DESC LIMIT 0,1)
		$clause 
		group by h.NoDokumen
		order by cast(h.NoDokumen as unsigned) desc Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
	
	function getDetail($id)
	{
		$sql = "
		select d.*,NamaLengkap as NamaInitial
                from trans_order_detail d,trans_order_header h,masterbarang b
                where h.NoDokumen='$id' and h.NoDokumen=d.NoDokumen and d.FlagDelete='T' and h.FlagDelete='T' AND ((QtyOrder - QtyKonfTerima)> 0) 
                and d.PCode = b.PCode ORDER BY d.PCode ASC
		";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
    
    function num_order_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " and $with like '%$id%'";
		}
		$sql = "SELECT h.NoDokumen
		from trans_order_header h
		where h.FlagKonfirmasi='0' and h.FlagDelete='T'$clause ";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
}
?>