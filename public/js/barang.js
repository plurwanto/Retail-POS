function getSubDiv(url)
{
	$.post(url+"index.php/master/barang/getSubDivisiBy",{ divisi: $("#divisi").val()},
	function(data){
	   $("#subdivisi").empty();
	   $("#subdivisi").append("<option value=''>--Please Select--</option");
	   $("#subdivisi").append(data);
	});
}
function getSubKat(url)
{
	$.post(url+"index.php/master/barang/getSubKategoriBy",{ kategori: $("#kategori").val()},
	function(data){
	   $("#subkategori").empty();
	   $("#subkategori").append("<option value=''>--Please Select--</option>");
	   $("#subkategori").append(data);
	});
}
function getSubBrand(url)
{
	$.post(url+"index.php/master/barang/getSubBrandBy",{ brand: $("#brand").val()},
	function(data){
	   $("#subbrand").empty();
	   $("#subbrand").append("<option value=''>--Please Select--</option>");
	   $("#subbrand").append(data);
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
function cekbarang()
{
	if(cekoption("kode","Memasukkan Kode Barang"))
	if(cekoption("nama","Memasukkan Nama Struk"))
	if(cekoption("nlengkap","Memasukkan Nama Lengkap"))
	if(cekoption("ninitial","Memasukkan Nama Initial"))
	if(cekoption("ninitial","Memasukkan Nama Initial"))
	if(cekoption("divisi","Memilih Divisi"))
	if(cekoption("subdivisi","Memilih Sub Divisi"))
//	if(cekoption("kategori","Memilih Kategori"))
//	if(cekoption("subkategori","Memilih Sub Kategori"))
	if(cekoption("brand","Memilih Brand"))
	if(cekoption("subbrand","Memilih Sub Brand"))
	if(cekoption("size","Memilih Satuan Ukuran"))
	if(cekoption("subsize","Memilih Ukuran"))
	if(cekoption("kemasan","Memilih Kemasan"))
//	if(cekoption("departemen","Memilih Departemen"))
	if(cekoption("kelas","Memilih Kelas Produk"))
	if(cekoption("tipe","Memilih Tipe Produk"))
//	if(cekoption("tag","Memilih Product Tag"))
	if(cekoption("supplier","Memilih Supplier"))
//	if(cekoption("principal","Memilih Principal"))
	if(cekoption("satuan","Memilih Satuan Barang"))
	if(cekAngka("hjual","Harga Jual"))
	if(cekAngka("konv","Konversi"))
	if(cekAngka("minimum","Minimum Order"))
//	if(cekoption("flag","Memilih Flag Harga"))
	if(cekoption("status","Memilih Status"))
		document.getElementById("barang").submit();
}