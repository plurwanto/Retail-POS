function keydown(e,flag,obj){
	//var e = window.event;
	if(window.event) // IE
	{
		var code = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		var code = e.which;
	}
	if (code == 13){ //checks for the escape key
		objek = obj.id;
		if(flag=="order"){
			$("#hiddennoorder").val($("#noorder").val());
			setTimeout("getOrder()",1);
		}
	}
}
function loading()
{
	base_url = $("#baseurl").val();
	$('#tgl').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
}

function getOrder()
{
	if(validateForm("noorder","hiddennoorder","No Order")){
		$("#Layer1").css("display","");
		$('fieldset.disableMe :input').attr('disabled', true);
		base_url = $("#baseurl").val();
		tahun = $("#tgl").val().substr(6,4);
		bulan = $("#tgl").val().substr(3,2);
		$.post(base_url+"index.php/transaksi/pengambilan/getOrder",{ noorder:$("#noorder").val(),tahun:tahun,bulan:bulan},
		function(data){
		    //alert(data);
			if(data=="")
			{
				alert("No Order Tidak Diketemukan");
				$('fieldset.disableMe :input').attr('disabled', false);
				$("#Layer1").css("display","none");
				$("#noorder").focus();
			}
			else if(data=="picking method not defined")
			{
				alert("beberapa barang belum ditentukan cara pengambilannya");
				$('fieldset.disableMe :input').attr('disabled', false);
				$("#Layer1").css("display","none");
				$("#noorder").focus();
			}
			else if(data=="@0")
			{
				alert("Beberapa stok barang tidak ada di semua lokasi, cek stok di laporan stok terlebih dahulu");
				$('fieldset.disableMe :input').attr('disabled', false);
				$("#Layer1").css("display","none");
				$("#noorder").focus();
			}
			else if(data=="deletedo")
			{
				alert("DO telah dihapus");
				$('fieldset.disableMe :input').attr('disabled', false);
				$("#Layer1").css("display","none");
				$("#noorder").focus();
			}
			else{
				counterBaris = 0;
				detail = data.split("||");
				header = detail[0].split("^^");
				$("#tglorder").val(header[0]);
				$("#tglorder").val(header[0]);
				$("#kontak").val(header[1]);
				$("#hidecontact").val(header[2]);
				$("#hidegudang").val(header[3]);
				$("#gudang").val(header[4]);
				perbaris = detail[1].split("%%");
				disabled_btn_id = new Array();
				flag_isi = false;
				for(var s=0;s<perbaris.length-1;s++)
				{
					detbaris = perbaris[s].split("**");
					barisatas = detbaris[0].split("##");
					barisbawah = detbaris[1].split("!!");
					if(parseFloat(barisbawah.length)>1)
					{
						flag_isi = true;
						for(var k=0;k<barisbawah.length-1;k++){
							detail = barisbawah[k].split("(*)");
							nildet = detail[0].split("$");
							nilattr = detail[1];
							counterBaris++;
							if(counterBaris>1)
							{
								detailNew();
							}
							
							if( parseFloat(nildet[0]) % parseFloat(nildet[6])==0)
							{
								qtybaru = parseFloat(nildet[0]) / parseFloat(nildet[6]);
								$sat = "B|" + barisatas[18];
								nama_satnya = barisatas[17];
							}
							else
							{
								if( parseFloat(nildet[0]) % parseFloat(nildet[7])==0)
								{
									qtybaru = parseFloat(nildet[0]) / parseFloat(nildet[7]);
									$sat = "T|" + barisatas[16];
									nama_satnya = barisatas[15];
								}
								else
								{
									qtybaru = nildet[0];
									$sat = "K|" + barisatas[14];
									nama_satnya = barisatas[13];
								}
							}
							$("#pcode"+counterBaris).val(barisatas[12]);
							$("#nama"+counterBaris).val(barisatas[1]);
							$("#kdsatuan"+counterBaris).val(barisatas[2]);
							$("#satuan"+counterBaris).val(barisatas[3]);
							$("#qtypcs"+counterBaris).val(barisatas[4]);
							$("#qty"+counterBaris).val(barisatas[5]);
							$("#kdsatuanj"+counterBaris).val(barisatas[6]);
							$("#satuanj"+counterBaris).val(barisatas[7]);
							$("#konverbk"+counterBaris).val(nildet[6]);
							$("#konvertk"+counterBaris).val(nildet[7]);
							$("#konverjk"+counterBaris).val(barisatas[10]);
							$("#satuanambil"+counterBaris).val(nama_satnya);
							$("#qtyambil"+counterBaris).val(qtybaru);
							$("#qtypcsambil"+counterBaris).val(nildet[0]);
							$("#kdsatuanambil"+counterBaris).val($sat);
							$("#kdlokasi"+counterBaris).val(nildet[1]);
							$("#lokasi"+counterBaris).val(nildet[2]);
							$("#qtydisplay"+counterBaris).val(convert(nildet[0],counterBaris));
						}
					}
					else
					{
						disabled_btn_id[disabled_btn_id.length] = counterBaris;
					}
					
				}
				$("#noorder").attr("readonly",true);
				$('fieldset.disableMe :input').attr('disabled', false);
				$("#Layer1").css("display","none");
				$("#btnorder").attr('disabled', 'disabled');
				for(p=0;p<disabled_btn_id.length;p++)
				{
					$("#btnatr"+disabled_btn_id[p]).attr("disabled","disabled");
				}
				if(!flag_isi)
				{
					$("#btnatr1").attr("disabled","disabled");
				}
				$("#ket").focus();
			}
		});
	}
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
		objek = obj;
		if(flag=='pcode'){
                        objek = obj.id;
                        id = parseFloat(objek.substr(5,objek.length-5));
//                        alert(id);
			$("#temppcode"+id).val($("#pcode"+id).val());
			findPCode(id);
		}
		else if(flag=='qty'){
			id = obj;
			InputQty(id,'enter');
		}
		else if(flag=='satuan'){
			id = obj;
			$("#qty"+id).focus();
		}
	}
}

