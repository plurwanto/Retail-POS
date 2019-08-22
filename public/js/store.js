function getSubTipe(url)
{
	$.post(url+"index.php/master/store/getSubTipeBy",{ tipe: $("#tipe").val()},
	function(data){
	   $("#subtipe").empty();
	   $("#subtipe").append("<option value=''>--Please Select--</option>");
	   $("#subtipe").append(data);
	});
}
function getGrupHarga(url)
{
	$.post(url+"index.php/master/store/getGrupHargaBy",{ subtipe: $("#subtipe").val()},
	function(data){
	   $("#gruph").empty();
	   $("#gruph").append("<option value=''>--Please Select--</option>");
	   $("#gruph").append(data);
	});
}
function getSubArea(url)
{
	$.post(url+"index.php/master/store/getSubAreaBy",{ area: $("#area").val()},
	function(data){
	   $("#subarea").empty();
	   $("#kodepos").empty();
	   $("#kodepos").append("<option value=''>--Please Select--</option>");
	   $("#subarea").append("<option value=''>--Please Select--</option>");
	   $("#subarea").append(data);
	});
}
function getKodePos(url)
{
	$.post(url+"index.php/master/store/getKodePosBy",{ subarea: $("#subarea").val()},
	function(data){
	   $("#kodepos").empty();
	   $("#kodepos").append("<option value=''>--Please Select--</option>");
	   $("#kodepos").append(data);
	});
}
function getSubSize(url)
{
	$.post(url+"index.php/master/barang/getSubSizeBy",{ size: $("#size").val()},
	function(data){
	   $("#subsize").empty();
	   $("#subsize").append("<option value=''>--Please Select--</option>");
	   $("#subsize").append(data);
	});
}
function cekstore()
{
	if(cekoption("kode","Memasukkan Kode Store"))
	if(cekoption("nama","Memasukkan Nama Store"))
	if(cekoption("dc","Memilih DC Default"))
	if(cekoption("tipe","Memilih Tipe Store"))
	if(cekoption("subtipe","Memilih Sub Tipe Store"))
	if(cekoption("klasifikasi","Memilih Klasifikasi Store"))
	if(cekoption("channel","Memilih Channel Store"))
	if(cekoption("area","Memilih Area"))
	if(cekoption("subarea","Memilih Sub Area"))
	if(cekoption("kodepos","Memilih KodePOS"))
	if(cekoption("pic","Memasukkan PIC"))
	if(cekAngka("panjang","Panjang Store"))
	if(cekAngka("lebar","Lebar Store"))
	if(cekAngka("panjangAll","Panjang All"))
	if(cekAngka("lebarAll","Lebar All"))
		document.getElementById("store").submit();
}