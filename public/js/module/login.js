;(function($){
	$("a[name=register]").click(function(){
		var msg_html = '' + 
		'<div class="sui-form form-horizontal" id="clkDialog">'+
			'<div class="control-group">'+
				'<label for="inputUser" class="control-label">用户名：</label>'+
				'<div class="controls">'+
					'<input type="text" id="inputUser" placeholder="用户名" data-util="1">'+
				'</div>'+
			'</div>'+
			'<div class="control-group">'+
				'<label for="inputEmail" class="control-label">邮箱：</label>'+
				'<div class="controls">'+
					'<input type="text" id="inputEmail" placeholder="邮箱">'+
				'</div>'+
			'</div>'+
			'<div class="control-group">'+
				'<label for="inputPassworda" class="control-label">密码：</label>'+
				'<div class="controls">'+
					'<input type="password" id="inputPassworda" placeholder="密码" data-util="1">'+
				'</div>'+
			'</div>'+
			'<div class="control-group">'+
				'<label for="inputRepassword" class="control-label">重复密码：</label>'+
				'<div class="controls">'+
					'<input type="password" id="inputRepassword" placeholder="重复密码" data-util="1">'+
				'</div>'+
			'</div>'+
			'<div class="control-group">'+
				'<label class="control-label"></label>'+
				'<div class="controls">'+
					'<button id="register" class="sui-btn btn-primary">立即注册</button>'+
				'</div>'+
			'</div>'+
		'</div>';
		var index = layer.open({
			content: msg_html,
			skin: 'layui-layer-molv',
			title:'注册',
			shade: [0.8, '#000'], 
			area:['350px','300px'],
			move: false,
			btn:false,
			success:function(){				
				$("div.layui-layer-shade")[0].style.pointerEvents="none";
			}
		});
	});
	$(document).on('keyup','input[data-util]',function(){
		var words = ['~','!','@','#','$','%','^','&','*','(',')','_','-','+'];
		for (var i in words) {
			if((this.value).indexOf(words[i])!=-1){
				layer.tips('不能含有`'+words[i]+"`等字眼", this);
				this.style.borderColor="red";
				$("#register").attr("disabled","true");			
				break;
			}else{
				$("#register").removeAttr("disabled");
				this.style.borderColor="";
			}
		}
	});
	$(document).on('change','#inputEmail',function(){
		if(!/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(this.value)){
			this.style.borderColor="red";
			$("#register").attr("disabled","true");
			return layer.tips('邮箱格式不正确', this);
		}else{
			$("#register").removeAttr("disabled");
			this.style.borderColor="";
		}
	});
	$(document).on("click","#register",function(){
		var user = $("#inputUser").val(),
			email = $("#inputEmail").val(),
			pass = $("#inputPassworda").val(),
			r_pass = $("#inputRepassword").val();
		if(isEmpty(user)){
			var dom = $("#inputUser")[0];
			dom.style.borderColor="red";
			return layer.tips('不能为空', dom);
		}
		if(isEmpty(email)){
			var dom =$("#inputEmail")[0];
			dom.style.borderColor="red";
			return layer.tips('不能为空', dom);
		}
		if(isEmpty(pass)){
			var dom = $("inputPassworda")[0];
			dom.style.borderColor="red";
			return layer.tips('不能为空', dom);
		}
		if(isEmpty(r_pass)){
			var dom = $("inputRepassword")[0];
			dom.style.borderColor="red";
			return layer.tips('不能为空', dom);
		}
		if(pass!=r_pass){
			return layer.alert("两次密码不对");
		}
		return http_clent("/handle/register",{
			nick:user,
			pass:pass,
			email:email
		},function(r){
			layer.msg("注册成功");
		},function(m){
			layer.msg("注册失败");
		});
	});
	$("form.sui-form").submit(function(){
		var is_true = false;
		var username = $.trim($("input[name=J_user]").val()),
			pwd = $.trim($("input[name=J_pwd]").val()),
			prikey = "MIICXgIBAAKBgQDE9GQeZdR6Qoqtm07+zdWxX4PiQz48+PWIyxHrqvr88qS4peCsmL9kz0rTEwUI2pykurlYB3b1e+yOw9Nnpf2rE4OqFRuDleUS2/dvyAgxlYFsQlhTrjpY82Jy55V3+Uj2nsHX0tf4M7qr2U+UQA1md/Pmd+7MWRZ6ORuj67BPcQIDAQABAoGBAJ+o2iyGMfu2S4M2npASPkPegSP/jMmSrEBrFijsXzCEZqHE3mAsJyNKo4Z+KZum1Er5j1xcGMQh5H9LIeoo9nu2k/S7zRckY+ieCZ+2J8T+fsagpU0wPd5DShzQfCqakMlUAWLOZ14BXShq6/8Bo8TDJjjkq7ztW1kwTefuzimtAkEA8LARaGW5u/3W3LieTCcJtqlx1a1G2tpWgxnkfhSZDkNWCRQ++wluxS4zM3kZ6F7AzDCjk5ZT5kSa0JBO8vvO4wJBANF8Ea56ugZCNJnHL+RNTzfegv9KRzIzaRqVAiS1LnhmKN8QBgZZZgQflx2eHBnsOjrmjEpAg59bcTobRy/PhJsCQHx8FATS2EWK/F4cfoMUjcmTyfSiMktvMd+MvMkZDjB6Uz1O42QjdM83HfQ5ZlTw7PavEWt8DNjEEu5cNPknk9ECQQCGRcczlhicoF0E4GazKFaJkgdXSS3/YHKTBkW8b6GcrKav655g/XZlWDZNVqXee8sLK/FqOpXjVAJsY0WqwJHXAkEAyMBl3x70ERChQCyoCjHcPC8YpqjgwBwT/AOTBRC75z22OHYV7WkJ67SBk5TH2XoiTO7KWsvm5ktkAS/Hf/Q5rg==";
			if(isEmpty(username)){
				layer.alert("\u5e10\u53f7\u4e3a\u7a7a",{icon:2});
				return false;
			}
			if(isEmpty(pwd)){
				layer.alert("\u5bc6\u7801\u4e3a\u7a7a",{icon:2});
				return false;
			}
			var cryptPwd=Encrypt.RSAUnit.encryptToRSA(prikey,pwd);
			$.ajax({ 
		        type: "post", 
		       	url: "/Login/checkAccount", 
		       	data:{
					account:username,
					password:cryptPwd
				},
		       	cache:false, 
		       	async:false
			}).done(function(rsp){				
				if(rsp.code==1) {
					$("input[name=J_pwd]").val('');
					is_true = true;
				} else {
					is_true = false;
					layer.alert(rsp.message,{icon:2});
				}
			}).fail(function(){
				is_true = false;
			});
		return is_true;		
	});
})(jQuery);