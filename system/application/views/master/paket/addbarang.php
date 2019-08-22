<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'barang',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/barang.js"></script>
<body onload="firstLoad('barang')">
<table align = 'center'>
<form method='post' name="barang" id="barang" action='<?=base_url();?>index.php/master/barang/save_new_barang'>
	<?php
	if($msg){ echo $msg;}
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode Barang","kode",$id,"20","15","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Barcode","barcode",$barcode,"35","30","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama Struk","nama",$nama,"35","30","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama Lengkap","nlengkap",$nlengkap,"75","70","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama Initial","ninitial",$ninitial,"15","10","","text",$gantikursor,"5");
	$action = "onchange =\"getSubDiv('".base_url()."');\"";
	echo $mylib->write_combo("Divisi","divisi",$mdivisi,$divisi,"KdDivisi","NamaDivisi",$gantikursor,$action,"tidak");
	echo $mylib->write_plain_combo("Sub Divisi","subdivisi",$gantikursor,$subdiv,"","ya");	
	$action = "onchange =\"getSubKat('".base_url()."');\"";
	$action = "onchange =\"getSubBrand('".base_url()."');\"";
	echo $mylib->write_combo("Brand","brand",$mbrand,$brand,"KdBrand","NamaBrand",$gantikursor,$action,"tidak");
	echo $mylib->write_plain_combo("Sub Brand","subbrand",$gantikursor,$subbrand,"","ya");
	$action = "onchange =\"getSubSize('".base_url()."');\"";
	echo $mylib->write_combo("Satuan Ukuran","size",$msize,$size,"KdSize","NamaSize",$gantikursor,$action,"tidak");
	echo $mylib->write_plain_combo("Ukuran","subsize",$gantikursor,$subsize,"","ya");
	echo $mylib->write_combo("Kemasan","kemasan",$mkemasan,$kemasan,"KdKemasan","NamaKemasan",$gantikursor,"","ya");
	echo $mylib->write_combo("Kelas Produk","kelas",$mclass,$class,"KdKelas","NamaKelas",$gantikursor,"","ya");
	echo $mylib->write_combo("Tipe Produk","tipe",$mtipe,$tipe,"KdType","NamaType",$gantikursor,"","ya");
	echo $mylib->write_combo("Supplier","supplier",$msupplier,$supplier,"KdSupplier","Keterangan",$gantikursor,"","ya");
	echo $mylib->write_combo("Satuan Barang","satuan",$msatuan,$satuan,"NamaSatuan","keterangan",$gantikursor,"","ya");
	echo $mylib->write_textbox("Harga Jual","hjual",$hjual,"15","12","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Konversi","konv",$konv,"6","4","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Min.Order","minimum",$minimum,"15","10","","text",$gantikursor,"5");
//	echo $mylib->write_combo("Grup Harga","grup",$mgrup,$grup,"KdGrupHarga","Keterangan",$gantikursor,"","ya");
	?>
	<!--<tr>
		<td nowrap>Flag Harga</td>
		<td nowrap>:</td>
		<td nowrap>
			<select size="1" id="flag" name="flag" <?=$gantikursor;?>>
			<option value="">--Please Select--</option>
			<?php
			$nilaiflag = array_keys($mflag);
			for($a = 0;$a<count($mflag);$a++){
			 	$select = "";
			 	if($flag==$nilaiflag[$a]){
					$select = "selected";
				}
			?>
			<option <?=$select;?> value= "<?=$nilaiflag[$a]?>"><?=$mflag[$nilaiflag[$a]]?></option>
			<?php
			}
			?>
			</select>
		</td>
	</tr>-->
	<tr>
		<td nowrap>Status</td>
		<td nowrap>:</td>
		<td nowrap>
			<select size="1" id="status" name="status" <?=$gantikursor;?>>
			<option value="">--Please Select--</option>
			<?php
			for($a = 0;$a<count($mstatus);$a++){
			 	$select = "";
			 	if($status==$mstatus[$a]){
					$select = "selected";
				}
			?>
			<option <?=$select;?> value= "<?=$mstatus[$a]?>"><?=$mstatus[$a]?></option>
			<?php
			}
			?>
			</select>
		</td>
	</tr>
	<?php
	echo $mylib->write_combo("Parent Code","parents",$mparent,$parent,"PCode","NamaStruk",$gantikursor,"","ya");?>
	<tr>
		<td nowrap colspan="4">
			<input type='button' value='Save' onclick="cekbarang();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/barang/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>