<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'opname',this)\"";
?>
<script language="javascript" src="<?= base_url(); ?>public/js/global.js"></script>
<script language="javascript" src="<?= base_url(); ?>public/js/jquery.js"></script>
<script language="javascript" src="<?= base_url(); ?>public/js/opname.js"></script>
<script>
    function konfirm() {
        x = confirm("Anda akan memproses opname ? ");
        if (x) {
            $("#Layer1").attr("style", "display:");
            $("#opname").submit();
        }
    }

</script>

<style type="text/css">
    <!--
    #Layer1 {
        position:absolute;
        left:45%;
        top:40%;
        width:0px;
        height:0px;
        z-index:1;
        background-color:#FFFFFF;
    }
    -->
</style>
<body >
    <form method='post' name="opname" id="opname" action='<?= base_url(); ?>index.php/transaksi/proses_opname/ProsesToOpname' onsubmit="return false">
        <table align="center" >
            <tr>
                <td>
                    <fieldset class="disableMe">
                        <legend class="legendStyle">Catatan</legend>
                        <table>
                            <tr>
                                <td> => </td>
                                <td>Jika Ingin Edit Opname masuk ke menu Transaksi -> Opname</td>
                            </tr>

                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>

        <table align = 'center' >
            <tr>
                <td>
                    <fieldset class="disableMe">
                        <legend class="legendStyle">Proses Opname</legend>
                        <table class="table_class_list">
                            <?php
                            $mylib = new globallib();
                            echo $mylib->write_textbox("No", "nodok", $header->NoDokumen, "10", "10", "readonly='readonly'", "text", $gantikursor, "1");
                            echo $mylib->write_textbox("Tgl Opname", "tgl", $header->TglDokumen, "10", "10", "readonly='readonly'", "text", $gantikursor, "1");
                            echo $mylib->write_textbox("Keterangan", "ket", $header->Keterangan, "35", "30", "", "text", $gantikursor, "1");
                            ?>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset class = "disableMe">
                        <legend class="legendStyle">Detail</legend>
                        <div id="Layer1" style="display:none">
                            <p align="center">
                                <img src='<?= base_url(); ?>public/images/ajax-loader.gif'>
                            </p>
                        </div>
                        <table class="table_class_list" id="detail">
                            <tr id="baris0">
                                <td>KdBarang</td>
                                <td>NamaBarang</td>
                                <td>Qty Komp</td>
                                <td>Qty Opname</td>
                                <td>Selisih Opname</td>
                            </tr>
                            <?php
                            $modeller = new opnamemodel();
                            for ($a = 0; $a < count($detail); $a++) {
                                $pcode = $detail[$a]['PCode'];
                                $nama = $detail[$a]['NamaInitial'];
                                $qtykom = $detail[$a]['QtyKomputer'];
                                $qtyop = $detail[$a]['QtyOpname'];
                                $selisih = $detail[$a]['Selisih'];
                                detailThis((int) $a + 1, $pcode, $nama, $qtykom, $qtyop, $selisih);
                            }
                            //detailThis((int)$a+1,"","","","",""); untuk menambah baris
                            ?>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td nowrap>
                    <input type='hidden' id="no" name="no" value="no">
                    <input type='hidden' id="flag" name="flag" value="edit">
                    <input type='hidden' value='<?= base_url() ?>' id="baseurl" name="baseurl">
                    <input type='button' value='Proses' onclick="konfirm();"/>
                    <input type="button" value="Back" id="back" name="back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/proses_opname/" />
                </td>
            </tr>
        </table>
    </form>
</body>
    <?php
    //$this->load->view('footer');

    function detailThis($counter, $pcode, $nama, $qtykom, $qtyop, $selisih) {
        ?>
    <tr id="baris<?= $counter ?>">
        <td nowrap><input type="text" id="pcode<?= $counter ?>" name="pcode[]" size="25" maxlength="20" value="<?= $pcode ?>"> </td>
        <td nowrap><input type="text" id="nama<?= $counter ?>" name="nama[]" size="25" readonly="readonly" value="<?= $nama ?>"></td>
        <td nowrap><input type="text" id="qtykom<?= $counter ?>" name="qtykom[]" size="15" readonly="readonly" value="<?= $qtykom ?>"></td>
        <td nowrap><input type="text" id="qty<?= $counter ?>" name="qty[]" size="15" maxlength="11" readonly="readonly" value="<?= $qtyop ?>"></td>
        <td nowrap><input type="text" id="sisa<?= $counter ?>" name="sisa[]" size="15" maxlength="11" readonly="readonly" value="<?= $selisih ?>"></td>
        <td nowrap><input type="hidden" id="pcodesave<?= $counter ?>" name="pcodesave[]" size="5" maxlength="11" readonly="readonly" value="<?= $pcode ?>"></td>
    </tr>
    <?php
}
?>