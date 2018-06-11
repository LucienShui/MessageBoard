<html>
<head>
<title>简陋的留言板</title>
<link rel="stylesheet" type="text/css" media="screen" href="./style.css" />
<meta name="Author" contect="www.lfhacks.com">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="Robots" contect= "none">

<meta charset="UTF-8">
</head>
<body>
<div class=wrapper>
<span>
每天00:00清空信息
</span>
<div>
<form method="POST" action=""> 
    <div><textarea name="msg" rows="4"></textarea></div>
    <div class=btn><input name="Btn" type="submit" value="提交"></div>
</form>
</div>

<?php
$SAME_FILE = True;
if (!is_dir("./.data")) mkdir("./.data", 0755);
$filename = "./.data/" . date("Ymd") . ".txt";
file_exists($filename) or file_put_contents($filename, "");
$posts = file_get_contents($filename);
if (isset($_POST["msg"])) {
    $msg = $_POST["msg"];
    ($msg=='') and die('Empty message');
    $msg = htmlspecialchars($msg);
    $msg = str_replace('    ', '&nbsp&nbsp&nbsp&nbsp', $msg);
    $msg = str_replace(' ', '&nbsp', $msg);
    $msg = preg_replace("/\bhttp:\/\/(\w+)+.*\b/",'<a href="$0">$0</a>',$msg);
    preg_match("/(\w{3}) (\d{2})##\d{2}:\d{2}##\w{3}/s", $posts, $matches);
    $post_month= $matches[1];
    $post_day= $matches[2];
    $current_month = date("M");
    $current_day = date("d");
    if($SAME_FILE || ($current_month === $post_month)){
        $time = date("H:i");
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>" . $posts;
        file_put_contents($filename, $posts);
    }
    else{
        $time = date("H:i");
        $posts = "<div class=post><div class=time>$time</div><div class=msg>$msg</div></div>";
        if($post_month==='Dec' && $current_month==='Jan') $newfile = "posts_".strval(intval(date("Y"))-1).'_'.$post_month.'.txt';
        else $newfile = "posts_".date("Y").'_'.$post_month.'.txt';
        if (rename($filename, $newfile)) file_put_contents($filename, "\xEF\xBB\xBF".$posts);
        else die('Unable to rename $filename to $newfile');
    }
}
if (isset($posts)) {
    $posts = preg_replace("/(>\w{3} \d{2})##(\d{2}:\d{2})##(\w{3}<)/","$1<br />$2<br />$3",$posts);
    echo nl2br($posts);
}


?>
</div>
<span>&copy; 2018 版权所有 <a href='http://www.lucien.ink' target='_blank'>Lucien Shui</a></span>
</body>
</html>
