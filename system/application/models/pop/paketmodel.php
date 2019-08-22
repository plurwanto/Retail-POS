<?php
class Paketmodel extends Model
{
	function __construct(){
        parent::__construct();
    }

    function getbarangList($num, $offset,$id,$with)
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
                $sql = "SELECT PCode, NamaLengkap,HargaJual FROM masterbarang where PCode like '9%' $clause order by PCode Limit $offset,$num";
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
		$sql = "SELECT PCode FROM masterbarang where PCode like '9%' $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
}
?>