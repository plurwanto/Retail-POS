<?php
class downloadd_model extends Model {
	
    function __construct(){
        parent::__construct();
    }
    //model
function upload_csv_id() // untuk download manual
 {
     //ini alamat untuk tempat copy file CSV
            $pathDta	= set_realpath(APPPATH."downloadmail");
     $target_path ="D:/BackupOmah";
     $target_path = $target_path.basename( $_FILES['filename']['name']);
     if(move_uploaded_file($_FILES['filename']['tmp_name'], $target_path)) {
     } else{
     redirect("admin/setting_csv");
     }
     // ini script untuk mengimport data CSV ke MySQL
     $filename=$target_path;
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
     {
//         echo "<pre>";print_r($data[0]);echo "</pre>";
        switch ($data[1]){
             case "Transaksi_header" :
                 $det = $data[2];
                 $gd  = $data[0];
                 $this->do_simpan_header($det,$gd);
                 break;
         }
//         echo "<pre>".$a;print_r($data[1]);echo "</pre>";
//         $da = split (";", $data[0]);
//         echo "<pre>";print_r($da);echo "</pre>";
//      $import="INSERT INTO id_anggota (nik,nama_anggota,tanggal_lahir,jenis_kelamin,no_hp)
//      VALUES('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
//      mysql_query($import) or die(mysql_error());
     }
     fclose($handle);
     /*$msg="<script>alert('File :". basename( $_FILES['filename']['name'])." berhasil di upload')</script>";
     $this->session->set_flashdata("wow",$msg);*/
     return true; 
}

function download_auto($pathbaca,$nmattc) // untuk download manual
 {
     //ini alamat untuk tempat copy file CSV
     $target_path = $pathbaca;
     
     // ini script untuk mengimport data CSV ke MySQL
     $filename=$target_path.$nmattc;
//     print_r($filename);
     $handle = fopen("$filename", "r");
     $word = explode(";",fgets($handle, 4096));

//      $stockquotes = file_get_contents($filename);
// echo nl2br($stockquotes);die();
//     $data = explode("\n", $stockquotes);
print_r($word);die();
  
//     echo count($baris);die();
//     while (($data = fgetcsv($handle, 1000, ";")) !== FALSE)
      for ($i=0;$i<count($data)-1;$i++)
     {
//          $baris = split(";",$data[$i]);
		  $baris = explode(";",$data[$i]);
  echo "<pre>";print_r($baris);echo "</pre>";die();
         echo "<pre>";print_r($data[1]);echo "</pre>";die();
        switch ($baris[1]){
             case "transaksi_header" :
                $det = $baris[2];
                $gd  = $baris[0];
                 $this->do_simpan_header($det,$gd);
                 break;
             case "transaksi_detail" :
                $det = $baris[2];
                $gd  = $baris[0];
                 //$this->do_simpan_detail($det,$gd);
                 break;
         }
//         echo "<pre>".$a;print_r($data[1]);echo "</pre>";
//         $da = split (";", $data[0]);
//         echo "<pre>";print_r($da);echo "</pre>";
//      $import="INSERT INTO id_anggota (nik,nama_anggota,tanggal_lahir,jenis_kelamin,no_hp)
//      VALUES('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
//      mysql_query($import) or die(mysql_error());
     }//die();
     fclose($handle);
     $msg="<script>alert('File :". basename( $_FILES['filename']['name'])." berhasil di upload')</script>";
     $this->session->set_flashdata("wow",$msg);
     return true; 
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
    function do_simpan_header($det,$gd)
    {
         $da = split("#",$det);
         echo "<pre>";print_r($da);echo "</pre>";die();
         //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
         $cek = $this->getRow("select * from transaksi_header where NoStruk = '$da[2]' and Gudang='$gd'") ;
         if(!empty ($cek)){
//             echo "oke";
             $this->db->delete('transaksi_header',array("NoStruk" => $da[2], "Gudang"=>$gd));
         }
         $data = array(
             'NoKassa'  => $da[0],
             'Gudang'   => $gd,
             'NoStruk'  => $da[2],
             'Tanggal'  => $da[3],
             'Waktu'    => $da[4],
             'Kasir'    => $da[5],
             'KdStore'  => $da[6],
             'TotalItem' => $da[7],
             'TotalNilai'=> $da[8],
             'TotalBayar'=> $da[9],
             'Kembali'  => $da[10],
             'Tunai'    => $da[11],
             'KKredit'  => $da[12],
             'KDebit'   => $da[13],
             'Voucher'  => $da[14],
             'Discount' => $da[15],
             'Status'   => $da[16],
             'KdCustomer'=> $da[17],
             'NamaCustomer'=> $da[18],
             'Gender'   => $da[19],
             'TglLahir' => $da[20],
             'Keterangan'=> $da[21],
             'EditDate' => $da[22],
             'EditUser' => $da[23]
            );
            $this->db->insert('transaksi_header', $data);
    }
    
    function do_simpan_detail($det,$gd)
    {
         $da = split("#",$det);
//         echo "<pre>";print_r($da);echo "</pre>";die();
         //cek terlebih dahulu sudah ada atau blm jika sudah delete instert
         $cek = $this->getRow("select * from transaksi_detail where NoStruk = '$da[2]' and Gudang='$gd' and PCode= $da[7]") ;
         if(!empty ($cek)){
//             echo "oke";
             $this->db->delete('transaksi_detail',array("NoStruk" => $da[2], "Gudang"=>$gd, "PCode"=> $da[7]));
         }
         $data = array(
             'NoKassa'	=> $da[0],
        		 'Gudang'	=> $gd,
        		 'NoStruk'  => $da[2],
        		 'Tanggal'	=> $da[3],
        		 'Waktu'		=> $da[4],
        		 'Kasir'		=> $da[5],
        		 'KdStore'	=> $da[6],
        		 'PCode'		=> $da[7],
        		 'Qty'		=> $da[8],
        		 'Harga'		=> $da[9],
        		 'Ketentuan1'=> $da[10],
        		 'Disc1'		=> $da[11],
        		 'Jenis1'	=> $da[12],
        		 'Ketentuan2'=> $da[13],
        		 'Disc2'		=> $da[14],
        		 'Jenis2'	=> $da[15],
        		 'Ketentuan3'=> $da[16],
        		 'Disc3'		=> $da[17],
        		 'Jenis3'	=> $da[18],
        		 'Ketentuan4'=> $da[19],
        		 'Disc4'		=> $da[20],
        		 'Jenis4'	=> $da[21],
        		 'Netto'		=> $da[22],
        		 'Hpp'		=> $da[23],
        		 'Status'	=> $da[24],
        		 'Keterangan' => $da[25]
        
        );
            $this->db->insert('transaksi_detail', $data);//die();
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
                $sql = "SELECT KdCabang,KdGU FROM aplikasi";
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