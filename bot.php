<?php
$m = "/root";
require('conf.php');
$info = json_decode(file_get_contents('info.json'),true);
$token =  readline("Enter Token : ");
$id = readline("Enter iD : ");
$info["token"] = "$token";
file_put_contents($m.'/info.json', json_encode($info));
$info["id"] = "$id";
file_put_contents($m.'/info.json', json_encode($info));
$tg = new Telegram($info["token"]);
$lastupdid = 1;
while(true){ 
$upd = $tg->vtcor("getUpdates", ["offset" => $lastupdid]);
 if(isset($upd['result'][0])){
  $text = $upd['result'][0]['message']['text'];
  $chat_id = $upd['result'][0]['message']['chat']['id'];
$from_id = $upd['result'][0]['message']['from']['id'];
$username = $upd['result'][0]['message']['from']['username'];
$sudo = $info["id"];
$admin = $sudo;
if($from_id == $admin){ 
  if($text == "/start" ){
    $tg->vtcor('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"Hi in Cheker @iven4 ",
    'inline_keyboard'=>true,
 'reply_markup'=>json_encode([
      'keyboard'=>[
        [['text'=>'/run']],
        [['text'=>'/stop']],
        [['text'=>'/setac']],
        [['text'=>'/setch']],
        [['text'=>'/add']],
        [['text'=>'/rem']],
        [['text'=>'/number']],
        [['text'=>'/users']],
        [['text'=>'/info']]
      ]
    ])
        ]);
}
if($text  == "/run"){ 
file_get_contents("run" , "yes"); 
shell_exec('screen -dmS checker php checker.php'); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"Running Done", 
]); 
} 
if($text  == "/stop"){ 
file_get_contents("run" , "no"); 
shell_exec('screen -S checker -X kill'); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"Stop Done", 
]); 
} 
if(preg_match('/add/', $text )) { 
$ex = explode('/add ',$text)[1]; 
file_put_contents("users",$ex."\n",FILE_APPEND); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"@$ex add To List", 
]); 
} 
if($text  == "/users"){ 
$user = file_get_contents("users");  
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"Users \n\n $user", 
]); 
} 
if($text  == "/info"){ 
$loop = file_get_contents("l.txt"); 
$us = file_get_contents("u.txt"); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"UserName : @".$us."\nLoops : ".$loop." ", 
]); 
}
if(preg_match('/(rem)/', $text )) { 
$ex = explode('/rem ',$text); 
$user = file_get_contents("users"); 
$s = str_replace(" ","\n",$ex[1]); 
$se = str_replace($s."\n","",$user); 
file_put_contents("users",$se); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>" @$ex[1] Removed From List ", 
]); 
} 
if($text  == "/setac"){ 
file_put_contents("where","u"); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"Set Account Done", 
]); 
} 
if($text  == "/setch"){ 
file_put_contents("where","c"); 
$tg->vtcor('sendMessage',[ 
'chat_id'=>$chat_id, 
'text'=>"Set Channel Done", 
]); 
} 
if($text == '/clear'){ 
file_put_contents("users",""); 
$tg->vtcor('sendmessage',[ 
'chat_id'=>$chat_id,  
'text'=>"The list is clear Now", 
]); 
} 
if($text == '/run'){
file_put_contents("run","yes");
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"- Was Running The Checker.!",
]);
}
if($text == '/number'){
	system('rm -rf *m*');
file_put_contents("step","");
if(file_get_contents("step") == ""){
$tg->vtcor('sendmessage',[
'chat_id'=>$chat_id, 
'text'=>"Send Number Phone ",
]);
file_put_contents("step","2");
  system('php g.php');

}
}
} 
$lastupdid = $upd['result'][0]['update_id'] + 1; 
} 
}
