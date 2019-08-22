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
	$judullap = $nilai[$x]['judullap'];
	$judul1 = $nilai[$x]['judul1'];
	$niljudul1 = $nilai[$x]['niljudul1'];
	$judul2 = $nilai[$x]['judul2'];
	$pt = $nilai[$x]['pt'];
	$niljudul2 = $nilai[$x]['niljudul2'];
	$colspan_line = $nilai[$x]['colspan_line'];
	$judul_detail = $nilai[$x]['judul_detail'];
	$tipe_judul_detail = $nilai[$x]['tipe_judul_detail'];
	$banyakBarang = $nilai[$x]['banyakBarang'];
	$max_field_len = $nilai[$x]['max_field_len'];
	$detail = $nilai[$x]['detail'];

	$no = 0;
	for($a=0;$a<$tot_hal;$a++){
		if($x==0)
		{
			$printlib->PrintJudulDO($judullap,"w",$fileName,$fontstyle,$spasienter,$pt);
		}
		else
		{
			$printlib->PrintJudulDO($judullap,"a",$fileName,$fontstyle,$spasienter,$pt);
		}
		$printlib->subjudultabel_print($judul1,$niljudul1,$judul2,$niljudul2,$fileName);
		$line = $printlib->print_judul_detail_kertas($max_field_len,$banyakBarang,$fileName,$judul_detail,$tipe_judul_detail);
		$no = $printlib->print_detail_kertas($no,$max_field_len,$banyakBarang,$fileName,$detail[$a],$tipe_judul_detail,$judul_detail);
		$printlib->print_footer_cetak($line,$fileName,(int)$a+1,$tot_hal,$nfontstyle);
	}
	if($string1!=""&&isset($string1)){
		$printlib->persetujuan_sj($string1,$string1a,$string2,$fileName);
	}
}

header("Content-type: application/txt");
header("Content-Disposition: attachment; filename=".$IP.$fileName2);
$content = read_file($fileName);
echo $content;
?>