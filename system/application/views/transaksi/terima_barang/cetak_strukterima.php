<?php
$reset  =chr(27).'@';
$plength=chr(27).'C';
$lmargin=chr(27).'l';
$cond   =chr(15);
$ncond  =chr(18);
$dwidth =chr(27).'!'.chr(16);
$ndwidth=chr(27).'!'.chr(1);
$draft  =chr(27).'x'.chr(48);
$nlq    =chr(27).'x'.chr(49);
$bold   =chr(27).'E';
$nbold  =chr(27).'F';
$uline  =chr(27).'!'.chr(129);
$nuline =chr(27).'!'.chr(1);
$dstrik =chr(27).'G';
$ndstrik=chr(27).'H';
$elite  ='';
$pica   =chr(27).'P';
$height =chr(27).'!'.chr(16);
$nheight=chr(27).'!'.chr(1);
$spasi05=chr(27)."3".chr(16);
$spasi1 =chr(27)."3".chr(24);
$fcut   =chr(10).chr(10).chr(10).chr(10).chr(10).chr(13).chr(27).'i';
$pcut   =chr(10).chr(10).chr(10).chr(10).chr(10).chr(13).chr(27).'m';
$op_cash=chr(27).'p'.chr(0).chr(50).chr(20).chr(20);

//$ftext = printer_open("\\\\".$_SERVER['REMOTE_ADDR']."\\HPLaserJ");//
//$ftext = printer_open("\\\\192.168.14.172\\HP LaserJet P2015");
//$ftext = printer_open("\\\\".$ip."\\".$nm_printer);
//$ftext = printer_open($_SERVER['REMOTE_ADDR']);
$ftext = printer_open("epson");
printer_set_option($ftext, PRINTER_MODE, "raw");
printer_set_option($ftext, PRINTER_COPIES, "1"); 
$alamatPT=$store[0]['Alamat1PT'];
$tgl=$header->TglDokumen;
//echo $header->Keterangan;die();
//print_r($header);die();
//$tgl_1=explode("-",$tgl);
$tgl_tampil=$header->TglDokumen;
printer_write($ftext, $reset.$elite);
printer_write($ftext, $dwidth.str_pad($store[0]['NamaPT'],33," ",STR_PAD_BOTH).$ndwidth."\r\n");
printer_write($ftext, str_pad($store[0]['Alamat1PT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, str_pad($store[0]['Alamat2PT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, str_pad($store[0]['TelpPT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, "\r\n");
//printer_write($ftext, str_pad($tgl_tampil." - ".$header[0]['Waktu'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, "Opname : ".$header->NoDokumen."\r\n");
printer_write($ftext, "Keterangan : ".$header->Keterangan."\r\n");
printer_write($ftext, "========================================\r\n");
$by = 0;
for($a=0;$a<count($detail);$a++){
	printer_write($ftext,str_pad(substr($detail[$a]['PCode'],0,6),6). 
			 str_pad(substr($detail[$a]['NamaInitial'],0,26),26).
	       str_pad($detail[$a]['QtyTerima'],5," ",STR_PAD_LEFT)."\r\n"); 
//	       str_pad(round($detail[$a]['HJ']),7," ",STR_PAD_LEFT).
//		   str_pad(round($detail[$a]['Netto']),8," ",STR_PAD_LEFT)."\r\n");   
//        $by = $by + $detail[$a]['Netto'];
}
printer_write($ftext, "----------------------------------------\r\n");
//printer_write($ftext, str_pad("Total ".$header[0]['TotalItem']." item",24).":".str_pad(number_format($header[0]['TotalNilai'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//if($header[0]['Discount']<>0)
//printer_write($ftext, str_pad("Discount ".$header[0]['InitDisc'],24).":".str_pad(number_format($header[0]['DiscRupiah'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, str_pad("Total Rupiah ",24).":".str_pad(number_format($header[0]['TotalNilai'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, "----------------------------------------\r\n");
//printer_write($ftext, "----------------------------------------\r\n");
//printer_write($ftext, str_pad("Total ",24).":".str_pad(number_format($by, 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");

printer_write($ftext, "========================================\r\n");
printer_write($ftext, "             ===Terima kasih===          \r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, $fcut);
printer_write($ftext, $op_cash);
printer_close($ftext);
?>