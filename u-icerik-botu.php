<?php 
/*
Plugin Name: umiT Wordpress İçerik Botu Rss
Plugin URI: umiT Wordpress İçerik Botu Rss
Description: umiT Wordpress İçerik Botu Rss
Version: 1.0
Author: umit ozay
Author URI:  umit ozay
License: GNU
*/
add_action('admin_menu','upanel');function upanel(){add_menu_page('umiT Wp Bot','umiT Wp Bot','manage_options','umit-worpdress-bot','umit_bot_yonetim');}function umit_bot_yonetim(){ ?>
<h1>umiT Wordpress İçerik Botu</h1><form enctype="multipart/form-data"method="post"><table class="form-table"><tr><th style="width:20%"><label>Site Url Adresi</label></th><td><input name="url"size="30"style="width:97%"><br><td><button class="button button-large button-primary"type="submit">Verileri Gör</button></tr></table></form>
<?php if($_SERVER["REQUEST_METHOD"]=="POST"){if(!empty($_POST['url'])){$veriurl=$_POST['url'];}} ?>
<?php function feedMe($feed){$ch=curl_init();curl_setopt($ch,CURLOPT_URL,$feed);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_HEADER,0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$rss=curl_exec($ch);curl_close($ch);$rss=str_replace("<content:encoded>","<contentEncoded>",$rss);$rss=str_replace("</content:encoded>","</contentEncoded>",$rss);$rss=simplexml_load_string($rss);$siteTitle=$rss->channel->title;$cnt=count($rss->channel->item);for($i=0;$i<$cnt;$i++){$url=$rss->channel->item[$i]->link;$title=$rss->channel->item[$i]->title;$desc=$rss->channel->item[$i]->description;$makale=$rss->channel->item[$i]->contentEncoded;echo"\n<br><br><br>\n<form id='new_post' name='new_post' method='post' action='#' enctype='multipart/form-data' novalidate='novalidate' onsubmit='return check();'>\n<label>Makale Başlığı</label><br><br>\n<input type='text' style='width:97%' id='websitetitle' name='websitetitle' required value='".strip_tags($title)."'>\n<br><br><br>\n<label>Makale İçeriği <font style='color:red;font-weight:bold;'>Makalelerin sonunda bulunan The post -MakaleBaşlık- first appeared on -SiteAdı- kısmını kaldırınız.</font></label><br><br>\n<textarea style='width:97%;min-height:250px;' autocomplete='off' cols='40' name='description' id='description'>".strip_tags($makale)."</textarea>\n<br><br><br>\n<button type='submit' class='button button-primary button-large' id='submit' name='submit'>Yazıyı Yayınla</button>\n<input type='hidden' name='action' value='new_post' />\n".wp_nonce_field('new-post')."\n</form>\n";}}feedMe($veriurl."/feed"); ?>
<?php if('POST'==$_SERVER['REQUEST_METHOD']&&!empty($_POST['action'])&&$_POST['action']=="new_post"){if(isset($_POST['submit'])){$error="";if(!empty($_POST['websitetitle'])){$title=$_POST['websitetitle'];}else{$error.="Lütfen <b>Başlık</b> girin.<br />";}if(!empty($_POST['description'])){$description=$_POST['description'];}else{$error.="Lütfen <b>Tanım</b> girin.<br />";}$tags=$_POST['tags_input'];$cat=array($_POST['cat']);if(empty($error)){$new_post=array('post_title'=>$title,'post_content'=>$description,'post_category'=>array($_POST['cat']),'post_status'=>'publish','post_type'=>'post','tags_input'=>$tags,);$pid=wp_insert_post($new_post);$success.="<div class='alert alert-success alert-dismissible fade show' role='alert'>\n<strong>Veriler güncelleniyor. Yazılar paylaşılıyor...</strong>\n<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>\n</div>";}}}do_action('wp_insert_post','wp_insert_post'); ?>
<?php
}
?>
