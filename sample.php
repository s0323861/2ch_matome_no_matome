<?php

// 連想配列にセット
$data['feedurl'][] = "http://blog.livedoor.jp/dqnplus/index.rdf";
$data['feedurl'][] = "http://alfalfalfa.com/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/news23vip/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/kinisoku/index.rdf";
$data['feedurl'][] = "http://yutori2ch.blog67.fc2.com/?xml";
$data['feedurl'][] = "http://michaelsan.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/goldennews/index.rdf";
$data['feedurl'][] = "http://news4wide.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://burusoku-vip.com/index.rdf";
$data['feedurl'][] = "http://twintailsokuhou.blog.jp/index.rdf";
$data['feedurl'][] = "http://himasoku.com/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/chihhylove/index.rdf";
$data['feedurl'][] = "http://itaishinja.com/index.rdf";
$data['feedurl'][] = "http://workingnews.blog117.fc2.com/?xml";
$data['feedurl'][] = "http://cherio199.blog120.fc2.com/?xml";
$data['feedurl'][] = "http://blog.livedoor.jp/nwknews/index.rdf";
$data['feedurl'][] = "http://news.2chblog.jp/index.rdf";
$data['feedurl'][] = "http://vippers.jp/index.rdf";
$data['feedurl'][] = "http://lifehack2ch.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://ggsoku.com/feed/";
$data['feedurl'][] = "http://purisoku.com/index.rdf";
$data['feedurl'][] = "http://world-fusigi.net/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/bluejay01-review/index.rdf";
$data['feedurl'][] = "http://www.negisoku.com/index.rdf";
$data['feedurl'][] = "http://www.gurum.biz/atom.xml";
$data['feedurl'][] = "http://blog.livedoor.jp/nonvip/index.rdf";
$data['feedurl'][] = "http://www.mudainodocument.com/index.rdf";
$data['feedurl'][] = "http://ayacnews2nd.com/index.rdf";
$data['feedurl'][] = "http://warotanikki.com/feed";
$data['feedurl'][] = "http://i2chmeijin.blog.fc2.com/?xml";
$data['feedurl'][] = "http://news4vip.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://kanasoku.info/index.rdf";
$data['feedurl'][] = "http://bipblog.com/index.rdf";
$data['feedurl'][] = "http://hamusoku.com/index.rdf";
$data['feedurl'][] = "http://majikichi.com/index.rdf";
$data['feedurl'][] = "http://chaos2ch.com/index.rdf";
$data['feedurl'][] = "http://watashe.blog135.fc2.com/?xml";
$data['feedurl'][] = "http://www.watch2chan.com/index.rdf";
$data['feedurl'][] = "http://brow2ing.doorblog.jp/index.rdf";
$data['feedurl'][] = "http://kuromacyo.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://aqua2ch.net/index.rdf";
$data['feedurl'][] = "http://blog.esuteru.com/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/ladymatome/index.rdf";
$data['feedurl'][] = "http://www.res2ch.net/index.rdf";
$data['feedurl'][] = "http://vipsister23.com/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/nicovip2ch/index.rdf";
$data['feedurl'][] = "http://kabumatome.doorblog.jp/index.rdf";
$data['feedurl'][] = "http://blog.livedoor.jp/itsoku/index.rdf";
$data['feedurl'][] = "http://nanjyakyu.ldblog.jp/index.rdf";
$data['feedurl'][] = "http://master-asia.livedoor.biz/index.rdf";
$data['feedurl'][] = "http://www.news-us.jp/index.rdf";
$data['feedurl'][] = "http://hattatu-matome.ldblog.jp/index.rdf";

// 日本語の曜日配列
$weekjp = array(
  '(日)', //0
  '(月)', //1
  '(火)', //2
  '(水)', //3
  '(木)', //4
  '(金)', //5
  '(土)'  //6
);

// 表示記事数
$hyojiNum = 50;

$rssList = $data['feedurl'];

// キャッシュ準備
require_once('Cache/Lite.php');
$cacheDir = 'rsscache/';
$lifeTime = 60*60;
$automaticCleaningFactor = 100;
$options = array('cacheDir' => $cacheDir ,'caching' => true, 'lifeTime' => $lifeTime, 'automaticSerialization' => 'true','automaticCleaningFactor' => $automaticCleaningFactor);
$cacheData = new Cache_Lite($options);

$outdata =  $cacheData->get('rsscache');