function simpanKontak()
{
	kontak = $("#kontak").val();
	$("#hidecontact").val(kontak);
	resetRow(1);
	$("#ket").focus();
	$("#pcode1").val("");
}

function putLokasi(obj){
	objek = obj.id;
	id = parseFloat(objek.substr(6,objek.length-6));
	tipe = $("#hidetipe").val();
	if(tipe=="masuk"){
		cariLokasiSimpan(id);
	}
	else
	{
		cariStockKeluar(id);
	}
}

function changeSatuan(obj)
{
	objek = obj;
	id = obj;
	$("#kdsatuan"+id).val($("#satuan"+id).val());
	if($("#qty"+id).val()!=""){
		if($("#satuan"+id).val()!=""){
			if($("#qty"+id).val()!=""){
				InputQty(id,"enter");
			}
		}
		else
		{
			$("#kdsatuan"+id).val("");
			$("#qtydisplay"+id).val("");
			$("#qty"+id).val("");
			$("#qty"+id).focus();
		}
	}
}
function pickThis(obj)
{
	if(cekheader())
	{
            
		base_url = $("#baseurl").val();
		objek = obj.id;
		id = parseFloat(objek.substr(4,objek.length-4));
		owner = $("#hidecontact").val();
		url = base_url+"index.php/pop/barangownerlainlain/index/"+owner+"/"+id+"/";
		window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
	}
}

