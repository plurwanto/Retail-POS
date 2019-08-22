function loadTooltips(){
	new xTooltipGroup('select', 'selects', 'mouse', 0, 40);
	new xTooltipGroup('plaininput', 'plaininputs', 'mouse', 0, 40);
}

function autoComplete (field, select, property, forcematch) {
	var autoComplete_found = false;
	var autoComplete_i = 0;
	for (var autoComplete_i = 0; autoComplete_i < select.options.length; autoComplete_i++) {
		if (select.options[autoComplete_i][property].toUpperCase().indexOf(field.value.toUpperCase()) == 0) {
			autoComplete_found=true; break;
		}
	}
	if (autoComplete_found)
		select.selectedIndex = autoComplete_i;
	else
		select.selectedIndex = 0;
	if (field.createTextRange) {
		if (forcematch && !autoComplete_found) {
			field.value=field.value.substring(0,field.value.length-1);
			return;
		}
		var cursorKeys ="8;46;37;38;39;40;33;34;35;36;45;";

		if (cursorKeys.indexOf(event.keyCode+";") == -1) {
			var r1 = field.createTextRange();
			var oldValue = r1.text;
			var newValue = autoComplete_found ? select.options[autoComplete_i][property] : oldValue;
			if (newValue != field.value) {
				field.value = newValue;
				var rNew = field.createTextRange();
				rNew.moveStart('character', oldValue.length) ;
				rNew.select();
			}
		}
	}
}

function addAValueToList(avalue,listdest){
	if (avalue!=null && avalue.trim().length>0){
		var addAValueToList_i=0;

		for (addAValueToList_i=0;addAValueToList_i<listdest.options.length;addAValueToList_i++){
			if (listdest.options[addAValueToList_i].value.trim().toUpperCase()==avalue.trim().toUpperCase()) {
				listdest.selectedIndex=-1;
				listdest.options[addAValueToList_i].selected=true;
				return false;
			}
		}
		listdest.selectedIndex=-1;
		j=listdest.options.length;
		listdest.options[j]=new Option(avalue.trim(),avalue.trim(),false,false);
		listdest.selectedIndex=j;
		return true;
	} else {
		return false;
	}
}

function addAValueTextPairToList(avalue,thetext,listdest){
	if (avalue!=null && avalue.trim().length>0){
		var addAValueTextPairToList_i=0;
		for (addAValueTextPairToList_i=0;addAValueTextPairToList_i<listdest.options.length;addAValueTextPairToList_i++){
			if (listdest.options[addAValueTextPairToList_i].text.trim().toUpperCase()==thetext.trim().toUpperCase()) {
				listdest.selectedIndex=-1;
				listdest.options[addAValueTextPairToList_i].selected=true;
				return false;
			}
		}
		listdest.selectedIndex=-1;
		j=listdest.options.length;
		listdest.options[j]=new Option(thetext.trim(),avalue.trim(),false,false);
		listdest.selectedIndex=j;
		return true;
	} else {
		return false;
	}
}

function removeSelectedFromList(listsource) {
	var removed=false;
	//window.alert(listsource);
	if (listsource && listsource.selectedIndex!=-1){
		j=0;
		while (j<listsource.options.length){
			if (listsource.options[j].selected==true){
				listsource.options[j]=null;
				removed=true;
			} else {
				j++;
			}
		}
		listsource.selectedIndex=(j<listsource.options.length)?j:listsource.options.length-1;
		listsource.focus();
	}
	return removed;
}

/*function addtolist(txtsource,listdest,divlistlen){
	if (txtsource.value!=null && txtsource.value.trim().length>0){
		for (i=0;i<listdest.options.length;i++){
			if (listdest.options[i].value.trim().toUpperCase()==txtsource.value.trim().toUpperCase()) {
				listdest.options[i].selected=true;
				txtsource.focus();
				txtsource.select();
				window.alert("The data: "+txtsource.value+" has already in the list");
				return false;
			}
		}

		j=listdest.options.length;
		listdest.options[j]=new Option(txtsource.value.trim(),txtsource.value.trim(),false,false);
		listdest.selectedIndex=j;
		//updatelen(listdest,divlistlen)
		changeElementContentById(divlistlen,listdest.length+' record(s) count');
		txtsource.focus();
		txtsource.select();
	} else {
		return;
	}
}*/

