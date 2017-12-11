<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>九宫格拼图游戏</title>
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0">
	<link rel="stylesheet" href="css/loading.css">
	<link rel="stylesheet" href="css/dialog.css">
</head>
<body>
	<div class="content_all">
		<div class="info_all">
			<h1 class="h1_html_title">九宫格</h1>
			<p class="userList">排行榜</p>
			<div class="div_TimeTip">
				<div class="time">
					<img src="images/clock.png" alt="用时">
					<span class="time_span">00:00</span>
				</div>
				<p></p>
				<div class="tips">
					<img src="images/bushu.png" alt="步数">
					<span class="tops_span">0</span>
				</div>
			</div>
		</div>

		<div class="reword_list" id="reword_list">
			<ul class="ul_rewordLists1">
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
			</ul>

			<ul class="ul_rewordLists2">
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
				<li class="li_rewordList">
					<span class="span_userName"></span>
					<span class="span_tips"></span>
				</li>
			</ul>
		</div>

		<div class="content">
			<ul>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
				<li class="game_li"></li>
			</ul>
		</div>
		<p class="coverPic">查看原图</p>
	</div>
	
	<div class="mask_div">
		<img class="coverPicImg" src="" alt="">
	</div>
</body>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/loading.js"></script>
<script src="js/dialog.js"></script>

