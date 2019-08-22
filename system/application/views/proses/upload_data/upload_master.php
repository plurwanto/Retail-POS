<?php
$this->load->view('header');
$this->load->view('space');
?>
<script>
    function cek() {
        var elm = document.getElementById('filename');
        var FileUploadPath = elm.value;
        if (FileUploadPath == '') {
            alert("Masukan File Attachment");
        } else {
            var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();
            if (Extension == "csv") {
                document.getElementById('frmupload').submit();
            } else if (Extension == "") {
                return true;
            }
            else {
                alert("Ekstensi file tidak valid. File diperbolehkan: csv");
            }
        }
    }
</script>
<form id="frmupload" action="<?php echo base_url() ?>index.php/proses/download_master/upload_csv" enctype="multipart/form-data" method="post">
    <fieldset><legend>Download Master Barang</legend>
        <br>
        Masukan File: <input name="filename" size="17" type="file" id="filename" accept=".csv">
        <input type="button" value="Upload" onclick="cek();" id="btnupload">
        <p></p>
        <p></p>
        <?php
        if ($this->session->flashdata('msg')) {
            echo "<div class='alert'>";
            $mesage = $this->session->flashdata('msg');
            echo $mesage;

            echo "</div>";
        }
        ?>
    </fieldset>
</form>

<?php
$this->load->view('footer');
?>