function add_value_to_list(value,listdest,divlistlen,allowdup){
	if (value!=null && value.trim().length>0){
		if (!allowdup){
			var add_value_to_list_i=0;
			for (add_value_to_list_i=0;add_value_to_list_i<listdest.options.length;add_value_to_list_i++){
				if (listdest.options[add_value_to_list_i].value.trim().toUpperCase()==value.trim().toUpperCase()) {
					listdest.options[add_value_to_list_i].selected=true;
					return false;
				}
			}
		}

		var add_value_to_list_j=listdest.options.length;
		listdest.options[add_value_to_list_j]=new Option(value.trim(),value.trim(),false,false);
		listdest.selectedIndex=add_value_to_list_j;
		if (divlistlen)
			changeElementContentById(divlistlen,listdest.length+' record(s) count');
			//updatelen(listdest,divlistlen);
		return true;
	} else {
		return false;
	}
}

function add_all_value_to_list(listsource,listdest,divlistlen,allowdup){
	var add_all_value_to_list_i=0;
	for (add_all_value_to_list_i=0;add_all_value_to_list_i<listsource.options.length;add_all_value_to_list_i++){
		add_value_to_list(listsource.options[add_all_value_to_list_i].value,listdest,divlistlen,allowdup)
	}
}

function addAllValueTextPairToList(listsource,listdest){
	var addAllValueTextPairToList_i=0;
	for (addAllValueTextPairToList_i=0;addAllValueTextPairToList_i<listsource.options.length;addAllValueTextPairToList_i++){
		addAValueTextPairToList(listsource.options[addAllValueTextPairToList_i].value,listsource.options[addAllValueTextPairToList_i].text,listdest)
	}
}


//document.onkeypress = accidentallyBackspaceKeypressHandler
//document.onkeydown = accidentallyBackspaceKeypressHandler;
function accidentallyBackspaceKeypressHandler(e) {
	//doesn't work with opera && netscape 4.7
	if (componentHasFocus()) {
		return;
	}

	if (!e) var e = window.event;
	if (e.keyCode) key = e.keyCode;
	else if (e.which) key = e.which;

	if (key && key==8){
		if (e.preventDefault) e.preventDefault();
		else if (document.all) event.returnValue = false;
		return false;
	}
};

var s_iscomponenthasfocus=false;

function componentHasFocus(){
	return s_iscomponenthasfocus;
}
function setComponentFocus(){
	s_iscomponenthasfocus=true;
}

function releaseComponentFocus(){
	s_iscomponenthasfocus=false;
}
/*function listkeyhandler(e,components){
	if (document.layers) {
		key = e.which;
	} else {
		key = window.event.keyCode;
		components=e;
	}
	if (key==46){
		removefromlist(components[0],components[1])
	}


}

function removefromlist(listsource,divlistlen) {
	if (listsource.selectedIndex!=-1){
		j=0;
		while (j<listsource.options.length){
			if (listsource.options[j].selected==true){
				listsource.options[j]=null;
			} else {
				j++;
			}
		}
		listsource.selectedIndex=(j<listsource.options.length)?j:listsource.options.length-1;
		listsource.focus();
	}
	//updatelen(listsource,divlistlen);
	changeElementContentById(divlistlen,listsource.length+' record(s) count');
}*/


function changeElementContentById(elementId,newContent){
	//if (elementId=='validmessage')
	//	window.alert('validmessage');
	var _element = (document.getElementById && document.childNodes && document.createElement)?document.getElementById(elementId):document.all?document.all[elementId]:null;
	if (_element){
		_element.innerHTML=newContent;
	} /*else {
		if (elementId=='validmessage2')
			window.alert("hah?");
	}*/
}

function getElementContentById(elementId){
	var _ele = (document.getElementById && document.childNodes && document.createElement)?document.getElementById(elementId):document.all?document.all[elementId]:null;
	if (_ele){
		return _ele.innerHTML;
	} else {
		return '';
	}
}

function populateData(selectcomp,data){
		//data must be array
	if (data!=null) {
		i=0;
		while (selectcomp.options.length>i){
			selectcomp.options[i]=null;
		}
		for (i=0;i<data.length;i++){
			selectcomp.options[i]=new Option(data[i][0]);
			selectcomp.options[i].value=data[i][1];
		}
	}
}

