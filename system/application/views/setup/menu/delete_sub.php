<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'root',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('root')">
<form method='post' name="root" id="root" action='<?=base_url();?>index.php/setup/menu/delete_this_subs'>
<table align = 'center'>
<tr>
	<td nowrap>Sub Menu</td>
	<td nowrap>:</td>
	<td nowrap>
	<select name="menu" id="menu" size="1" <?=$gantikursor;?>>
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
			<input type='submit' value='Delete'/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/setup/menu/" />
		</td>
</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>