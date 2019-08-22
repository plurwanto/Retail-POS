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
		$printlib->PrintJudul($judullap,"w",$fileName,$fontstyle,$spasienter,$pindah_hal,$pt);
	}
	else
	{
		$printlib->PrintJudul($judullap,"a",$fileName,$fontstyle,$spasienter,$pindah_hal,$pt);
	}
	$printlib->subjudultabel_print($judul1,$niljudul1,$judul2,$niljudul2,$fileName);
	$line = $printlib->print_judul_detail_kertas($max_field_len,$banyakBarang,$fileName,$judul_detail,$tipe_judul_detail);
	$no = $printlib->print_detail_kertas($no,$max_field_len,$banyakBarang,$fileName,$detail[$a],$tipe_judul_detail,$judul_detail);
	$printlib->print_footer_cetak_retur($line,$fileName,(int)$a+1,$tot_hal,$nfontstyle,$judul_netto);
}
if($string1!=""&&isset($string1)){
	$printlib->persetujuan($string1,$string2,$fileName);
}

header("Content-type: application/txt");
header("Content-Disposition: attachment; filename=".$IP.$fileName2);
$content = read_file($fileName);
echo $content;
?>