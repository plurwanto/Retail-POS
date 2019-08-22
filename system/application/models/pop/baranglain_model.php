<?php
class Baranglain_model extends Model
{
	function __construct(){
        parent::__construct();
    }

    function getbarangList($num, $offset,$id,$with)
	{
	 	if($offset !=''){
			$offset = $offset;
		}else{
        	$offset = 0;
                }
//                echo $offset;
		$clause="";
		if($id!=""){
			$clause = " where $with like '%$id%'";
		}
    	$sql = "SELECT PCode, NamaLengkap,HargaBeliAkhir FROM masterbarang $clause order by PCode Limit $offset,$num";
//echo $sql;
	$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_barang_row($id,$with){
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