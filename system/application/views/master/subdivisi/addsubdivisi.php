<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'subdivisi',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('subdivisi')">
<table align = 'center'>
<form method='post' name="subdivisi" id="subdivisi" action='<?=base_url();?>index.php/master/subdivisi/save_new_subdivisi'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="5" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Divisi</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="master" name="master" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($master);$a++){
		 	$select = "";
		 	if($master1==$master[$a]['KdDivisi']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$master[$a]['KdDivisi']?>"><?=$master[$a]['NamaDivisi']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','subdivisi','Kode Sub Divisi','Nama Sub Divisi');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/subdivisi/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>