function findPCode(id)
{
    if(cekheader())
    {
        base_url = $("#baseurl").val();
        pcode = $("#pcode"+id).val();
        tgl=$("#tgl").val();
        $("#nobarcode"+id).val(pcode);
         caariPCode(pcode,id);
    }
}
function caariPCode(pcode,id){
//                                 alert(pcode);
                                                $.post(base_url+"index.php/transaksi/lainlain/getPCode",{ pcode:pcode,tgl:tgl},
                                                function(data){
                                                        if(data!="")
                                                        {
                                                                        nilai = data.split('++');
                                                                        result = nilai[0].split("*&^%");
                                                                        $("#nama"+id).val(result[0]);
                                                                        $("#hrg"+id).val(result[1]);
                                                                        $("#pcode"+id).val(result[3]);
                                                                        $("#qty"+id).val("0");
                                                                        $("#nil"+id).val("0");
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

function openAttr(obj)
{
	if(cekheader()){
		
		id = obj;
		if($("#qtydisplay"+id).val()==""){
			alert("Qty barang "+$("#pcode"+id).val()+" belum diinput dengan benar");
			$("#qty"+id).focus();
		}
		else{
//			if(validateForm2("qtypcs"+id,"tempqty"+id,"qty"+id,"Jumlah Barang")){
				base_url = $("#baseurl").val();
				pcode = $("#pcode"+id).val();
				url = base_url+"index.php/pop/isi_attr2/isidata/";
                                tipe = $("#hidetipe").val();
                               if(tipe=="masuk")
				{
					child = window.open(url+id+"/isi/"+pcode+"/",'popuppage','scrollbars=yes,width=550,height=500,top=180,left=150');
				}
				else{ 
                                    lok=$("#lok"+id).val();
                                    noper=$("#nodetail"+id).val();
                                    asl=$("#asaldata"+id).val();
                                    counter=$("#counter"+id).val();
                                    tgl=$("#tgl").val();
                                    child = window.open(url+id+"/lihat/"+pcode+"/"+lok+"/"+noper+"/"+asl+"/"+counter+"/"+tgl,'popuppage','scrollbars=yes,width=550,height=500,top=180,left=150');
				}
//			}
		}
	}
}

function openAttrEnter(id)
{
	if(cekheader()){
		if(validateForm2("qtypcs"+id,"tempqty"+id,"qty"+id,"Jumlah Barang")){
			base_url = $("#baseurl").val();
			pcode = $("#pcodebarang"+id).val();
			url = base_url+"index.php/pop/isi_attr/index/";
			tipe = $("#hidetipe").val();
			if(tipe=="masuk")
			{
				child = window.open(url+id+"/isi/"+pcode+"/",'popuppage','scrollbars=yes,width=550,height=500,top=180,left=150');
			}
		}
	}
}

function resetRow(id)
{
	$("#pcode"+id).focus();
	$("#satuan"+id).empty();
	$("#temppcode"+id).val("");
	$("#nama"+id).val("");
	$("#konverjk"+id).val("");
	$("#konverbk"+id).val("");
	$("#konvertk"+id).val("");
	$("#kdsatuanj"+id).val("");
	$("#satuanj"+id).val("");
	$("#qtydisplay"+id).val("");
	$("#tempqty"+id).val("");
	$("#qty"+id).val("");
	$("#qtypcs"+id).val("");
	$("#pcodebarang"+id).val("");
	$("#satuan"+id).empty();
	$("#lokasi"+id).empty();
	$("#kdsatuan"+id).val("");
	$("#satuanall"+id).val("");
	$("#hidelokasi"+id).val("");
}
function storeSatuan(obj)
{
	objek = obj.id;
	id = parseFloat(objek.substr(6,objek.length-6));
	if($("#satuan"+id).val()!=""){
		$("#kdsatuan"+id).val($("#satuan"+id).val());
		if($("#qty"+id).val()!=""){
			InputQty(id,"enter");
		}
	}
	else
	{
		$("#kdsatuan"+id).val("");
		$("#qtydisplay"+id).val("");
		$("#tempqty"+id).val("");
		$("#qty"+id).val("");
		$("#qty"+id).focus();
	}
}

function Inputhrg(obj)
{
        objek = obj.id;
	id = parseFloat(objek.substr(3,objek.length-3));

        qty = $("#qty"+id).val();
	hrg = $("#hrg"+id).val();
        ttl = qty * hrg;
        $("#nil"+id).val(ttl);
}

function InputQty(obj)
{
        objek = obj.id;
	id = parseFloat(objek.substr(3,objek.length-3));

        qty = $("#qty"+id).val();
	hrg = $("#hrg"+id).val();
        ttl = qty * hrg;
        $("#nil"+id).val(ttl);
}


function cariStockKeluar(id)
{
	var pcodebarang = $("#pcodebarang"+id).val();
	var pickmethod = $("#pickingmethod"+id).val();
	var qtypcs = $("#qtypcs"+id).val();
	var satuanall = $("#satuanall"+id).val().split("<<");
	var varsatuan_B = satuanall[0].split("|");
	var varsatuan_T = satuanall[1].split("|");
	var varsatuan_K = satuanall[2].split("|");
	var satuan_B = varsatuan_B[0];
	var satuan_T = varsatuan_T[0];
	var satuan_K = varsatuan_K[0];
	var namasatuan_B = varsatuan_B[1];
	var namasatuan_T = varsatuan_T[1];
	var namasatuan_K = varsatuan_K[1];
	//var konverbk = $("#konverbk"+id).val();
	//var konvertk = $("#konvertk"+id).val();
	var mandat = $("#mandatattr"+id).val();
	var base_url = $("#baseurl").val();
	if(qtypcs==""){qtypcs=0;}
	$.post(base_url+"index.php/transaksi/lainlain/getstringstock",{ 
	pcodebarang:pcodebarang,pickmethod:pickmethod,qtypcs:qtypcs},
	function(data){
		counterBaris = id;
		detbaris = data.split("**");
		barisatas = detbaris[0].split("##");
		barisbawah = detbaris[1].split("!!");
		lokasi_nil = barisatas[3].split("||");
		$("#lokasi"+counterBaris).empty();
		for(var y=0;y<lokasi_nil.length-1;y++){
			lokasi_get = lokasi_nil[y].split("^^");
			$("#lokasi"+counterBaris).append("<option value='"+lokasi_get[0]+"'>"+lokasi_get[1]+"</option>");
		}
		if(parseFloat(barisbawah.length)>1)
		{
			for(var k=0;k<barisbawah.length-1;k++){
				detail = barisbawah[k].split("(*)");
				nildet = detail[0].split("$");
				nilattr = detail[1];
				if(counterBaris!=id)
				{
					counterBaris = detailInsertRow(id,nildet[0],nildet[1],mandat);
				}
				$("#konverbk"+counterBaris).val(nildet[6]);
				$("#konvertk"+counterBaris).val(nildet[7]);
				var konverbk = nildet[6];
				var konvertk = nildet[7];
				if( parseFloat(nildet[0]) % parseFloat(konverbk)==0)
				{
					qtybaru = parseFloat(nildet[0]) / parseFloat(konverbk);
					$sat = "B|" + namasatuan_B;
				}
				else
				{
					if( parseFloat(nildet[0]) % parseFloat(konvertk)==0)
					{
						qtybaru = parseFloat(nildet[0]) / parseFloat(konvertk);
						$sat = "T|" + namasatuan_T;
					}
					else
					{
						qtybaru = nildet[0];
						$sat = "K|" + namasatuan_K;
					}
				}
				$("#lokasi"+counterBaris).val(nildet[1]);
				$("#hidelokasi"+counterBaris).val(nildet[1]);
				$("#satuan"+counterBaris).val($sat);
				$("#kdsatuan"+counterBaris).val($sat);
				$("#qty"+counterBaris).val(qtybaru);
				$("#qtypcs"+counterBaris).val(nildet[0]);
				$("#tempqty"+counterBaris).val(nildet[0]);
				$("#qtydisplay"+counterBaris).val(convert(counterBaris));
				$("#attr"+counterBaris).val(nilattr);
				$("#counternoterima"+counterBaris).val(nildet[3]);
				$("#noterima"+counterBaris).val(nildet[4]);
				$("#asaldata"+counterBaris).val(nildet[5]);
				if(counterBaris==id)
				{
					$("#Layer1").css("display","");
					$('fieldset.disableMe :input').attr('disabled', true);
					saveItem(id);
				}
				counterBaris++;
			}
			detailNew();
		}
		else
		{
			alert("Stok tidak tersedia");
		}
	});
}

function cariLokasiSimpan(id)
{
	pcode = $("#pcode"+id).val();
	pcodebarang = $("#pcodebarang"+id).val();
	no = $("#nodok").val();
	counter = $("#counter"+id).val();
	kontak = $("#hidecontact").val();
	qty = $("#qtypcs"+id).val();
	var base_url = $("#baseurl").val();
	$.post(base_url+"index.php/transaksi/lainlain/lokasisimpan",{ 
	pcode:pcode,pcodebarang:pcodebarang,no:no,counter:counter,
	kontak:kontak},
	function(data){
		if(data=="noroke")
		{
			alert("Semua lokasi yang ada tidak dapat ditempati.\nRevisi ketentuan penyimpanan yang ada");
		}
		else if(data=="notenough")
		{
			alert("Barang tidak dapat tersimpan karena vol barang melebihi vol semua lokasi yang ada");
		}
		else
		{
		/*	split_lokasi = data.split("**");
			$("#lokasi"+id).empty();
			for(k=0;k<split_lokasi.length-1;k++)
			{
				var_lokasi = split_lokasi[k].split("~");
				kdlokasi = var_lokasi[0];
				namalokasi = var_lokasi[1];
				$("#lokasi"+id).append("<option value='"+kdlokasi+"'>"+namalokasi+"</option>");
				if(k==0)
				{
					$("#lokasi"+id).val(kdlokasi);
					$("#hidelokasi"+id).val(kdlokasi);
				}
			}*/
			simpanTempatLain(id,pcode,qty,no,data,pcodebarang);
		}
	});
}

function simpanTempatLain(id,pcode,qty,no,data,pcodebarang)
{
	var rekomen = [];
	var namarekomen = [];
	var pecahan = [];
	var lokasi_ok = [];
	var nilai = data.split("**");
	var tmptotaldapat = 0;
	var tmptotal = 1;
	var location = false;
	if(qty==""){qty=0;}
	for(s=0;s<nilai.length-1;s++)
	{
		hasil = nilai[s].split("~");
		kodelokasi = hasil[0]
		namalokasi = hasil[1]
		terpakai = hasil[2];
		vollokasi = hasil[3];
		volbarang = hasil[4];
		qtyawal = hasil[5];
		if(terpakai==""){ terpakai = 0; }
		if(qtyawal==""){ qtyawal = 0; }

		totalvolbarang = parseFloat(volbarang) * parseFloat(qty);
		totalvolbarangawal = parseFloat(volbarang) * parseFloat(qtyawal);
	//	alert(vollokasi+" "+terpakai+" "+totalvolbarangawal+" "+volbarang);
		jumlahqtymasuk = Math.floor((parseFloat(vollokasi) - parseFloat(terpakai) + parseFloat(totalvolbarangawal)) / parseFloat(volbarang));
		jumlahqtymasuk_tmp = jumlahqtymasuk;
	//	alert(vollokasi+" "+totalvolbarang+" "+jumlahqtymasuk+" "+qty+" "+tmptotaldapat)
		if(parseFloat(jumlahqtymasuk)>0)
		{
			tmptotaldapat = parseFloat(tmptotaldapat) + parseFloat(jumlahqtymasuk);
		//	alert(tmptotaldapat)
			if(tmptotaldapat>qty){
				lebihbanyak = parseFloat(tmptotaldapat) - parseFloat(qty);
				jumlahqtymasuk = parseFloat(jumlahqtymasuk) - parseFloat(lebihbanyak);
				tmptotaldapat = parseFloat(tmptotaldapat) - parseFloat(lebihbanyak);
			}
			if(parseFloat(jumlahqtymasuk)>0)
			{
				var nilqtysisakonversi = cariSisa(jumlahqtymasuk,id);
				var sisanya = parseFloat(jumlahqtymasuk) - parseFloat(nilqtysisakonversi);
			//	alert(sisanya+" "+jumlahqtymasuk)
				jumlahqtymasuk_tmp = parseFloat(jumlahqtymasuk)-parseFloat(sisanya);
				jumlahqtymasuk = jumlahqtymasuk_tmp;
				if(parseFloat(nilqtysisakonversi)>0){
				//	alert(nilqtysisakonversi)
					pecahan[pecahan.length] = kodelokasi+ "~" + nilqtysisakonversi+ "~" +namalokasi;
					rekomen[rekomen.length] = kodelokasi+ "~" +namalokasi;
				}
				tmptotaldapat = parseFloat(tmptotaldapat) - parseFloat(sisanya);
			}
		//	alert(tmptotaldapat)
		//	alert(kodelokasi+" "+jumlahqtymasuk_tmp+" "+qty)
			if(parseFloat(jumlahqtymasuk_tmp)>=parseFloat(qty))
			{
				lokasi_ok[lokasi_ok.length] = kodelokasi+ "~" + namalokasi;
				//if(kodelokasi==lokasi){
				//	location = true;
				//}
			}
		}
	}
	var lokasiawal = $("#lokasi"+id).val();
	var lokasiawal_ada = false;
	$("#lokasi"+id).empty();
	if(rekomen.length!=0&&lokasi_ok.length!=0){
		var merginglokasi = rekomen.concat(lokasi_ok);
	}
	else
	{
		if(rekomen.length==0&&lokasi_ok.length!=0){
			var merginglokasi = lokasi_ok;
		}
		else if(rekomen.length!=0&&lokasi_ok.length==0){
			var merginglokasi = rekomen;
		}
	}
	if(tmptotaldapat>0)
	{
		for(k=0;k<merginglokasi.length-1;k++)
		{
			lokasidapat = merginglokasi[k].split("~");
			$("#lokasi"+id).append("<option value='"+lokasidapat[0]+"'>"+lokasidapat[1]+"</option>");
			if(k==0&&!lokasiawal_ada)
			{
				$("#lokasi"+id).val(lokasidapat[0]);
				$("#hidelokasi"+id).val(lokasidapat[0]);
			}
			if(lokasidapat[0]==lokasiawal)
			{
				lokasiawal_ada = true;
			}
		}
	}
	if(lokasiawal_ada)
	{
		$("#lokasi"+id).val(lokasiawal);
		$("#hidelokasi"+id).val(lokasiawal);
	}
	lokasi = $("#lokasi"+id).val();
	mandat = $("#mandatattr"+id).val();
	nilattr = $("#attr"+id).val();
	nilattrdef = "KonverBK++"+$("#konverbk"+id).val()+"~KonverTK++"+$("#konvertk"+id).val()+"~"
	for(k=0;k<lokasi_ok.length;k++)
	{
		lokasi_oksplit = lokasi_ok[k].split("~");
		if(lokasi_oksplit[0]==lokasi){
			location = true;
			break;
		}
	}

	//alert(tmptotaldapat+" "+qty+" "+pecahan.length+" "+lokasi_ok.length)
	if(tmptotaldapat==qty&&pecahan.length>0&&lokasi_ok.length>0){
		if(!location){
			lokasi_ok_kd = lokasi_ok[0].split("~");
			tanya = confirm("Lokasi "+lokasi_ok_kd[1]+" dapat menyimpan seluruh barang.\nTekan ok untuk menyimpan di lokasi "+lokasi_ok_kd[1]+".Tekan Cancel untuk tetap menyimpan di lokasi ini dan membaginya ke beberapa lokasi lain");
			if(tanya==true)
			{
				$("#lokasi"+id).val(lokasi_ok_kd[0]);
				$("#hidelokasi"+id).val(lokasi_ok_kd[0]);
				if(mandat=="no"||(nilattr!=""&&nilattr!=nilattrdef))
				{
					$("#Layer1").css("display","");
					$('fieldset.disableMe :input').attr('disabled', true);
					saveItem(id);
				}
				else if(mandat=="yes"&&(nilattr==""||nilattr==nilattrdef))
				{
					openAttrEnter(id);
				}
				detailNew();
			}
			else
			{
				nilaibaru = pecahan[pecahan.length-1].split("~");
				$("#lokasi"+id).val(nilaibaru[0]);
				$("#hidelokasi"+id).val(nilaibaru[0]);
				$("#qtypcs"+id).val(nilaibaru[1]);
				qtykonver = findQty(id,nilaibaru[1],nilaibaru[0]);
			//	InputQty(id,qtykonver);
				if(mandat=="no"||(nilattr!=""&&nilattr!=nilattrdef))
				{
					$("#Layer1").css("display","");
					$('fieldset.disableMe :input').attr('disabled', true);
					saveItem(id);
				}
				else if(mandat=="yes"&&(nilattr==""||nilattr==nilattrdef))
				{
					openAttrEnter(id);
				}
				for(p=pecahan.length-2;p>=0;p--){
					nilaibaru = pecahan[p].split("~");
					barisbaru = detailInsertRow(id,nilaibaru[1],nilaibaru[0],mandat);
					qtykonver = findQty(barisbaru,nilaibaru[1],nilaibaru[0]);
				}
				detailNew();
			}
		}
		else
		{
			if(mandat=="no"||(nilattr!=""&&nilattr!=nilattrdef))
			{
				$("#Layer1").css("display","");
				$('fieldset.disableMe :input').attr('disabled', true);
				saveItem(id);
			}
			else if(mandat=="yes"&&(nilattr==""||nilattr==nilattrdef))
			{
				openAttrEnter(id);
			}
			detailNew();
		}
	}
	else if(tmptotaldapat==qty){
		tanya = confirm("Tidak ada lokasi yang dapat menampung keseluruhan barang, apakah anda mau membagi barang ke beberapa lokasi berbeda ?");
		if(tanya==true)
		{
		//	alert(pecahan)
			nilaibaru = pecahan[pecahan.length-1].split("~");
			$("#lokasi"+id).val(nilaibaru[0]);
			$("#hidelokasi"+id).val(nilaibaru[0]);
			$("#qtypcs"+id).val(nilaibaru[1]);
		//	alert(nilaibaru[1])
			qtykonver = findQty(id,nilaibaru[1],nilaibaru[0]);
		//	InputQty(id,qtykonver);
			if(mandat=="no"||(nilattr!=""&&nilattr!=nilattrdef))
			{
				$("#Layer1").css("display","");
				$('fieldset.disableMe :input').attr('disabled', true);
				saveItem(id);
			}
			else if(mandat=="yes"&&(nilattr==""||nilattr==nilattrdef))
			{
				openAttrEnter(id);
			}
			for(p=pecahan.length-2;p>=0;p--){
				nilaibaru = pecahan[p].split("~");
				barisbaru = detailInsertRow(id,nilaibaru[1],nilaibaru[0],mandat);
				qtykonver = findQty(barisbaru,nilaibaru[1],nilaibaru[0]);
			}
			prosesHasil();
			detailNew();
		}
	}
	else if(parseFloat(tmptotaldapat)<parseFloat(qty)&&tmptotaldapat!=0)
	{
		tanya = confirm("Semua lokasi yang ada hanya dapat menampung sebagian jumlah barang.\nTekan Ok untuk membagi barang ke beberapa lokasi, tetapi sisa yang tidak tertampung tidak akan tersimpan.\nTekan Cancel untuk tidak menyimpan keseluruhan jumlah barang ini");
		if(tanya)
		{
			nilaibaru = pecahan[pecahan.length-1].split("~");
			$("#lokasi"+id).val(nilaibaru[0]);
			$("#hidelokasi"+id).val(nilaibaru[0]);
			$("#qtypcs"+id).val(nilaibaru[1]);
			qtykonver = findQty(id,nilaibaru[1],nilaibaru[0]);
		//	InputQty(id,qtykonver);
			if(mandat=="no"||(nilattr!=""&&nilattr!=nilattrdef))
			{
				$("#Layer1").css("display","");
				$('fieldset.disableMe :input').attr('disabled', true);
				saveItem(id);
			}
			else if(mandat=="yes"&&(nilattr==""||nilattr==nilattrdef))
			{
				openAttrEnter(id);
			}
			for(p=pecahan.length-2;p>=0;p--)
			{
				nilaibaru = pecahan[p].split("~");
				barisbaru = detailInsertRow(id,nilaibaru[1],nilaibaru[0],mandat);
		//		InputQty(barisbaru,0);
				qtykonver = findQty(barisbaru,nilaibaru[1],nilaibaru[0]);
			}
			prosesHasil();
			detailNew();
		}
	}
	else if(tmptotaldapat==0)
	{
		alert("Semua lokasi penuh, barang ini tidak dapat disimpan");
	}
	return "ok";
}

function cariSisa(jumlahqtymasuk,id)
{
	var konverbk = $("#konverbk"+id).val();
	var konvertk = $("#konvertk"+id).val();
	var konverjk = $("#konverjk"+id).val();
	var kdsatuan_now = $("#kdsatuan"+id).val();
	SatuanFlg = kdsatuan_now[0];
	if(SatuanFlg=="B"&&parseFloat(jumlahqtymasuk)>parseFloat(konverbk))
	{
		karton = Math.floor(parseFloat(jumlahqtymasuk) / parseFloat(konverbk));
		nilaiambil = parseFloat(karton) * parseFloat(konverbk);
	}
	else
	{
		if(SatuanFlg=="T"&&parseFloat(jumlahqtymasuk)>parseFloat(konvertk))
		{
			lusin = Math.floor(parseFloat(jumlahqtymasuk) / parseFloat(konvertk));
			nilaiambil = parseFloat(lusin) * parseFloat(konvertk);
		}
		else
		{
			nilaiambil = jumlahqtymasuk;
		}
	}
	return nilaiambil;
}

function findQty(id,qtypcs,lokasi)
{
	//alert(id+" "+qtypcs+ " "+lokasi)
	var mandat = $("#mandatattr"+id).val();
	var kdsatuan_now = $("#kdsatuan"+id).val();
	var satuanall = $("#satuanall"+id).val().split("<<");
	var varsatuan_B = satuanall[0].split("|");
	var varsatuan_T = satuanall[1].split("|");
	var varsatuan_K = satuanall[2].split("|");
	var satuan_B = varsatuan_B[0];
	var satuan_T = varsatuan_T[0];
	var satuan_K = varsatuan_K[0];
	var namasatuan_B = varsatuan_B[1];
	var namasatuan_T = varsatuan_T[1];
	var namasatuan_K = varsatuan_K[1];
	var konverbk = $("#konverbk"+id).val();
	var konvertk = $("#konvertk"+id).val();
	var konverjk = $("#konverjk"+id).val();
	SatuanFlg = kdsatuan_now[0];
	var prosesT = false;
	var prosesK = false;
	var prosesT2 = false;
	if(qtypcs==""){qtypcs=0;}
	if(parseFloat(qtypcs)>=parseFloat(konverbk)&&parseFloat(qtypcs)>0)
	{
		karton = Math.floor(parseFloat(qtypcs) / parseFloat(konverbk));
		sisa = parseFloat(qtypcs) % parseFloat(konverbk);
		$("#qty"+id).val(karton);
		$("#kdsatuan"+id).val("B|"+namasatuan_B);
		$("#satuan"+id).val("B|"+namasatuan_B);
		nilai = convert(id);
		$("#qtydisplay"+id).val(nilai);
		if(parseFloat(sisa)>=parseFloat(konvertk))
		{
			prosesT = true;
			qtypcs = sisa;
		}
		else
		{
			prosesK = true;
			qtypcs = sisa;
		}
	}
	else
	{
		prosesT2 = true;
	}
	if(prosesT||prosesT2)
	{
		if(parseFloat(qtypcs)>=parseFloat(konvertk)&&parseFloat(qtypcs)>0)
		{
			lusin = Math.floor(parseFloat(qtypcs) / parseFloat(konvertk));
			sisa_lusin = parseFloat(qtypcs) % parseFloat(konvertk);
			if(prosesT){
				barisbaru = detailInsertRow(id,lusin,lokasi,mandat);
				$("#kdsatuan"+barisbaru).val("T|"+namasatuan_T);
				$("#satuan"+barisbaru).val("T|"+namasatuan_T);
				nilai = convert(barisbaru);
				$("#qtydisplay"+barisbaru).val(nilai);
			}
			else
			{
				$("#qty"+id).val(lusin);
				$("#kdsatuan"+id).val("T|"+namasatuan_T);
				$("#satuan"+id).val("T|"+namasatuan_T);
				nilai = convert(id);
				$("#qtydisplay"+id).val(lusin);
			}
			if(sisa_lusin!=0)
			{
				prosesK = true;
				qtypcs = sisa_lusin;
			}
		}
		else
		{
			$("#qty"+id).val(qtypcs);
			$("#kdsatuan"+id).val("K|"+namasatuan_K);
			$("#satuan"+id).val("K|"+namasatuan_K);
			nilai = convert(id);
			$("#qtydisplay"+id).val(nilai);
		}
	}
	if(parseFloat(qtypcs)<parseFloat(konvertk)||prosesK)
	{
		if(prosesK){
			if(parseFloat(qtypcs)>0){
				barisbaru = detailInsertRow(id,qtypcs,lokasi,mandat);
				$("#kdsatuan"+barisbaru).val("K|"+namasatuan_K);
				$("#satuan"+barisbaru).val("K|"+namasatuan_K);
				nilai = convert(barisbaru);
				$("#qtydisplay"+barisbaru).val(nilai);
			}
		}
		else
		{
			$("#qty"+id).val(qtypcs);
			$("#kdsatuan"+id).val("K|"+namasatuan_K);
			$("#satuan"+id).val("K|"+namasatuan_K);
			nilai = convert(id);
			$("#qtydisplay"+id).val(nilai);
		}
	}
}

function prosesHasil()
{
	$('fieldset.disableMe :input').attr('disabled', false);
	$("#Layer1").css("display","none");
}

function saveThis(id)
{
	child.close();
	$("#Layer1").css("display","");
	$('fieldset.disableMe :input').attr('disabled', true);
	//setTimeout("saveItem('"+id+"');",0.5);
}

function saveAll()
{
	if(cekheader())
//	if(cekDetailAll()){
		//alert("A");
		$("#lainlain").submit();
//	}
}

function cekheader()
{
	if(cekoption("ket","Mengisi Keterangan"))
	return true;
	return false;
}
function cekbrg(i)
{
    if(cekoption("pcode"+i,"Anda Belum Mengisi Barang"))
	return true;
	return false;
}
function cekDetail(id)
{
	if(cekoption("pcode"+id,"Memasukkan Kode Barang"))
	if(validateForm("pcode"+id,"temppcode"+id,"Kode Barang"))
	if(cekoption("qty"+id,"Memasukkan Jumlah Barang"))
	if(validateForm2("qtypcs"+id,"tempqty"+id,"qty"+id,"Jumlah Barang"))
	if(cekAttribut(id))
		return true;
	return false;
}

function cekAttribut(id)
{
	if($("#attr"+id).val()==""&&$("#mandatattr"+id).val()=="yes")
	{
		alert("Attribut harus terisi untuk barang "+$("#nama"+id).val());
		return false;
	}
	return true;
}
function cekDetailAll()
{
	var lastRow = document.getElementsByName("pcode[]").length;
        
        for(index=0;index<lastRow;index++){
		nama = document.getElementsByName("pcode[]");
		temp = nama[index].id;
//                alert(temp);
		indexs = temp.substr(5,temp.length-5);
		if(index<parseFloat(lastRow)-1||index==0){
			if(cekoption("pcode"+indexs ,"Memasukkan Kode Barang"))
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
				return false;
			}
		}
	}
	return true;
}

function saveItem(id)
{
	if($("#transaksi").val()=="no"){
		$("#transaksi").val("yes");
		no = $("#nodok").val();
		tgl = $("#tgl").val();
		tipe = $("#hidetipe").val();
		kontak = $("#hidecontact").val();
		keterangan = $("#ket").val();
		flag = $("#flag").val();
		pcode = $("#pcode"+id).val();
		qty = $("#qty"+id).val();
		qtypcs = $("#qtypcs"+id).val();
		qtydisplay = $("#qtydisplay"+id).val();
		pcodesave = $("#savepcode"+id).val();
		base_url = $("#baseurl").val();
		satuan = $("#kdsatuan"+id).val();
		satuanj = $("#kdsatuanj"+id).val();
		lokasi = $("#hidelokasi"+id).val();
		konverbk = $("#konverbk"+id).val();
		konvertk = $("#konvertk"+id).val();
		konverjk = $("#konverjk"+id).val();
		pickmethod = $("#pickingmethod"+id).val();
		noterima = $("#noterima"+id).val();
		counternoterima = $("#counternoterima"+id).val();
		attr = $("#attr"+id).val();
		counter = $("#counter"+id).val();
		pcodebarang = $("#pcodebarang"+id).val();
		asaldata = $("#asaldata"+id).val();
		$.post(base_url+"index.php/transaksi/lainlain/save_new_item",{ 
			no:no,tgl : tgl,tipe:tipe,kontak:kontak,ket : keterangan,flag:flag,pcode:pcode,
			qty : qty,qtypcs:qtypcs,qtydisplay:qtydisplay,pcodesave:pcodesave,
			satuan:satuan,satuanj:satuanj,lokasi:lokasi,konverbk:konverbk,konvertk:konvertk,konverjk:konverjk,
			pickmethod:pickmethod,noterima:noterima,counternoterima:counternoterima,attr:attr,
			counter:counter,pcodebarang:pcodebarang,asaldata:asaldata
		},
		function(data){
			hasil = data.split("**");
			if(flag=="add")
			{
                            $("#nodok").val(hasil[0]);
                            $("#nodokumen").css("display","");
			}
			$("#counter"+id).val(hasil[1]);
			$("#savepcode"+id).val($("#pcode"+id).val());
			$('fieldset.disableMe :input').attr('disabled', false);
			$("#Layer1").css("display","none");
			if(flag=="add")
			{
				jQuery("input[name='tipe']").each(function(i) {
						jQuery(this).attr('disabled', 'disabled');
				});
			}
			$("#transaksi").val("no");
		});
	}
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
	var JmlRow = $("#totalbaris").val();
	var intCurrentRowId = parseFloat($('#detail tr').length )-2;
	nama = document.getElementsByName("pcode[]");
	temp = nama[intCurrentRowId].id;
	intCurrentRowId = temp.substr(5,temp.length-5);
	if($("#nama"+intCurrentRowId).val()!=""||$("#kdsatuan"+intCurrentRowId).val()!=""||$("#qtypcs"+intCurrentRowId).val()!="")
	{
		var intNewRowId = parseFloat(JmlRow) + 1;
		$("#pcode" + intCurrentRowId , clonedRow ).attr( { "id" : "pcode" + intNewRowId,"value" : ""} );
		$("#pick" + intCurrentRowId , clonedRow ).attr( { "id" : "pick" + intNewRowId} );
		$("#del" + intCurrentRowId , clonedRow ).attr( { "id" : "del" + intNewRowId} );
		$("#nama" + intCurrentRowId , clonedRow ).attr( { "id" : "nama" + intNewRowId,"value" : ""} );
		$("#satuan" + intCurrentRowId , clonedRow ).attr( { "id" : "satuan" + intNewRowId});
		$("#qty" + intCurrentRowId , clonedRow ).attr( { "id" : "qty" + intNewRowId,"value" : ""} );
		$("#hrg" + intCurrentRowId , clonedRow ).attr( { "id" : "hrg" + intNewRowId,"value" : ""} );
		$("#nil" + intCurrentRowId , clonedRow ).attr( { "id" : "nil" + intNewRowId,"value" : ""} );
		$("#qtydisplay" + intCurrentRowId , clonedRow ).attr( { "id" : "qtydisplay" + intNewRowId,"value" : ""} );
		$("#temppcode" + intCurrentRowId , clonedRow ).attr( { "id" : "temppcode" + intNewRowId,"value" : ""} );
		$("#counter" + intCurrentRowId , clonedRow ).attr( { "id" : "counter" + intNewRowId,"value" : ""} );
		$("#tempqty" + intCurrentRowId , clonedRow ).attr( { "id" : "tempqty" + intNewRowId,"value" : ""} );
		$("#qtypcs" + intCurrentRowId , clonedRow ).attr( { "id" : "qtypcs" + intNewRowId,"value" : ""} );
		$("#savepcode" + intCurrentRowId , clonedRow ).attr( { "id" : "savepcode" + intNewRowId,"value" : ""} );
		$("#pcodebarang" + intCurrentRowId , clonedRow ).attr( { "id" : "pcodebarang" + intNewRowId,"value" : ""} );
		$("#noterima" + intCurrentRowId , clonedRow ).attr( { "id" : "noterima" + intNewRowId,"value" : ""} );
		$("#counternoterima" + intCurrentRowId , clonedRow ).attr( { "id" : "counternoterima" + intNewRowId,"value" : ""} );
		$("#detail").append(clonedRow);
		$("#detail tr:last" ).attr( "id", "baris" +intNewRowId ); // change id of last row
		$("#satuan"+intNewRowId).empty();
		$("#totalbaris").val(intNewRowId);
		$("#satuan"+intNewRowId).attr("disabled","");
		$("#pcode"+intNewRowId).attr("disabled","");
		$("#qty"+intNewRowId).attr("readonly",false);
		$("#pcode" + intNewRowId).focus();
	}
}

function detailInsertRow(id,qtybaru,lokasibaru,mandat)
{
	var intNewRowId = parseFloat($("#totalbaris").val()) + 1;
	var clonedRow = $("#baris"+id).clone(true).attr("id","baris"+intNewRowId);
	intCurrentRowId = id;
	$("#pcode" + intCurrentRowId , clonedRow ).attr( { "id" : "pcode" + intNewRowId} );
	$("#pick" + intCurrentRowId , clonedRow ).attr( { "id" : "pick" + intNewRowId} );
	$("#del" + intCurrentRowId , clonedRow ).attr( { "id" : "del" + intNewRowId} );
	$("#nama" + intCurrentRowId , clonedRow ).attr( { "id" : "nama" + intNewRowId} );
	$("#satuan" + intCurrentRowId , clonedRow ).attr( { "id" : "satuan" + intNewRowId});
	$("#qty" + intCurrentRowId , clonedRow ).attr( { "id" : "qty" + intNewRowId,"value" : qtybaru} );
	$("#qtydisplay" + intCurrentRowId , clonedRow ).attr( { "id" : "qtydisplay" + intNewRowId} );
	$("#tempqty" + intCurrentRowId , clonedRow ).attr( { "id" : "tempqty" + intNewRowId} );
	$("#qtypcs" + intCurrentRowId , clonedRow ).attr( { "id" : "qtypcs" + intNewRowId} );
	$("#konverbk" + intCurrentRowId , clonedRow ).attr( { "id" : "konverbk" + intNewRowId} );
	$("#konvertk" + intCurrentRowId , clonedRow ).attr( { "id" : "konvertk" + intNewRowId} );
	$("#konverjk" + intCurrentRowId , clonedRow ).attr( { "id" : "konverjk" + intNewRowId} );
	$("#kdsatuanj" + intCurrentRowId , clonedRow ).attr( { "id" : "kdsatuanj" + intNewRowId} );
	$("#kdsatuan" + intCurrentRowId , clonedRow ).attr( { "id" : "kdsatuan" + intNewRowId} );
	$("#satuanall" + intCurrentRowId , clonedRow ).attr( { "id" : "satuanall" + intNewRowId} );
	$("#baris"+id).after(clonedRow);
	$("#totalbaris").val(intNewRowId);
	tipe = $("#hidetipe").val();
	if(tipe=="masuk"){
		if(mandat=="yes")
		{
//			document.getElementById("tanda"+intNewRowId).innerHTML = "O";
		}
		else{
//			document.getElementById("tanda"+intNewRowId).innerHTML = "";
		}
	}
	else if(tipe=="keluar"){
//		document.getElementById("tanda"+intNewRowId).innerHTML = "";
	}
	return intNewRowId;
}

function deleteRow(obj)
{

	var objek = obj.id;
	var id = objek.substr(3,objek.length-3);
	var pcode = $("#pcode"+id).val();
	var banyakBaris = 1;
	var totalbaris = $("#totalbaris").val();
	var lastRow = document.getElementsByName("pcode[]").length;
	for(index=0;index<lastRow;index++){
		nama = document.getElementsByName("pcode[]");
		temp = nama[index].id;
		indexs = temp.substr(5,temp.length-5);
		if($("#savepcode"+indexs).val()!=""){
			banyakBaris++;
		}
	}
	if($("#savepcode"+id).val()==""&&banyakBaris>1){
		$('#baris'+id).remove();
		$("#totalbaris").val(parseFloat(totalbaris)-1);
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
			no = $("#nodok").val();
			if(pcode!=""){
				var r=confirm("Apakah Anda Ingin Menghapus Kode Barang "+pcode+" ?");
				if(r==true){
					$('#baris'+id).remove();
					if(no!=""){
						deleteItem(pcode,counter);
					}
				}
			}
		}
	}
}

function deleteItem(pcode,counter)
{
	if($("#transaksi").val()=="no"){
		no = $("#nodok").val();
		$("#transaksi").val("yes");
		base_url = $("#baseurl").val();
		$.post(base_url+"index.php/transaksi/lainlain/delete_item",{ 
			no:no,pcode:pcode,counter:counter,tgl:$("#tgl").val(),tipe:$("#hidetipe").val()},
		function(data){
			$("#transaksi").val("no");
		});
	}
}
function tambahbaris(tableID) {

        var clonedRow =  $("#"+tableID+" tr:last").clone(true);
//	var clonedRow = $("#detail tr:last").clone(true);
	var intCurrentRowId = parseFloat($('#detail tr').length )-2;
	nama = document.getElementsByName("pcode[]");
	tm = nama[intCurrentRowId].id;
	intCurrentRowId = tm.substr(5,tm.length-5);
	var intNewRowId = parseFloat(intCurrentRowId) + 1;

$("#del" + intCurrentRowId , clonedRow ).attr( { "id" : "del" + intNewRowId,"value" : ""} );
$("#pcode" + intCurrentRowId , clonedRow ).attr( { "id" : "pcode" + intNewRowId,"value" : ""} );
$("#pick" + intCurrentRowId , clonedRow ).attr( { "id" : "pick" + intNewRowId} );
$("#nama" + intCurrentRowId , clonedRow ).attr( { "id" : "nama" + intNewRowId,"value" : ""} );
$("#satuan" + intCurrentRowId , clonedRow ).attr( { "id" : "satuan" + intNewRowId,"value" : ""} );
$("#qty" + intCurrentRowId , clonedRow ).attr( { "id" : "qty" + intNewRowId,"value" : ""} );
$("#hrg"+ intCurrentRowId , clonedRow ).attr( { "id" : "hrg" + intNewRowId,"value" : ""} );
$("#nil"+ intCurrentRowId , clonedRow ).attr( { "id" : "nil" + intNewRowId,"value" : ""} );
$("#qtydisplay"+ intCurrentRowId , clonedRow ).attr( { "id" : "qtydisplay" + intNewRowId,"value" : ""} );
$("#tempqty"+ intCurrentRowId , clonedRow ).attr( { "id" : "tempqty" + intNewRowId,"value" : ""} );
$("#qtypcs"+ intCurrentRowId , clonedRow ).attr( { "id" : "qtypcs" + intNewRowId,"value" : ""} );
$("#qtykom"+ intCurrentRowId , clonedRow ).attr( { "id" : "qtykom" + intNewRowId,"value" : ""} );
	$("#detail").append(clonedRow);
	$("#detail tr:last" ).attr( "id", "tr" +intNewRowId ); // change id of last row
	return intNewRowId;

}