#### tg-bot-youtube-downloader
a telegram bot for download youtube videos.

 1 - open `index.php` and set your API_TOKEN
 
 2 - call setWebhook at https://api.telegram.org/API_TOKEN/setWebhook?url=https://yourdomain.com/log.php 
 
 change API_TOKEN and yourdomain.com in the above link
 
 3 - send `vid:YOUTUBE_VIDEO_ID` to your bot


`vid:nCmUwepQrbA`

## Notice :

files save in `videos/` directory. videos will be delete after 24 hours. you can change the time in `$life_time` variable in `index.php`