function isDataNumber(thedata){
	if (thedata!=null && thedata.trim().length>0){
		var isDataNumber_i=0;
		var isDataNumber_isnumber=true;
		for (isDataNumber_i=0;isDataNumber_i<thedata.length;isDataNumber_i++){
			if (thedata.charAt(isDataNumber_i)<'0' || thedata.charAt(isDataNumber_i)>'9'){
				isDataNumber_isnumber=false;
				break;
			}

		}
		return isDataNumber_isnumber;
	} else {
		return false;
	}
}


/*function isdatanumber(thedata){
	if (thedata!=null && thedata.trim().length>0){
		var isdatanumber_i=0;
		var isdatanumber_isnumber=true;
		for (isdatanumber_i=0;isdatanumber_i<thedata.length;isdatanumber_i++){
			if (thedata.charAt(isdatanumber_i)<'0' || thedata.charAt(isdatanumber_i)>'9'){
				isdatanumber_isnumber=false;
				break;
			}

		}
		return isdatanumber_isnumber;
	} else {
		return false;
	}
}*/



function selectUnselectMatchingOptions(obj,regex,which,only){if(window.RegExp){if(which == "select"){var selected1=true;var selected2=false;}else if(which == "unselect"){var selected1=false;var selected2=true;}else{return;}var re = new RegExp(regex);for(var i=0;i<obj.options.length;i++){if(re.test(obj.options[i].text)){obj.options[i].selected = selected1;}else{if(only == true){obj.options[i].selected = selected2;}}}}}
function selectMatchingOptions(obj,regex){selectUnselectMatchingOptions(obj,regex,"select",false);}
function selectOnlyMatchingOptions(obj,regex){selectUnselectMatchingOptions(obj,regex,"select",true);}
function unSelectMatchingOptions(obj,regex){selectUnselectMatchingOptions(obj,regex,"unselect",false);}
function sortSelect(obj){var o = new Array();if(obj.options==null){return;}for(var i=0;i<obj.options.length;i++){o[o.length] = new Option( obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected) ;}if(o.length==0){return;}o = o.sort(
function(a,b){if((a.text+"") <(b.text+"")){return -1;}if((a.text+"") >(b.text+"")){return 1;}return 0;});for(var i=0;i<o.length;i++){obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);}}
function selectAllOptions(obj){for(var i=0;i<obj.options.length;i++){obj.options[i].selected = true;}}
function moveSelectedOptions(from,to){if(arguments.length>3){var regex = arguments[3];if(regex != ""){unSelectMatchingOptions(from,regex);}}for(var i=0;i<from.options.length;i++){var o = from.options[i];if(o.selected){to.options[to.options.length] = new Option( o.text, o.value, false, false);}}for(var i=(from.options.length-1);i>=0;i--){var o = from.options[i];if(o.selected){from.options[i] = null;}}if((arguments.length<3) ||(arguments[2]==true)){sortSelect(from);sortSelect(to);}from.selectedIndex = -1;to.selectedIndex = -1;}
function copySelectedOptions(from,to){var options = new Object();for(var i=0;i<to.options.length;i++){options[to.options[i].text] = true;}for(var i=0;i<from.options.length;i++){var o = from.options[i];if(o.selected){if(options[o.text] == null || options[o.text] == "undefined"){to.options[to.options.length] = new Option( o.text, o.value, false, false);}}}if((arguments.length<3) ||(arguments[2]==true)){sortSelect(to);}from.selectedIndex = -1;to.selectedIndex = -1;}
function moveAllOptions(from,to){selectAllOptions(from);if(arguments.length==2){moveSelectedOptions(from,to);}else if(arguments.length==3){moveSelectedOptions(from,to,arguments[2]);}else if(arguments.length==4){moveSelectedOptions(from,to,arguments[2],arguments[3]);}}
function copyAllOptions(from,to){selectAllOptions(from);if(arguments.length==2){copySelectedOptions(from,to);}else if(arguments.length==3){copySelectedOptions(from,to,arguments[2]);}}
function swapOptions(obj,i,j){var o = obj.options;var i_selected = o[i].selected;var j_selected = o[j].selected;var temp = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);var temp2= new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);o[i] = temp2;o[j] = temp;o[i].selected = j_selected;o[j].selected = i_selected;}
function moveOptionUp(obj){var selectedCount=0;for(i=0;i<obj.options.length;i++){if(obj.options[i].selected){selectedCount++;}}if(selectedCount!=1){return;}var i = obj.selectedIndex;if(i == 0){return;}swapOptions(obj,i,i-1);obj.options[i-1].selected = true;}
function moveOptionDown(obj){var selectedCount=0;for(i=0;i<obj.options.length;i++){if(obj.options[i].selected){selectedCount++;}}if(selectedCount != 1){return;}var i = obj.selectedIndex;if(i ==(obj.options.length-1)){return;}swapOptions(obj,i,i+1);obj.options[i+1].selected = true;}
function removeSelectedOptions(from){for(var i=(from.options.length-1);i>=0;i--){var o=from.options[i];if(o.selected){from.options[i] = null;}}from.selectedIndex = -1;}


