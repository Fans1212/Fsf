function unselectall(){
if(document.myform.chkAll.checked){
document.myform.chkAll.checked = document.myform.chkAll.checked&0;
}
}
function CheckAll(form){
for (var i=0;i<form.elements.length;i++){
var e = form.elements[i];
if (e.Name != 'chkAll'&&e.disabled==false)
e.checked = form.chkAll.checked;
}
}
//检查是否数字
function isNum(s)
{
 var pattern = /^\d+(\.\d+)?$/;
 if(pattern.test(s))
 {
  return true;
 }
 alert('照片价格请输入数字！');
}
