<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'kodepos',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('kodepos')">
<table align = 'center'>
<form method='post' name="kodepos" id="kodepos" action='<?=base_url();?>index.php/master/kodepos/save_new_kodepos'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode Pos</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="6" size="8" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama Kode Pos</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="40" size="45" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Area</td>
		<td nowrap>:</td>
		<td nowrap>
			<select size="1" id="area" name="area" onchange="get_sub_area('<?=base_url()?>');" <?=$gantikursor;?>>
			<option value="not selected">==>  Please Select  <==</option>
		<?php
			for($s = 0;$s<count($masterarea);$s++)
			{			 	
			?>
			<option <?php if($masterarea[$s]['KdArea']==$area) echo "selected"; ?> value="<?=$masterarea[$s]['KdArea'];?>"><?=$masterarea[$s]['Keterangan']?></option>
			<?php
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td nowrap>Sub Area</td>
		<td nowrap>:</td>
		<td nowrap>
			<select size="1" id="subarea" name="subarea" <?=$gantikursor;?>>
			<?=$subarea;?>
			</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cek_kodepos()"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kodepos/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>