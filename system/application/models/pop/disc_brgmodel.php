<?php
class disc_brgmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getdisc_brgList($num, $offset,$id,$with)
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
    	$sql = "SELECT PCode, NamaStruk FROM masterbarang $clause order by PCode Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_disc_brg_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT PCode FROM masterbarang $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
}
?>