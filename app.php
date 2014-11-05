#!/usr/bin/php -q
<?php

//debug
//$mail = file_get_contents("/tmp/trello2slack/record.txt",true);

require_once("Mail/mimeDecode.php");

$mail = file_get_contents("php://stdin");

if(!$mail) {
  exit();   
}

$params['include_bodies'] = true;
$params['decode_bodies']  = true;
$params['decode_headers'] = true;
$decoder   = new Mail_mimeDecode($mail);
$structure = $decoder->decode($params);

// elements
//////////////////////////////////////////////////////////////////////////////
$from    = $structure -> headers['from'];
$to      = $structure -> headers['delivered-to'];
$subject = $structure -> headers['subject'];
$date    = $structure -> headers['date'];
$textObj = explode(" ",explode("\n",$structure -> parts[0] -> {'body'})[4]);
$task    = $textObj[0];
$taskUrl = rtrim(ltrim($textObj[1],"("),")");
$dueDate = "";

for($i = array_search("due",$textObj) + 1;$i < count($textObj);$i++){
  $dueDate .= ($textObj[$i]." ");
}
$dueDate = trim($dueDate)."\n";
//////////////////////////////////////////////////////////////////////////////

// read info.taxt
$slackInfo = explode("\n",file_get_contents(dirname(__FILE__)."/info.txt",true));
$domain    = $slackInfo[0];
$token     = $slackInfo[1];
$channel   = $slackInfo[2];
$botName   = $slackInfo[3];
$iconEmoji = $slackInfo[4];

/*
$text = <<< EOM
  "task: $task
  url: $taskUrl
  due date: $dueDate"
EOM;
*/

$text = "task: <$taskUrl|$task> due date: $dueDate";
$text = trim($text);
exec("curl -X POST --data-urlencode 'payload={\"channel\": \"$channel\", \"username\": \"$botName\", \"text\": \"$text\", \"icon_emoji\": \"$iconEmoji\"}' https://$domain.slack.com/services/hooks/incoming-webhook?token=$token");