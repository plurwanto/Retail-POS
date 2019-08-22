var i = $('table tr').length;
var base_url = $('#baseurl').val();
var save_method;
function keyShortcut(e, flag, obj) {
    //var e = window.event;
    if (window.event) // IE
    {
        var code = e.keyCode;
    }
    else if (e.which) // Netscape/Firefox/Opera
    {
        var code = e.which;
    }
    if (code == 13) { //checks for the escape key
        objek = obj.id;
        if (flag == 'pcode') {
            id = parseFloat(objek.substr(5, objek.length - 5));
            setTimeout("findPCode(" + id + ")", 1);
        }
        else if (flag == 'qty') {
            id = parseFloat(objek.substr(3, objek.length - 3));
//			InputQty(id,'enter');
            tambahbaris('dataTable');
        }
        else if (flag == 'satuan') {
            id = parseFloat(objek.substr(8, objek.length - 8));
            $("#qty" + id).focus();
        }
    }
}

// barang detail on auto_complete
$(document).on('focus', '.autocomplete_txt', function () {
    $(this).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'index.php/transaksi/opname/getBarangByName',
                dataType: "json",
                method: 'post',
                data: {
                    name_startsWith: request.term
                            //type: type
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        var code = item.split("|");
                        if (code[5] == true) {
                            $('#itemNo_0').val(code[1]);
                            $('#itemName_0').val(code[2]);
                            $('#qty_Komp_0').val(code[3]);
                            $('#qty_Opname_0').val(code[3]);
                            $('#HJ_0').val(code[4]);
                            $('#sisa_0').val(0);
                            $('#qty_Opname_0').focus();
                        }
                        return {
                            label: code[0],
                            value: code[1],
                            data: item
                        }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 2,
        select: function (event, ui) {
            var names = ui.item.data.split("|");
            id_arr = $(this).attr('id');
            id = id_arr.split("_");
            $('#itemNo_' + id[1]).val(names[1]);
            $('#itemName_' + id[1]).val(names[2]);
            $('#qty_Komp_' + id[1]).val(names[3]);
            $('#qty_Opname_' + id[1]).val(names[3]);
            $('#HJ_' + id[1]).val(names[4]);
            $('#sisa_' + id[1]).val(0);
            $('#qty_Opname_' + id[1]).focus();

        }
    });
});

$('#qty_Opname_0').on('keypress', function (e) {
   // e.preventDefault();
    var url;
    var nodok = $('#nodok').val();
    url = base_url + 'index.php/transaksi/opname/ajax_add';

    if (e.which === 13) {
        $('#itemNo_0').focus(); // biar gak duplikat entry
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $('#opname').serialize(),
            success: function (response) {
                if (response.success == true) {
                    $('#nodok').val(response.nodokumen);
                    reload_table();
                } else {
                    $.each(response.messages, function (key, value) {
                        var element = $('#' + key);
                        element.closest('div.err')
                                .removeClass('has-error')
                                .addClass(value.length > 0 ? 'has-error' : '')
                                .find('.text-danger')
                                .remove();
                        element.after(value);
                    });
                    if ($('#ket').val() == "") {
                        $('#ket').focus();
                    }else if ($('#itemNo_0').val() == "") {
                        $('#itemNo_0').focus();
                    } else if ($('#qty_Opname_0').val() == "") {
                        $('#qty_Opname_0').focus();
                    }
                }
            }
        });
    }
});

