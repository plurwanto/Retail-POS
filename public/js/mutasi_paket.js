function loading()
{
	base_url = $("#baseurl").val();
	$('#tglterima').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
}

function CatatLokasi()
{
	$("#lokasi").val($("#namalokasi").val())
}

function keyShortcut(e,flag,obj) {
	//var e = window.event;
	if(window.event) // IE
	{
		var code = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		var code = e.which;
	}
	if (code == 13) { //checks for the escape key
		objek = obj.id;//alert(objek);
		if(flag=='pcode'){
			id = parseFloat(objek.substr(5,objek.length-5));
//                        alert(pcode+id);
			findPCode(id);
		}
		else if(flag=='qty'){
			id = parseFloat(objek.substr(3,objek.length-3));
			InputQty(id,'enter');
		}
		else if(flag=='order'){
			sumber = $("input[@name='sumber']:checked").val();
			if(sumber=="O")
			{
				noorderan = $("#noorder").val();
				$("#hiddennoorder").val(noorderan);
				setTimeout("getOrder()",1);
			}
			else
			{
				$("#nokirim").focus();
			}
		}
		else if(flag=='kirim'){
			sumber = $("input[@name='sumber']:checked").val();
			if(sumber=="P")
			{
				nokirim = $("#nokirim").val();
				$("#hiddennokirim").val(nokirim);
				setTimeout("getKirim()",1);
			}
			else
			{
				if(sumber=="M"||sumber=="R"){
					$("#kontak").focus();
				}
				else
				{
					$("#kendaraan").focus();
				}
			}
		}
		else if(flag=='satuan'){
			id = parseFloat(objek.substr(6,objek.length-6));
			$("#qty"+id).focus();
		}
	}
}

function simpanKontak()
{
	kontak = $("#kontak").val();
	$("#hidecontact").val(kontak);
	resetRow(1);
	$("#pcode1").val("");
	$("#kendaraan").focus();
}

function simpanPerusahaan()
{
	$("#kontak").focus();
}

function resetRow(id)
{
	$("#pcode"+id).val("");
	$("#satuan"+id).empty();
	$("#tmppcode"+id).val("");
	$("#nama"+id).val("");
	$("#konverjk"+id).val("");
	$("#konverbk"+id).val("");
	$("#konvertk"+id).val("");
	$("#kdsatuanj"+id).val("");
	$("#satuanj"+id).val("");
	$("#qtydisplay"+id).val("");
	$("#tmpqty"+id).val("");
	$("#qty"+id).val("");
	$("#qtypcs"+id).val("");
	$("#pcodebarang"+id).val("");
	$("#satuan"+id).empty();
	$("#satuantmp"+id).val("");
}

function ubahsumber()
{
	sumber = $("input[@name='sumber']:checked").val();
	$("#hiddensumber").val(sumber);
	if(sumber=="M")
	{
		$("#btnorder").attr("disabled", "disabled");
	}
	else if(sumber=="O")
	{
		$("#btnorder").attr("disabled", "");
	}
//	ubahkontak(sumber);
}

function ubahkontak(sumber)
{
	base_url = $("#baseurl").val();
	$.post(base_url+"index.php/transaksi/penerimaan_barang/carikontak",{ sumber:sumber},
	function(data){
		data_kontak = data.split("^_^");
		$("#kontak").empty();
		$("#kontak").append("<option value=''>--Please Select--</option>");
		$("#kontak").append(data);
	});
}

function getOrder()
{
	if(cekoption("noorder","Memasukkan No Order")){
			noorderan = $("#noorder").val();
			base_url = $("#baseurl").val();
			$.post(base_url+"index.php/transaksi/penerimaan_barang/getsumber",{ order:noorderan,kirim:""},
			function(data){
				if(data=="+")
				{
					alert("No Order Tidak Ditemukan\nPeriksa Kembali No Order");
					$("#noorder").focus();
					$("#hiddennoorder").val("");
				}
				else{
					$("#noorder").attr("readonly",true);
					$("#btnorder").attr("disabled","disabled");
					$("#kontak").attr("disabled","disabled");
					Fill(data);
					$("#nokirim").focus();
				}
			});
	}
}

