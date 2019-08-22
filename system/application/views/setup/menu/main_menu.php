<?php
$this->load->view('header'); 
$this->load->view('space');?>

<table cellspacing="3" cellpadding="3" align = 'center' border='2'>
<tr>
<td>
<a href="<?=base_url();?>index.php/setup/menu/addroot"><b>Add Root</b></a>
</td>
<td>
<a href="<?=base_url();?>index.php/setup/menu/addsubs"><b>Add Sub Menu</b></a>
</td>
<td>
<a href="<?=base_url();?>index.php/setup/menu/delroot"><b>Delete Root / Menu</b></a>
</td>
<td>
<a href="<?=base_url();?>index.php/setup/menu/delsubs"><b>Delete Sub Menu</b></a>
</td>
<td>
<a href="<?=base_url();?>index.php/setup/menu/delsubs"><b>Delete Sub Menu</b></a>
</td>
<!--
<td>
<a href="<?=base_url();?>index.php/setup/menu/editmenu"><b>Edit</b></a>
</td>-->
</tr>
</table>
<?php
$this->load->view('footer'); ?>