<?php
$this->load->helper('path');
$this->load->helper('file');
$this->load->library('printreportlib');
$printlib = new printreportlib();
$pathDta = set_realpath(APPPATH."Report");
$IP = $printlib->getIP();
$fileName = $pathDta.$IP.$fileName2;
for($x=0;$x<count($nilai);$x++){
	$tot_hal = $nilai[$x]['tot_hal'];
	$pt = $nilai[$x]['pt'];
	$nosj = $nilai[$x]['nosj'];
	$nopol = $nilai[$x]['nopol'];
	$supir = $nilai[$x]['supir'];
	$detail = $nilai[$x]['detail'];

	$no = 0;
	$spasibarisatas = "";
	for($s=0;$s<55;$s++)
	{
		$spasibarisatas .= " ";
	}
	$barisatas = substr($spasibarisatas,0,54).$pt->Nama;
	for($a=0;$a<$tot_hal;$a++){
		if($x==0)
		{
			$Handle = fopen($fileName, "w");
			$Data = chr(27).chr(64).chr(27).chr(67).chr(33).$fontstyle.chr(27).chr(69)."\r\n".$spasibarisatas.date("d-m-Y")."\r\n\r\n";
			$Data .= $barisatas."\r\n";
		}
		else
		{
			$Handle = fopen($fileName, "a");
			$Data = $spasienter.chr(27).chr(64).chr(27).chr(67).chr(33).$fontstyle.chr(27).chr(69)."\r\n".$spasibarisatas.date("d-m-Y")."\r\n\r\n";
			$Data .= $barisatas."\r\n";
		}
		$Data .= "\r\n\r\n";
		$Data .= "                             ".$nosj."\r\n\r\n";
		$Data .= "                    ".$supir."      ".$nopol."\r\n\r\n\r\n\r\n";

		$list_detail = $detail[$a];
		for($t=0;$t<count($list_detail);$t++){
			$detail = $list_detail[$t];
			$spasi = "";
			$limitSpasi = 11;
			$sisa = (int)$limitSpasi - (int)strlen($detail[2]);
			for($k=0;$k<$sisa;$k++){
				$spasi .= " ";
			}
			$Data .= chr(27).chr(48)."\r\n  ".$detail[2].$spasi." ";
			$spasi = "";
			$limitSpasi = 40;
			$sisa = (int)$limitSpasi - (int)strlen($detail[1]) - (int)strlen($detail[0]);
			for($k=0;$k<$sisa;$k++){
				$spasi .= " ";
			}
			$Data .=$detail[0]." ".$detail[1].$spasi." \r\n\r\n";
		}
		$Data .= chr(27).chr(50);
		fwrite($Handle, $Data);
		fclose($Handle);
	}
}

header("Content-type: application/txt");
header("Content-Disposition: attachment; filename=".$IP.$fileName2);
$content = read_file($fileName);
echo $content;
?>