function reload_table() {
    var no = $('#nodok').val();
    $.ajax({
        url: base_url + 'index.php/transaksi/opname/ajax_list/' + no,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (no != "") {
                for (var i = 0; i < response.length; i++) {
                    x = i + 1;
                    $('table#barangtable tr#baris' + x).remove();
                    html = '<tr id="baris' + x + '" class="clickable-row">';
                    html += '<td width="3%">' + x + '</td>';
                    html += '<td width="20%" name="pcode[]" id="pcode' + x + '">' + response[i].PCode + '<input type="hidden" name="pcode_temp[]" id="pcode_temp' + x + '" value="' + response[i].PCode + '"></td>';
                    html += '<td width="40%" name="nama[]" id="nama' + x + '">' + response[i].NamaLengkap + '</td>';
                    html += '<td width="15%" class="text-right" name="qtykomp[]" id="qtykomp' + x + '">' + response[i].QtyKomputer + '</td>';
                    html += '<td width="15%" class="text-right" name="qtyopname[]" id="qtyopname' + x + '">' + response[i].QtyOpname + '</td>';
                    html += '<td width="15%" class="text-right" name="selisih[]" id="selisih' + x + '">' + response[i].Selisih + '</td>';
                    html += '<td width="2%"><div class="action-buttons"><a id="btndel' + x + '" class="red" title="Delete" onclick="delete_item(' + x + ')" style="cursor: pointer;"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div></td>';
                    html += '</tr>';
                    $('#barangtable').append(html);
                }
                $('#btnsave').prop('disabled', false);
            }
            $('#itemNo_0').val("");
            $('#itemName_0').val("");
            $('#qty_Komp_0').val("");
            $('#qty_Opname_0').val("");
            $('#sisa_0').val("");
            $('#itemNo_0').focus();
            var element = $('#qty_Opname_0');
            element.closest('div.err')
                    .removeClass('has-error')
                    .find('.text-danger')
                    .remove();
        }
    });
}

function delete_item(rows)
{
    var no = $('#nodok').val();
    var kodebarang = $('#pcode_temp' + rows).val();
    var tbl = $('#barangtable tr').length;
    if (tbl == 1) {
        alert("Minimal harus ada 1 baris");
    } else {
        if (confirm('Kode barang ' + kodebarang + ' akan didelete?'))
        {
            $.ajax({
                url: base_url + 'index.php/transaksi/opname/ajax_delete/' + no + '/' + kodebarang,
                type: "POST",
                dataType: "JSON",
                success: function (response)
                {
                    if (response.success == true) {
                        $('table#barangtable tr#baris' + rows).remove();
                    }
                }
            });
        }
    }
}

function sum_selisih() {
    var qtyopname = $('#qty_Opname_0').val();
    var qtykomp = $('#qty_Komp_0').val();
    var total = qtyopname - qtykomp;
    $('#sisa_0').val(total);
}

$('#opname').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

