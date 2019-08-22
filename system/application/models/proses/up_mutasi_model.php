<?php
class Up_mutasi_model extends Model {
	
    function __construct(){
        parent::__construct();
    }
    
    function getReports( $sDateStart, $sDateEnd ) {

        $csv_terminated = "\n";
        $csv_separator  = "|";
        $csv_enclosed   = '"';
        $csv_escaped    = "\"";
        $schema_insert  = "";
        $out            = '';

        $aResults = $this->db
                ->select('*')
                ->from( 'transaksi_header' )
                ->where( 'Tanggal >=', $sDateStart )
                ->where( 'Tanggal <=', $sDateEnd )
                ->get();
        

        $aFields = $aResults->list_fields();
        $out .= 'Transaksi_header'.$csv_separator.$csv_terminated;
 /* header  field */
        /*
         foreach ( $aFields as $sField ) {

                $l = $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes( $sField ) ) . $csv_enclosed;
                $schema_insert .= $l;
                $schema_insert .= $csv_separator;
        }
       
        $out .= $schema_insert . $csv_terminated; */
         /* end header */

        // Format the data
        foreach ( $aResults->result_array() as $aResult ) {
            $schema_insert = '';
            foreach ( $aFields as $sField ) 
            {
                 $schema_insert .= $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, $aResult[$sField] ) . $csv_enclosed . $csv_separator;
            }
                $out .= $schema_insert;
                $out .= $csv_terminated;
        }
        return $out;
        }
        
    function getReportsDetail($tabel,$kondisi,$sDateStart,$sDateEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul)
    {
//        $sql = $this->db->query("select * from $tabel where $kondisi between $sDateStart and $sDateEnd") ;
        $wh = "$kondisi between '$sDateStart' and '$sDateEnd'";
        $aResults = $this->db
                ->select('*')
                ->from( $tabel )
                ->where($wh)
                ->get();

        $aFields = $aResults->list_fields();
        $out .= $tabel.$batasjudul.$csv_terminated;
        
 
        // Format the data
        foreach ( $aResults->result_array() as $aResult ) {
            $schema_insert = '';
            foreach ( $aFields as $sField ) 
            {
                 $schema_insert .= $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, $aResult[$sField] ) . $csv_enclosed . $csv_separator;
            }
                $out .= $schema_insert;
                $out .= $csv_terminated;
        }
        return $out;
    }
    
    function getDetailTrx($tabelD,$tabelH,$kondisi,$tgl,$sDateStart,$sDateEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul)
    {
        $out .= $tabelD.$batasjudul.$csv_terminated;
        $dt = "select $tabelD.* FROM $tabelD
               INNER JOIN $tabelH ON 
               $tabelH.$kondisi=$tabelD.$kondisi 
               AND $tabelH.TglDokumen BETWEEN '$sDateStart' AND '$sDateEnd'";
        $nil = $this->getRow($dt);
        
//        fro
//        print_r($nil);
        $aList= $this->db
                ->select('*')
                ->from( $tabelD )
                ->get();
        
        $aFields = $aList->list_fields();
 /* header  field */
        $lstD = '';
        $by = '';
        if(!empty ($nil)){
            
        for ($y=0; $y<count($aFields);)
        {
            $lstD .= $aFields[$y].",";
//                $l = $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes( $sField ) ) . $csv_enclosed;
//                $schema_insert .= $l;
//                $schema_insert .= $csv_separator;
            $by .= $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, $nil->$aFields[$y] ) . $csv_enclosed . $csv_separator;
//            $by .=$nil->$aFields[$y];
           $y++;
        }
        }else{
            $by = "data tidak ditemukan";
        }
//        echo $lstD; 
        $out .= $by;
        
        $out .= $csv_terminated;
                  
//        $aFields = $aResults->list_fields();
        
 
        return $out;
    }

	function FindCabang()
	{
                $sql = "SELECT KdCabang FROM aplikasi";
                return $this->getRow($sql);
	}
	
	function getStokSimpan($tahun,$field)
	{
		$sql = "select KodeBarang,Gudang,$field from stock where Tahun='$tahun'";
		return $this->getArrayResult($sql);
	}
	function getAllAplikasi($tahun)
	{
		$sql = "select * from aplikasi where Tahun='$tahun'";//echo $sql;die();
		return $this->getRow($sql);
	}
	function getSetupNo($tahun)
	{
		$sql = "select * from setup_no where Tahun='$tahun'";
		return $this->getArrayResult($sql);
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
	function getLastDate()
	{
		$sql = "select TglTrans,Tahun from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
            return $this->getRow($sql);
	}
}
?>