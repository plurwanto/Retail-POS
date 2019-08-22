function number_format(a, b, c, d)
{
	a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);
	e = a + '';
	f = e.split('.');
	if (!f[0]) {
	f[0] = '0';
	}
	if (!f[1]) {
	f[1] = '';
	}
	if (f[1].length < b) {
	g = f[1];
	for (i=f[1].length + 1; i <= b; i++) {
	g += '0';
	}
	f[1] = g;
	}
	if(d != '' && f[0].length > 3) {
	h = f[0];
	f[0] = '';
	for(j = 3; j < h.length; j+=3) {
	i = h.slice(h.length - j, h.length - j + 3);
	f[0] = d + i +  f[0] + '';
	}
	j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
	f[0] = j + f[0];
	}
	c = (b <= 0) ? '' : c;
	
	return f[0] + c + f[1];
}

function SetNetto()
	{ 							
		document.getElementById('qty1').focus();
		
		qty1              = Number(document.getElementById('qty1').value);
		jualmtanpaformat1 = Number(document.getElementById('jualmtanpaformat1').value);
		
		netto1            = qty1 * jualmtanpaformat1;
		
		document.getElementById('netto1').value = number_format(netto1, 0, ',', '.');
		document.getElementById('nettotanpaformat1').value = netto1;		
	}
		
	function clear_trans(url)
	{
		if(confirm("Anda yakin ingin menghapus transaksi ini"))
			{
				document.forms["pindah"].submit();
			}
		else{
				return false;
			}
	}
	
	function oncek()
	{
		if(document.form1.pilihan[0].checked == true){
			document.getElementById('cash_bayar').disabled=false;
			document.getElementById('cash_bayar').focus();
		
			if(document.getElementById('id_kredit').value == "")
			{ document.getElementById('id_kredit').disabled=true;}
			else { document.getElementById('id_kredit').disabled=false;}
			
			if(document.getElementById('id_debet').value == "")
			{ document.getElementById('id_debet').disabled=true;}
			else{document.getElementById('id_debet').disabled=false;}
			
			if(document.getElementById('id_voucher').value == "")
			{ document.getElementById('id_voucher').disabled=true;}
			else{document.getElementById('id_voucher').disabled=false;}
			
			if(document.getElementById('kredit_bayar').value == "")
			{document.getElementById('kredit_bayar').disabled = true;}
			else{document.getElementById('kredit_bayar').disabled = false;}
			
			if(document.getElementById('debet_bayar').value == "")
			{document.getElementById('debet_bayar').disabled = true;}
			else{document.getElementById('debet_bayar').disabled = false;}
			
			if(document.getElementById('voucher_bayar').value == "")
			{document.getElementById('voucher_bayar').disabled = true;}
			else{document.getElementById('voucher_bayar').disabled = false;}
	}
	else if(document.form1.pilihan[1].checked == true){
			if(document.getElementById('cash_bayar').value == "")
			{ document.getElementById('cash_bayar').disabled=true;}
			else { document.getElementById('cash_bayar').disabled=false;}
			
			document.getElementById('id_kredit').disabled=false;
			document.getElementById('id_kredit').focus();
		
			if(document.getElementById('id_debet').value == "")
			{ document.getElementById('id_debet').disabled=true;}
			else{document.getElementById('id_debet').disabled=false;}
			
			if(document.getElementById('id_voucher').value == "")
			{ document.getElementById('id_voucher').disabled=true;}
			else{document.getElementById('id_voucher').disabled=false;}
			
			if(document.getElementById('debet_bayar').value == "")
			{document.getElementById('debet_bayar').disabled = true;}
			else{document.getElementById('debet_bayar').disabled = false;}
			
			if(document.getElementById('voucher_bayar').value == "")
			{document.getElementById('voucher_bayar').disabled = true;}
			else{document.getElementById('voucher_bayar').disabled = false;}
	}
	else if(document.form1.pilihan[2].checked == true){
			if(document.getElementById('cash_bayar').value == "")
			{ document.getElementById('cash_bayar').disabled=true;}
			else { document.getElementById('cash_bayar').disabled=false;}
			
			if(document.getElementById('id_kredit').value == "")
			{ document.getElementById('id_kredit').disabled=true;}
			else { document.getElementById('id_kredit').disabled=false;}
			
			document.getElementById('id_debet').disabled=false;
			document.getElementById('id_debet').focus();
		
			if(document.getElementById('id_voucher').value == "")
			{ document.getElementById('id_voucher').disabled=true;}
			else{document.getElementById('id_voucher').disabled=false;}
			
			if(document.getElementById('kredit_bayar').value == "")
			{document.getElementById('kredit_bayar').disabled = true;}
			else{document.getElementById('kredit_bayar').disabled = false;}
			
			if(document.getElementById('voucher_bayar').value == "")
			{document.getElementById('voucher_bayar').disabled = true;}
			else{document.getElementById('voucher_bayar').disabled = false;}	
		}
	else if(document.form1.pilihan[3].checked == true){
			if(document.getElementById('cash_bayar').value == "")
			{ document.getElementById('cash_bayar').disabled=true;}
			else { document.getElementById('cash_bayar').disabled=false;}
			
			if(document.getElementById('id_kredit').value == "")
			{ document.getElementById('id_kredit').disabled=true;}
			else { document.getElementById('id_kredit').disabled=false;}
			
			if(document.getElementById('id_debet').value == "")
			{ document.getElementById('id_debet').disabled=true;}
			else{document.getElementById('id_debet').disabled=false;}
			
			document.getElementById('id_voucher').disabled=false;
			document.getElementById('id_voucher').focus();
			
			if(document.getElementById('kredit_bayar').value == "")
			{document.getElementById('kredit_bayar').disabled = true;}
			else{document.getElementById('kredit_bayar').disabled = false;}
			
			if(document.getElementById('debet_bayar').value == "")
			{document.getElementById('debet_bayar').disabled = true;}
			else{document.getElementById('debet_bayar').disabled = false;}			
		}	
	}
	
	function SetKembali()
	{ 		
                total_biaya 			= Number(document.getElementById('total_biaya').value);
		
		cash_bayar        		= Number(document.getElementById('cash_bayar').value);
		kredit_bayar			= Number(document.getElementById('kredit_bayar').value);
		debet_bayar				= Number(document.getElementById('debet_bayar').value);
		voucher_bayar			= Number(document.getElementById('voucher_bayar').value);			
		
		total_bayar				= cash_bayar + kredit_bayar + debet_bayar + voucher_bayar;		
		kembali					= total_bayar - total_biaya;
		
		document.getElementById('total_bayar_hide').value = total_bayar;
		document.getElementById('total_bayar').value = number_format(total_bayar, 0, ',', '.');
		document.getElementById('cash_kembali').value = number_format(kembali, 0, ',', '.');		
	}
	
	function KreditCustomer(e,row,url)
	{
		if(window.event)
		{
			var code = e.keyCode;
		}
		else if(e.which)
		{
			var code = e.which;
		}
		
		if (code == 13)
		{
			if(document.getElementById('id_kredit').value !== "")
			{
				document.getElementById('kredit_bayar').disabled=false;
				document.getElementById('kredit_bayar').focus();
			}
			else{
				document.getElementById('kredit_bayar').disabled=true;
				}
		}
	
	}
	
	function DebetCustomer(e,row,url)
	{
		if(window.event)
		{
			var code = e.keyCode;
		}
		else if(e.which)
		{
			var code = e.which;
		}
		
		if (code == 13)
		{
			if(document.getElementById('id_debet').value !== "")
			{
				document.getElementById('debet_bayar').disabled=false;
				document.getElementById('debet_bayar').focus();
			}
			else{
				document.getElementById('debet_bayar').disabled=true;
				}
		}
	
	}
