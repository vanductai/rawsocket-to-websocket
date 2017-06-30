# rawsocket-to-websocket
These two PHP scripts would help transfer messages from raw socket clients to web socket clients (through 2 socket servers).

# How to start
Start servers in developing mode:
`php -q rawsocket.php
php -q websocket.php`

Start servers in background:
`nohup php -q rawsocket.php &
nohup php -q websocket.php &`