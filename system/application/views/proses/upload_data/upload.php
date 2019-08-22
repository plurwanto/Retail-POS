<?php
$this->load->view('header');
$this->load->view('space');
?>
//view
<form action="<?php echo base_url()?>index.php/proses/downloadD/upload_csv" enctype="multipart/form-data" method="post">
ID_anggota :<input name="filename" size="17" type="file">
<input type="submit" value="Upload">
</form>

<?php
$this->load->view('footer');
?>