function loadDate(url)
{
	$('#periode1').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: url+ "public/images/calendar.png", buttonImageOnly: true } );
	$('#periode2').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: url+ "public/images/calendar.png", buttonImageOnly: true } );
	$('#myHiddenDiv').hide();
}

function perhitungan()
{
	if(document.getElementById("rup").value=="P")
	{
		$("#hitung").empty();
		$("#hitung").append("<option value='B'>Bertingkat</option>");
		$("#hitung").append("<option value='S'>Sejajar</option>");
		$("#satuan").val("Persentase");
	}
	else if(document.getElementById("rup").value=="B"||document.getElementById("rup").value=="R")
	{
		$("#hitung").empty();
		$("#hitung").append("<option value='K'>Kelipatan</option>");
		$("#hitung").append("<option value='T'>Tidak</option>");
		if(document.getElementById("rup").value=="R")
		{
			$("#satuan").val("Rupiah");
		}
		else
		{
			$("#satuan").val("Barang");
		}
	}
	else
	{
		$("#hitung").empty();
		$("#satuan").val("");
	}
}

function changeJenis()
{
	if($("#jenis").val()=="R")
	{
		$('#periode1').datepicker("disable");
		$('#periode2').datepicker("disable");
	}
	else
	{
		$('#periode1').datepicker("enable");
		$('#periode2').datepicker("enable");
	}
}
function setBeban()
{
	if($("#jenis").val()=="R" && ($("#rup").val()=="P" || $("#rup").val()=="R"))
	{
	 	$("#beban1").val("S");
	 	$("#rek1").val("40030000");
 		$("#persen1").val("100");
	}
	else if($("#jenis").val()=="P" && ($("#rup").val()=="P" || $("#rup").val()=="R"))
	{
	 	$("#beban1").val("M");
	 	$("#rek1").val("42010040");
 		$("#persen1").val("100");
	}

	else if(($("#jenis").val()=="R"||$("#jenis").val()=="P") && $("#rup").val()=="B")
	{
	 	$("#beban1").val("M");
	 	$("#rek1").val("42010050");
	 	$("#persen1").val("100");
	}
	else
	{
		$("#beban1").val("");
	 	$("#rek1").val("");
		$("#persen1").val("");
	}
}

function hadiahbonus()
{
 	$("#bonus").val("");
 	if(document.getElementById("rup").value=="B")
 	{
		$("#bonus").removeAttr("disabled");
	}
	else
	{
		$("#bonus").attr("disabled", "disabled");
		$("#brghadiah").attr("readOnly", "readonly");
		$("#btnbrg").attr("disabled", "disabled");
		$("#brghadiah").val("");
		$('#myHiddenDiv').hide();
		emptyMultiple();
	}
}

function ambilHadiah()
{
 	if(document.getElementById("bonus").value=="B")
 	{
		$("#brghadiah").removeAttr("readOnly");
		$("#btnbrg").removeAttr("disabled");
		$('#myHiddenDiv').show();
		$("#brghadiah").focus();
	}
	else
	{
		$("#brghadiah").attr("readOnly", "readonly");
		$("#btnbrg").attr("disabled", "disabled");
		$("#brghadiah").val("");
		$('#myHiddenDiv').hide();
		emptyMultiple();
	}
}

function getItem(e,url)
{
 	if(window.event) // IE
	{
		var code = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		var code = e.which;
	}
	if (code == 13) {
	 	getPCode(url);
	}
}

function getPCode(url)
{
 	var sel = document.getElementById("brg[]");
	var postvalue = trimIt($("#brghadiah").val());
	for(var v = 0;v<sel.options.length;v++)	{
	 	var exist = false;
		if(sel.options[v].value==postvalue){
			alert("Kode Barang Sudah Dipilih");
			exist = true;
			break;
		}
	}
	if(!exist){
		$.post(url+"index.php/master/discount/getItemHadiah",{ pcode: postvalue},
		function(data){
		   if(data=="empty")
		   {
				alert("Kode Barang Tidak Ditemukan");
		   }
		   else
		   {
				sel.options[sel.length] = new Option(postvalue, postvalue);
				sel.options[sel.length-1].selected = true;
				$("#brghadiah").val("");
				$("#delbrg").removeAttr("disabled");
		   }
		});
	}
}
function emptyMultiple()
{
 	var sel = document.getElementById("brg[]");
 	for(var v = sel.options.length-1; v>=0; v--)
	{
		sel.options[v] = null;
	}
}