function OT_transferLeft(){moveSelectedOptions(this.right,this.left,this.autoSort);this.update();}
function OT_transferRight(){moveSelectedOptions(this.left,this.right,this.autoSort);this.update();}
function OT_transferAllLeft(){moveAllOptions(this.right,this.left,this.autoSort);this.update();}
function OT_transferAllRight(){moveAllOptions(this.left,this.right,this.autoSort);this.update();}
function OT_saveRemovedLeftOptions(f){this.removedLeftField = f;}
function OT_saveRemovedRightOptions(f){this.removedRightField = f;}
function OT_saveAddedLeftOptions(f){this.addedLeftField = f;}
function OT_saveAddedRightOptions(f){this.addedRightField = f;}
function OT_saveNewLeftOptions(f){this.newLeftField = f;}
function OT_saveNewRightOptions(f){this.newRightField = f;}
function OT_update(){var removedLeft = new Object();var removedRight = new Object();var addedLeft = new Object();var addedRight = new Object();var newLeft = new Object();var newRight = new Object();for(var i=0;i<this.left.options.length;i++){var o=this.left.options[i];newLeft[o.value]=1;if(typeof(this.originalLeftValues[o.value])=="undefined"){addedLeft[o.value]=1;removedRight[o.value]=1;}}for(var i=0;i<this.right.options.length;i++){var o=this.right.options[i];newRight[o.value]=1;if(typeof(this.originalRightValues[o.value])=="undefined"){addedRight[o.value]=1;removedLeft[o.value]=1;}}if(this.removedLeftField!=null){this.removedLeftField.value = OT_join(removedLeft,this.delimiter);}if(this.removedRightField!=null){this.removedRightField.value = OT_join(removedRight,this.delimiter);}if(this.addedLeftField!=null){this.addedLeftField.value = OT_join(addedLeft,this.delimiter);}if(this.addedRightField!=null){this.addedRightField.value = OT_join(addedRight,this.delimiter);}if(this.newLeftField!=null){this.newLeftField.value = OT_join(newLeft,this.delimiter);}if(this.newRightField!=null){this.newRightField.value = OT_join(newRight,this.delimiter);}}
function OT_join(o,delimiter){var val;var str="";for(val in o){if(str.length>0){str=str+delimiter;}str=str+val;}return str;}
function OT_setDelimiter(val){this.delimiter=val;}
function OT_setAutoSort(val){this.autoSort=val;}
function OT_init(theform){this.form = theform;if(!theform[this.left]){alert("OptionTransfer init(): Left select list does not exist in form!");return false;}if(!theform[this.right]){alert("OptionTransfer init(): Right select list does not exist in form!");return false;}this.left=theform[this.left];this.right=theform[this.right];for(var i=0;i<this.left.options.length;i++){this.originalLeftValues[this.left.options[i].value]=1;}for(var i=0;i<this.right.options.length;i++){this.originalRightValues[this.right.options[i].value]=1;}if(this.removedLeftField!=null){this.removedLeftField=theform[this.removedLeftField];}if(this.removedRightField!=null){this.removedRightField=theform[this.removedRightField];}if(this.addedLeftField!=null){this.addedLeftField=theform[this.addedLeftField];}if(this.addedRightField!=null){this.addedRightField=theform[this.addedRightField];}if(this.newLeftField!=null){this.newLeftField=theform[this.newLeftField];}if(this.newRightField!=null){this.newRightField=theform[this.newRightField];}this.update();}
function OptionTransfer(l,r){this.form = null;this.left=l;this.right=r;this.autoSort=true;this.delimiter=",";this.originalLeftValues = new Object();this.originalRightValues = new Object();this.removedLeftField = null;this.removedRightField = null;this.addedLeftField = null;this.addedRightField = null;this.newLeftField = null;this.newRightField = null;this.transferLeft=OT_transferLeft;this.transferRight=OT_transferRight;this.transferAllLeft=OT_transferAllLeft;this.transferAllRight=OT_transferAllRight;this.saveRemovedLeftOptions=OT_saveRemovedLeftOptions;this.saveRemovedRightOptions=OT_saveRemovedRightOptions;this.saveAddedLeftOptions=OT_saveAddedLeftOptions;this.saveAddedRightOptions=OT_saveAddedRightOptions;this.saveNewLeftOptions=OT_saveNewLeftOptions;this.saveNewRightOptions=OT_saveNewRightOptions;this.setDelimiter=OT_setDelimiter;this.setAutoSort=OT_setAutoSort;this.init=OT_init;this.update=OT_update;}

