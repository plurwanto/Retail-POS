<?php
$this->load->view('header');
$this->load->view('space');
?>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<form method="post" id="permisssion" name="permisssion" action="<?=base_url()?>index.php/master/userpermission/save_permission">
<table align = 'center' border='1' class='table_class_list'>
	<tr>
		<th>Menu</th>
		<th>Add <input type="checkbox" name="addAll" id="addAll" <?=$cekaddAll;?> onclick="SetChecked('addAll', 'add')"></th>
		<th>Edit <input type="checkbox" name="editAll" id="editAll" <?=$cekeditAll;?> onclick="SetChecked('editAll', 'edit')"></th>
		<th>Delete <input type="checkbox" name="delAll" id="delAll" <?=$cekdelAll;?> onclick="SetChecked('delAll', 'del')"></th>
		<th>View <input type="checkbox" name="viewAll" id="viewAll" <?=$cekviewAll;?> onclick="SetChecked('viewAll', 'view')"></th>
	</tr>
<?php
for($a = 0;$a<count($userpermissiondata);$a++)
{
 	$cekadd="";
 	$cekedit="";
 	$cekdel="";
 	$cekview="";
 	if($userpermissiondata[$a]['add']=="Y"){$cekadd="checked"; }
 	if($userpermissiondata[$a]['edit']=="Y"){$cekedit="checked"; }
 	if($userpermissiondata[$a]['delete']=="Y"){$cekdel="checked"; }
 	if($userpermissiondata[$a]['view']=="Y"){$cekview="checked"; }
?>
	<tr>
		<td nowrap><?=$userpermissiondata[$a]['tablename'];?></td>
		<td align="center"><input type="checkbox" name="add" id="add" <?=$cekadd;?> /></td>
		<td align="center"><input type="checkbox" name="edit" id="edit" <?=$cekedit;?> /></td>
		<td align="center"><input type="checkbox" name="del" id="del" <?=$cekdel;?> /></td>
		<td align="center"><input type="checkbox" name="view" id="view" <?=$cekview;?> /></td>
		<input type="hidden" name="nama" id="nama" value="<?=$userpermissiondata[$a]['tablename'];?>"/>
	<tr>
<?php
}
?>
</table>
<input type="text" name="namahidden" id="namahidden"/>
<input type="text" name="addhidden" id="addhidden"/>
<input type="text" name="edithidden" id="edithidden"/>
<input type="text" name="delhidden" id="delhidden"/>
<input type="text" name="viewhidden" id="viewhidden"/>
<input type="text" name="id" id="id" value="<?=$id;?>"/>
<table align = 'center'>
	<tr>
	<td colspan="4" align="right"><input type="button" value="Save" name="save" onclick="setpermission()"; />
	<input type="button" value="Back" name="back" onclick=parent.location="<?=base_url();?>index.php/master/userlevel/" /></td>
	</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>