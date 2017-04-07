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

window.onload = function(){
	if(typeof WebSocket === 'undefined'){
		alert("你的浏览器不支持 WebSocket ，推荐使用Google Chrome 或者 Mozilla Firefox");			 
	} else {

		(function(){

			var key='all',mkey;

			var users = {};

			var url='ws://114.215.240.129:9001';

			var so = false,
				n = false;

			var lus = document.getElementById('us'),
				lct = document.getElementById('ct');

			var bq = document.getElementById('imgbq'),
				ems = document.getElementById('ems');

			var l = 142,
				r=4,
				c=5,
				s=1,
				p=Math.ceil(l/(r*c));
			
			/****************************事件绑定区域********************************/
			/**************************************************************************/
			
			$("#sendMessageButton").click(function(){
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
					$("#sendMessageButton").trigger('click');
					$(this).val('');
				}
			});

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
			var img = new Image();
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

			$(window).resize(function(){
				$('#ltian').css("height",($(window).height()-70)+"px");
				return ct();
			});		

			$(bq).click(function(e){
				var e=e||event;
				if(!so){
					return st();
				}
				$(ems).show();
				$(document).click(function(){
					gb();
				});
				ct();
				try{
					e.stopPropagation();
				}catch(o){

				}
			});
			
			for(var i=0;i<p;i++){
				var a=$.parseHTML('<a href="javascript:void(0);">'+(i+1)+'</a>');
				$(ems).children(":eq(1)").append(a);
				ef(a,i);
			}
			$('#ltian').css("height",($(window).height()-70)+"px");
			//ems.children[1].children[0].className='ck';
			$(ems).children(":eq(1)").children(":eq(0)").attr('class','ck');
			
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
					var img = $.parseHTML('<img src="/assets/img/sk/'+i+'.gif">');
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
				$(ems).css('display','');
				$("#nrong").focus();
				$(document).unbind('click');
			}

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

			function st(){
				n = prompt('请给自己取一个响亮的名字：');
				n = n.substr(0,16);
				if(!n){
					return ;	
				}

				so = new WebSocket(url);//创建一个长链接

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

					var obj=false,
						c=false;

					if(da.type == 'add'){

						var obj = $.parseHTML('<p>'+da.name+'</p>');
						$(lus).append(obj);

						cuser(obj,da.code);
						obj = $.parseHTML('<p><span>['+da.time+']</span>欢迎<a>'+da.name+'</a>加入</p>');
						c = da.code;

					}else if(da.type == 'madd'){

						mkey = da.code;
						da.users.unshift({'code':'all','name':'大家'});
						for(var i=0;i<da.users.length;i++){
							var obj=$.parseHTML('<p>'+da.users[i].name+'</p>');
							$(lus).append(obj);
							if(mkey!=da.users[i].code){
								cuser(obj,da.users[i].code);
							}else{
								obj.className = 'my';
								document.title = da.users[i].name;
							}
						}
						obj=$.parseHTML('<p><span>['+da.time+']</span>欢迎'+da.name+'加入</p>');
						users.all.className = 'ck';
					}
					
					if(obj == false){
						if(da.type=='remove'){
							$(lct).append('<p class="c2"><span>['+da.time+']</span>'+users[da.nrong].innerHTML+'退出聊天室</p>');
							users[da.nrong].del();
							delete users[da.nrong];
						} else {
							da.nrong  =da.nrong.replace(/{\\(\d+)}/g,function(a,b){
								return '<img src="/assets/img/sk/'+b+'.gif">';
							}).replace(/^data\:image\/(png|gif|jpg|jpeg|bmp);base64\,.{50,}$/i,function(a){
								return '<img src="'+a+'" width="400" height="300">';
							});
							//da.code 发信息人的code
							if(da.code1 == mkey){
								obj = $.parseHTML('<p class="c3"><span>['+da.time+']</span><a>'+users[da.code].innerHTML+'</a>对我说：'+da.nrong+'</p>');
								c = da.code;
							}else if(da.code == mkey){
								if(da.code1 != 'all'){
									obj=$.parseHTML('<p class="c3"><span>['+da.time+']</span>我对<a>'+users[da.code1].innerHTML+'</a>说：'+da.nrong+'</p>');
								} else {
									obj=$.parseHTML('<p><span>['+da.time+']</span>我对<a>'+users[da.code1].innerHTML+'</a>说：'+da.nrong+'</p>');
									c = da.code1;
								}
							}else if(da.code == false){
								obj = $.parseHTML('<p><span>['+da.time+']</span>'+da.nrong+'</p>');
							}else if(da.code1){
								obj = $.parseHTML('<p><span>['+da.time+']</span><a>'+users[da.code].innerHTML+'</a>对'+users[da.code1].innerHTML+'说：'+da.nrong+'</p>');
								c = da.code;
							}
						}
					}
					if(c){
						obj.children[1].onclick=function(){
							$(users[c]).trigger('click');
						}
					}
					$(lct).append(obj);
					lct.scrollTop = Math.max(0,lct.scrollHeight-lct.offsetHeight);
				}
			}

			// st
			st();//开始执行的方法
			hh();							
		})();
	}
}