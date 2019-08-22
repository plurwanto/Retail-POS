<?php
class globallib {
	var $CI;
	function __construct(){
	    $this->CI =& get_instance();
	 	$this->CI->load->library('session');
		$this->CI->load->model('globalmodel');
	}

	function getAllowList($sign){
		$segs = $this->CI->uri->segment_array();
		$session_level = $this->CI->session->userdata('userlevel');
		$arr = "index.php/".$segs[1]."/".$segs[2]."/";
		$allowed = $this->CI->globalmodel->getPermission($arr,$session_level);
        //print_r($allowed);
		if($session_level=="")
		{
			$reaction = "T";
		}
		else
		{
			if($sign=="all")
			{
				if($allowed->view=="Y"||$allowed->add=="Y"||$allowed->edit=="Y"||$allowed->delete=="Y"){
					$reaction = "Y";
				}
				else { $reaction = "T"; }
			}
			if($sign=="view")
			{
				$reaction = $allowed->view;
			}
			if($sign=="add")
			{
				$reaction = $allowed->add;
			}
			if($sign=="del")
			{
				$reaction = $allowed->delete;
			}
			if($sign=="edit")
			{
				$reaction = $allowed->edit;
			}
		}

		return $reaction;
	}

        function getUser(){
            $u  = $this->CI->session->userdata('username');
            return $u;
        }

