<?php

/**
 * WebSocket
 *
 * Simple WebSocket class for learning purpose. Wrapping a socket PHP API.
 *
 * @package WebSocket
 * @author Gemblue
 */

namespace App;

class Websocket {

    /** Props */
    public $server;
    public $address;
    public $port;
    public $clients;
    public $pdo;
    private $connections;

    /**
     * Construct
     *
     * With address and port.
     *
     * @return void
     */
    public function __construct($address, $port, $pdo) {

        $this->server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $this->address = $address;
        $this->port = $port;
        $this->pdo = $pdo;

        socket_set_option($this->server, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->server, 0, $port);
        socket_listen($this->server);

    }

    /**
     * Send
     *
     * Kirim pesan ke semua client, sebelumnya di encode json dulu.
     *
     * @return bool
     */
    function send($message) {

        // Build json dengan seal.
        $raw = $this->seal(json_encode([
            'message'=> $message
        ]));

        if (is_array($message) && $message["type"] == 'chatmsg'){
            if (isset($this->connections[$message["receiverId"]]))
                @socket_write($this->connections[$message["receiverId"] ], $raw, strlen($raw));
            if (isset($this->connections[$message["sennderId"]]))
                @socket_write($this->connections[$message["sennderId"] ], $raw, strlen($raw));
        }else{

            foreach($this->clients as $client)
            {
                @socket_write($client, $raw, strlen($raw));
            }
        }
        return true;
    }

    /**
     * Unseal
     *
     * Karena socket receive masih mentah, kita harus unseal dulu.
     *
     * @return string
     */
    public function unseal($socketData) {

        $length = ord($socketData[1]) & 127;

        if ($length == 126) {
            $masks = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        } elseif ($length == 127) {
            $masks = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        } else {
            $masks = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }

        $socketData = "";

        for ($i = 0; $i < strlen($data); ++$i) {
            $socketData .= $data[$i] ^ $masks[$i%4];
        }

        return $socketData;
    }

    /**
     * Seal
     *
     * Untuk mengirimkan data seal.
     *
     * @return string
     */
    function seal($socketData) {

        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($socketData);

        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif ($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif ($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);

        return $header.$socketData;
    }

    /**
     * Handshake
     *
     * Mengirimkan handshake headers ke client yang connect.
     *
     * @return void
     */
    function handshake($header, $socket, $address, $port) {

        $headers = array();
        $lines = preg_split("/\r\n/", $header);
        foreach($lines as $line)
        {
            $line = chop($line);
            if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
            {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $buffer   = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n";
        $buffer  .= "Upgrade: websocket\r\n";
        $buffer  .= "Connection: Upgrade\r\n";
        $buffer  .= "WebSocket-Origin: $address\r\n";
        $buffer  .= "WebSocket-Location: ws://$address:$port/Server.php\r\n";
        $buffer  .= "Sec-WebSocket-Accept:$secAccept\r\n\r\n";

        socket_write($socket,$buffer,strlen($buffer));

    }

    /**
     * Run
     *
     * Running websocket server.
     *
     * @return void
     */
    public function run() {

        // Masukan koneksi server kedalam clients.
        $this->clients = [
            $this->server
        ];
        $this->connections = [];
        // Set address and port.
        $address = $this->address;
        $port = $this->port;

        // Log message
        echo "Listening incoming request on port {$this->port} ..\n";

        // Unlimited loop.
        while (true)
        {
            $resultado = $this->pdo->query("SELECT m.*, u.name  as senderName, u2.name  as receiverName
			FROM message as m 
			INNER join user as u
			ON u.id = m.sennder_id
			INNER join user as u2
			ON u2.id = m.receiver_id
			WHERE m.sended = 0");

            //iterar sobre los resultados de la consulta
            while ($registro = $resultado->fetch())
            {
                $current_receiver_id = $registro['receiver_id'];
                $current_sennder_id = $registro['sennder_id'];
                $user_message = $registro['text'];
                $senderName = $registro['senderName'];
                $this->send((array('type'=>'chatmsg', 'receiverId'=>$current_receiver_id, 'sennderId'=>$current_sennder_id,
                    'text'=>$user_message, 'timestamp'=>new \DateTime($registro['timestamp']), "sendername"=>$senderName)));
                $this->pdo->exec('UPDATE message SET sended  = true WHERE id = ' . $registro['id']);

            }

            $resultado = $this->pdo->query("SELECT * FROM user WHERE sended = 0");

            while ($registro = $resultado->fetch())
            {
                //sleep(10);
                $userId = $registro['id'];
                $userName = $registro['username'];
                $this->send((array('type'=>'usermsg', 'id'=>$userId, 'userName'=>$userName/*, 'info'=>$registro['info'], "image"=>$registro['image']*/)));
                $this->pdo->exec('UPDATE user SET sended  = true WHERE id = ' . $registro['id']);

            }

            $newClients = $this->clients;

            socket_select($newClients, $null, $null, 0, 10);

            //Si la conexión de socket actual existe en los clientes.
            if (in_array($this->server, $newClients))
            {
                // Aceptar nueva conexión..
                $newSocket = socket_accept($this->server);

                // Entrada en socket cliente/contenedor.
                $this->clients[] = $newSocket;

                // Lee los datos entrantes del socket del túnel entrante, el navegador generalmente envía encabezados.
                $header = socket_read($newSocket, 1024);

                // Apretón de manos, enviar datos de respuesta.
                $this->handshake($header, $newSocket, $address, $port);

                // Enviar un mensaje, se ha unido un nuevo cliente, a todos los clientes conectados.
                socket_getpeername($newSocket, $ip);
                $this->send("Cliente con ip {$ip} acaba de unirse");

                echo "Cliente con ip {$ip} acaba de unirse \n";

                $index = array_search($this->server, $newClients);
                unset($newClients[$index]);
            }
            foreach ($newClients as $newClientsResource)
            {


                // Durante el ciclo ilimitado, recibir datos enviados desde el cliente, desde el método "websocket.send" del navegador.
                while(socket_recv($newClientsResource, $socketData, 1024, 0) >= 1)
                {
                    // Si hay datos recibidos, entonces proceso
                    if ($socketData) {

                        // Reciba datos del cliente, luego abra y decodifique json.
                        $socketMessage = $this->unseal($socketData);
                        $messageObj = json_decode($socketMessage);

                        if (isset($messageObj->type) && $messageObj->type == 'chatData'){
                            echo $messageObj->type; ;
                            echo $messageObj->fromUserId . " " . $messageObj->toUserId;
                            $this->connections[$messageObj->fromUserId] =  $newClientsResource;
                        }
                        break 2;
                    }
                }
                // En el bucle, compruebe siempre si el cliente ha salido o no.
                // El método es leer desde un socket de lectura basado en un cliente conectado, si sale, da un mensaje de salida.
                $socketData = @socket_read($newClientsResource, 1024, PHP_NORMAL_READ);

                // Falso significa túnel de salida.
                if ($socketData === false)
                {
                    // Beri pesan keluar.
                    socket_getpeername($newClientsResource, $ip);
                    $this->send("Cliente con ip {$ip} acaba de cerrar sesión");

                    echo "Cliente con ip {$ip} acaba de cerrar sesión \n";

                    // Hapus current index dari connected client.
                    $index = array_search($newClientsResource, $this->clients);
                    unset($this->clients[$index]);
                }
            }
        }

        socket_close($this->server);
    }
}