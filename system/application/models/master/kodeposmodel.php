<?php
class kodeposmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getkodeposList($num, $offset,$id,$with)
	{
	 	if($offset !=''){
			$offset = $offset;
		}            
        else{
        	$offset = 0;
        }
		$clause="";
		if($id!=""){
			$clause = " where $with like '%$id%'";
		}
    	$sql = "select KodePos,sub.Keterangan as NamaKodePos,dive.Keterangan as NamaArea,subs.Keterangan as NamaSubArea from(
					SELECT KodePos, Keterangan,KdArea,KdSubArea
					FROM kodepos $clause order by KodePos  Limit $offset,$num
				) as sub
				left join
				(
					select KdArea, Keterangan from area
				) as dive
				on dive.KdArea = sub.KdArea
				left join
				(
					select KdSubArea, Keterangan from subarea
				) as subs
				on subs.KdSubArea = sub.KdSubArea";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_kodepos_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KodePos FROM kodepos $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT * from kodepos Where KodePos='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KodePos FROM kodepos Where KodePos='$id'";
		$qry = $this->db->query($sql);
		$num = $qry->num_rows();
		$qry->free_result();
		return $num;
	}
	function get_area()
	{
		$sql = "select KdArea,Keterangan from area order by KdArea";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function get_subs_area($area)
	{
		$sql = "select KdSubArea,Keterangan from subarea where KdArea='$area' order by KdSubArea";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function area($id)
	{
		$sql = "SELECT KdArea FROM kodepos Where KodePos='$id'";
		$qry = $this->db->query($sql);
		$num = $qry->row();
		$qry->free_result();
		return $num;
	}
}
?>