<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatHandler implements MessageComponentInterface
{
    //Método llamado cuando un nuevo cliente se conecta
    public function onOpen(ConnectionInterface $conn)
    {
        echo "Nueva conexión: {$conn->resourceId}\n";
    }

    //Método llamado cuando un cliente recibe un mensaje
    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Mensaje recibido de {$from->resourceId}: $msg\n";

        //Aquí podrías enviar el mensaje a todos los clientes conectados
        foreach ($from->httpRequest->getConnection()->getConnections() as $client) {
            if ($from !== $client) {
                $client->send($msg); //Enviar el mensaje a todos menos al remitente
            }
        }
    }

    //Método llamado cuando un cliente cierra la conexión
    public function onClose(ConnectionInterface $conn)
    {
        echo "Conexión cerrada: {$conn->resourceId}\n";
    }

    //Método llamado cuando ocurre un error
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