if(!$outdata) {

  // 同時呼び出し
  $rssdataRaw = multiRequest($rssList);

  for($n = 0; $n < count($rssdataRaw); $n++){
    //URL設定
    $rssdata = simplexml_load_string($rssdataRaw[$n]);

    $b_title = $rssdata->channel->title;

    if($rssdata->channel->item) $rssdata = $rssdata->channel;

    if($rssdata->item){

      foreach($rssdata->item as $myEntry){

        $rssDate = $myEntry->pubDate;
        if(!$rssDate) $rssDate = $myEntry->children("http://purl.org/dc/elements/1.1/")->date;
        date_default_timezone_set('Asia/Tokyo');
        $myDateGNU = strtotime($rssDate);
        // 曜日取得
        $weekno = date('w', $myDateGNU);
        $myDate = date('Y年m月j日 H時i分', $myDateGNU);
        $myDate = str_replace(" ", $weekjp[$weekno], $myDate);
        // タイトル取得
        $myTitle = $myEntry->title;
        // リンクURL取得
        $myLink = $myEntry->link;

        $mySubject = $myEntry->children('dc',true)->subject;
        if($mySubject != ""){
          $mySubject = "<p class=\"small\"><i class=\"fa fa-tags\"></i> " . $mySubject . "</p>";
        }

        // 出力内容
        if(preg_match('/PR:/', $myTitle)) continue;

  $outdata[$myDateGNU] .= <<< EOF
  <li class="list-group-item">
  <p><span class="label label-primary">{$b_title}</span></p>
  <h3><a href="{$myLink}" target="_blank">{$myTitle}</a></h3>
  <p class="list-group-item-text"><span class="glyphicon glyphicon-time"></span> {$myDate}</p>
  {$mySubject}
  </li>

EOF;

      }
    }
  }

  //ソート
  krsort($outdata);
 
  $cacheData->save($outdata, 'rsscache');
}

$nn = 0;
$html = '';

foreach($outdata as $outdata) {

  $nn++;
  $html .= $outdata;

  if($nn == $hyojiNum) break;

}

// 同時呼び出し関数
function multiRequest($data, $options = array()) {
 
  // array of curl handles
  $curly = array();
  // data to be returned
  $result = array();
 
  // multi handle
  $mh = curl_multi_init();
 
  // loop through $data and create curl handles
  // then add them to the multi-handle
  foreach ($data as $id => $d) {
 
    $curly[$id] = curl_init();
 
    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
    curl_setopt($curly[$id], CURLOPT_URL, $url);
    curl_setopt($curly[$id], CURLOPT_HEADER, 0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
 
    // post?
    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST, 1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }
 
    // extra options?
    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
 
    curl_multi_add_handle($mh, $curly[$id]);
  }
 
  // execute the handles
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
 
  // get content and remove handles
  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
 
  // all done
  curl_multi_close($mh);
 
  return $result;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="2ちゃんねるのまとめサイトをまとめました。" />
<meta name="keywords" content="２ちゃんねる,まとめサイトのまとめ" />
<title>2chまとめブログのアンテナサイト</title>
<link rel="shortcut icon" href="favicon.ico">
<!-- Bootstrap -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style type="text/css">
body { padding-top: 80px; }
@media ( min-width: 768px ) {
  #banner {
    min-height: 200px;
    border-bottom: none;
  }
  .bs-docs-section {
    margin-top: 8em;
  }
  .bs-component {
    position: relative;
  }
  .bs-component .modal {
    position: relative;
    top: auto;
    right: auto;
    left: auto;
    bottom: auto;
    z-index: 1;
    display: block;
  }
  .bs-component .modal-dialog {
    width: 90%;
  }
  .bs-component .popover {
    position: relative;
    display: inline-block;
    width: 220px;
    margin: 20px;
  }
  .nav-tabs {
    margin-bottom: 15px;
  }
  .progress {
    margin-bottom: 10px;
  }
}
</style>
</head>
<body>

<header>
<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
    <a href="./" class="navbar-brand"><i class="fa fa-comments-o"></i> 2ちゃんねるまとめのまとめ</a>
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    </div>
  </div>
</div>
</header>

<div class="container">

  <div class="row">
    <div class="col-lg-12">
      <h1><i class="fa fa-comments-o"></i> 2ちゃんねるまとめのまとめ</h1>
      <p class="lead">更新順</p>

    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">

      <ul class="list-group">

<?php echo $html; ?>

      </ul>

    </div>
  </div>

  <hr>

  <footer class="footer">

  <p>
  Copyright (C) 2016 <a href="http://tsukuba42195.top/">Akira Mukai</a>
  </p>

  </footer><!-- /footer -->

</div> <!-- /container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script>
$(function(){
  $('.well [data-toggle="tooltip"]').tooltip();

  $(".dropdown").hover(
  function() {
    $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
    $(this).toggleClass('open');
  },
  function() {
    $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
    $(this).toggleClass('open');
  });

});
</script>

</body>
</html>
