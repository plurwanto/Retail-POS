<?php
$this->load->view('header');
$this->load->view('space');
$reportlib = new report_lib();
?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.min.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/zebra_datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/default.css" />
  
  <style type="text/css">
    #a, #b{ margin: 0px;
		padding: 0px;
		height: 10px;
		width: 10px;
		position: absolute;
	}
      #a{ visibility:visible; }
      #b{ visibility:hidden; }
      
  </style>
<script language="javascript">
function rawform()
{
	base_url = $("#baseurl").val();
	url = base_url+"index.php/pop/barangreport/index/";
	window.open(url,'popuppage','scrollbars=yes,width=750,height=500,top=150,left=150');
}
function submitThis()
{
			$("#excel").val("");
			document.getElementById("search").submit();
			//$("#search").submit();
}
function cekpilihan()
{
	strjenis = $("input[@name='jenis']:checked").val();
	if(strjenis=="terima")
	{
		jQuery("input[name='opt']").each(function(i) {
			jQuery(this).attr('disabled', 'disabled');
		});

	}
	else
	{
		jQuery("input[name='opt']").each(function(i) {
			jQuery(this).attr('disabled', '');
		});
	}
}
function caritgl(){
	base_url = $("#baseurl").val();
	 $('#tgl').Zebra_DatePicker({
        format: 'm-Y'
    });
	//$('#tglbongkar').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
}
</script>
<body onload="caritgl()">
<p>
<form method="POST" name="search" id="search" action="<?=base_url()?>index.php/report/stok_barang/cari/" onsubmit="return false">
<table border="2" cellpadding="3" cellspacing="3" style="margin-left:10px">
	<tr>
		<td>
			<table border="0" cellpadding="3" cellspacing="3"><!--
				<tr>
				<td nowrap>Bulan</td>
				<td nowrap>:</td>
				<td nowrap>
					<select size="1" height="1" name ="bulan" id="bulan">
						<?php
						   for($t = 1;$t<13;$t++){
								$selected = "";
								if(strlen($t)==1){
									$t = '0'.$t;
								} 
								if($bulan==$t){
									$selected = "selected='selected'";
								}
								?>
								<option <?=$selected?> value="<?=$t?>"><?=$t?></option>
		<?php				}
						?>
					</select>
				</td>
                                <tr>
                                    <td nowrap>Tahun</td>
                                    <td nowrap>:</td>
                                    <td nowrap>
                                        <select size="1" name="tahun" id="tahun">
                                            <option value="2013">2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2013">2016</option>
                                        </select>
                                    </td>

                                </tr>
				</tr>
-->
                            <tr>
                                <?php
								
				$mylib = new globallib();
				echo $mylib->write_textbox("Tanggal","tgl",$tanggal,"15","15","readonly='readonly'","text",'',"1");
				echo $reportlib->write_textbox_barang("Barang","kdbrg1","kdbrg2",$kdbrg1,$kdbrg2,"10","20","","onClick='rawform();'","onClick='rawform();'");
				?>
                                
                               
                                </tr>
                                
			</table>
		</td>
	</tr>
	<tr bordercolor="#FFFFFF">
		<td colspan="2" align="center"><input type="button" value="Search (*)" onclick="submitThis()">
		<input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
		<input type='hidden' value='<?=$excel?>' id="excel" name="excel">
		</td>
	</tr>
</table>
</form>
<?php
	$this->load->view("report/stok_barang/reportR");
?>
</body>
<?php
$this->load->view('footer'); ?>
<script type="text/javascript">
    $(function(){
        $(":radio.pil").click(function(){
            if($(this).val() == "tgl"){
                document.getElementById("b").style.visibility="hidden";
                document.getElementById("a").style.visibility="visible";
                document.getElementById("lot").value="";
            }else{
                document.getElementById("b").style.visibility="visible";
                document.getElementById("a").style.visibility="hidden";
                document.getElementById("tglbongkar").value="";
            }
        });
    });
</script>