function getKirim()
{
	if(cekoption("nokirim","Memasukkan No Pengiriman")){
			nokirim = $("#nokirim").val();
			base_url = $("#baseurl").val();
			$.post(base_url+"index.php/transaksi/penerimaan_barang/getsumber",{ order:"",kirim:nokirim},
			function(data){
				if(data=="^&&^+")
				{
					alert("No Pengiriman Tidak Ditemukan\nPeriksa Kembali No Pengiriman");
					$("#nokirim").focus();
					$("#hiddennokirim").val("");
				}
				else{
					$("#nokirim").attr("readonly",true);
					$("#btnkirim").attr("disabled","disabled");
					$("#kontak").attr("disabled","disabled");
					var ajax = data.split("^&&^");
					Fill(ajax[1]);
					$("#kendaraan").val(ajax[0]);
					$("#kendaraan").attr("readonly",true);
					$("#ket").focus();
				}
			});
	}
}

function Fill(data)
{
	var ajax = data.split("+");
	var baris = 0;
	$("#newrow").css("display","none");
	var param = ajax[0].split("~");
	var msatuan = ajax[1].split("**");
	for(x=0;x<(param.length)-1;x++)
	{
		baris++;
		nilai = param[x].split("*&^%");
		if(x>0)
		{
			detailNew();
		}
		kdkontak = nilai[12];
		$("#pcode"+baris).val(nilai[0]);
		$("#tmppcode"+baris).val(nilai[0]);
		$("#qty"+baris).val(nilai[1]);
		$("#tmpqty"+baris).val(nilai[1]);
		$("#qtydisplay"+baris).val(nilai[2]);
		$("#qtypcs"+baris).val(nilai[3]);
		$("#nama"+baris).val(nilai[4]);
		$("#konverjk"+baris).val(nilai[5]);
		$("#konverbk"+baris).val(nilai[6]);
		$("#konvertk"+baris).val(nilai[7]);
		$("#kdsatuanj"+baris).val(nilai[8]);
		$("#satuanj"+baris).val(nilai[9]);
		$("#pcodebarang"+baris).val(nilai[11]);
		$("#pcode"+baris).attr("readonly",true);
		$("#del"+baris).css("display","none");
		$("#pick"+baris).css("display","none");
		$("#pcode"+baris).attr("readonly",true);
		$("#del"+baris).css("display","none");
		$("#pick"+baris).css("display","none");

		$("#satuan"+baris).empty();
		$("#satuan"+baris).append("<option value=''>--> Pilih <--</option>");
		$("#satuan"+baris).append(msatuan[x]);
		if(nilai[13]=="bar")
		{
			$("#satuan"+baris).attr("disabled","disabled");
		}
		$("#satuantmp"+baris).val($("#satuan"+baris).val());
		jQuery("input[name='sumber']").each(function(i) {
				jQuery(this).attr('disabled', 'disabled');
		});
	}
	$("#kontak").val(kdkontak);
	$("#hidecontact").val(kdkontak);
}

function pickThis(obj)
{
	if(cekheader())
	{
		base_url = $("#baseurl").val();
		objek = obj.id;
		id = parseFloat(objek.substr(4,objek.length-4));
		url = base_url+"index.php/pop/brgpaket/index/"+id+"/";
		window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
	}
}

