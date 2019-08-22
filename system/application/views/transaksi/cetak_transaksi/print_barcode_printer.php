<?php
$this->load->helper('path');
$this->load->helper('file');
$this->load->library('printreportlib');
$printlib = new printreportlib();
$print_model = new print_model();
$pathDta = set_realpath(APPPATH."Report");
$IP = $printlib->getIP();
$fileName = $pathDta.$IP.$fileName2;

$ftext = fopen($fileName, "wt");
for($a=0;$a<count($Barcode);$a++){
    if($QtyCetak[$a]<>0){
	$getSatuan = $print_model->getSatuan($pcode[$a]);
	if(substr($Barcode[$a],0,1)=="1")
	{
		$satuan = $getSatuan->NamaSatuanBesar;
	}
	if(substr($Barcode[$a],0,1)=="2")
	{
		$satuan = $getSatuan->NamaSatuanTengah;
	}
	if(substr($Barcode[$a],0,1)=="3")
	{
		$satuan = $getSatuan->NamaSatuanKecil;
	}
	fwrite($ftext, chr(27)."A");
	if(strlen(trim($Barcode[$a]))==14){
	fwrite($ftext, chr(27)."H0465".chr(27)."V0050".chr(27)."BG01100".$Barcode[$a]);//BG02100 itu udah asalnya
	}
	else
	{
	fwrite($ftext, chr(27)."H0465".chr(27)."V0050".chr(27)."BG02100".$Barcode[$a]);//BG02100 itu udah asalnya
	}
	fwrite($ftext, chr(27)."H0490".chr(27)."V0160".chr(27)."XS".$Barcode[$a]."-".$Attr[$a]);
	fwrite($ftext, chr(27)."H0490".chr(27)."V0180".chr(27)."XS".$Nama[$a]." ".$satuan);
	fwrite($ftext, chr(27)."H0490".chr(27)."V0200".chr(27)."XS".$konversibesarkecil[$a]."-".$konversitengahkecil[$a]." == ".$kdlokasi[$a]);
	fwrite($ftext, chr(27)."Q".$QtyCetak[$a].chr(27)."Z");// Q = banyaknya print
	}
}
/*
fwrite($ftext, chr(27)."A");
	fwrite($ftext, chr(27)."H0450".chr(27)."V0050".chr(27)."BG021001001900000021");//BG02100 itu udah asalnya 
	// H pengaturan kiri kanan makin besar makin ke kanan
	// v pengaturan atas bawah makin besar makin turun
	fwrite($ftext, chr(27)."H0590".chr(27)."V0160".chr(27)."XS1001900000021".$Barcode[$a]);
	fwrite($ftext, chr(27)."Q1".chr(27)."Z");// Q = banyaknya print
$banyaknya = array_sum($Qty);
*/

fclose($ftext);
header("Content-type: application/txt");
header("Content-Disposition: attachment; filename=".$IP.$fileName2);
$content = read_file($fileName);
echo $content;
?>