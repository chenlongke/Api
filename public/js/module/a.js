var _w=window,_d=document;
String.prototype.trim=function(){return this.replace(/(^\s+)|(\s+$)/g,'')};
HTMLElement.prototype.rcss=function(a,b){
	if(a==''){
		eval('var s = new RegExp(/\\b'+b+'\\b/)');
		if(!s.test(this.className)){
			this.className=this.className.trim()+' '+b;
		}
	}else{
		eval('var s=this.className.replace(/\\b'+a+'\\b/g,b)');
		this.className=s.trim();
	}
	return this;
}
HTMLElement.prototype.del=function(){
	this.parentNode.removeChild(this);	
}
Array.prototype.each=HTMLCollection.prototype.each=NodeList.prototype.each=function(f){
	for(var i=0,l=this.length;i<l;i++){
		f(this[i],i,this);
	}
	return this;
}
HTMLCollection.prototype.rcss=NodeList.prototype.rcss=function(a,b){
	this.each(function(t){t.rcss(a,b);});
	return this;
}
NodeList.prototype.del=function(){
	this.each(function(t){t.del();});
}
HTMLElement.prototype.prev=function(){
	return this.previousElementSibling||this.previousSibling;
}
HTMLElement.prototype.next=function(){
	return this.nextElementSibling||this.nextSibling;
}
HTMLElement.prototype.weiz=function(){
		var y=this.offsetTop,x=this.offsetLeft,e=this.offsetParent;
		while(e){
			y+=e.offsetTop;
			x+=e.offsetLeft;
			e=e.offsetParent
		};
		return{
			'y':y,'x':x
		}
}
A={
	$:function(id){
		return _d.getElementById(id);
	},
	$$:function(a){
		if(a.indexOf('<')==-1){
			return _d.createElement(a);
		}
		else{
			var _1=_d.createElement('div');
			_1.innerHTML=a;
			return _1.children[0];
		}
	},
	r:function(n,m,f){
		if(f){
			return Math.random()*(m-n)+n
		}else{
			return Math.round(Math.random()*(m-n)+n);
		}
	},
	aj:function(url,data,f,gs){
		var self=this;
		var k=url.replace(/[^\w]/g,'');
		if(typeof(self.ajone[k])=='undefined'){
			self.ajone[k]=true;
			if(_w.XMLHttpRequest){
				var xm=new XMLHttpRequest()
			}
			else{
				var M=['MSXML2.XMLHTTP','Microsoft.XMLHTTP','MSXML2.XMLHTTP.5.0','MSXML2.XMLHTTP.4.0','MSXML2.XMLHTTP.3.0'];
				for(n=0;n<M.length;n++){
					try{
						var xm=new ActiveXObject(M[n]);
						break
					}
					catch(e){}
				}
			};
			xm.open("post",url,true);
			xm.setRequestHeader("is_ajax","1");
			xm.onreadystatechange=function(){
				if(xm.readyState==4){
					if(xm.status==200){
						delete self.ajone[k];
						if(f){
							if(typeof(f)=='string')A.$(f).innerHTML=xm.responseText;
							else{
								if(gs&&gs=='json'){
									eval('var _j='+xm.responseText);
									eval(f(_j))
								}
								else eval(f(xm.responseText))
							}
						}
					}
				}
			};
			xm.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
			xm.send(data)
		}
	},
	on:function(el,ev,fn){
		if(el.attachEvent){
			el.attachEvent("on"+ev,function(){
				fn.apply(el,arguments)
			})
		}
		else{
			el.addEventListener(ev,fn,false)
		}
	},
	fnr:function(f){
		var s=[];
		for(var i=0,l=f.length;i<l;i++){
			if(f[i].name&&f[i].name!=''){
				switch(f[i].tagName.toLowerCase()){
					case'select':for(var a=0;a<f[i].length;a++){
						if(f[i][a].selected){
							s.push(f[i].name+'='+encodeURIComponent(f[i][a].value))
						}
					};
					break;
					default:switch(f[i].type.toLowerCase()){
						case'radio':var fo=f[f[i].name];
						for(var a=0;a<fo.length;a++){
							if(fo[a].checked){
								s.push(fo[a].name+'='+encodeURIComponent(fo[a].value));
								break
							}
						};
						i+=(fo.length-1);
						break;
						case'checkbox':if(f[i].checked){
							s.push(f[i].name+'='+encodeURIComponent(f[i].value))
						};
						break;
						default:s.push(f[i].name+'='+encodeURIComponent(f[i].value));
						break
					}
				}
			}
		};
		return s.join('&')
	}
};
if(typeof WebSocket === 'undefined'){
	alert("你的浏览器不支持 WebSocket ，推荐使用Google Chrome 或者 Mozilla Firefox");			 
}else{
	(function(){					
		var key='all',mkey;
		var users = {};
		var url='ws://127.0.0.1:8080';		
		var so = false,
			n = false;
		var lus = document.getElementById('us'),
			lct = document.getElementById('ct');
			
		function st(){
			n=prompt('请给自己取一个响亮的名字：');
			n=n.substr(0,16);
			if(!n){
				return ;	
			}
			so = new WebSocket(url);
			so.onopen=function(){
				if(so.readyState==1){
					so.send('type=add&ming='+n);
				}
			}
			
			so.onclose=function(){
				so = false;
				$(lct).append('<p class="c2">'+n+'退出聊天室</p>');							
			}
			
			so.onmessage=function(msg){
				eval('var da='+msg.data);
				var obj=false,c=false;
				if(da.type == 'add'){
					/*var obj=A.$$('<p>'+da.name+'</p>');
					lus.appendChild(obj);*/
					var obj = $.parseHTML('<p>'+da.name+'</p>');
					$(lus).append(obj);
					cuser(obj,da.code);
					obj = A.$$('<p><span>['+da.time+']</span>欢迎<a>'+da.name+'</a>加入</p>');
					c = da.code;
				}else if(da.type == 'madd'){
					mkey = da.code;
					da.users.unshift({'code':'all','name':'大家'});
					for(var i=0;i<da.users.length;i++){
						var obj=A.$$('<p>'+da.users[i].name+'</p>');
						lus.appendChild(obj);
						if(mkey!=da.users[i].code){
							cuser(obj,da.users[i].code);
						}else{
							obj.className = 'my';
							document.title = da.users[i].name;
						}
					}
					obj=A.$$('<p><span>['+da.time+']</span>欢迎'+da.name+'加入</p>');
					users.all.className = 'ck';
				}
				
				if(obj == false){
					if(da.type=='remove'){
						/*var obj=A.$$('<p class="c2"><span>['+da.time+']</span>'+users[da.nrong].innerHTML+'退出聊天室</p>');
						lct.appendChild(obj);*/
						$(lct).append('<p class="c2"><span>['+da.time+']</span>'+users[da.nrong].innerHTML+'退出聊天室</p>');
						users[da.nrong].del();
						delete users[da.nrong];
					} else {
						da.nrong  =da.nrong.replace(/{\\(\d+)}/g,function(a,b){
							return '<img src="/img/sk/'+b+'.gif">';
						}).replace(/^data\:image\/(png|gif|jpg|jpeg|bmp);base64\,.{50,}$/i,function(a){
							return '<img src="'+a+'" width="400" height="300">';
						});
						//da.code 发信息人的code
						if(da.code1 == mkey){
							obj = A.$$('<p class="c3"><span>['+da.time+']</span><a>'+users[da.code].innerHTML+'</a>对我说：'+da.nrong+'</p>');
							c = da.code;
						}else if(da.code == mkey){
							if(da.code1 != 'all'){
								obj=A.$$('<p class="c3"><span>['+da.time+']</span>我对<a>'+users[da.code1].innerHTML+'</a>说：'+da.nrong+'</p>');
							} else {
								obj=A.$$('<p><span>['+da.time+']</span>我对<a>'+users[da.code1].innerHTML+'</a>说：'+da.nrong+'</p>');
								c = da.code1;
							}
						}else if(da.code == false){
							obj = A.$$('<p><span>['+da.time+']</span>'+da.nrong+'</p>');
						}else if(da.code1){
							obj = A.$$('<p><span>['+da.time+']</span><a>'+users[da.code].innerHTML+'</a>对'+users[da.code1].innerHTML+'说：'+da.nrong+'</p>');
							c = da.code;
						}
					}
				}
				if(c){
					obj.children[1].onclick=function(){
						//users[c].onclick();
						$(users[c]).trigger('click');
					}
				}
				$(lct).append(obj);
				lct.scrollTop = Math.max(0,lct.scrollHeight-lct.offsetHeight);
			}
		}
		$("#sd").click(function(){
			if(!so){
				return st();
			}
			var data = $.trim($("#nrong").val());
			if(data==''){
				return alert('内容不能为空');
			}
			$("#nrong").val('');
			so.send('nr='+esc(data)+'&key='+key);
		});

		$("#nrong").keydown(function(e){
			var e=e||event;
			if(e.keyCode==13){
				$("#sd").trigger('click');
				$(this).val('');
			}
		});

		$(window).resize(function(){
			$('#ltian').css("height",($(window).height()-70)+"px");
			return ct();
		});

		$('#ltian').css("height",($(window).height()-70)+"px");

		function esc(da){
			da = da.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\"/g,'&quot;');
			return encodeURIComponent(da);
		}
		function cuser(t,code){
			users[code]=t;
			$(t).click(function(){
				$(this).parent().children().removeClass('ck');
				$(this).addClass('ck');
				key=code;
			});
		}					
		st();
		var bq = document.getElementById('imgbq'),ems = document.getElementById('ems');
		var l=142,r=4,c=5,s=1,p=Math.ceil(l/(r*c));
		
		$(bq).click(function(e){
			var e=e||event;
			if(!so){
				return st();
			}
			$(ems).show();
			$(document).click(function(){
				gb();
			});
			/*document.onclick=function(){
				gb();	
			}*/
			ct();
			try{
				e.stopPropagation();
			}catch(o){

			}
		});
		
		for(var i=0;i<p;i++){
			var a=A.$$('<a href="javascript:void(0);">'+(i+1)+'</a>');
			ems.children[1].appendChild(a);
			ef(a,i);
		}
		ems.children[1].children[0].className='ck';
		
		function ct(){
			var wz=bq.weiz();
			with(ems.style){
				top = wz.y-242+'px';
				left = wz.x+bq.offsetWidth-235+'px';
			}
		}
			
		function ef(t,i){
			t.onclick=function(e){
				var e = e || event;
				s = i * r * c;
				$(ems).children(":eq(0)").empty();
				/*ems.children[0].innerHTML='';*/
				hh();
				this.parentNode.children.rcss('ck','');
				this.rcss('','ck');
				try{
					e.stopPropagation();
				}catch(o){

				}
			}
		}
		
		function hh(){
			var z = Math.min(l,s+r*c);
			for(var i=s;i<z;i++){
				var img = $.parseHTML('<img src="/img/sk/'+i+'.gif">');
				$(ems).children(":eq(0)").append(img);

				$(img).click(function(){
					var e = e||event;
					$('#nrong').val(function(){
						return this.value + '{\\'+i+'}';
					});

					if(!e.ctrlKey){
						gb();
					}
					try{
						e.stopPropagation();
					}catch(o){

					}
				});
				
			}
			return ct();
		}					
		function gb(){
			ems.style.display = '';
			$("#nrong").focus();
			$(document).unbind('click');
			/*document.onclick='';*/
		}
		hh();

		var img = new Image();
		
		$("#upimg").change(function(ev){
			if(!so){
				return st();
			}
			if(key==='all'){
				return alert('由于资源限制 发图只能私聊');
			}
			var f = this.files[0];
			if(f.type.match('image.*')){
				var r = new FileReader();
				r.onload = function(e){
					img.setAttribute('src',e.target.result);
			    };
			    r.readAsDataURL(f);
			}else{
				return alert("选择的不是图片");
			}
		});
		
		img.onload = function(){
			/*var dw = 400,dh = 300;
			ih = img.height,iw = img.width;
			if(iw / ih > dw / dh && iw > dw) {
				ih = ih/iw*dw;
				iw = dw;
			} else if(ih > dh) {
				iw = iw/ih*dh;
				ih = dh;
			}
			var rc = document.createElement("canvas");
			var ct = rc.getContext('2d');
			rc.width = iw;
			rc.height = ih;
			ct.drawImage(img,0,0,iw,ih);
			var da = rc.toDataURL();*/
			so.send('nr='+esc(img.src)+'&key='+key);
		}					
	})();
}