function print_this(){
	for (m=0;m<document.images.length;m++){
		if (document.images[m].src.indexOf("ascendhover.gif")!=-1){
			//document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("ascendhover.gif"))+"ascendhoverprinter.gif";
			document.images[m].style.visibility='hidden';
			continue;
		}
		if (document.images[m].src.indexOf("descendhover.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("descendhover.gif"))+"descendhoverprinter.gif";
			//document.images[m].src='';
			//document.images[m].style.visibility='hidden';
			continue;
		}
		if (document.images[m].src.indexOf("ascend.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("ascend.gif"))+"ascendprinter.gif";
			//document.images[m].src='';
			//document.images[m].style.visibility='hidden';
			continue;
		}
		if (document.images[m].src.indexOf("descend.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("descend.gif"))+"descendprinter.gif";
			//document.images[m].style.visibility='hidden';
			continue;
		}
	}
	setElementVisibilityById('btn_print1',false);
	setElementVisibilityById('btn_print2',false);
	setElementVisibilityById('btn_print3',false);
	setElementVisibilityById('btn_print4',false);

	window.print();
	for (m=0;m<document.images.length;m++){
		if (document.images[m].src.indexOf("ascendhoverprinter.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("ascendhoverprinter.gif"))+"ascendhover.gif";
			continue;
		}
		if (document.images[m].src.indexOf("descendhoverprinter.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("descendhoverprinter.gif"))+"descendhover.gif";
			continue;
		}
		if (document.images[m].src.indexOf("ascendprinter.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("ascendprinter.gif"))+"ascend.gif";
			continue;
		}
		if (document.images[m].src.indexOf("descendprinter.gif")!=-1){
			document.images[m].src=document.images[m].src.substring(0,document.images[m].src.indexOf("descendprinter.gif"))+"descend.gif";
			continue;
		}
	}
	setElementVisibilityById('btn_print1',true);
	setElementVisibilityById('btn_print2',true);
	setElementVisibilityById('btn_print3',true);
	setElementVisibilityById('btn_print4',true);

}

function populateSelectComponentWithData(componentSelect,thedata){
	/*
	 * thedata = [
	 *		['value_a','label_a'],
	 *		['value_b','label_b'],
	 *		['value_c','label_c']
	 *	     ]
	 */
	var i=0;
	while (i<componentSelect.length){
		componentSelect.options[i]=null;
		i++;
	}
	i=0;
	while (i<thedata.length){
		componentSelect.options[i]=new Option(thedata[i][0],thedata[i][1]);
		i++;
	}
}
function isElementVisibleById(elementNameId){
	var _element = document.getElementById(elementNameId);
	return (!(_element.style.display=='none'));
}
function setElementVisibilityById(elementNameId,show){
	if ((elementNameId instanceof Array))
		for (i=0;i<elementNameId.length;i++)
			_internalSetElementVisibilityById(elementNameId[i],show);
	else
		_internalSetElementVisibilityById(elementNameId,show);
}
function _internalSetElementVisibilityById(elementNameId,show){
	var _mode=show?'':'none';
	var _element=document.getElementById(elementNameId);
	_element.style.display=_mode;
}
function setElementVisibilityByName(elementName,show){
	var _mode=show?'':'none';
	var _element=document.getElementsByName(elementName);
	for (i=0;i<_element.length;i++)
		_element[i].style.display=_mode;
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}