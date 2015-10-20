<?php
/**
 * User: Saeed Moqadam
 * email : phpro.ir@gmail.com
 * home page : http://github.com/smoqadam
 * Date: 10/15/2015
 * Time: 04:14 PM
 */

namespace Smoqadam;

class Youtube
{
    /**
     * store video information
     * @var array
     */
    private $info;

    /**
     * store current website url
     * @var string
     */
    private $url;

    /**
     * store mime type video format
     * @var string
     */
    private $format;

    /**
     * store errors
     * @var string
     */
    private $error = null;


    /**
     * Initial downloading and check for download permission
     *
     * @param $video_id
     * @param string $format
     */
    public function init($video_id, $format = 'video/mp4')
    {

        parse_str(file_get_contents("http://youtube.com/get_video_info?video_id=" . $video_id), $info); //decode the data

        if ($info['status'] == 'fail') {
            $this->error = $info['reason'];
            return false;
        }
        $this->info = $info;

        $url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $url .= $_SERVER["SERVER_NAME"];
        $this->url = $url;

        $this->format = $format;


        if (!file_exists('videos/')) {
            mkdir('videos');
            @chmod('videos', 0755);
        }

    }

    /**
     * return video information
     *
     * @return mixed
     */
    public function getStream()
    {
        return $this->info['url_encoded_fmt_stream_map'];
    }

    /**
     * get the video title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return str_replace([' ', '?', '/', '\\', '&'], '_', $this->info['title']);
    }

    /**
     * return videos directory path
     * @return string
     */
    public function getPath()
    {
        return 'videos/';
    }

    /**
     * return error
     *
     * @return bool|null
     */
    public function getError()
    {
        if ($this->error) {
            return $this->error;
        }
        return false;
    }


    /**
     * Download video from youtube.
     *
     * @return string
     */
    public function download()
    {
        $streams = $this->getStream();
        $streams = explode(',', $streams);
        $title = $this->getTitle();


        if (file_exists('videos/' . $title . '.mp4')) {
            return "{$this->url}/videos/" . $title . '.mp4';
        }

        foreach ($streams as $stream) {
            parse_str($stream, $data); //decode the stream
            if (stripos($data['type'], $this->format) !== false) { //We've found the right stream with the correct format
                $video = fopen($data['url'] . '&amp;signature=' . $data['sig'], 'r'); //the video
                $file = fopen('videos/' . $title . '.mp4', 'w');
                stream_copy_to_stream($video, $file); //copy it to the file
                fclose($video);
                fclose($file);
                $msg = "{$this->url}/videos/{$title}.mp4";
                break;
            }
        }

        return $msg;

    }


    /**
     * Remove files older that 24 hours
     */
    public function checkForOldFiles()
    {
        $path = $this->getPath();
        $life_time = 24 * 3600;
        if ($handle = opendir($path)) {

            while (false !== ($file = readdir($handle))) {
                $filelastmodified = filemtime($path . $file);
                if ((time() - $filelastmodified) > $life_time) {
                    $this->deleteFile($path . $file);
                }

            }
            closedir($handle);
        }
    }


    /**
     * Delete file
     *
     * @param String $path
     */
    public function deleteFile($path)
    {
        unlink($path);
    }

}