function deletebrg()
{
  var theSel = document.getElementById("brg[]");
  var selIndex = theSel.selectedIndex;
  if (selIndex != -1) {
    for(i=theSel.length-1; i>=0; i--)
    {
      if(theSel.options[i].selected)
      {
        theSel.options[i] = null;
      }
    }
    if (theSel.length > 0) {
      theSel.selectedIndex = selIndex == 0 ? 0 : selIndex - 1;
    }
    else
    {
		if(theSel.length==0)
		{
			$("#delbrg").attr("disabled", "disabled");
		}
	}
  }
}

function popbrg(base_url)
{
	url = base_url+"index.php/pop/disc_brg/index/";
	window.open(url+escape(1),'popuppage','width=350,height=400,top=200,left=300');
}

function ubah(namasel, namacek)
{
	dml=document.forms[0];
	len = dml.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		if (dml.elements[i].name==namasel){
  		    byk =dml.elements[i].options.length;
  		    var semua = true;
			for(a=0;a<byk;a++){
				if(dml.elements[i].options[a].selected==false)
				{
					semua = false;
					break;
				}
				else semua = true;
			}
		}
		if (dml.elements[i].name==namacek){
			if(semua) dml.elements[i].checked = 1;
			else dml.elements[i].checked = 0;
		}
	}
}

function cekall(nama, namasel)	{ //select all
	dml=document.forms[0];
	len = dml.elements.length;
	var i=0;
	var val = 0;
	for( i=0 ; i<len ; i++) {
 	    if (dml.elements[i].name==nama){
			if(dml.elements[i].checked==1) val = 1;
			else val = 0;
		}
		if (dml.elements[i].name==namasel){
			byk =dml.elements[i].options.length;
			 for (var a=0; a < byk;a++){
			    if(val==1)
					dml.elements[i].options[a].selected=true;
			    else dml.elements[i].options[a].selected=false;
			 }
		}
	}
}
function pilih( opr, nil, opr2, flag, nil2){
	dml=document.forms[0];
	len = dml.elements.length;
	var i=0;
	for( i=0 ; i<len ; i++) {
		 if(flag=='1'){
	 	    if (dml.elements[i].name==opr) {
				if(dml.elements[i].value=='NIL'||dml.elements[i].value=='='||dml.elements[i].value=='<='||dml.elements[i].value<='<') {
					val2 = 1 //operator2
					if(dml.elements[i].value=='NIL'){
						val = 1;
					}
					else val = 0;
				}
				else if(dml.elements[i].value=='>'||dml.elements[i].value=='>='){
					val2 = 0;
					val = 0;
				}
			}
			if (dml.elements[i].name==nil) {
				if(val==1)	{
					dml.elements[i].readOnly = true;
					dml.elements[i].value = "";
				}
				else {
					dml.elements[i].readOnly = false;
					dml.elements[i].focus();
				}
			}
			if (dml.elements[i].name==opr2) {
				if(val2==1)	{
					dml.elements[i].disabled = true;
					dml.elements[i].selectedIndex=0;
				}
				else {
					dml.elements[i].disabled = false;
				}
			}
			if (dml.elements[i].name==nil2) {
				if(val2==1)	{
					dml.elements[i].readOnly = true;
					dml.elements[i].value = "";
				}
				else {
					dml.elements[i].readOnly = false;
					dml.elements[i].focus();
				}
			}
		}
		else if(flag=='2'){
			if (dml.elements[i].name==opr2) {
			    if( dml.elements[i].value=='>='||dml.elements[i].value=='>')
			     var c = 0;
			    else var c = 1;
			}
			if (dml.elements[i].name==opr) {
			  if( dml.elements[i].value=='>='||dml.elements[i].value=='>'){
						alert("Operator Ke - 2 Salah");
						val = 0;
						dml.elements[i].selectedIndex=0;
			  }
			  else if (dml.elements[i].value=='NIL' )
			     val = 0;
			  else val = 1;
			}
			if (dml.elements[i].name==nil) {
			  	if(val==0)	{
					dml.elements[i].readOnly = true;
					dml.elements[i].value = "";
				}
				else {
					dml.elements[i].readOnly = false;
					dml.elements[i].focus();
				}
			}
		}
	}
}

function cekdiscount(url){
	if(cekoption("nama","Mengisi Nama Discount"))
	if(ceknama(url))
	if(cekoption("jenis","Mengisi Jenis Discount"))
	if(cekoption("rup","Memilih Rupiah / Barang"))
	if(cekoption("hitung","Memilih Perhitungan Discount"))
	if(cekagain())
	if(cekallbeban())
	if(cekbeban1())
	if(cekallbeban2())
	if(cekallbeban3())
	if(cekAngka("nilai","Nilai Discount"))
	if(percentage())
	if(cekmore())
	if(cekdetail())
	if(ceknilaidetail()){
	//	alert("a");
		document.getElementById("discount").submit();
	}
}

