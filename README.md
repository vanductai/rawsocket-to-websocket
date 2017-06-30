# rawsocket-to-websocket
These two PHP scripts would help transfer messages from raw socket clients to web socket clients (through 2 socket servers).

# Usage situation
You have some information within your application/software that you want to publish to your audiences on websites. The best case is that your application can start a web-socket connection. However, most of application does not support this protocol but raw-socket. That's why this bridge exists to solve.

```
  RAW-SOCKET SERVER  
(as a web-socket client) -------> WEB-SOCKET SERVER -------> WEB-SOCKET CLIENT(s)  
      ^  
      |  
      |  
RAW-SOCKET CLIENT(s)
```

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

# Default address & port
The raw-socket server starts at 0.0.0.0 with port 9000. This would accept all connections to this server. If you want to limit to localhost to connect (your clients run on the same machine), you should change host to 127.0.0.1.
The web-socket server starts at 0.0.0.0 with port 9001. This would accept all connections to this server. If you want to limit to localhost to connect (your clients run on the same machine), you should change host to 127.0.0.1.

You can change these ports to any number you want. However, they should be larger than 5000; if not, you need more granted permission to start these servers.