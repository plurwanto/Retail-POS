<?php
class Kirim_data_model extends Model {
	
    function __construct(){
        parent::__construct();
    }
    
    function getReports( $sDateStart, $sDateEnd ) {

        $csv_terminated = "\n";
        $csv_separator  = "#";
        $csv_enclosed   = '';
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
//        $out .= 'Transaksi_header'.$csv_separator.$csv_terminated;
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
                $out .= 'Transaksi_header,'.$schema_insert;
                $out .= $csv_terminated;
        }
        return $out;
        }
        
    function getReportsDetail($tabel,$kondisi,$sDateStart,$sDateEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul,$kg)
    {
//        $sql = $this->db->query("select * from $tabel where $kondisi between $sDateStart and $sDateEnd") ;
        $dt = "select * FROM $tabel
               where $tabel.$kondisi BETWEEN '$sDateStart' AND '$sDateEnd'";
        $nil = $this->getRow($dt);
//        print_r($nil);die();

        $wh = "$kondisi between '$sDateStart' and '$sDateEnd'";
        $aResults = $this->db
                ->select('*')
                ->from( $tabel )
                ->where($wh)
                ->get();

        $aFields = $aResults->list_fields();
        
        
        if(!empty($nil)){
        // Format the data
            foreach ( $aResults->result_array() as $aResult ) {
                $schema_insert = '';
                $out .= $kg.$batasjudul.$tabel.$batasjudul;
                foreach ( $aFields as $sField )
                {
                     $schema_insert .= $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, $aResult[$sField] ) . $csv_enclosed . $csv_separator;                
                }
                    $out .= $schema_insert;
                    $out .= $csv_terminated;
            }
        }else{
            $out .= $kg.$batasjudul.$tabel.$batasjudul;
            $schema_insert = "data tidak ditemukan";
            $out .= $schema_insert;
            $out .= $csv_terminated;
        }
        return $out;
    }
    
    function getDetailTrx($tabelD,$tabelH,$kondisi,$tgl,$sDateStart,$sDateEnd,$csv_terminated,$csv_separator,$csv_enclosed,$csv_escaped,$schema_insert,$out,$batasjudul,$kg)
    {
        
        $dt = "select $tabelD.* FROM $tabelD
               INNER JOIN $tabelH ON 
               $tabelH.$kondisi=$tabelD.$kondisi 
               AND $tabelH.TglDokumen BETWEEN '$sDateStart' AND '$sDateEnd'";
        $nil = $this->getArrayResult($dt);
        
//        print_r($nil);
        $aList= $this->db
                ->select('*')
                ->from( $tabelD )
                ->get();
        
        $aFields = $aList->list_fields();
 /* header  field */
        $lstD = '';
        $by = '';
//        echo count($aFields);
//        die();
        if(!empty ($nil)){
            for($x=0;$x<count($nil);$x++){
                $out .= $kg.$batasjudul.$tabelD.$batasjudul;
                $by ="";
                for ($y=0; $y<count($aFields);)
                {
                    $bts = $y;
                    $lstD .= $aFields[$y].",";
    //                $l = $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes( $sField ) ) . $csv_enclosed;
    //                $schema_insert .= $l;
                    $by .= $csv_enclosed . str_replace( $csv_enclosed, $csv_escaped . $csv_enclosed, $nil[$x][$aFields[$y]] ) . $csv_enclosed . $csv_separator;
    //            $by .=$nil->$aFields[$y];

                   $y++;
                }
                
                $out .= $by;
                $out .= $csv_terminated;
//                     $out .= $csv_terminated;
            }
        }else{
            $by = "data tidak ditemukan";
        }
//        echo $lstD; 
//        $out .= $by;
//        $out .= $csv_terminated;
                  
//        $aFields = $aResults->list_fields();
        
 
        return $out;
    }

	function FindCabang()
	{
                $sql = "SELECT a.KdCabang,a.KdGU,c.`Keterangan` FROM aplikasi a,counter c  
                        WHERE a.KdGU=c.KdCounter";
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