function findPCode(id)
{
	if(cekheader())
	{
		if(cekoption("pcode"+id,"Memasukkan Kode Barang")){
			base_url = $("#baseurl").val();
			pcode = $("#pcode"+id).val();
			$.post(base_url+"index.php/transaksi/penerimaan_barang/getRealPCode",{ pcode:pcode},
			function(datakode){
				if(datakode!=""){
					var lastRow = document.getElementsByName("pcode[]").length;
					var dobel = false;
					for(index=0;index<lastRow;index++){
						nama = document.getElementsByName("pcode[]");
						temp = nama[index].id;
						indexs = temp.substr(5,temp.length-5);
						if($("#pcodebarang"+indexs).val()==datakode)
						{
							if(index==lastRow-1||indexs==id){
								continue;
							}
							else{
								dobel = true;
								break;
							}
						}
					}
					if(!dobel){
						$("#tmppcode"+id).val(pcode);
						$.post(base_url+"index.php/transaksi/penerimaan_barang/getPCode",{ pcode:pcode},
						function(data){
							if(data!="")
							{
								result = data.split("*&^%");
                                                                $("#nama"+id).val(result[0]);
                                                                $("#pcodebarang"+id).val(result[1]);
                                                                $("#qty"+id).val("");
                                                                $("#hrg"+id).val(result[2]);
                                                                $("#qty"+id).focus();
							}
							else
							{
								alert("Data Tidak Ditemukan");
								resetRow(id);
								$("#pcode"+id).focus();
							}
						});
					}
					else
					{
						alert("Kode Barang Sudah Ada");
						resetRow(id);
						$("#pcode"+id).focus();
					}
				}
				else
				{
					alert("Data Tidak Ditemukan");
					resetRow(id);
					$("#pcode"+id).focus();
				}
			});
		}
		else
		{
			resetRow(id);
			$("#pcode"+id).focus();
		}
	}
}

function InputQty(id,from)
{
	if(cekheader())
	{
		if(cekoption("pcode"+id,"Memasukkan Kode Barang"))
		{
				if(cekoption("satuan"+id,"Memilih Satuan"))
				{
					if(cekAngkaPas("qty"+id,"Qty","zero","no minus"))
					{
						var qty = parseFloat($("#qty"+id).val());
						var hrg = parseFloat($("#hrg"+id).val());
                                                ttl = hrg * qty;
                                                $("#ttl"+id).val(ttl);
					}
				}
		}
		else
		{
			resetRow(id);
			$("#pcode"+id).focus();
		}
	}
}


function InQty(obj)
{
    objek = obj.id;
    id = parseFloat(objek.substr(3,objek.length-3));
//    alert(id);
	if(cekheader())
	{
		if(cekoption("pcode"+id,"Memasukkan Kode Barang"))
		{
                        var qty = parseFloat($("#qty"+id).val());
                        var hrg = parseFloat($("#hrg"+id).val());
                        ttl = hrg * qty;
                        $("#ttl"+id).val(ttl);
		}
		else
		{
			resetRow(id);
			$("#pcode"+id).focus();
		}
	}
}


function convert(id)
{
	var qty = parseFloat($("#qty"+id).val());
	satuan = $("#satuan"+id).val().split("|");;
	satuanj = $("#kdsatuanj"+id).val();
	konver = $("#konverjk"+id).val();
	SatuanFlg = satuan[0];
	if(SatuanFlg=="B")
	{
		qty = parseFloat($("#konverbk"+id).val()) * parseFloat(qty);
	}
	else if(SatuanFlg=="T")
	{
		qty = parseFloat($("#konvertk"+id).val()) * parseFloat(qty);
	}
	else if(SatuanFlg=="K")
	{
		qty = qty;
	}
	$("#qtypcs"+id).val(qty);
	if(konver==1)
	{
		nilai = qty+".0";
	}
	else
	{
		if(parseFloat(qty)>=parseFloat(konver))
		{
			karton = Math.floor(parseFloat(qty) / parseFloat(konver));
			sisa = parseFloat(qty) % parseFloat(konver);
			nilai = karton +"."+ sisa;
		}
		else
		{
			nilai = "0." + qty;
		}
	}
	return nilai;
}