function getCodeForSales(e,row,url,action)
{
    if(window.event)
    {
        var code = e.keyCode;
    }
    else if(e.which)
    {
        var code = e.which;
    }
    if (code == 13)
    {
        if(action == 'insert')
        {
            PCode = document.getElementById('kdbrg' + row).value;
//                                                alert(PCode);
            $.ajax({
                type: "POST",
                url: url+"index.php/master/grup_harga/DetailItemForSales/"+PCode,
                success: function(msg){
					//alert(msg);
                    if(msg=="salah"){
                        alert("Kode / Barcode Salah !!!");
                        document.getElementById('kdbrg1').value = "";
                    }else{
                        var jsdata = msg;
                        eval(jsdata);

                        document.getElementById('kdbrg1').value = datajson[0].PCode;//tambahannya
                        document.getElementById('nmbrg' + row).value = datajson[0].NamaStruk;
                        document.getElementById('qty' + row).value = 1;
                        document.getElementById('jualm' + row).value = number_format(datajson[0].HargaJual, 0, ',', '.');
                        document.getElementById('netto' + row).value = number_format(datajson[0].HargaJual, 0, ',', '.');
                        document.getElementById('jualmtanpaformat' + row).value = datajson[0].HargaJual;
                        //document.getElementById('disk' + row).value = datajson[0].disc;
                        document.getElementById('nettotanpaformat' + row).value = datajson[0].HargaJual;
                        document.forms["salesform"].submit();
                    }
                }
            });
        }
        else
        {
            if(document.getElementById('qty1').value == "")
            {
                alert('Qty tidak boleh kosong');
            }
            else
            {
                PCode = document.getElementById('kdbrg' + row).value;
                $.post(url+"index.php/transaksi/sales/cekkode",{ PCode:PCode},
                    function(data){
                        if(data=="ok"){
                            document.forms["salesform"].submit();
                        }else{
                            alert('Kode barang salah');
                        }
                    });
            }
        }
    }
}