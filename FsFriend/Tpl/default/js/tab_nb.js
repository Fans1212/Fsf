function gtab(name,cur,n){
	for(i=1; i<=n; i++){
		var menu =document.getElementById(name+i);
		var con = document.getElementById(name+"_con"+i);
		if(i==cur){
			menu.className = "on";
			con.style.display = "block";
			}
		else{
			menu.className = "";
			con.style.display = "none";
			}	
		}
	}
	////////////////
	//alert(static_n);
	if(static_n!=0){
		for(i=1; i <= 3; i++){
			if (i==static_n){
				document.getElementById("menuId"+static_n).className="on";
			}else{
				document.getElementById("menuId"+i).className="";
			}
				
		}
	}
				