function storeSatuan(obj)
{
	objek = obj.id;
	id = parseFloat(objek.substr(6,objek.length-6));
	if($("#satuan"+id).val()!=""){
		$("#satuantmp"+id).val($("#satuan"+id).val());
		if($("#qty"+id).val()!=""){
			InputQty(id,"enter");
		}
	}
	else
	{
		$("#satuantmp"+id).val("");
		$("#qtydisplay"+id).val("");
		$("#tmpqty"+id).val("");
		$("#qty"+id).val("");
		$("#qty"+id).focus();
	}
}

function saveThis(id)
{
	if(cekheader())
	if(cekDetail(id))
	{
		$('fieldset.disableMe :input').attr('disabled', true);
		saveItem(id);
	}
}

function saveAll(){
//{ alert("Tets ");
	if(cekheader()){
//	if(cekDetailAll()){
	//	alert("K")
		$("#terima").submit();
	}
}

function cekheader()
{
	if(cekoption("ket","Mengisi Keterangan"))
	return true;
}

function ceksumberorder()
{
	sumber = $("input[@name='sumber']:checked").val();
	hiddenorder = $("#hiddennoorder").val();
	if(sumber=="O" && hiddenorder=="")
	{
		alert("Anda Belum Meng-enter No Order");
		$("#noorder").focus();
		return false;
	}
	return true;
}

function cektanggal()
{
	var d1 = $("#tgl").val().split("-");
	var d2 = $("#tglterima").val().split("-");

	var date1 = new Date(d1[2],parseFloat(d1[1])-1,d1[0]);
	//date1.setFullYear();
	var date2 = new Date(d2[2],parseFloat(d2[1])-1,d2[0]);
	//date2.setFullYear(d2[2],parseFloat(d2[1])-1,d2[0]);
        alert(date1+"||"+date2);
	if(date1 > date2){
		alert("Tanggal Sekarang Harus Lebih Besar Dari Tanggal Pengiriman");
		$("#tglterima").focus();
		return false;
	}
	else return true;
}

function cekDetail(id)
{
	if(cekoption("pcode"+id,"Memasukkan Kode Barang"))
	if(cekoption("qty"+id,"Memasukkan Jumlah Barang"))
	return true;
}

function cekDetailAll()
{
	var lastRow = document.getElementsByName("pcode[]").length;
	for(index=0;index<lastRow;index++){
		nama = document.getElementsByName("pcode[]");
		temp = nama[index].id;
		indexs = temp.substr(5,temp.length-5);
		if(index<parseFloat(lastRow)-1||index==0){
			if(cekoption("pcode"+indexs ,"Memasukkan Kode Barang"))
			if(cekoption("qty"+indexs ,"Memasukkan Jumlah Barang"))
			return false;
		}
		else if(index==parseFloat(lastRow)-1)
		{
			if($("#pcode"+indexs).val()==""&&$("#qty"+indexs).val()=="")
			{
				continue;
			}
			else
			{
				if(cekoption("pcode"+indexs ,"Memasukkan Kode Barang"))
				if(cekoption("qty"+indexs ,"Memasukkan Jumlah Barang"))
				return false;
			}
		}
	}
	return true;
}

function saveItem(id)
{
        detailNew();
}

function AddNew()
{
	var lastRow = document.getElementsByName("pcode[]").length-1;
	nama = document.getElementsByName("pcode[]");
	temp = nama[lastRow].id;
	indexs = temp.substr(5,temp.length-5);

	if(cekDetail(indexs)){
		saveItem(indexs);
	}
}

