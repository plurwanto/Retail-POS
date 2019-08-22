var kodebrg = "kdbrg";
var namabrg = "nmbrg";
var jualm = "jualm";
var jualg = "jualg";
var plu = "plu";
var indexingkd = new Array();
var indexingnm = new Array();
var indexingjg = new Array();
var indexingjm = new Array();
var indexingpl = new Array();
indexingkd[0] = "kdbrg1";
indexingnm[0] = "nmbrg1";
indexingjg[0] = "jualg1";
indexingjm[0] = "jualm1";
indexingpl[0] = "plu1";

function popbrg(index,base_url)
{
	if(cekheader())
	{
		url = base_url+"index.php/pop/grup_barang/index/master/";
		window.open(url+(index),'popuppage','width=750,height=500,top=100,left=150');
	}
}

function popbrgFromSales(index,base_url)
{
	url = base_url+"index.php/pop/grup_barang/index/sales/";
	window.open(url+(index),'popuppage','width=750,height=500,top=100,left=150');
}

function iterate(obj){
  	var delRow = obj.parentNode.parentNode;
	var tbl = delRow.parentNode.parentNode;
	var rIndex = delRow.sectionRowIndex;
	document.getElementById("nomor").value = rIndex;
}

function keyShortcut(e,row,where,url)
{
	if(window.event) // IE
	{
		var code = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		var code = e.which;
	}
	if (code == 13) { //checks for the escape key
		if(cekheader())
		{
		 	if(where=="kdbrg")
		 	{
				getCode(url,row);
			}
			else if(where=="plu")
			{
				cekgrup('tambah');
			}
			else
			{
				pindahkursor(row);
			}
		}
	}
}

function pindahkursor(row)
{
	document.getElementById("plu"+row).focus();
}

function keypresstest(e,obj){    //define keyboard action on new row
    var objId;
    if (obj != null) {
      objId = obj.id;
    } else {
      objId = this.id;
    }
    index= trimIt(document.getElementById('nomor').value);
    url = document.getElementById("urladd").value;
    if(objId.charAt(0)=='k'){
		keyShortcut(e,index,"kdbrg",url)
	}
	else if(objId.charAt(0)=='p'){
	 	keyShortcut(e,index,"plu",url)
	}
	else{
	 	keyShortcut(e,index,"grup",url)
	}
}

function getCode(url,row)
{
	var lastRow = indexingkd.length + 1;
	var kodebarang = "kdbrg" + row;
	var exist = false;
	for(a = 1;a<lastRow;a++){
	 	if(a==row){ a = a + 1; }
	 	if(a==lastRow){ break; }
		var kd = "kdbrg" + a;
		if(document.getElementById(kd).value==document.getElementById(kodebarang).value)
		{
			alert("Kode Barang Sudah Ada");
			var exist = true;
			break;
		}
	}
	if(!exist)
	{
		$("#kdtemp"+row).val($("#kdbrg"+row).val());
		$.post(url+"index.php/master/grup_harga/getPCode",{ pcode: $("#kdbrg"+row).val()},
		function(data){
		   if(data=="*-*0.00")
		   {
				alert("Kode Barang Tidak Ditemukan");
				$("#nmbrg"+row).val("");
			    $("#jualm"+row).val("");
			    $("#kdtemp"+row).val("");
				$("#kdbrg"+row).focus();
		   }
		   else
		   {
			   list = data.split("*-*");
			   $("#nmbrg"+row).val(list[0]);
			   $("#jualm"+row).val(list[1]);
			   $("#jualg"+row).focus();
		   }
		});
	}
}

function cekheader()
{
	if(cekoption("kode","Memasukkan Kode Grup Harga"))
	if(cekoption("nama","Memasukkan Keterangan"))
		return true;
	return false;
}

function cekdetail()
{
	var lastRow = indexingkd.length + 1;
	for(a = 1;a<lastRow;a++){
	 	var kode = trimIt(document.getElementById(kodebrg + a).value);
	 	var nama = trimIt(document.getElementById(namabrg + a).value);
	 	var harga = trimIt(document.getElementById(jualg + a).value);
	 	var pl = document.getElementById(plu + a).value;
	 	var temp = document.getElementById("kdtemp" + a).value;
	 	if((kode==""||nama=="")||(harga==""&&pl==""))
	 	{
	 	 	document.getElementById(kodebrg + a).focus();
			alert("Detail Harus Diisi");
			return false;
		}
		if(kode!=temp)
		{
			document.getElementById(kodebrg + a).focus();
			alert("Kode Barang Tidak Valid");
			return false;
		}
	}
	return true;
}

function cekgrup(flag)
{
 	if(cekheader())
 	if(cekdetail())
 		putdetail(flag);
}
function putdetail(flag)
{
 	document.getElementById("tempall").value = "";
 	var lastRow = indexingkd.length + 1;
	var temp = "";
	for(a = 1;a<lastRow;a++){
	 	var kode  = trimIt(document.getElementById(kodebrg + a).value);
	 	var harga = trimIt(document.getElementById(jualg + a).value);
	 	var pl 	  = trimIt(document.getElementById(plu + a).value);
	 	var str   = kode + "*-*" + harga + "*-*" + pl;
	 	var temp  = temp + "<.<" + str;
	}
	document.getElementById("how").value = flag;
	document.getElementById("tempall").value = temp;
	document.getElementById("grup_harga").submit();
}

