<?php 
$info = json_decode(file_get_contents('info.json'),true);
define('API_KEY',$info['token']);
function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}
if (!file_exists('madeline.php')) {  
copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');  
}  
define('MADELINE_BRANCH', 'deprecated');
include 'madeline.php';  
$settings['app_info']['api_id'] = 203088;  
$settings['app_info']['api_hash'] = 'f360233d3627586775bd7298ee775bd1';  
$MadelineProto = new \danog\MadelineProto\API('me.madeline', $settings);  
$MadelineProto->start(); 
$admin = $info['id'];
$i = 0; 
$x = 0; 
$u = explode("\n",file_get_contents("users")); 
$b = explode("\n",file_get_contents("block")); 
while(true){ 
ob_start(); 
if(file_get_contents("run") == "yes"){ 
if($i > count($u)-2){ 
$i = 0; 
$u = explode("\n",file_get_contents("users")); 
$b = explode("\n",file_get_contents("block")); 
} 
if(!in_array($u[$i], $b)){ 
try{ 
$MadelineProto->messages->getPeerDialogs(['peers'=> [$u[$i]]]); 
echo "@".$u[$i]." - ".$x." - ".date("i:s")."\n"; 
$x++; 
file_put_contents("u.txt",$u[$i]); 
file_put_contents("l.txt",$x); 
}catch(Exception $e){ 
try{ 
$MadelineProto->account->checkUsername(['username' => $u[$i]]); 
}catch(Exception $e) { 
file_put_contents("block", $u[$i]."\n",FILE_APPEND); 
} 
$b = explode("\n",file_get_contents("block")); 
if(!in_array($u[$i], $b)){ 
$p = file_get_contents("type"); 
if(!preg_match("/bot/",$u[$i])){ 
if($p == "u"){ 
$MadelineProto->account->updateUsername(['username' => $u[$i]]); 
}elseif($p == "c"){ 
  $updates = $MadelineProto->channels->createChannel(['broadcast' => true, 'megagroup' => false, 'title' => "Z", 'about'=>"Z", ]);
  $ch = $updates['updates'][1];
$MadelineProto->channels->updateUsername(['channel' =>$ch, 'username' => $u[$i], ]);
} else {
  $MadelineProto->account->updateUsername(['username' => $u[$i]]); 
}
bot('sendmessage',[  
'chat_id'=>$admin,  
'text'=>"Hi : @$u[$i]"  
]); 
echo "New UserName : @$u[$i]\n"; 
if($p != "c" and !preg_match("/bot/",$u[$i])){ 
file_put_contents("run","no"); 
} 
} 
} 
} 
$i++; 
} 
ob_end_flush(); 
}