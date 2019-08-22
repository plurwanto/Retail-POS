<?php
$this->load->view('header'); 
$gantikursor = "onkeydown=\"changeCursor(event,'grup_harga',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/grup_harga.js"></script>
<body onLoad="firstLoad('grup_harga')">

<form method='post' name="grup_harga" id="grup_harga" action='<?=base_url();?>index.php/master/grup_harga/save_new_grup_harga'>
<table>
	<tr>
		<td>
			<table align="left">
				<?php
				if($msg){ echo $msg;}
				$mylib = new globallib();
				echo $mylib->write_textbox("Kode","kode",$id,"5","2","","text",$gantikursor,"1");
				echo $mylib->write_textbox("Keterangan","nama",$nama,"35","25","","text",$gantikursor,"1");
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<table name="detail" id="detail">
			<tr>
				<th bgcolor="#414141"><font color="white">Kode Barang</font></th>
				<th bgcolor="#414141"><font color="white">Nama Barang</font></th>
				<th bgcolor="#414141"><font color="white">Harga Jual Master</font></th>
				<th bgcolor="#414141"><font color="white">Harga Jual Grup</font></th>
				<th bgcolor="#414141"><font color="white">Kode PLU</font></th>
			</tr>
			<tr>
				<td><input type="text" id="kdbrg1" name="kdbrg1" size="20" maxlength="15" value="<?=$kdbrg;?>" onKeyDown="keyShortcut(event,'1','kdbrg','<?=base_url();?>')"><input type='button' value='...' onClick="popbrg('1','<?=base_url();?>')"/></td>
				<td><input type="text" readonly id="nmbrg1" name="nmbrg1" size="35" value="<?=$nmbrg;?>"></td>
				<td><input type="text" readonly id="jualm1" name="jualm1" size="18" value="<?=$jualm;?>"></td>
				<td><input type="text" id="jualg1" name="jualg1" size="15"  maxlength="14" value="<?=$jualg;?>" <?=$gantikursor;?>></td>
				<td><input type="text" id="plu1" name="plu1" size="20"  maxlength="15" value="<?=$plu;?>"  onkeydown="keyShortcut(event,'1','plu','<?=base_url();?>')"></td>
				<td><input type="hidden" id="kdtemp1" name="kdtemp1" value="<?=$kdbrg;?>"></td>
				<td><input type="hidden" id="tempall" name="tempall" value="<?=$tempall;?>"></td>
				<td><input type="hidden" id="how" name="how"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td nowrap colspan="3">
						<input type='button' value='Save' onClick="cekgrup('save')"/>
						<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/grup_harga/" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>