	function restrictLink($str){
		$session_level = $this->CI->session->userdata('userlevel');
		$allowed = $this->CI->globalmodel->getPermission($str,$session_level);
		return $allowed;
	}
	function write_header($str)
	{
		for($a=0;$a<count($str);$a++)
		{
			echo "<th nowrap>$str[$a]</th>";
		}
	}
	function write_textbox($ket,$name,$value,$size,$max,$readonly,$type,$gantikursor,$colspan)
	{
	?>
		<tr>
			<td nowrap><?=$ket;?></td>
			<td nowrap>:</td>
			<td nowrap colspan="<?=$colspan?>" ><input type="<?=$type;?>" maxlength="<?=$max;?>" size="<?=$size;?>" <?=$readonly;?> name="<?=$name;?>" id="<?=$name;?>" value="<?=$value;?>" <?=$gantikursor;?> /></td>
		</tr>
	<?php
	}
	function textaja($ket,$value)
	{
	?>
		<tr>
			<td nowrap><?=$ket;?></td>
			<td nowrap>:</td>
			<td nowrap><?=$value;?></td>
		</tr>
	<?php
	}
	function write_combo($judul,$nama,$isi_semua,$val,$primary,$nilai,$gantikursor,$action,$close)
	{
	 ?>
	 	<tr>
	 	<td nowrap><?=$judul;?></td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="<?=$nama;?>" name="<?=$nama;?>" <?=$gantikursor;?> <?=$action;?> >
		<option value="">--Please Select--</option>
		<?php
		for($a = 0;$a<count($isi_semua);$a++){
		 	$select = "";
		 	if($val==$isi_semua[$a][$primary]){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$isi_semua[$a][$primary]?>"><?=$isi_semua[$a][$nilai]?></option>
		<?php
		}
		?>
		</select>
		</td>
	<?php
		if($close=="ya")
		{
			echo "</tr>";
		}
	}

        function write_comboAll($judul,$nama,$isi_semua,$val,$primary,$nilai,$gantikursor,$action,$close)
	{
	 ?>
	 	<tr>
	 	<td nowrap><?=$judul;?></td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="<?=$nama;?>" name="<?=$nama;?>" <?=$gantikursor;?> <?=$action;?> >
		<option value="00">-- All --</option>
		<?php
		for($a = 0;$a<count($isi_semua);$a++){
		 	$select = "";
		 	if($val==$isi_semua[$a][$primary]){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$isi_semua[$a][$primary]?>"><?=$isi_semua[$a][$nilai]?></option>
		<?php
		}
		?>
		</select>
		</td>
	<?php
		if($close=="ya")
		{
			echo "</tr>";
		}
	}

	function write_plain_combo($judul,$nama,$gantikursor,$value,$action,$close)
	{
	?>
		<td nowrap> <?=$judul;?> </td>
		<td nowrap>
			<select size="1" id="<?=$nama;?>" name="<?=$nama;?>" <?=$gantikursor;?> <?=$action;?>>
			<option value="">--Please Select--</option>
			<?=$value;?>
			</select>
		</td>
	<?php
		if($close=="ya")
		{
			echo "</tr>";
		}
	}

	function ubah_format($harga){
		$s = number_format($harga, 2, ',', '.');
		return $s;
	}
	function ubah_format_awal($harga){
		$s = explode(".",$harga);
		$k = implode($s,"");
		$k = explode(",",$k);
		$s = implode($k,".");
		return $s;
	}

        function detailkassa($ip){
            $k = $this->CI->globalmodel->getkassa($ip);
            return $k;
        }
        
        function getStock($bln,$thn,$min){
            $k = $this->CI->globalmodel->getBarangmin($bln,$thn,$min);
            return $k;
        }
	function ubah_tanggal($tanggalan)
	{
	 list ($tanggal, $bulan, $tahun) = explode ("-", $tanggalan);
	 $tgl = $tahun."-".$bulan."-".$tanggal;
	 return $tgl;
	}

	function ubah_format_tanggal($tanggalan)
	{
	 list ($tahun, $bulan, $tanggal) = explode ("-", $tanggalan);
	 $tgl = $tanggal."-".$bulan."-".$tahun;
	 return $tgl;
	}

        function print_track()
	{
		$segs = $this->CI->uri->segment_array();
		if(count($segs)>=2){
			$arr = "index.php/".$segs[1]."/".$segs[2]."/";
			return $this->findRoot($arr);
		}
	}

        function findRoot($url)
	{
		$first = $this->CI->globalmodel->getName($url);
		if(substr($first->root,0,9)!="ddsubmenu")
		{
			$string = $first->root." > ".$first->nama;
			$second = $this->CI->globalmodel->getName2($first->root);
			if(substr($second->root,0,9)=="ddsubmenu")
			{
				$fourth = $this->CI->globalmodel->getRoot($second->root);
				$string = $fourth->nama." > ".$string;
			}
		}
		else{
			$string = $first->nama;
			$fourth = $this->CI->globalmodel->getRoot($first->root);
			$string = $fourth->nama." > ".$string;
		}
		return $string;
	}
        
	function standard_date($tgl,$jam)
	{
		$tgl = 'DATE_RFC822';
		$jam = time();
	}
        
        function Updateheadernya($id,$tblH,$tbld,$thn,$fielqty,$fielharga){
                $this->CI->globalmodel->updatePPN($id,$tblH,$thn);
                $this->CI->globalmodel->updateTTLHeader($id,$tblH,$tbld,$fielqty,$fielharga);
        }

        function findBarcode($barcode)
	{
		$nilai = $this->CI->globalmodel->getkd($barcode);
                if(!empty($nilai))
                {
			$data = $nilai->PCode;
		}else{
                    $data = "";

                }
            return $data;
        }
        function to_excel($query, $filename='exceloutput')
        {
             $headers = ''; // just creating the var for field headers to append to below
             $data = ''; // just creating the var for field data to append to below

             $obj =& get_instance();

             /*$fields = $query->field_data();
             if ($query->num_rows() == 0) {
                  echo '<p>The table appears to have no data.</p>';
             } else {
                  foreach ($fields as $field) {
                     $headers .= $field->name . "\t";
                  }

                  foreach ($query->result() as $row) {
                       $line = '';
                       foreach($row as $value) {
                            if ((!isset($value)) OR ($value == "")) {
                                 $value = "\t";
                            } else {
                                 $value = str_replace('"', '""', $value);
                                 $value = '"' . $value . '"' . "\t";
                            }
                            $line .= $value;
                       }
                       $data .= trim($line)."\n";
                  }

                  $data = str_replace("\r","",$data);

                  header("Content-type: application/x-msdownload");
                  header("Content-Disposition: attachment; filename=$filename.xls");
                  echo "$headers\n$data";
             }*/
             header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
                header( "Content-Length: " . strlen( $query ) );
      //          header( "Content-type: text/x-csv" );
                header( "Content-type: text/csv" );
                //header("Content-type: application/x-msdownload");
                header( "Content-type: application/csv" );
                header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
                echo $query;

         
              //header("Content-type: application/x-msdownload");
             //header("Content-Disposition: attachment; filename=$filename.xls");
                //  echo "$query";
        }
}
?>