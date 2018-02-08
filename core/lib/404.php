<!DOCTYPE html>
<html>
<head>
<style type="text/css" media="screen">
@font-face{
	font-family: iran-sans;
	src:url("<?php echo host; ?>/static/fonts/IRANSans-Bold-web.woff");
}

body{
	margin:0;
}

.wraper404{
	width: 100%;
	min-height: 650px;
	margin: 0;
	padding: 0;
	background-image:url("<?php echo host; ?>/static/img/patern-404.png");
}

.page404-main{
	width: 40%;
	height:100%;
	margin: 0px auto;
	display: block;
	position: relative;
}

.page404-main span{
    margin-top: 100px;
    color: #97c9a5;
    font-family: iran-sans;
    font-size: 1em;
    font-weight: 500;
    display: inline-block;
    text-align: center;
    width: 100%;
}

.page404-main #logo404{
 	display: block;
 	margin: 20px 0 0 0 ;
 	text-align: center
}

.page404-main #logo404 p{
    color: #97c9a5;
    font-family: iran-sans;
    font-size: .5em;
    font-weight: 500;
    margin: 0 0 50px 0;
}

.page404-main .notfound li{
    font-size: .5em;
    font-family: iran-sans;
    font-weight: 500;
    width: 100%;
    display: inline-block;
    text-align: center;
}

.page404-main .notfound+p{
    text-align: center;
    font-size: 1.3em;
    color: #9fa39d;
    font-family: iran-sans;
    font-weight: 500;
}

.page404-main ul{
	list-style: none;
	display: inline-block;
}

.page404-main li{
	display: inline;
	width: 10%;
    font-size: 0.7em !important;
}

.page404-main #back {
    text-decoration: none;
    padding: 7px;
    background-color: rgba(207,208,207,0.5);
    border-radius: 1px;
    font-family: iran-sans;
    color: #636363;
    font-size: .8em;
    position: absolute;
    left: 25%;
    font-weight: bold;
    width: 6em;
    text-align: center;
}


.page404-main #home{
	width: 6em;
    text-decoration: none;
    padding: 7px;
    background-color: rgba(207,208,207,0.5);
    border-radius: 1px;
    font-family: iran-sans;
    color: #636363;
    font-size: .8em;
    float: left;
    position: absolute;
    right: 25%;
    font-weight: bold;
    text-align: center;
}


</style>
  <meta name="name" content="content">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $str; ?></title>
</head>
<body>
<div class="wraper404">
	<div class="page404-main">
		<span>السلام علیک یا فاطمة المعصومة اشفعی لنا فی الجنة</span>
		<div id="logo404">
			<img src="<?php echo host; ?>/static/svg/404logo-animation.svg" alt="logo">
			<p>آستان مقدس حضرت فاطمة معصومة سلام الله علیها</p>
		</div>
		<div class="notfound">
            <li><?php echo nl2br($hadith); ?></li>
        <!-- error -->
            <li><?php echo $error; ?></li>
		</div>
			<p>صفحه مورد نظر شما پیدا نشد</p>
		<a href="<?php echo  host; ?>" title="" id="home">سایت آستان</a>
		<a href="#" title="" id="back">صفحه قبل</a>
	</div>
</div>
</body>
</html>