function ceknama(url)
{
	$.post(url+"index.php/master/discount/cekNama",{ nama: trimIt($("#nama").val())},
	function(data){
		 $("#tempnama").val(data);
	});
	if($("#tempnama").val()!=0)
	{
		alert("Nama Discount Sudah Ada");
		$("#nama").focus();
		return false;
	}
	return true;
}

function percentage(){
 	disc = document.getElementById("nilai");
	if(trimIt(document.getElementById("satuan").value)=="Persentase"){
		if(parseFloat(trimIt(disc.value))>100){
			alert("Persentase Tidak Bisa Lebih Dari 100 %");
			disc.focus();
		}
		else return true;
		return false;
	}
	else return true;
}

function cekdetail(){
 	s = cekdetail1();
 	x = cekdetail2();
	if(s==true || x==true)
		return true;
	else{
		alert("Minimal 1 Detail Harus Diisi");
		return false;
	}
}

function ceknilaidetail(){
 	var c;
 	var d;
	dml=document.forms[0];
	len = dml.elements.length;
	for( i=27 ; i<len ; i++) {
	 	var nam = dml.elements[i].name;
		if(nam.substring(0,3) == "opr"||nam.substring(0,3)=="nil"){
		 	for(u = 0;u<19;u++){
				opr1 = 'opr1'+ u;
				nil1 = 'nil1'+ u;
				opr2 = 'opr2'+ u;
				nil2 = 'nil2'+ u;
				if (dml.elements[i].name==opr1){
				 	if(trimIt(dml.elements[i].value)!='NIL'){
				 	  var ceknil1 = trimIt(dml.elements[i].value);
				 	  d = u;
				 	}
				 	else {
						d = 1111;
					}
				}
				if (dml.elements[i].name==nil1){
				 	if(ceknil1!='NIL'&&u==d){
					 	if(trimIt(dml.elements[i].value)==''){
					 	   alert ("Anda Belum Memasukkan Nilai Operator 1");
					 	   dml.elements[i].focus();
					 	   c =  false;
					 	}
					 	else {
					 	 	var a1 = trimIt(dml.elements[i].value);
						  	if(isNaN(trimIt(a1))){
								alert("Nilai Operator Hanya Dapat Berupa Angka\nGunakan Tanda Titik Untuk Nilai Desimal");
								dml.elements[i].select();
								dml.elements[i].focus();
								c = false;
							}
							else if(trimIt(a1)<0){
								alert("Nilai Operator Tidak Boleh Lebih Kecil Dari 0");
								dml.elements[i].select();
								dml.elements[i].focus();
								c = false;
							}
							else{
								var nilai1 = trimIt(dml.elements[i].value);
								c =  true;
							}
						}
					}
					else c = true;
				}
				if (dml.elements[i].name==opr2){
					 if((ceknil1==">"||ceknil1 ==">=")&&u==d){
				 		if(trimIt(dml.elements[i].value)!="NIL"){
					 		   var ceknil2 = trimIt(dml.elements[i].value);
					 		   d = u;
						}
						else {
							d = 1111;
						}
					 }
				}
				if (dml.elements[i].name==nil2){
					 if((ceknil1==">="||ceknil1 ==">")&&(ceknil2=="<="||ceknil2=="<"||ceknil2=="NIL"||ceknil2=="=")&&u==d){
				 		if(trimIt(dml.elements[i].value)==''){
					 	   alert ("Anda Belum Memasukkan Nilai Operator 2");
					 	   dml.elements[i].focus();
					 	   c =  false;
					 	}
					 	else {
					 	 		var a2 = trimIt(dml.elements[i].value);
					 	 		if(isNaN(trimIt(a2))){
									alert("Nilai Operator 2 Hanya Dapat Berupa Angka\nGunakan Tanda Titik Untuk Nilai Desimal");
									dml.elements[i].select();
									dml.elements[i].focus();
									c = false;
								}
								else if(trimIt(a2)<0){
									alert("Nilai Operator 2 Tidak Boleh Lebih Kecil Dari 0");
									dml.elements[i].select();
									dml.elements[i].focus();
									c = false;
								}
								else{
								 	var nilai2 = trimIt(dml.elements[i].value);
									if(nilai1 > nilai2 ){
										alert("Nilai Operator 2 Tidak Boleh Lebih Kecil Dari Nilai Operator 1");
										dml.elements[i].focus();
										c =  false;
									}
									else c =  true;
								}
						}
					 }
				}
			}
			if(c==false) break;
		}
	}
	return c;
}

