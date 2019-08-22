<?php
$this->load->helper('path');
$this->load->helper('file');
$this->load->library('printreportlib');
$printlib = new printreportlib();
if(empty($pt)){
	$pt = $printlib->getNamaPT();
}
$pathDta = set_realpath(APPPATH."Report");
$IP = $printlib->getIP();
$fileName = $pathDta.$IP.$fileName2;
$no = 0;
for($a=0;$a<$tot_hal;$a++){
	if($a==0)
	{
		$printlib->PrintJudulDO($judullap,"w",$fileName,$fontstyle,$spasienter,$pindah_hal,$pt);
	}
	else
	{
		$printlib->PrintJudulDO($judullap,"a",$fileName,$fontstyle,$spasienter,$pindah_hal,$pt);
	}
	$printlib->subjudultabel_print($judul1,$niljudul1,$judul2,$niljudul2,$fileName);
	$line = $printlib->print_judul_detail_kertas($max_field_len,$banyakBarang,$fileName,$judul_detail,$tipe_judul_detail);
	$no = $printlib->print_detail_kertas($no,$max_field_len,$banyakBarang,$fileName,$detail[$a],$tipe_judul_detail,$judul_detail);
	$printlib->print_footer_cetak($line,$fileName,(int)$a+1,$tot_hal,$nfontstyle);
}
$spasi_ttl ="                                       ";
$ps=array();
	$d_dot=array();
 $Handle = fopen($fileName, 'a');
 fwrite($Handle, "\r\n");
	for($z=0; $z<count($judul_netto); $z++){
	 	
		$st =strlen($spasi_ttl.$judul_netto[$z]);
		$wrf =$spasi_ttl.$judul_netto[$z];
               
		$d_dot[$z]=18-strlen($judul_netto[$z]);
			for($o=1; $o<=$d_dot[$z]; $o++){
				$wrf.=" ";
			}
		$wrf.=":";
		$jnum=10-strlen($isi_netto[$z]);
		for($ps[$z]=1; $ps[$z]<=$jnum; $ps[$z]++){
		$wrf.=" ";
		}
		$wrf.=$isi_netto[$z];
//		$wrf.="\r\n";
		fwrite($Handle, $wrf."\r\n");
                
                }
if($string1!=""&&isset($string1)){
	$printlib->persetujuan($string1,$string2,$fileName);
}

header("Content-type: application/txt");
header("Content-Disposition: attachment; filename=".$IP.$fileName2);
$content = read_file($fileName);
echo $content;
?>