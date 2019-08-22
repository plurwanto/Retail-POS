<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'grup_harga',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/grup_harga.js"></script>
<body onload="firstLoad('grup_harga')">
<table align = 'center'>
<form method='post' name="grup_harga" id="grup_harga" action='<?=base_url();?>index.php/master/grup_harga/save_grup_harga'>
<table>
	<tr>
		<td>
			<table align="left">
				<?php
				$mylib = new globallib();
				echo $mylib->write_textbox("Kode","kode",$header->KdGrupHarga,"5","2","readonly","text",$gantikursor,"1");
				echo $mylib->write_textbox("Keterangan","nama",$header->Keterangan,"35","25","","text",$gantikursor,"1");
				?>
			</table>
		</td>
	</tr>
	<tr>
	<td>
		<table name="detail" id="detail">
			<tr>
				<?php if($edit) { ?>
				<th><input type="button" value="Tambah Barang" onclick="cekgrup('tambah')"></th>
				<?php } ?>
				<th bgcolor="#414141"><font color="white">Kode Barang</font></th>
				<th bgcolor="#414141"><font color="white">Nama Barang</font></th>
				<th bgcolor="#414141"><font color="white">Harga Jual Master</font></th>
				<th bgcolor="#414141"><font color="white">Harga Jual Grup</font></th>
				<th bgcolor="#414141"><font color="white">Kode PLU</font></th>
			</tr>
		<?php
			if($edit){
				 $status="edit"; 
				 $keydownbrg = "onkeydown=\"keyShortcut(event,'1','kdbrg','".base_url()."')\"";
				 $keydownplu = "onkeydown=\"keyShortcut(event,'1','plu','".base_url()."')\"";
				 $focus = "onfocus=\"iterate(this)\"";
				 $pop  = "<input type='button' value='...' onclick=\"popbrg('1','".base_url()."')\"/>";			
			}
			else{ 
			 	$status = "view";
				$keydownbrg = "";
			 	$focus = "";
			 	$keydownplu = "";
			 	$pop = "";
			}			
			$mylib = new globallib();
			for($a=0;$a<count($detail);$a++)
			{		 	
			 	if($a==0){
			?>
				<tr>					
					<input type="hidden" id="statusedit" name="statusedit" value="<?=$status;?>">
					<input type="hidden" id="nomor" name="nomor" value="1">
					<input type="hidden" id="urladd" name="urladd" value="<?=base_url();?>">
					<input type="hidden" id="tempall" name="tempall">
					<input type="hidden" id="how" name="how">
					<?php if($edit){ ?>
					<td>&nbsp;</td>
					<?php } ?>
					<td><input type="text" id="kdbrg1" name="kdbrg1" size="20" maxlength="15" value="<?=$detail[$a]['PCode'];?>" <?=$keydownbrg;?> <?=$focus;?>><?=$pop;?></td>
					<td><input type="text" readonly id="nmbrg1" name="nmbrg1" size="35" value="<?=$detail[$a]['NamaStruk'];?>"></td>
					<td><input type="text" readonly id="jualm1" name="jualm1" size="18" value="<?=$mylib->ubah_format($detail[$a]['HargaMaster']);?>"></td>
					<td><input type="text" id="jualg1" name="jualg1" size="15"  maxlength="14" value="<?=$mylib->ubah_format($detail[$a]['HargaJual']);?>" <?=$gantikursor;?>></td>
					<td><input type="text" id="plu1" name="plu1" size="20"  maxlength="15" value="<?=$detail[$a]['KdPLU'];?>" <?=$gantikursor;?> <?=$keydownbrg;?> <?=$focus;?>></td>
					<td><input type="hidden" id="kdtemp1" name="kdtemp1" value="<?=$detail[$a]['PCode'];?>"></td>
				</tr>
		<?php 
				}
				else
				{
				?>
				<script>
				 	addRaw("<?=$detail[$a]['PCode'];?>","<?=$detail[$a]['NamaStruk'];?>","<?=$mylib->ubah_format($detail[$a]['HargaMaster']);?>","<?=$mylib->ubah_format($detail[$a]['HargaJual']);?>","<?=$detail[$a]['KdPLU'];?>");
				</script>
				<?php				
			}
		}
		if($edit){
	 	?>
	 	<script>
		 	addRaw("","","","","");
		</script>
	<?php }?>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td nowrap colspan="3">
					<?php if($edit) { ?>
						<input type='button' value='Save' onclick="cekgrup('save')"/>
					<?php } ?>
						<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/grup_harga/" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form/
<?php
$this->load->view('footer'); ?>