<script>
	$('body').loading({
		loadingWidth:240,
		title:'请稍等!',
		name:'initImage',
		discription:'让图片加载一会儿',
		direction:'column',
		type:'origin',
		originBg:'#71EA71',
		originDivWidth:40,
		originDivHeight:40,
		originWidth:6,
		originHeight:6,
		smallLoading:false,
		loadingMaskBg:'rgba(0,0,0,0.5)'
	});

	var arrayLists =  [["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["0","0"]],	//将要被打乱的序列
		arrayListsDefalut = [[["4","4"],["6","6"],["0","0"],["5","5"],["8","8"],["7","7"],["1","1"],["3","3"],["2","2"]],
							 [["0","0"],["1","1"],["4","4"],["5","5"],["8","8"],["7","7"],["6","6"],["3","3"],["2","2"]],
							 [["8","8"],["1","1"],["4","4"],["6","6"],["0","0"],["7","7"],["5","5"],["3","3"],["2","2"]],
							 [["2","2"],["7","7"],["4","4"],["0","0"],["6","6"],["3","3"],["5","5"],["8","8"],["1","1"]],
							 [["1","1"],["6","6"],["0","0"],["2","2"],["5","5"],["8","8"],["4","4"],["3","3"],["7","7"]],
							 [["3","3"],["1","1"],["8","8"],["0","0"],["5","5"],["6","6"],["4","4"],["2","2"],["7","7"]],
							 [["2","2"],["5","5"],["3","3"],["8","8"],["4","4"],["6","6"],["7","7"],["1","1"],["0","0"]],],
		defalutLists = [["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["0","0"]],   //正确的图片序列
		imgPath = ['sea','yourName','yourName1'],   //图片地址
		m = 0,s = 1,	//时间秒表计数
		tiger = true,   //是否是第一次开始   是就开始计时
		tips = 0,	//步数
		touch_x = 0,	//手触摸屏幕的起始x值	
		touch_y = 0,	//手触摸屏幕的起始y值
		move_x = 0,
		move_y = 0,
		x = 0,	//获取的是手指x方向的位移距离
		y = 0,	//获取的是手指x方向的位移距离
		touch_start = 0,
		touch_move = 0,
		imgSrcIndex = Math.floor(Math.random() * imgPath.length),  //照片集随机
		dis = 0,
		j = 0,   //随机的组
		t ;  //计时

	$(document).ready(function(){
		shuffle();  //背景随机
		$('.game_li').on('click',function(){
			var _this = $(this);
			var index = _this.index();
			var ImgIndex = _this.attr('data-name');
			var noneImgIndex = getNoneImgIndex(arrayLists);

			var canClickIndex = getCanClickIndex(noneImgIndex);
			
			if($.inArray(index, canClickIndex) != -1){
				var timer;
				if(tiger){
					t = setInterval("second()",1000);
					tiger = false; 
				}

				//计算步数
				tips++;
				$('.tops_span').text(tips);

				arrayLists.splice(index, 1, [0,0]);  
				arrayLists.splice(noneImgIndex, 1, [ImgIndex,ImgIndex]);  
				// alert(arrayLists);

				for(var i = 0;i<arrayLists.length;i++){
					$('.game_li').eq(i).css({
						'background-image':'url(images/'+imgPath[imgSrcIndex]+'/'+arrayLists[i][1]+'.jpg)',
						'background-size': 'cover',
					}).attr({
						"data-name" : arrayLists[i][0],
		   				"data-src" : arrayLists[i][1]
					});
				}

				if(arrayLists.toString() ==  defalutLists.toString()){
					clearInterval(t);
					$('body').loading({
						loadingWidth:240,
						title:'请稍等!',
						name:'success',
						discription:'让图片加载一会儿',
						direction:'column',
						type:'origin',
						originBg:'#71EA71',
						originDivWidth:40,
						originDivHeight:40,
						originWidth:6,
						originHeight:6,
						smallLoading:false,
						loadingMaskBg:'rgba(0,0,0,0.5)'
					});

					tiger = true; 
					m = 0;
					s = 1;
					setTimeout(function(){
						showSignIn();
					},1000);
				}
			}

		});

		$("#wxclose").on('click',function(){
			$('.mask_div_reward').remove();
		});

		//********************************  关闭图片使用的 滑动事件
        $('.coverPicImg').on('touchstart', function(event) {
            event.preventDefault();
            touch_start = event.originalEvent.touches[0] || event.originalEvent.targetTouches[0];
            touch_x = touch_start.pageX;
            touch_y = touch_start.pageY;
        });
        
        $('.coverPicImg').on('touchmove',function(event){
            touch_move = event.originalEvent.touches[0] || event.originalEvent.targetTouches[0];
            move_x = touch_move.pageX;
            move_y = touch_move.pageY;
            x = (move_x - touch_x);    //获取的是手指x方向的位移距离
            y = (move_y - touch_y);    //获取的是手指y方向的位移距离
            
            var translate_y = -50+(y*0.2);  //图片位移的值
            var translate_x = -50+(x*0.1);
            var scale_y = 1-Math.abs(y*0.001);  //图片缩小的值
            var touming = 0.7-Math.abs(y*0.0012);
            
            $('.coverPicImg').css({
            	transform:'translate3d('+translate_x+'%,'+translate_y+'%,0)scale('+scale_y+')',
            });

            $('.mask_div ').css({
            	background:'rgba(0,0,0,'+touming+')',
            });
        });

        $('.coverPicImg').on('touchend', function(event) {
            event.preventDefault();
            if(Math.abs(y)>150){
            	$('.coverPicImg').css({
	            	transform:'translate3d(-50%,-50%,0)scale(1)',
	            });
	            $('.mask_div').css({
	            	background:'rgba(0,0,0,0.7)',
	            }).hide();;
            }else{
            	$('.coverPicImg').css({
	            	transform:'translate3d(-50%,-50%,0)scale(1)',
	            });
	            $('.mask_div').css({
	            	background:'rgba(0,0,0,0.7)',
	            });
            }
        });

        //********************************  关闭图片使用的 滑动事件
        $('.mask_div').on('click', function(event) {
            event.preventDefault();
       		$(this).hide();
        });

        $('.coverPic').on('click', function(event) {
            event.preventDefault();
       		$('.mask_div').show();
        });

        $('body').on('click','.mask_div_reward', function(event) {
            event.preventDefault();
       		$(this).remove();
        });

        $('.userList').on('click', function(event) {
        	$('body').loading({
				loadingWidth:240,
				title:'请稍等!',
				name:'userInfo',
				discription:'数据加载中',
				direction:'column',
				type:'origin',
				originBg:'#71EA71',
				originDivWidth:40,
				originDivHeight:40,
				originWidth:6,
				originHeight:6,
				smallLoading:false,
				loadingMaskBg:'rgba(0,0,0,0.5)'
			});

            event.preventDefault();
            var str_li ='';
            var str = '';
            str += '<div class="mask_div_reward">'
			str += '<div class="div_rewardList">';
			str += '<button id="wxclose" style="color:#fff;position: absolute;right:0;top: -40px;">ㄨ</button>'
			str += '<div class="mask_div_title">';
			str += '<span>用户名</span>';
			str += '<span>时间</span>';
			str += '<span>步数</span>';
			str += '</div>';
			str += '<div class="rewardList">';
			str += '<ul class="ul_rewardList1">';
			str += '</ul>';
			str += '<ul class="ul_rewardList2">';
			str += '</ul>';
			str += '</div>';
			str += '</div>';
			str += '</div>';
			str += '</div>';

			$.ajax({
   				url:"server.php?inAjax=1&do=getUserInfo",
   				data:{'token':'<?= $token = md5(date("Y-m-d H")."token"); ?>'},
   				datatype:'json',
   				type:'post',
   				success:function(data){
   					var getData = eval(data);
   					var length = getData.length;

   					for(var i = 0;i < length;i++){
   						str_li += '<li>';
						str_li += '<span class="rewardName">'+getData[i]['username']+'</span>';
						str_li += '<span class="rewardTime">'+getData[i]['usetime']+'</span>';
						str_li += '<span class="rewardSteps">'+getData[i]['steps']+'</span>';
						str_li += '</li>';
   					}

   					$('body').append(str);
   					$('.ul_rewardList1').append(str_li);
   				},
   				error:function(){
   					alert('数据获取失败');
   				},
   			});

			removeLoading("userInfo");

       		$('.mask_div_reward').show();

			var mySet=setInterval("autoWinsList()",50);
        });
	});

	window.onload = function(){
		removeLoading('initImage');
	}

	function autoWinsList(){
		dis++;
		$('.rewardList').scrollTop(dis);
		if ($('.rewardList').scrollTop()>=$('.ul_rewardList1').height()) {
			dis = 0;
			$('.rewardList').scrollTop(dis);
		}
	}

	function shuffle() {
		j = Math.floor(Math.random() * arrayListsDefalut.length);
		//设置1-9位置的随机背景
		for(var i = 0;i<arrayListsDefalut[j].length;i++){
			$('.game_li').eq(i).css({
				'background-image':'url(images/'+imgPath[imgSrcIndex]+'/'+arrayListsDefalut[j][i][1]+'.jpg)',
				'background-size': 'cover',
			}).attr({
				"data-name" : arrayListsDefalut[j][i][0],
   				"data-src" : arrayListsDefalut[j][i][1]
			});
		}
		arrayLists = arrayListsDefalut[j];
		//设置全图背景图
		$('.coverPicImg').attr('src','images/'+imgPath[imgSrcIndex]+'/bg.jpg');   
	};

	function getNoneImgIndex(arrayLists){
		this.arrayLists = arrayLists;
		var index;
		for(var i = 0;i<arrayLists.length;i++){
			if(arrayLists[i][0]==0){
				index = i;
				break;
			}
		}
		return index;
	}

	function getCanClickIndex(index){
		var arrCanClick;
		
		switch(index){
			case 0:
				arrCanClick = [1,3];
				break;

			case 1:
				arrCanClick = [0,2,4];
				break;

			case 2:
				arrCanClick = [1,5];
				break;

			case 3:
				arrCanClick = [0,4,6];
				break;

			case 4:
				arrCanClick = [1,3,5,7];
				break;

			case 5:
				arrCanClick = [2,4,8];
				break;

			case 6:
				arrCanClick = [3,7];
				break;

			case 7:
				arrCanClick = [6,8,4];
				break;

			case 8:
				arrCanClick = [5,7];
				break;
		}

		return arrCanClick;
	}

	// 时分秒
	function second(){  
		if(s>0 && (s%60)==0){m+=1;s=0;}  
		
		var mm = checkTime(m);
		var ss = checkTime(s);
		var t=mm+":"+ss;
		$('.time_span').text(t);
		s++;
	}

	function checkTime(i){
		if (i<10){
			i="0" + i
		}
		return i
	}

	function showSignIn(){
		removeLoading('success');
		$('body').dialog({
			width:280,
			type:'primary',
			title:'恭喜!',
			isInput:true,
			buttons:['提交'],
			inputPlaceholder:'留下您的大名!!!'
		},function(ret){
			if(ret.index === 0){
				var inputName = ret.input.value;
				if(inputName === ''){
	       			alert('用户名不能为空!');
	       		}else{
	       			var data = new Date();
	       			var userName = inputName;
	       			var dataTime = $('.time_span').text();
	       			var steps = $('.tops_span').text();
	       			$.ajax({
	       				url:"server.php?inAjax=1&do=submitUserInfo",
	       				data:{'username':userName,'steps':steps,'usetime':dataTime,'token':'<?= $token = md5(date("Y-m-d H")."token"); ?>'},
	       				datatype:'json',
	       				type:'post',
	       				success:function(){
	       					alert('数据提交成功,请在排行榜上查看您的排名!')
	       				},
	       				error:function(){
	       					alert('数据提交失败');
	       				},
	       			});
	       			// ************再次初始化******************
	       			shuffle();  //背景随机  
	       			$('.time_span').html('00:00');
	       			tips = 0;
	       			$('.tops_span').html('0');
	       		}
			}
		});
	}
</script>
<!-- pv浏览统计 接收socket推送消息 -->
<div id="ip" style="display:none;">
<script type="text/javascript" src="http://ip.chinaz.com/getip.aspx"></script>
</div>
<script type="text/javascript" src="https://cdn.bootcss.com/socket.io/2.0.4/socket.io.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/layer/3.1.0/layer.js"></script>
<script type="text/javascript">
function api_getip(){var data = $("#ip").text();var str = data.substr(2,data.length-4);var arr = str.split(",");var info = [];for(var i=0;i<arr.length;i++){var item = arr[i];var f = item.split(":");info[f[0]] = f[1];}return info;}
var data = api_getip();var data_url = window.location.href;$.ajax({type:"POST",url:'/pv.php',dataType:"json",data:{"ip":data['ip'],"address":data['address'],"url":data_url},success:function(data){console.log('统计：'+data);},error:function(jqXHR){if (jqXHR.status!=200) {alert("发生错误：" + jqXHR.status);}}});
var socket = io('http://eson.site:2120');uid = data['ip'];socket.on('connect', function(){socket.emit('login', uid);});socket.on('new_msg', function(msg){layer.msg(msg, {anim: Math.floor(Math.random()*7+1)-1});});
</script>
<!-- pv浏览统计 接收socket推送消息 -->
</html>