function cekdetail1(){
	dml=document.forms[0];
	len = dml.elements.length;
	ada = false;
	for( i=27 ; i<len ; i++) {
		for(u = 0;u<19;u++){
			nama = 'sel'+ u + '[]';
			if (dml.elements[i].name==nama){
				if(trimIt(dml.elements[i].value)!=''){
					ada = true;
					break;
				}
			}
		}
		if (ada) break;
	}
	return ada;
}

function cekdetail2(){
	dml=document.forms[0];
	len = dml.elements.length;
	ada = false;
	for( i=27 ; i<len ; i++) {
		for(u = 0;u<19;u++){
			nama = 'kec'+ u + '[]';
			if (dml.elements[i].name==nama){
				if(trimIt(dml.elements[i].value)!=''){
					ada = true;
					break;
				}
			}
		}
		if (ada) break;
	}
	return ada;
}

function cekagain(){
	if(trimIt(document.getElementById("jenis").value)!=""){
		if(trimIt(document.getElementById("jenis").value)=="P"){
			if(trimIt(document.getElementById("periode1").value)==""){
				alert("Anda Belum Memilih Tanggal Awal Promosi");
				document.getElementById("periode1").focus();
			}
			else {
				if(trimIt(document.getElementById("periode2").value)==""){
					alert("Anda Belum Memilih Tanggal Akhir Promosi");
					document.getElementById("periode2").focus();
				}
				else {
				 	var d1 = document.getElementById("periode1").value.split("-");
				    var d2 = document.getElementById("periode2").value.split("-");

				 	var date1 = new Date();
				 	date1.setFullYear(d1[2],parseFloat(d1[1])-1,d1[0])
				 	var date2 = new Date();
				 	date2.setFullYear(d2[2],parseFloat(d2[1])-1,d2[0])

					if(date1 > date2){
						alert("Tanggal Akhir Promosi Tidak Boleh Lebih Kecil Dari Tanggal Awal Promosi");
						document.getElementById("periode2").focus();
					}
					else return true;
				}
				return false
			}
			return false;
		}
		else return true;
	}
	else return true;
}


function cekallbeban(){
	beban1 = trimIt(document.getElementById("beban1").value);
	beban2 = trimIt(document.getElementById("beban2").value);
	beban3 = trimIt(document.getElementById("beban3").value);
	if(beban1==""&&beban2==""&&beban3==""){
		alert ("Minimal 1 Beban Harus Dipilih");
		document.getElementById("beban1").focus();
	}
	else if((beban1==""&&beban2!=""&&beban3!="")||(beban1==""&&beban2==""&&beban3!="")||(beban1!=""&&beban2==""&&beban3!="")){
		alert("Beban Harus Diisi Dari Paling Atas");
		document.getElementById("beban1").focus();
	}
	else{
		return true;
	}
	return false;
}

function cekbeban1(){
	if(cekoption("beban1","Memilih Beban"))
	if(cekoption("rek1","Memilih No Rekening"))
	if (cekAngka("persen1","Persentase Beban"))
	      return true
	else return false
}

function cekallbeban2(){
	beban2 = trimIt(document.getElementById("beban2").value);
	persen1 = trimIt(document.getElementById("persen1").value);

	if(persen1==100 && beban2!=""){
		alert("Persentase Beban Sudah 100 % Tidak Bisa Menambah Beban Kembali");
		document.getElementById("beban2").focus();
	}
	if(persen1<100 && beban2==""){
		alert("Persentase Beban Belum 100 % Harap Menambah Beban Kembali");
		document.getElementById("beban2").focus();
	}
	if((persen1>100 && beban2=="")||(persen1>100 && beban2!="")){
		alert("Persentase Beban Melebihi 100 % Harap Kurangi Persentase Beban Kembali");
		document.getElementById("persen1").focus();
	}
	if(persen1<100 && beban2!=""){
		if(cekbeban2())
		  if(cekacc2())
	  	    if (cekAngka("persen2","Persentase Beban 2"))
	  	      return true;
	}
	if(persen1==100 && beban2=="")
	  return true;
  else return false;
}

