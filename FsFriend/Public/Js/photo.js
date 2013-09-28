var photoPages = 0;
var photoNums = 0;
var photoNumPerPage = 10;
var photoCurPage = 0;
var photosCurPhoto = 0;
var photos;

function getPhotoUrl(userPhoto,size){
	return userPhoto.defaultUrl.replace("120_150",size);
}

function showPhoto(_photos){
	photos = _photos;
	
	calPhotoVar();
	
	//showPhotoByPage();
	
	if(_curPos != photosCurPhoto){
		clickSmallPhoto(_curPos);
	}
	
	//add liuchong 2010-05-04
	initBigPhotoMouseMove();
}


var _bigPhotoMidPos = 0;
var _bigPhotoMouseLeft = true;

function initBigPhotoMouseMove(){
	
	var _width = $("#photoBigPic").width();
	var _left = $("#photoBigPic").offset().left;
	
	var _bigPhotoMidPos = _width / 2 + _left; 	
	
	$("#photoBigPic").mousemove(function(evt){
		var _posx = evt.clientX;

		if(_posx < _bigPhotoMidPos){
			if(photosCurPhoto == 0){
				$("#photoBigPic").attr("class","");
			}else{
				$("#photoBigPic").attr("class","bigPhoto-left-img");
			}
			
			_bigPhotoMouseLeft = true;
		}else{
			if(photosCurPhoto == photoNums - 1){
				$("#photoBigPic").attr("class","");
			}else{
				$("#photoBigPic").attr("class","bigPhoto-right-img");
			}
			_bigPhotoMouseLeft = false;
		}
	});
	
	$("#photoBigPic").click(function(){
		var _pos = photosCurPhoto + (_bigPhotoMouseLeft?-1:1);
		
		if(_pos >= 0 && _pos < photoNums){
			clickSmallPhoto(_pos);
		}
	});
}


function calPhotoVar(){
	photoNums = photos.totalNum;
	//photoPages = (photoNums - 1) / photoNumPerPage + 1;
	//photoCurPage = 1;
}

function clickSmallPhoto(pos){
	showPhotoNowDiv(photosCurPhoto,false);
	
	photosCurPhoto = pos;
	
	changePhotoBigPic();
	
	showPhotoNowDiv(photosCurPhoto,true);
	
}

function showPhotoNowDiv(pos,flag){
	
	if(!flag){//remove
		$("#smallPhoto_"+pos).removeClass("now");
	}else{//add
		$("#smallPhoto_"+pos).addClass("now");
	}
}

function changePhotoBigPic(){
	
	$("#photoBigPic").attr("src",getPhotoUrl(photos.upList[photosCurPhoto],'640_480'));
	$("#photoBigPic").attr("width",photos.upList[photosCurPhoto].width);
	$("#photoBigPic").attr("height",photos.upList[photosCurPhoto].height);
	$("#photoTitle").text(photos.upList[photosCurPhoto].title);

}

function pagePhoto(page){
	var _page = photoCurPage + page;
	
	if(_page > 0 && _page <= photoPages){
		photoCurPage = _page;
		
		showPhotoByPage();
	}
}

function showPhotoByPage(){
	photosCurPhoto = (photoCurPage - 1)*photoNumPerPage;
	
	
	var showPhotoNum = photoNumPerPage;
	
	if(photoNums < photosCurPhoto + showPhotoNum){
		showPhotoNum = photoNums - photosCurPhoto;
	}
	
	var _html = "";
		
	_html += "<div class='frame tl'></div>";
	_html += "<div class='frame tr'></div>";
	_html += "<div class='pic'>";
	_html += "	<div class='photo_intro' id='photoIntro'><p id='photoTitle'>"+photos.upList[photosCurPhoto].title+"</p></div>";
	_html += "	<img id='photoBigPic' src='"+getPhotoUrl(photos.upList[photosCurPhoto],'640_480')+"' alt='' width='640px' height='480px'/>";
	_html += "</div>";
	_html += "<div class='piclist'>";
	_html += "	<a class='arrowlf' style='left:10px;' href='javascript:pagePhoto(-1)'>&nbsp;</a>";
	_html += "	<a class='arrowrf' style='right:10px;' href='javascript:pagePhoto(1)'>&nbsp;</a>";
	_html += "	<table border='0' cellspacing='1' cellpadding='0' bgcolor='#cccccc'>";
	_html += "		<tr>";
	for(var i = photosCurPhoto ; i < photosCurPhoto + showPhotoNum ; i ++){
		var _class = "";
		if(i == photosCurPhoto){
			_class = " class='now'";
		}
		_html += "<td id='smallPhoto_"+i+"' valign='middle'"+_class+"><a href='javascript:clickSmallPhoto("+i+")'><img src='"+getPhotoUrl(photos.upList[i],'52_52')+"' alt='' /></a></td>";
			
	}
	
	_html += "		</tr>";
	_html += "	</table>";
	_html += "</div>";
	_html += "<div class='frame bl'></div>";
	_html += "<div class='frame br'></div>";
	
	$("#photoDiv").html(_html);
}