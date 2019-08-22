<?php
$this->load->view('header');
$this->load->view('space');
$searchby = $this->input->post('stSearchingKey');
?>
<script language="javascript" src="<?= base_url(); ?>public/js/jquery.js"></script>
<script language="javascript" src="<?= base_url(); ?>public/js/ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/css/ui.datepicker.css" />
<script language="javascript">
    function gantiSearch()
    {
        if ($("#searchby").val() == "NoDokumen")
        {
            $("#normaltext").css("display", "");
            $("#datetext").css("display", "none");
            $("#date1").datepicker("destroy");
            $("#date1").val("");
        }
        else
        {
            $("#datetext").css("display", "");
            $("#normaltext").css("display", "none");
            $("#stSearchingKey").val("");
            $("#date1").datepicker({dateFormat: 'dd-mm-yy', showOn: 'button', buttonImageOnly: true, buttonImage: '<?php echo base_url(); ?>/public/images/calendar.png'});
        }
    }
    function PopUpPrint(kode, baseurl)
    {
        url = "index.php/transaksi/opname/cetak/" + escape(kode);
        window.open(baseurl + url, 'popuppage', 'scrollbars=yes, width=900,height=500,top=50,left=50');
    }
</script>
<form method="POST"  name="search" action="">
    <table align='center'>
        <tr>
            <td id="normaltext" style=""><input type='text' size='15' name='stSearchingKey' id='stSearchingKey' value="<?php echo @$_POST['stSearchingKey'] ?>"></td>
            <td id="datetext" style="display:none"><input type='text' size='10' readonly='readonly' name='date1' id='date1'></td>
            <td>
                <select size="1" height="1" name ="searchby" id ="searchby" onchange="gantiSearch()">
                    <option <?php if ($searchby=="NoDokumen") echo "Selected='selected'" ;?> value="NoDokumen">No Dokumen</option>
                    <option value="TglDokumen">Tgl Opname</option>
                </select>
            </td>
            <td><input type="submit" value="Search (*)"></td>
        </tr>
    </table>
</form>
<br>
<table align = 'center' border='1' class='table_class_list'>
    <tr>
        <?php
        if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
            ?>
            <th></th>
            <?php
        }
        $mylib = new globallib();
        echo $mylib->write_header($header);
        ?>
    </tr>
    <?php
    if (count($data) == 0) {
        ?>
        <td nowrap colspan="<?php echo count($header) + 1; ?>" align="center">Tidak Ada Data</td>
        <?php
    }
    for ($a = 0; $a < count($data); $a++) {
        ?>
        <tr>
            <?php
            if ($link->edit == "Y" || $link->delete == "Y") {
                ?>
                <td nowrap>
                    <?php
                    if ($link->view == "Y") {
                        ?>
                        <img src='<?= base_url(); ?>public/images/printer.png' border = '0' title = 'Print' onclick="PopUpPrint('<?= $data[$a]['NoDokumen']; ?>', '<?= base_url(); ?>');"/></a>
                        <?php
                    }
                    if ($link->view == "Y" && $data[$a]['FlagProses'] == "0") {
                        ?>
                        <a 	href="<?= base_url(); ?>index.php/transaksi/proses_opname/proses_edit_opname/<?= $data[$a]['NoDokumen']; ?>"><img src='<?= base_url(); ?>public/images/accept.png' border = '0' title = 'Proses'/></a>
                        <?php
                    }
                    ?>
                </td>
            <?php } ?>
            <td nowrap><?= $data[$a]['NoDokumen']; ?></td>
            <td nowrap><?= $data[$a]['Tanggal']; ?></td>
            <td nowrap><?= stripslashes($data[$a]['Keterangan']); ?></td>
        <tr>
            <?php
        }
        ?>
</table>
<table align = 'center'  >
    <tr>
        <td>
            <?php echo $this->pagination->create_links(); ?>
        </td>
    </tr>

</table>
<?php $this->load->view('footer'); ?>