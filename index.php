<?php 

$message = file_get_contents('php://input');

require 'libs/Telegram.php';

use Smoqadam\Telegram;

$api_token = 'API_TOKEN';
    
$tg = new Telegram($api);
   
$tg->cmd('vid:<<:any>>', function ($video_id) use ($tg){
        
        $url  = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $url  .= $_SERVER["SERVER_NAME"];
 
      if(!strlen($video_id)){
           
             $tg->sendMessage("vid:<youtube video ID>" , $tg->getChatId());
             return;
        }
       
       
        if(file_exists('videos/'.$video_id.'.mp4')){
             $tg->sendMessage("this file already downloaded. {$url}/videos/".$video_id.'.mp4' , $tg->getChatId());
             return;
        }
        
        
        $format = 'video/mp4'; //the MIME type of the video. e.g. video/mp4, video/webm, etc.
        parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=".$video_id),$info); //decode the data
        $streams = $info['url_encoded_fmt_stream_map']; //the video's location info
        
        $streams = explode(',',$streams);
        
        foreach($streams as $stream){
             parse_str($stream,$data); //decode the stream
            if(stripos($data['type'],$format) !== false){ //We've found the right stream with the correct format
                $video = fopen($data['url'].'&amp;signature='.$data['sig'],'r'); //the video
                $file = fopen('videos/'.$video_id.'.mp4','w');
                stream_copy_to_stream($video,$file); //copy it to the file
                fclose($video);
                fclose($file);
                echo 'Download finished! Check the file.<a href="{$url}/videos/"'.$video_id.'.mp4">DOWNLOAD</a>';
                break;
            }
        
        }
        
        $tg->sendMessage("Download finished. {$url}/videos/".$video_id.'.mp4' , $tg->getChatId());
        
        
        $path = 'videos/';
        $life_time = 24*3600;
        if ($handle = opendir($path)) {
        
            while (false !== ($file = readdir($handle))) { 
                $filelastmodified = filemtime($path . $file);
                //24 hours in a day * 3600 seconds per hour
                if((time() - $filelastmodified) > $life_time)
                {
                unlink($path . $file);
                }
        
            }
        
            closedir($handle); 
        }
        
        
});

$tg->process(json_decode($message,true));


