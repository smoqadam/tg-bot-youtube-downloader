#### tg-bot-youtube-downloader
a telegram bot for download youtube videos.

1.Open `index.php` and set your API_TOKEN
 
2.Call setWebhook by opening this url : `https://api.telegram.org/API_TOKEN/setWebhook?url=https://yourdomain.com/index.php` 
>>> change API_TOKEN and yourdomain.com in the above link
 
3.Send `vid:YOUTUBE_VIDEO_ID` to your bot

Example : 

`vid:nCmUwepQrbA`

## Notice :

files saved in `videos/` directory will be delete after 24 hours. you can change the time in `checkForOldFiles` in `Youtube.php` class.
