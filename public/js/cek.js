function ceksubsize(){
	if(cekoption("kode","Memasukkan Kode Sub Size"))
	if(cekoption("nama","Memasukkan Ukuran"))
	if(cekAngka("realsize","Total Ukuran"))
		document.getElementById("subsize").submit();
}
//////////////////****************user*************//////////
function cekuser(){
	if(cekoption("kode","Memasukkan Kode User"))
	if(cekoption("nama","Memasukkan Nama User"))
	if(cekPjgKode("nama","Nama User",6))
	if(cekoption("passw","Memasukkan Password"))
	if(cekPjgKode("passw","Password",6))
		document.getElementById("user").submit();
}

////////////////**********gudang************///////////

function cekgudang(){
	if(cekoption("kode","Memasukkan Kode Gudang"))
	if(cekoption("nama","Memasukkan Keterangan"))
	if(cekAngka("panjang","Panjang Gudang"))
	if(cekAngka("lebar","Lebar Gudang"))
	if(cekAngka("tinggi","Tinggi Gudang"))
		document.getElementById("gudang").submit();
}

////////////////**********supplier************///////////

function cekSupplier(){
	if(cekoption("kode","Memasukkan Kode Supplier"))
	if(cekoption("nama","Memasukkan Keterangan"))
	if(cekoption("alm1","Memasukkan Alamat 1 Supplier"))
	if(cekoption("alm2","Memasukkan Alamat 2 Supplier"))
	if(cekoption("kota","Memasukkan Kota Supplier"))
	if(cekoption("telp","Memasukkan Telepon Supplier"))
	if(cekoption("fax","Memasukkan Fax Supplier"))
	if(cekoption("cp","Memasukkan Contact Person Supplier"))
	if(document.getElementById("npwp").value!="" && document.getElementById("npwp").value!="-")
	{
	 	if(cekoption("namapjk","Memasukkan Nama Pajak Supplier"))
		if(cekoption("alm1pjk","Memasukkan Alamat 1 Pajak Supplier"))
		if(cekoption("alm2pjk","Memasukkan Alamat 2 Pajak Supplier"))
		if(cekoption("kotapjk","Memasukkan Kota Pajak Supplier"))
		if(cekAngka("top","TOP"))
		if(cekoption("bayar","Memilih Tipe Bayar Supplier"))
		if(cekoption("kirim","Memilih Tipe Kirim Supplier"))
			document.getElementById("supplier").submit();
	}
	else
	{
		if(cekAngka("top","TOP"))
		if(cekoption("bayar","Memilih Tipe Bayar Supplier"))
		if(cekoption("kirim","Memilih Tipe Kirim Supplier"))
			document.getElementById("supplier").submit();
	}

}
//////////*****************customer**********///////////
function cekcustomer()
{
	if(cekoption("kode","Memasukkan Kode Customer"))
	if(cekoption("nama","Memasukkan Nama Customer"))
	if(cekoption("alm","Memasukkan Alamat"))
	if(cekoption("kota","Memasukkan Kota"))
	if(cekoption("telp","Memasukkan No. Telp"))
	if(cekoption("bdate","Memasukkan Tanggal Lahir"))
		document.getElementById("customer").submit();
}

function loading(url)
{
	$('#bdate').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: url+ "public/images/calendar.png", buttonImageOnly: true } );
}
//////////////************voucher**************////////////
function cekvoucher(){
	if(cekoption("kode","Memasukkan Kode Voucher"))
	if(cekoption("nama","Memasukkan Keterangan"))
	if(cekAngka("nilai","Nominal"))
		document.getElementById("voucher").submit();
}

///////////////////**********************menu******************/////////////
function cekmenuroot(){
	if(cekoption("nama","Memasukkan Nama Root"))
		document.getElementById("root").submit();
}

function watchMenu(url)
{
 	 $("#nama").val($("#allmenu").val());

	$.post(url+"index.php/setup/menu/find_url",{ menu: $("#allmenu").val()},
	function(data){
	 	$("#url").val(data);
	 	document.getElementById("url").disabled = false;
		if(data=='')
		{
			document.getElementById("url").disabled = true;
		}
	});
	$.post(url+"index.php/setup/menu/GetRootSibling",{ menu: $("#allmenu").val()},
	function(data){
	   $("#menu").empty();
	   $("#menu").append("<option value='tetap'>Tetap</option>");
	   $("#menu").append("<option value=''>Baris Awal</option>");
	   $("#menu").append(data);
	});
}

function ambilsubs(url)
{
	$.post(url+"index.php/setup/menu/getSubMenuSibling",{ menu: $("#rootmenu").val()},
	function(data){
	   $("#menu").empty();
	   $("#menu").append("<option value=''>Baris Awal</option>");
	   $("#menu").append(data);
	});
}
/**********************kodepos***********/////////

function get_sub_area(url)
{
	$.post(url+"index.php/master/kodepos/sub_area",{ area: $("#area").val()},
	function(data){
	   $("#subarea").empty();
	   $("#subarea").append(data);
	});
}

function cek_kodepos()
{
	if(cekoption("kode","Memasukkan Kode Pos"))
	if(cekoption("nama","Memasukkan Keterangan"))
	if(cekselected("area","Memilih Area"))
		document.getElementById("kodepos").submit();
}

//*******************user permission*************//
function setpermission(){
	dml=document.forms[0];
	len = dml.elements.length;
	document.getElementById("addhidden").value="";
	document.getElementById("edithidden").value="";
	document.getElementById("delhidden").value="";
	document.getElementById("viewhidden").value="";
	document.getElementById("namahidden").value="";
	add = "";
	edit = "";
	del = "";
	view = "";
	nama="";
 	for(i=0;i<dml.add.length;i++){
 	 	nama = nama + "|" +dml.nama[i].value;
 	 	if(dml.add[i].checked==true)
		{
			add = add+"Y";
		}
		else
		{
			add = add+"T";
		}
		if(dml.edit[i].checked==true)
		{
			edit = edit+"Y";
		}
		else
		{
			edit = edit+"T";
		}
		if(dml.del[i].checked==true)
		{
			del = del+"Y";
		}
		else
		{
			del = del+"T";
		}
		if(dml.view[i].checked==true)
		{
			view = view+"Y";
		}
		else
		{
			view = view+"T";
		}
 	}
	document.getElementById("addhidden").value=add;
	document.getElementById("edithidden").value=edit;
	document.getElementById("delhidden").value=del;
	document.getElementById("viewhidden").value=view;
	document.getElementById("namahidden").value=nama;
	document.getElementById("permisssion").submit();
}