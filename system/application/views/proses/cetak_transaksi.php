<?php
//print "asdasdasda";//dhfgd
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
$tgl=$header[0]['Tanggal'];
$tgl_1=explode("-",$tgl);
$tgl_tampil=$tgl_1[2]."/".$tgl_1[1]."/".$tgl_1[0];
//$tglnow = date('h-m-Y H:s:w');
for($a=0;$a<count($header);){

printer_write($ftext, $reset.$elite);
printer_write($ftext, $dwidth.str_pad($store[0]['NamaPT'],37," ",STR_PAD_BOTH).$ndwidth."\r\n");
printer_write($ftext, str_pad($store[0]['Alamat1PT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, str_pad($store[0]['Alamat2PT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, str_pad($store[0]['TelpPT'],39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, "\r\n");
//printer_write($ftext, str_pad($tgl_tampil." - ".$tglnow,39," ",STR_PAD_BOTH)."\r\n");
printer_write($ftext, "Kasir : ".$header[$a]['Kasir']."\r\n");
printer_write($ftext, "Tanggal Transaksi : ".$tgl_tampil."\r\n");
printer_write($ftext, "========================================\r\n");
//printer_write($ftext, str_pad("Total ".$a." Struk ",24).":".str_pad(number_format($nil, 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
printer_write($ftext, str_pad("Total Struk ",24).":".str_pad($header[$a]['TTLStruk'],15," ",STR_PAD_LEFT)."\r\n");
printer_write($ftext, "----------------------------------------\r\n");
printer_write($ftext, str_pad("Total Pendapatan ",24).":".str_pad(number_format($header[$a]['TotalNilai'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
printer_write($ftext, "----------------------------------------\r\n");
printer_write($ftext, str_pad("Rincian ",40," ",STR_PAD_RIGHT)."\r\n");
if($header[$a]['Tunai']<>0)
printer_write($ftext, str_pad("Tunai ",24).":".str_pad(number_format(($header[$a]['Tunai']-$header[$a]['Kembali']), 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
if($header[$a]['KKredit']<>0)
printer_write($ftext, str_pad("Kartu Kredit ",24).":".str_pad(number_format($header[$a]['KKredit'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
if($header[$a]['KDebit']<>0)
printer_write($ftext, str_pad("Kartu Debit ",24).":".str_pad(number_format($header[$a]['KDebit'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
if($header[$a]['Voucher']<>0)
printer_write($ftext, str_pad("Voucher ",24).":".str_pad(number_format($header[$a]['Voucher'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, str_pad("NoStruk ",10," ",STR_PAD_RIGHT)."".
//                      str_pad("Jam",10," ",STR_PAD_RIGHT)."".
//                      str_pad("User",10," ",STR_PAD_RIGHT)."".
//                      str_pad("Ttl Nilai",10," ",STR_PAD_LEFT)."\r\n");
//print_r($header);
//for($a=0;$a<count($header);$a++){
//	printer_write($ftext, str_pad(substr($header[$a]['NoStruk'],0,10),10).
//	       str_pad($header[$a]['Waktu'],10," ",STR_PAD_RIGHT).
//	       str_pad($header[$a]['Kasir'],10," ",STR_PAD_RIGHT).
//               str_pad(number_format($header[$a]['TotalNilai'],0,',','.'),10," ",STR_PAD_LEFT)."\r\n");
//           $nil += $header[$a]['TotalNilai'];
//           $nilTunai += $header[$a]['Tunai'];
//           $nilKKredit += $header[$a]['KKredit'];
//           $nilKDebit += $header[$a]['KDebit'];
//           $nilVoucher += $header[$a]['Voucher'];

//}
//printer_write($ftext, "----------------------------------------\r\n");
//if($header[0]['Discount']<>0)
//printer_write($ftext, str_pad("Discount ".$header[0]['InitDisc'],24).":".str_pad(number_format($header[0]['DiscRupiah'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, str_pad("Total Rupiah ",24).":".str_pad(number_format($header[0]['TotalNilai'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, "----------------------------------------\r\n");
//if($header[0]['Tunai']<>0)
//printer_write($ftext, str_pad("Tunai ",24).":".str_pad(number_format($header[0]['Tunai'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//if($header[0]['KKredit']<>0)
//printer_write($ftext, str_pad("Kartu Kredit ",24).":".str_pad(number_format($header[0]['KKredit'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//if($header[0]['KDebit']<>0)
//printer_write($ftext, str_pad("Kartu Debit ",24).":".str_pad(number_format($header[0]['KDebit'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//if($header[0]['Voucher']<>0)
//printer_write($ftext, str_pad("Voucher ",24).":".str_pad(number_format($header[0]['Voucher'], 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, "----------------------------------------\r\n");
//printer_write($ftext, str_pad("Total Penjualan ",24).":".str_pad(number_format($nil, 0, ',', '.'),15," ",STR_PAD_LEFT)."\r\n");
//printer_write($ftext, str_pad("Kembali ",24).":".$dwidth.str_pad(number_format($header[0]['Kembali'], 0, ',', '.'),12," ",STR_PAD_LEFT).$ndwidth."\r\n");
printer_write($ftext, "========================================\r\n");
printer_write($ftext, "             ===Terima kasih===          \r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, "\r\n");
    $a++;
}// di luping sebanyak kasir
printer_write($ftext, $fcut);
printer_write($ftext, $op_cash);
printer_close($ftext);
?>