function detailNew()
{
	var clonedRow = $("#detail tr:last").clone(true);
	var intCurrentRowId = parseFloat($('#detail tr').length )-2;
	nama = document.getElementsByName("pcode[]");
	temp = nama[intCurrentRowId].id;
	intCurrentRowId = temp.substr(5,temp.length-5);
	var intNewRowId = parseFloat(intCurrentRowId) + 1;
	$("#pcode" + intCurrentRowId , clonedRow ).attr( { "id" : "pcode" + intNewRowId,"value" : ""} );
	$("#pick" + intCurrentRowId , clonedRow ).attr( { "id" : "pick" + intNewRowId,"value" : ""} );
	$("#del" + intCurrentRowId , clonedRow ).attr( { "id" : "del" + intNewRowId} );
	$("#nama" + intCurrentRowId , clonedRow ).attr( { "id" : "nama" + intNewRowId,"value" : ""} );
	$("#qty" + intCurrentRowId , clonedRow ).attr( { "id" : "qty" + intNewRowId,"value" : ""} );
	$("#qtydisplay" + intCurrentRowId , clonedRow ).attr( { "id" : "qtydisplay" + intNewRowId,"value" : ""} );
	$("#hrg" + intCurrentRowId , clonedRow ).attr( { "id" : "hrg" + intNewRowId,"value" : ""} );
	$("#ttl" + intCurrentRowId , clonedRow ).attr( { "id" : "ttl" + intNewRowId,"value" : ""} );
	$("#tmppcode" + intCurrentRowId , clonedRow ).attr( { "id" : "tmppcode" + intNewRowId,"value" : ""} );
	$("#tmpqty" + intCurrentRowId , clonedRow ).attr( { "id" : "tmpqty" + intNewRowId,"value" : ""} );
	$("#qtypcs" + intCurrentRowId , clonedRow ).attr( { "id" : "qtypcs" + intNewRowId,"value" : ""} );
	$("#savepcode" + intCurrentRowId , clonedRow ).attr( { "id" : "savepcode" + intNewRowId,"value" : ""} );
	$("#pcodebarang" + intCurrentRowId , clonedRow ).attr( { "id" : "pcodebarang" + intNewRowId,"value" : ""} );
	$("#detail").append(clonedRow);
	$("#detail tr:last" ).attr( "id", "baris" +intNewRowId ); // change id of last row
	$("#pcode" + intNewRowId).focus();
}

function deleteRow(obj)
{
	objek = obj.id;
	id = objek.substr(3,objek.length-3);
	pcode = $("#pcode"+id).val();
	var banyakBaris = 1;
	var lastRow = document.getElementsByName("pcode[]").length;
	for(index=0;index<lastRow;index++){
		nama = document.getElementsByName("pcode[]");
		temp = nama[index].id;
		indexs = temp.substr(5,temp.length-5);
		if($("#savepcode"+indexs).val()!=""){
			banyakBaris++;
		}
	}
	if($("#savepcode"+id).val()==""){
		$('#baris'+id).remove();
	}
	else if($("#savepcode"+id).val()==""&&banyakBaris==1){
		alert("Baris ini tidak dapat dihapus\nMinimal harus ada 1 baris");
	}
	else{
		if(banyakBaris==2)
		{
			alert("Baris ini tidak dapat dihapus\nMinimal harus ada 1 baris tersimpan");
		}
		else
		{
			no      = $("#nodok").val();
			tgl     = $("#tgl").val();
			objek   = obj.id;
			id      = objek.substr(3,objek.length-3);
			pcode   = $("#pcode"+id).val();
			pcodesave = $("#savepcode"+id).val();
			qty = $("#qty"+id).val();
			if(pcode!=""){
				var r=confirm("Apakah Anda Ingin Menghapus Kode Barang "+pcode+" ?");
				if(r==true){
					$('#baris'+id).remove();
					if(no!=""){
//                                                ($flag,$no,$tgl,$pcode,$pcodebarang,$qtyterima)
						deleteItem(no,tgl,pcode,pcodesave,qty);
					}
				}
			}
		}
	}
}

function deleteItem(no,tgl,pcode,pcodesave,qty)
{
	if($("#transaksi").val()=="no"){
//		no = $("#nodok").val();
//		flag = $("#flag").val();
		$("#transaksi").val("yes");
		base_url = $("#baseurl").val();
		$.post(base_url+"index.php/transaksi/penerimaan_barang/delete_item",{
			no:no,tgl:tgl,pcode:pcode,pcodesave:pcodesave,qty:qty},
		function(data){
			$("#transaksi").val("no");
		});
	}
}