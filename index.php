<?php

$message = file_get_contents('php://input');

require 'libs/Telegram.php';
require 'libs/Youtube.php';

use Smoqadam\Telegram;
use Smoqadam\Youtube;

$api_token = '257526801:AAFsNsUX1FLs-K3PDixyr4mzVn4ErDOjrWQ';

$tg = new Telegram($api_token);
$y = new Youtube();


/**
 * download a video by video Id
 */
$tg->cmd('vid:<<:any>>', function ($video_id, $option) use ($tg, $y, $message) {


    if (!strlen($video_id)) {

        $tg->sendMessage("vid:<youtube video ID>", $tg->getChatId());
        return;
    }

    // get video information and initial
    if($y->init($video_id) !== false){

    	$tg->sendMessage("Please wait ...", $tg->getChatId());
    	//$tg->sendMessage($y->getName(), $tg->getChatId());
    	$msg = "Download finished! \n".$y->download();
    }else{
  	$msg = $y->getError();
    }

    $tg->sendMessage($msg, $tg->getChatId());

    $y->checkForOldFiles();

});

/**
 * delete file by video id
 */
$tg->cmd('del:<<:any>>', function ($video_id) use ($tg, $y) {

    $y->init($video_id);
    $y->deleteFile($y->getPath() . $y->getTitle() . '.mp4');
    $msg = $y->getTitle() . ' Deleted!!';
    $tg->sendMessage($msg, $tg->getChatId());

});


$tg->process(json_decode($message, true));


