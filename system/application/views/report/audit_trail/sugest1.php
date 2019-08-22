
$(document).ready(function(){
	$(document).click(function(){
		$("#ajax_response1").fadeOut('slow');
	});
	$("#keyword1").focus();
	var offset = $("#keyword1").offset();
	var width = $("#keyword1").width()-2;
	$("#ajax_response1").css("left",offset.left); 
	$("#ajax_response1").css("width",width);
	$("#keyword1").keyup(function(event){
		 //alert(event.keyCode);
		 var keyword1 = $("#keyword1").val();
		 if(keyword1.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13)
			 {
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: "<?php echo base_url()?>index.php/report/audit_trail/caribrg",
				   data: "data="+keyword1,
				   success: function(msg){	
					if(msg != 0)
					  $("#ajax_response1").fadeIn("slow").html(msg);
					else
					{
					  $("#ajax_response1").fadeIn("slow");	
					  $("#ajax_response1").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:first").addClass("selected");
					 }
				 break;
				 case 38:
				 {
					  found = 0;
					  $("li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $("li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$("li:last").addClass("selected");
				 }
				 break;
				 case 13:
					$("#ajax_response1").fadeOut("slow");
					$("#keyword1").val($("li[class='selected'] a").text());
				 break;
				}
			 }
		 }
		 else
			$("#ajax_response1").fadeOut("slow");
	});
	$("#ajax_response1").mouseover(function(){
		$(this).find("li a:first-child").mouseover(function () {
			  $(this).addClass("selected");
		});
		$(this).find("li a:first-child").mouseout(function () {
			  $(this).removeClass("selected");
		});
		$(this).find("li a:first-child").click(function () {
			  $("#keyword1").val($(this).text());
			  $("#ajax_response1").fadeOut("slow");
		});
	});
});