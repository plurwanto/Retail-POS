function ceklokasi(){
	if(cekoption("kode","Memasukkan Kode Lokasi"))
	if(cekoption("nama","Memasukkan Nama"))
	if(cekAngka("tingkat","Tingkat"))
	if(cekAngka("panjang","Panjang"))
	if(cekAngka("lebar","Lebar"))
	if(cekAngka("tinggi","Tinggi"))
		document.getElementById("lokasi").submit();
}