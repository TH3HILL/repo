<?php
if (!file_exists('madeline.php')){
copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
$settings['app_info']['api_id'] = 203088;
$settings['app_info']['api_hash'] = 'f360233d3627586775bd7298ee775bd1';
$MadelineProto = new \danog\MadelineProto\API('me.madeline', $settings);
require("conf.php");
$info = json_decode(file_get_contents('info.json'),true);
$tg = new Telegram($info['token']);
$lastupdid = 1;
while(true){
 $upd = $tg->vtcor("getUpdates", ["offset" => $lastupdid]);
 if(isset($upd['result'][0])){
  $text = $upd['result'][0]['message']['text'];
  $chat_id = $upd['result'][0]['message']['chat']['id'];
$from_id = $upd['result'][0]['message']['from']['id'];
try{
if(file_get_contents("step") == "2"){
if($text !== number{
$MadelineProto->phone_login($text);
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Send Code Now ",
]);
file_put_contents("step","3");
}
}elseif(file_get_contents("step") == "3"){
if($text){
$authorization = $MadelineProto->complete_phone_login($text);
if ($authorization['_'] === 'account.password') {
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Send Account password",
]);
file_put_contents("step","4");
}else{
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Login Done",
]);
file_put_contents("step","");
exit;
}
}
}elseif(file_get_contents("step") == "4"){
if($text){
$authorization = $MadelineProto->complete_2fa_login($text);
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Login Done",
]);
file_put_contents("step","");
exit;
}
}
}catch(Exception $e) {
  $tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Try Again",
]);
exit;
}
$lastupdid = $upd['result'][0]['update_id'] + 1;
}
}