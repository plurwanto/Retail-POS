<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'root',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<body onload="firstLoad('root');">
<form method='post' name="root" id="root" action='<?=base_url();?>index.php/setup/menu/save_new_sub'>
<table align = 'center'>
<font color="red"><b><?=$msg?></b></font>
<tr>
	<td nowrap> Nama Menu</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="nama" name="nama" size="35" maxlength="30" <?=$gantikursor;?> value="<?=$nama;?>" ></td>
</tr>
<tr>
	<td nowrap>Url</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="url" name="url" size="45" maxlength="40" <?=$gantikursor;?> value="<?=$url;?>" ></td>
</tr>
<tr>
	<td nowrap>Induk Menu</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="rootmenu" id="rootmenu" size="1" <?=$gantikursor;?> onchange="ambilsubs('<?=base_url();?>')">
<?php
	for($s=0;$s<count($root);$s++)
	{
		?>
		<option <?php if($root[$s]['nama']==$menu) echo "selected"; ?> value="<?=$root[$s]['nama'];?>"><?=$root[$s]['nama'];?></option>
	<?php
	}
?>
	</select></td>
</tr>
<tr>
	<td nowrap>Urutan Menu Setelah</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="menu" id="menu" size="1" <?=$gantikursor;?>>
	<option <?php if($menuafter=="") echo "selected"; ?> value="">Baris Awal</option>
<?php
	for($s=0;$s<count($anak);$s++)
	{
		?>
		<option <?php if($anak[$s]['nama']==$menuafter) echo "selected"; ?> value="<?=$anak[$s]['nama'];?>"><?=$anak[$s]['nama'];?></option>
	<?php
	}
?>
	</select></td>
</tr>
<tr>
	<td nowrap>Jenis Menu</td>
	<td nowrap>:</td>
	<td nowrap>
		<select name="jenis" id="jenis" size="1" <?=$gantikursor;?> >		
		<?php 
		$c = array_keys($jenis);
		for ($a=0;$a<count($jenis);$a++)
		{
		 	$str ="";		 	
		 	if($jenis1==$c[$a]) { $str = "selected"; }
			echo "<option $str value='$c[$a]'>".$jenis[$c[$a]]."</option>";
		}
		?>
		</select>
	</td>
</tr>
<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekmenuroot();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/setup/menu/" />
		</td>
</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>