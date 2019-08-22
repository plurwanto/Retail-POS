<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'subsize',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('subsize')">
<table align = 'center'>
<form method='post' name="subsize" id="subsize" action='<?=base_url();?>index.php/master/subsize/save_subsize'>
<?php
	$mylib = new globallib(); 
	echo $mylib->write_textbox("Kode","kode",$viewsubsize->KdSubSize,"5","4","readonly","text",$gantikursor);
	echo $mylib->write_textbox("Ukuran","nama",$viewsubsize->Ukuran,"35","20","","text",$gantikursor);
	echo $mylib->write_textbox("Total Ukuran","realsize",$viewsubsize->NumericSize,"15","11","","text",$gantikursor);
	?>
	<tr>
		<td nowrap>Size</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="master" name="master" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($master);$a++){
		 	$select = "";
		 	if($viewsubsize->KdSize==$master[$a]['KdSize']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$master[$a]['KdSize']?>"><?=$master[$a]['NamaSize']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="ceksubsize();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/subsize/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>