function createElement(row,cell,num,txtinp,element,type,name,size,max,read,focused,key,val,dir,flagBtn,iteration){
  //make new element on new rows
	cell = row.insertCell(num);
	txtinp = document.createElement(element);
	txtinp.setAttribute('type', type);
	txtinp.setAttribute('name', name);
	txtinp.setAttribute('id', name);
	txtinp.setAttribute('size', size);
	txtinp.setAttribute('nowrap', 'nowrap');

	if(max!="a"){			txtinp.setAttribute('maxlength', max);	}
	if(read!=""){			txtinp.setAttribute('readonly', 'readonly'); }
	if(focused=="iterate"){	txtinp.onfocus = function () {iterate(this);};	}
	if(key=="key"){			txtinp.onkeydown = keypresstest;	}
	if(val!=""){			txtinp.setAttribute('value',val); }
	if(dir!=""){			txtinp.setAttribute('dir','RTL');	}

	cell.appendChild(txtinp);

	if(flagBtn=="btn"){
	 	s = createButton("",row,cell,num,'btn',"-",'...',iteration,"btn");
	 	row.myRows = new myRowBtn(s);
	}
	return txtinp;
}

function createButton(flag,row,cell,num,name,access,val,klik,name1){ //crete new button on new row;
	if(flag=="new"){	cell = row.insertCell(num);	}
	base_url = document.getElementById("urladd").value;
	name = document.createElement('input');
	name.setAttribute('type', 'button');
	name.setAttribute('value', val);
	name.setAttribute('name', name1+klik);
	name.setAttribute('id', name1+klik);
	if (klik=="delete"){
	 	name.onclick = function () {deleteCurrentRow(this)};
	}
	else if(val=="..."){	name.onclick = function () {popbrg(klik,base_url)};	}
	cell.appendChild(name);
	return name;
}

function myRowObject(a0,a1,a2,a3,a4,a5) // define object for new row
{
 	this.a0 = a0; 		this.a1 = a1;
 	this.a2 = a2; 		this.a3 = a3;
 	this.a4 = a4;		this.a5 = a5;
}

function myRowBtn(s){
	this.s=s;
}

function addRaw(nilkd,nilnm,nilmaster,nilgrup,nilplu){  //create new rows
 	var tbl = document.getElementById('detail');
	var lastRow = indexingkd.length + 1;
 	var iteration = lastRow;

	var num = 0;
	document.getElementById("nomor").value = iteration;
	var flag = document.getElementById("statusedit").value;
	var row = tbl.insertRow(lastRow);
	var cell = "cell" + num;
	row.bordercolor = '#FFFFFF';

	indexingkd[iteration-1] = kodebrg + iteration;
	indexingnm[iteration-1] = namabrg + iteration;
	indexingjg[iteration-1] = jualg + iteration;
	indexingjm[iteration-1] = jualm + iteration;
	indexingpl[iteration-1] = plu + iteration;
	if(flag=="edit")
	{
		iterated = "iterate";
		key = "key";
		btn= "btn";
		createButton("new",row,cell,num,'BtnEl','','Delete',"delete");
		num++;	cell = "cell" + num;
	}
	else
	{
		iterated = "";
		key = "";
		btn= "";
	}

	txtinp0= createElement(row,cell,num,"txtinp0",'input','text',kodebrg + iteration,"20","15","",iterated,key,nilkd,"",btn,iteration);

	num++;	cell = "cell" + num;
	txtinp1 = createElement(row,cell,num,"txtinp1",'input','text',namabrg + iteration,"35","a","readonly","","",nilnm,"","",iteration);

	num++;	cell = "cell" + num;
	txtinp2 = createElement(row,cell,num,"txtinp2",'input','text',jualm + iteration,"18","a","readonly","","",nilmaster,"","",iteration);

	num++;	cell = "cell" + num;
	txtinp3 = createElement(row,cell,num,"txtinp3",'input','text',jualg + iteration,"15","14","","iterate","key",nilgrup,"","",iteration);

	num++;	cell = "cell" + num;
	txtinp4 = createElement(row,cell,num,"txtinp4",'input','text',plu + iteration,"20","15","","iterate","key",nilplu,"","",iteration);

	num++;	cell = "cell" + num;
	txtinp5 = createElement(row,cell,num,"txtinp5",'input','hidden',"kdtemp" + iteration,"20","a","","","",nilkd,"","",iteration);

	row.myRow = new myRowObject(txtinp0,txtinp1,txtinp2,txtinp3,txtinp4,txtinp5);
	document.getElementById(kodebrg + iteration).focus();
}

function deleteCurrentRow(obj){
	var delRow = obj.parentNode.parentNode;
	var tbl = delRow.parentNode.parentNode;
	var rIndex = delRow.sectionRowIndex;
	var kode= kodebrg +(rIndex);
	var prev = trimIt(document.getElementById(kode).value);
	if(prev!=''){
	 	if(cekheader()&&cekdetail()){
		    var r=confirm("Apakah Anda Ingin Menghapus Kode Barang "+prev+"");
		 	if(r==true){
				var rowArray = new Array(delRow);
				deleteRows(rowArray);
				reorderRows(rIndex -1 );
				cekgrup('tambah');
			}
		}
	}
	else{
		var rowArray = new Array(delRow);
		deleteRows(rowArray);
		reorderRows(rIndex - 1);
	}
}

function deleteRows(rowObjArray)
{
	for (var i=0; i<rowObjArray.length; i++) {
		var rIndex = rowObjArray[i].sectionRowIndex;
		rowObjArray[i].parentNode.deleteRow(rIndex);
	}
}

function reorderRows(index)
{
	indexingkd.splice(index, 1);
	indexingnm.splice(index, 1);
	indexingjg.splice(index, 1);
	indexingjm.splice(index, 1);
	indexingpl.splice(index, 1);
}