function cekallbeban3(){
	beban3 = trimIt(document.getElementById("beban3").value);
	persen1 = trimIt(document.getElementById("persen1").value);
	persen2 = trimIt(document.getElementById("persen2").value);
	if(persen1=="" ) persen1 = 0;
	if(persen2=="" ) persen2 = 0;
	percent = parseFloat(persen1) + parseFloat(persen2);
	if(percent==100 && beban3!=""){
		alert("Persentase Beban Sudah 100 % Tidak Bisa Menambah Beban Kembali");
		document.getElementById("beban3").focus();
	}
	if(percent<100 && beban3==""){
		alert("Persentase Beban Belum 100 % Harap Menambah Beban Kembali");
		document.getElementById("beban3").focus();
	}
	if((percent>100 && beban3=="")||(percent>100 && beban3!="")){
		alert("Persentase Beban Melebihi 100 % Harap Kurangi Persentase Beban Kembali");
		document.getElementById("persen2").focus();
	}
	if(percent<100 && beban3!=""){
		if(cekbeban3())
		  if(cekacc3())
	  	    if (cekAngka("persen3","Persentase Beban 3"))
				if(cekpersen())
				  return true;
	}
	if(percent==100 && beban3=="")
	  return true;
   else return false;
}

function cekpersen(){
	persen1 = trimIt(document.getElementById("persen1").value);
	persen2 = trimIt(document.getElementById("persen2").value);
	persen3 = trimIt(document.getElementById("persen3").value);
	if(persen1=="" ) persen1 = 0;
	if(persen2=="" ) persen2 = 0;
	if(persen3=="" ) persen3 = 0;
	total = parseFloat(persen1) + parseFloat(persen2) + parseFloat(persen3);
	if(total==100)
		return true;
	else {
		alert("Persentase Beban Belum / Melebihi 100 % Harap Revisi Persentase Kembali");
		document.getElementById("persen3").focus();
		return false;
	}
}

function cekbeban2(){
	if(trimIt(document.getElementById("beban2").value)==""){
		alert("Anda Belum Memilih Beban 2");
		document.getElementById("beban2").focus();
	}
	else {
		if(trimIt(document.getElementById("beban2").value)==trimIt(document.getElementById("beban1").value)){
			alert("Beban Telah Dipilih Harap Pilih Beban Lain");
			document.getElementById("beban2").focus();
		}
		else   return true;
		return false;
	}

}
function cekacc2(){
	if(trimIt(document.getElementById("rek2").value)==""){
		alert("Anda Belum Memilih No Rekening 2");
		document.getElementById("rek2").focus();
	}
	else {
		if(trimIt(document.getElementById("rek2").value)==trimIt(document.getElementById("rek1").value)){
			alert("Rekening " + trimIt(document.getElementById("rek2").value) + " Telah Dipilih Harap Pilih Rekening Lain");
			document.getElementById("rek2").focus();
		}
		else   return true;
		return false;
	}
}



function cekbeban3(){
	if(trimIt(document.getElementById("beban3").value)==""){
		alert("Anda Belum Memilih Beban 3");
		document.getElementById("beban3").focus();
	}
	else {
		if((trimIt(document.getElementById("beban3").value)==trimIt(document.getElementById("beban2").value))||(trimIt(document.getElementById("beban3").value)==trimIt(document.getElementById("beban1").value))){
			alert("Beban Telah Dipilih Harap Pilih Beban Lain");
			document.getElementById("beban3").focus();
		}
		else   return true;
		return false;
	}

}
function cekacc3(){
	if(trimIt(document.getElementById("rek3").value)==""){
		alert("Anda Belum Memilih No Rekening3");
		document.getElementById("rek3").focus();
	}
	else {
		if((trimIt(document.getElementById("rek3").value)==trimIt(document.getElementById("rek1").value))||(trimIt(document.getElementById("rek3").value)==trimIt(document.getElementById("rek2").value))){
			alert("Rekening " + trimIt(document.getElementById("rek3").value)+ " Telah Dipilih Harap Pilih Rekening Lain");
			document.getElementById("rek3").focus();
		}
		else   return true;
		return false;
	}
}

function cekmore(){
	if(document.getElementById("rup").value=="B"){
		if(trimIt(document.getElementById("bonus").value)==""){
			alert("Anda Belum Memilih Hadiah Barang");
			document.getElementById("bonus").focus();
		}
		else {
		  if(trimIt(document.getElementById("bonus").value)=="B"){
			dml=document.forms[0];
			len = dml.elements.length;
			var i=0;
			var selected = 0;
			for( i=0 ; i<len ; i++) {
				if (dml.elements[i].id=="brg[]") {
		  			byk =dml.elements[i].options.length;
		  			for(var x=0;x<byk;x++)
		  			{
						if(dml.elements[i].options[x].selected==true)
						{
							selected += 1;
						}
					}
		  			break;
		  		}
		     }
		     if(selected==0){
				alert("Anda Belum Memasukkan / Memilih Kode Hadiah Barang");
				document.getElementById("brghadiah").focus();
			}
			else return true;
			return false;
		  }
		  else return true;
		}
	}
	else return true;
}