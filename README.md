# rawsocket-to-websocket
These two PHP scripts would help transfer messages from raw socket clients to web socket clients (through 2 socket servers).

# How to start
Start servers in developing mode with terminal to see debug messages (if needed):
```
php -q rawsocket.php  
php -q websocket.php
```

Start servers in background with defaul nohup.out at current directory:
```
nohup php -q rawsocket.php &  
nohup php -q websocket.php &
```

Start servers in background without output (nohup.out):
```
nohup php -q rawsocket.php >/dev/null 2>&1 &  
nohup php -q websocket.php >/dev/null 2>&1 &
```

# Default values
The raw-socket server start with port 9000.
The web-socket server start with port 9001.
