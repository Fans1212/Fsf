var XmlHttp;
function $FF(id){return document.getElementById(id);}
function CreateXMLHttpRequest(){
	if (window.XMLHttpRequest) { // Non-IE browsers
		XmlHttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		XmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}		
}
//顶踩与评分-----主要用到ajax的get方式
function $(id){return document.getElementById(id);}
function $ajaxqy(url){
	var req;
	if (window.XMLHttpRequest) { // Non-IE browsers
		req = new XMLHttpRequest();
		try {
			req.open("GET", url, false);
		} catch (e) {
			alert(e);
		}
		req.send(null);
	} else if (window.ActiveXObject) { // IE
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.open("GET", url, false);
			req.send();
		}
	}
	if (req.readyState == 4) { 
		if (req.status == 200) { 
			return req.responseText;
		}
	} else {
		return -1;
	}
}
function plus_diary_ud(id,type,root){
	var url = 'index.php/View/ajax/id/'+id+'/t/'+type;
	var ajaxhtml = $ajaxqy(url);
	if (ajaxhtml == -1) {
		msg = "暂时不能进行投票!";
	}else if(ajaxhtml == 0){
		msg = "您已经投过票了，感谢您的参与!";
	}else{
		var diggs = ajaxhtml.split(':');
		var sUp = parseInt(diggs[0]);
		var sDown = parseInt(diggs[1]);
		var sTotal = sUp+sDown;
		var spUp=(sUp/sTotal)*100;
		spUp=Math.round(spUp*10)/10;
		var spDown=100-spUp;
		spDown=Math.round(spDown*10)/10;
		if(sTotal!=0){
			$('s1').innerHTML=sUp;
			$('s2').innerHTML=sDown;
			$('sp1').innerHTML=spUp+'%';
			$('sp2').innerHTML=spDown+'%';
			$('eimg1').style.width = parseInt((sUp/sTotal)*55);
			$('eimg2').style.width = parseInt((sDown/sTotal)*55);
		}
		msg = "投票成功！";
	}
	if(type!=0){alert(msg);}
}