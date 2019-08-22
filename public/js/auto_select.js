function select_outlet(){
 	var thesales=document.getElementById('sales_r').value;
 	var pilihan=document.getElementById('outlet');
 	//alert(thesales);
	if(thesales !=""){
	jsrsExecute('dir_file/file.process_select.php',outs,'select_outlet',thesales);
	}
	else{
		for(i=1; i<pilihan.options.length; i++){
			pilihan.remove(i);
		}
	
	}
}

function select_outletrt3(thesales){
 	jsrsExecute('dir_file/file.process_select.php',outs,'select_outlet',thesales);
}

function select_outletnya2(){
 	var thesales=document.getElementById('salesan').value;
 	if(thesales !=''){
	jsrsExecute('dir_file/file.process_select.php',outs,'select_outlet',thesales);
	}
	document.getElementById('lihat1').style.display="";
}

function outs(str){
 	var pilihan=document.getElementById('outlet');
 	if(str=="no"){
 	 	alert("Outlet Untuk Sales Tersebut Tidak Ada");
		for(i=1; i<pilihan.options.length; i++){
			pilihan.remove(i);
		}
	}
	else{
	 	var data=str.split('|');
		var selected_outlet=document.getElementById('outlet_select').value;
		
		for(k=1; k < data.length-1; k++){	
		 	var isinya=data[k];
		 	var isi='isi' + k;
			var isi=isinya.split('-');
			
			if(isi[0]==selected_outlet){
			pilihan.options[k].selected=new Option(isinya,isi[0]);
			}
			else{			
			pilihan.options[k]=new Option(isinya,isi[0]);	
			}
		
		}
	}
}