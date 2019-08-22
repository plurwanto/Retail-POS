<?php
class grup_barangmodel extends Model
{
	function __construct(){
        parent::Model();
    }

    function getgrup_barangList($num, $offset,$id,$with)
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
    	$sql = "SELECT PCode, NamaStruk,NamaLengkap,HargaJual FROM masterbarang where FlagAktif='A' $clause 
                order by PCode Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_grup_barang_row($id,$with){
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