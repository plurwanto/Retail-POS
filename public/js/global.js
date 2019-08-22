function cekMaster(field1,field2,form,alert1,alert2){
	if(cekoption(field1,"Memasukkan " + alert1))
	if(cekPjgKode(field1,alert1,2))
	if(cekoption(field2,"Memasukkan " + alert2))
		document.getElementById(form).submit();
}

function cekMaster2(field1,field2,form,alert1,alert2){
	if(cekoption(field1,"Memasukkan " + alert1))
	if(cekoption(field2,"Memasukkan " + alert2))
		document.getElementById(form).submit();
}

function cekoption(elm1,ket){
	if (trimIt(document.getElementById(elm1).value)==''){
		alert("Anda Belum "+ ket);
		document.getElementById(elm1).focus();
	}
	else return true;
	return false;
}

function cekselected(elm1,ket){
	if (trimIt(document.getElementById(elm1).value)=='not selected'){
		alert("Anda Belum "+ ket);
		document.getElementById(elm1).focus();
	}
	else return true;
	return false;
}

function cekAngka(elm,ket){
 	var nilai = document.getElementById(elm);
	if(ubah(trimIt(nilai.value)) == ""){
		alert("Anda Belum Memasukkan "+ket);
		nilai.focus();
	}
	else{
		if(isNaN(ubah(trimIt(nilai.value)))){
			alert(ket + " Hanya Dapat Berupa Angka\nGunakan Tanda Titik Untuk Nilai Desimal");
			nilai.select();
			nilai.focus();
		}
		else if(ubah(trimIt(nilai.value))<0){
			alert(ket + " Tidak Boleh Lebih Kecil Dari 0");
			nilai.select();
			nilai.focus();
		}
		else
			return true;
	}	
	return false;
}

function trimIt( str ) { // http://kevin.vanzonneveld.net // + improved by: mdsjack (http://www.mdsjack.bo.it) // + improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)

return str.replace(/(^[\s\xA0]+|[\s\xA0]+$)/g, ''); 
}

function SetChecked(chk, elm) {	//chk ==nama cekall, elm nama cekbiasa
	dml=document.forms[0];
	
	len = dml.elements.length;
	var i=0;	
	for( i=0 ; i<len ; i++) {
 	    if (dml.elements[i].name==chk) {
			if(dml.elements[i].checked==1) val = 1;
			else val = 0;
		}	 	   
		if (dml.elements[i].name==elm) {
			dml.elements[i].checked=val;
		}
		
	}
}

function validateForm(elm1,elm2,ket){
	if(trimIt(document.getElementById(elm1).value)!=trimIt(document.getElementById(elm2).value)){
		alert(ket + " Tidak Sama \nHarap Menekan Enter Untuk Validasi Pilihan Kode");
		document.getElementById(elm1).focus();
	}
	else return true;
	return false;
}

function cekPjgKode(elm1,ket,pjg){
 	elm = document.getElementById(elm1);
	if(elm.value.length<parseFloat(pjg)){
		alert("Panjang " + ket + " Harus Minimal "+pjg + " Karakter");
		elm.focus();
	}
	else return true;
	return false;
}

function changeCursor(e,form1,obj) {
	if(window.event) // IE
	{
		var code = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		var code = e.which;
	}
	if (code == 13) { //checks for the escape key
	  	dml=document.forms[form1];
		len = dml.elements.length;
		//cari letak kursor ada di field mana
		for( i=0 ; i<len ; i++) {
		 	if(dml.elements[i].name==obj.name){
		 	 	break;
			}
		}

		//cari next field name
		for( y=i ; y<len ;y++) {
		 	if(dml.elements[y+1].type!="hidden"){
			 	if(dml.elements[y+1].readOnly==false||dml.elements[y+1].type=="select-one"){
			 	 	if(dml.elements[y+1].value=="Tambah Barang"||dml.elements[y+1].value=="Tambah"||dml.elements[y+1].value=="Delete"){			 	 	 
						y=y+1;
					}
					if(dml.elements[y+1].type!="hidden"){
						dml.elements[y+1].focus();
						break;
					}
				}
			}
		}
	}
}

function firstLoad(form1){
	dml=document.forms[form1];
	len = dml.elements.length;
	for( i=0 ; i<len ; i++) {
		if(dml.elements[i].type!="hidden"&&dml.elements[i].type!="checkbox"){
		 	if(dml.elements[i].readOnly==false||dml.elements[i].type=="select-one"){
				dml.elements[i].focus();
				break;
			}
		}
	}
}

function ubah (harga){
	 t = harga.split(".");
	 k = t.join("");
	 s = k.split(",");
	 s = s.join(".");
	 return s;
}

function jam(){
	var waktu = new Date();
	var jam = waktu.getHours();
	var menit = waktu.getMinutes();
	var detik = waktu.getSeconds();
	 
	if (jam < 10){
	jam = "0" + jam;
	}
	if (menit < 10){
	menit = "0" + menit;
	}
	if (detik < 10){
	detik = "0" + detik;
	}
	var jam_div = document.getElementById('jam');
	jam_div.innerHTML = jam + ":" + menit + ":" + detik;
	setTimeout("jam()", 1000);
	}