function generateList() {
    base_url = $("#baseurl").val();
    $.post(base_url + "index.php/transaksi/opname/getlistBarang", {},
            function (data) {
                if (data != "")
                {
                    //alert(data.length);
                    sp = data.split("##");
                    ar = sp[1].split("**");
                    alert(ar[0]);
                    id = 1;
                    for (by = 0; by < sp[0]; by++) {

                        tambahbaris('dataTable');
                        det = ar[by].split("||");
                        document.getElementById('pcode' + id).value = det[2];
                        document.getElementById('nama' + id).value = det[1];
                        document.getElementById('qtykom' + id).value = det[0];
                        document.getElementById('qty' + id).value = det[0];
                        document.getElementById('sisa' + id).value = 0;
                        document.getElementById('HJ' + id).value = det[3];
                        id++;
                    }
                }

            })
}
//
//function simpanLokasi()
//{
//    resetRow(1);
//    $("#ket").focus();
//    $("#pcode1").val("");
//}
//
//function pickThis(obj)
//{
//    base_url = $("#baseurl").val();
////        alert(base_url);
//    objek = obj.id;
//    id = parseFloat(objek.substr(4, objek.length - 4));
//    url = base_url + "index.php/pop/opnamebrg/index/" + id;
//    window.open(url, 'popuppage', 'scrollbars=yes,width=750,height=400,top=200,left=150');
//}
//
//function openThis(data)
//{
//    alert(data);
//    data_split = data.split("||");
//    pcodebarangnya = data_split[0];
//    jenis_satnya = data_split[1];
//    base_url = $("#baseurl").val();
////	url = base_url+"index.php/pop/opnamelokasiattr/index/"+lokasi+"/"+id+"/"+pcode+"/"+pcodebarangnya+"/"+jenis_satnya+"/";
////	window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
//}
//
//function findPCode(id)
//{
////    alert(id);
//    if (cekoption("pcode" + id, "Memasukkan Kode Barang")) {
//        base_url = $("#baseurl").val();
//        pcode = $("#pcode" + id).val();
//        $.post(base_url + "index.php/transaksi/opname/getRealPCode", {pcode: pcode},
//        function (datakode) {
////                                resetRow(id);
//            if (datakode != "") {
//                $("#tmppcode" + id).val(pcode);
//                $.post(base_url + "index.php/transaksi/opname/getPCode", {pcode: pcode},
//                function (data) {
//                    if (data != "")
//                    {
//                        data_split = data.split("||");
////									openThis(data);
//                        document.getElementById('pcode' + id).value = data_split[2];
//                        document.getElementById('nama' + id).value = data_split[1];
//                        document.getElementById('qtykom' + id).value = data_split[0];
//                        document.getElementById('qty' + id).value = data_split[0];
//                        document.getElementById('sisa' + id).value = 0;
//                        document.getElementById('HJ' + id).value = data_split[3];
//                        document.getElementById('qty' + id).focus();
//                    }
//                    else
//                    {
//                        alert("Data Tidak Ditemukan");
//                        resetRow(id);
//                        $("#pcode" + id).focus();
//                    }
//                });
//            }
//            else
//            {
//                alert("Data Tidak Ditemukan");
//                resetRow(id);
//                $("#pcode" + id).focus();
//            }
//        });
//    }
//    else
//    {
//        resetRow(id);
//        $("#pcode" + id).focus();
//    }
//}
//function resetRow(id)
//{
//    $("#pcode" + id).val("");
//    $("#pcode" + id).focus();
//    $("#nama" + id).val("");
//    $("#satuankom" + id).empty();
//    $("#qtykom" + id).val("");
//    $("#satuanop" + id).empty();
//    $("#qty" + id).val("");
//    $("#tmppcode" + id).val("");
//    $("#tempqty" + id).val("");
//    $("#qtypcs" + id).val("");
//    $("#konverbk" + id).val("");
//    $("#konvertk" + id).val("");
//    $("#attr" + id).val("");
//    $("#savepcode" + id).val("");
//    $("#kdsatuankom" + id).val("");
//    $("#pcodebarang" + id).val("");
//    $("#noterima" + id).val("");
//    $("#counterterima" + id).val("");
//    $("#asaldata" + id).val("");
//}
//
//function openAttr(obj)
//{
//    objek = obj.id;
//    id = parseFloat(objek.substr(6, objek.length - 6));
//    if (cekoption("pcodebarang" + id, "Memasukkan Kode Barang")) {
//        base_url = $("#baseurl").val();
//        pcode = $("#pcodebarang" + id).val();
//        url = base_url + "index.php/pop/isi_attr/index/";
//        child = window.open(url + id + "/lihat/" + pcode + "/", 'popuppage', 'scrollbars=yes,width=550,height=500,top=180,left=150');
//    }
//}
//
//function changeSatuanOp(obj)
//{
//    objek = obj.id;
//    id = parseFloat(objek.substr(8, objek.length - 8));
//    if ($("#satuanop" + id).val() != "") {
//        if ($("#qty" + id).val() != "") {
//            InputQty(id, "enter");
//        }
//    }
//    else
//    {
//        $("#tempqty" + id).val("");
//        $("#qty" + id).val("");
//        $("#qty" + id).focus();
//    }
//}
//
//function InputQty(id, from)
//{
//    if (cekoption("pcode" + id, "Memasukkan Kode Barang"))
//    {
//        if (validateForm("pcode" + id, "tmppcode" + id, "Kode Barang")) {
//            if (cekoption("satuanop" + id, "Memilih Satuan"))
//            {
//                if (cekAngkaPas("qty" + id, "Qty", "no zero", "no minus"))
//                {
//                    convert(id);
//                    if (from == "enter") {
//                        saveThis(id);
//                    }
//                }
//            }
//        }
//    }
//    else
//    {
//        resetRow(id);
//        $("#pcode" + id).focus();
//    }
//}
//
//function convert(id)
//{
//    var qty = parseInt($("#qty" + id).val());
//    satuan = $("#satuanop" + id).val().split("|");
//    ;
//    SatuanFlg = satuan[0];
//    if (SatuanFlg == "B")
//    {
//        qty = parseFloat($("#konverbk" + id).val()) * parseFloat(qty);
//    }
//    else if (SatuanFlg == "T")
//    {
//        qty = parseFloat($("#konvertk" + id).val()) * parseFloat(qty);
//    }
//    else if (SatuanFlg == "K")
//    {
//        qty = qty;
//    }
//    $("#qtypcsop" + id).val(qty);
//    $("#tempqty" + id).val(qty);
//}
//
//function saveThis(id)
//{
//    if (cekDetail(id)) {
//        $("#Layer1").css("display", "");
//        $('fieldset.disableMe :input').attr('disabled', true);
//        saveItem(id);
//    }
//}
//
//function saveAll()
//{
//    $("#opname").submit();
//}
//
//function Inputhrg(obj)
//{
//    objek = obj.id;
////        alert(id);
//    id = parseFloat(objek.substr(3, objek.length - 3));
//    qtykom = $("#qtykom" + id).val();
//    qty = $("#qty" + id).val();
//    ttl = qty - qtykom;
//    $("#sisa" + id).val(ttl);
//}
//
//function cekDetail(id)
//{
//    if (cekoption("pcode" + id, "Memasukkan Kode Barang"))
//        if (validateForm("pcode" + id, "tmppcode" + id, "Kode Barang"))
//            if (cekoption("qty" + id, "Memasukkan Qty Opname"))
//                if (validateForm2("qtypcsop" + id, "tempqty" + id, "qty" + id, "Qty Opname"))
//                    return true;
//    return false;
//}
//
//function cekDetailAll()
//{
//    var lastRow = document.getElementsByName("pcode[]").length;
//    for (index = 0; index < lastRow; index++) {
//        nama = document.getElementsByName("pcode[]");
//        temp = nama[index].id;
//        indexs = temp.substr(5, temp.length - 5);
//        if (index < parseFloat(lastRow) - 1 || index == 0) {
//            if (cekoption("pcode" + indexs, "Memasukkan Kode Barang"))
//                if (cekoption("qty" + indexs, "Memasukkan Opname"))
//                {
//                    InputQty(indexs, 'cek');
//                    continue;
//                }
//            return false;
//        }
//        else if (index == parseFloat(lastRow) - 1)
//        {
//            if ($("#pcode" + indexs).val() == "" && $("#qty" + indexs).val() == "")
//            {
//                continue;
//            }
//            else
//            {
//                if (cekoption("pcode" + indexs, "Memasukkan Kode Barang"))
//                    if (cekoption("qty" + indexs, "Memasukkan Jumlah Barang"))
//                    {
//                        InputQty(indexs, 'cek');
//                        continue;
//                    }
//                return false;
//            }
//        }
//    }
//    return true;
//}
//
//function saveItem(id)
//{
//    if ($("#transaksi").val() == "no") {
//        $("#transaksi").val("yes");
//        no = $("#nodok").val();
//        tgl = $("#tgl").val();
//        lokasi = $("#hidelokasi").val();
//        keterangan = $("#ket").val();
//        flag = $("#flag").val();
//        pcode = $("#pcode" + id).val();
//        satuankom = $("#kdsatuankom" + id).val();
//        qtykom = $("#qtykom" + id).val();
//        satuanop = $("#satuanop" + id).val();
//        qty = $("#qty" + id).val();
//        counter = $("#counter" + id).val();
//        qtypcsop = $("#qtypcsop" + id).val();
//        qtypcskom = $("#qtypcskom" + id).val();
//        konverbk = $("#konverbk" + id).val();
//        konvertk = $("#konvertk" + id).val();
//        attr = $("#attr" + id).val();
//        savepcode = $("#savepcode" + id).val();
//        pcodebarang = $("#pcodebarang" + id).val();
//        noterima = $("#noterima" + id).val();
//        counterterima = $("#counterterima" + id).val();
//        asaldata = $("#asaldata" + id).val();
//        base_url = $("#baseurl").val();
//        $.post(base_url + "index.php/transaksi/opname/save_new_item", {
//            flag: flag, no: no, tgl: tgl, lokasi: lokasi, ket: keterangan, pcode: pcode,
//            satuankom: satuankom, qtykom: qtykom, satuanop: satuanop, qty: qty,
//            counter: counter, qtypcsop: qtypcsop, qtypcskom: qtypcskom,
//            konverbk: konverbk, konvertk: konvertk, attr: attr,
//            savepcode: savepcode, pcodebarang: pcodebarang, noterima: noterima,
//            counterterima: counterterima, asaldata: asaldata
//        },
//        function (data) {
//            split_data = data.split("**");
//            if (flag == "add")
//            {
//                $("#nodok").val(split_data[0]);
//                $("#nodokumen").css("display", "");
//            }
//            $("#counter" + id).val(split_data[1]);
//            $("#savepcode" + id).val($("#pcode" + id).val());
//            $('fieldset.disableMe :input').attr('disabled', false);
//            var lastRow = document.getElementsByName("pcode[]").length - 1;
//            nama = document.getElementsByName("pcode[]");
//            temp = nama[lastRow].id;
//            indexs = temp.substr(5, temp.length - 5);
//            $("#lokasi").attr("disabled", true);
//            if ($("#savepcode" + indexs).val() != "") {
//                detailNew();
//            }
//            $("#Layer1").css("display", "none");
//            $("#transaksi").val("no");
//        });
//    }
//}
//
//function AddNew()
//{
//    var lastRow = document.getElementsByName("pcode[]").length - 1;
//    nama = document.getElementsByName("pcode[]");
//    temp = nama[lastRow].id;
//    indexs = temp.substr(5, temp.length - 5);
//    if (cekDetail(indexs)) {
//        saveItem(indexs);
//    }
//}
//
//function detailNew()
//{
//    var clonedRow = $("#detail tr:last").clone(true);
//    var intCurrentRowId = parseFloat($('#detail tr').length) - 2;
//    nama = document.getElementsByName("pcode[]");
//    temp = nama[intCurrentRowId].id;
//    intCurrentRowId = temp.substr(5, temp.length - 5);
//    var intNewRowId = parseFloat(intCurrentRowId) + 1;
//    $("#pcode" + intCurrentRowId, clonedRow).attr({"id": "pcode" + intNewRowId, "value": ""});
//    $("#pick" + intCurrentRowId, clonedRow).attr({"id": "pick" + intNewRowId});
//    $("#del" + intCurrentRowId, clonedRow).attr({"id": "del" + intNewRowId});
//    $("#nama" + intCurrentRowId, clonedRow).attr({"id": "nama" + intNewRowId, "value": ""});
//    $("#satuankom" + intCurrentRowId, clonedRow).attr({"id": "satuankom" + intNewRowId});
//    $("#qtykom" + intCurrentRowId, clonedRow).attr({"id": "qtykom" + intNewRowId, "value": ""});
//    $("#satuanop" + intCurrentRowId, clonedRow).attr({"id": "satuanop" + intNewRowId});
//    $("#HJ" + intCurrentRowId, clonedRow).attr({"id": "HJ" + intNewRowId});
//    $("#qty" + intCurrentRowId, clonedRow).attr({"id": "qty" + intNewRowId, "value": ""});
//    $("#btnatr" + intCurrentRowId, clonedRow).attr({"id": "btnatr" + intNewRowId});
//    $("#counter" + intCurrentRowId, clonedRow).attr({"id": "counter" + intNewRowId, "value": ""});
//    $("#tmppcode" + intCurrentRowId, clonedRow).attr({"id": "tmppcode" + intNewRowId, "value": ""});
//    $("#tempqty" + intCurrentRowId, clonedRow).attr({"id": "tempqty" + intNewRowId, "value": ""});
//    $("#qtypcsop" + intCurrentRowId, clonedRow).attr({"id": "qtypcsop" + intNewRowId, "value": ""});
//    $("#qtypcskom" + intCurrentRowId, clonedRow).attr({"id": "qtypcskom" + intNewRowId, "value": ""});
//    $("#savepcode" + intCurrentRowId, clonedRow).attr({"id": "savepcode" + intNewRowId, "value": ""});
//    $("#pcodebarang" + intCurrentRowId, clonedRow).attr({"id": "pcodebarang" + intNewRowId, "value": ""});
//    $("#noterima" + intCurrentRowId, clonedRow).attr({"id": "noterima" + intNewRowId, "value": ""});
//    $("#detail").append(clonedRow);
//    $("#detail tr:last").attr("id", "baris" + intNewRowId); // change id of last row
//    $("#pcode" + intNewRowId).focus();
//}
//
//function deleteRow(obj)
//{
//    objek = obj.id;
//    id = objek.substr(3, objek.length - 3);
//    pcode = $("#pcode" + id).val();
//    var counter = $("#counter" + id).val();
//    var banyakBaris = 1;
//    var lastRow = document.getElementsByName("pcode[]").length;
//    for (index = 0; index < lastRow; index++) {
//        nama = document.getElementsByName("pcode[]");
//        temp = nama[index].id;
//        indexs = temp.substr(5, temp.length - 5);
//        if ($("#savepcode" + indexs).val() != "") {
//            banyakBaris++;
//        }
//    }
//
//    if (banyakBaris == 2)
//    {
//        alert("Baris ini tidak dapat dihapus\nMinimal harus ada 1 baris tersimpan");
//    }
//    else
//    {
//        no = $("#nodok").val();
//        if (pcode != "") {
//            var r = confirm("Apakah Anda Ingin Menghapus Kode Barang " + pcode + " ?");
//            if (r == true) {
//                $('#tr' + id).remove();
//            }
//        }
//    }
//}
//
//function tambahbaris(tableID) {
//
//    var clonedRow = $("#" + tableID + " tr:last").clone(true);
//    var intCurrentRowId = parseFloat($('#detail tr').length) - 2;
//    nama = document.getElementsByName("pcode[]");
//    tm = nama[intCurrentRowId].id;
//    intCurrentRowId = tm.substr(5, tm.length - 5);
//    var intNewRowId = parseFloat(intCurrentRowId) + 1;
//    $("#del" + intCurrentRowId, clonedRow).attr({"id": "del" + intNewRowId, "value": ""});
//    $("#pick" + intCurrentRowId, clonedRow).attr({"id": "pick" + intNewRowId});
//    $("#pcode" + intCurrentRowId, clonedRow).attr({"id": "pcode" + intNewRowId, "value": ""});
//    $("#nama" + intCurrentRowId, clonedRow).attr({"id": "nama" + intNewRowId, "value": ""});
//    $("#qty" + intCurrentRowId, clonedRow).attr({"id": "qty" + intNewRowId, "value": ""});
//    $("#sisa" + intCurrentRowId, clonedRow).attr({"id": "sisa" + intNewRowId, "value": ""});
//    $("#HJ" + intCurrentRowId, clonedRow).attr({"id": "HJ" + intNewRowId, "value": ""});
//    $("#nil" + intCurrentRowId, clonedRow).attr({"id": "nil" + intNewRowId, "value": ""});
//    $("#qtydisplay" + intCurrentRowId, clonedRow).attr({"id": "qtydisplay" + intNewRowId, "value": ""});
//    $("#tempqty" + intCurrentRowId, clonedRow).attr({"id": "tempqty" + intNewRowId, "value": ""});
//    $("#qtypcs" + intCurrentRowId, clonedRow).attr({"id": "qtypcs" + intNewRowId, "value": ""});
//    $("#qtykom" + intCurrentRowId, clonedRow).attr({"id": "qtykom" + intNewRowId, "value": ""});
//    $("#detail").append(clonedRow);
//    $("#detail tr:last").attr("id", "tr" + intNewRowId); // change id of last row
//    $("#pcode" + intNewRowId).focus();
//    return intNewRowId;
//}