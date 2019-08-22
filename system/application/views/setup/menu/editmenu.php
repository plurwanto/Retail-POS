<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'root',this)\"";
$script = "";
if($msg=="")
{
	$script = "watchMenu('".base_url()."');";
}
?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<body onload="firstLoad('root');<?=$script;?>" >
<form method='post' name="root" id="root" action='<?=base_url();?>index.php/setup/menu/save_menu'>
<table align = 'center'>
<font color="red"><b><?=$msg?></b></font>
<tr>
	<td nowrap>Menu</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="allmenu" id="allmenu" size="1" <?=$gantikursor;?> onchange="watchMenu('<?=base_url();?>');">
<?php
	for($a=0;$a<count($allmenu);$a++)
	{
	?>
	<option <?php if($allselect==$allmenu[$a]['nama']){ echo "selected";}?> value = "<?=$allmenu[$a]['nama'];?>"><?=$allmenu[$a]['nama'];?></option>
<?php	
	}
?>
	</select>
	</td>
</tr>
<tr>
	<td nowrap>Nama</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="nama" name="nama" size="35" maxlength="30" <?=$gantikursor;?> value="<?=$nama;?>" ></td>
</tr>
<tr>
	<td nowrap>Url</td>
	<td nowrap>:</td>
	<td nowrap><input type="text" id="url" name="url" size="45" maxlength="40" <?=$gantikursor;?> value="<?=$url;?>" ></td>
</tr>
<tr>
	<td nowrap>Urutan Setelah</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="menu" id="menu" size="1" <?=$gantikursor;?>>
	<?=$aftermenu;?>
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