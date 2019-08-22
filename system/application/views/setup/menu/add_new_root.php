<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'root',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('root')">
<form method='post' name="root" id="root" action='<?=base_url();?>index.php/setup/menu/save_new_root'>
<table align = 'center'>
<font color="red"><b><?=$msg?></b></font>
<tr>
	<td nowrap> Nama Root</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="nama" name="nama" size="35" maxlength="30" <?=$gantikursor;?> value="<?=$nama;?>" ></td>
</tr>
<tr>
	<td nowrap>Url</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="url" name="url" size="45" maxlength="40" <?=$gantikursor;?> value="<?=$url;?>" ></td>
</tr>
<tr>
	<td nowrap>Urutan Root Setelah</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="menu" id="menu" size="1" <?=$gantikursor;?>>
	<option <?php if($menu=="") echo "selected"; ?> value="">Baris Awal</option>
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
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekmenuroot();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/setup/menu/" />
		</td>
</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>