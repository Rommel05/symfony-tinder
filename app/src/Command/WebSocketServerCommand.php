<?php

namespace App\Command;

use App\WebSocket\ChatHandler;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebSocketServerCommand extends Command
{
    //Nombre del comando
    protected static $defaultName = 'app:websocket-server';

    //Aquí se define lo q hará el comando, al ejecutar el comando se mostrará esto por pantalla
    protected function configure(): void
    {
        $this
            ->setDescription('Inicia el servidor WebSocket.')
            ->setHelp('Este comando ejecuta un servidor WebSocket en Ratchet para manejar conexiones en tiempo real.');
    }

    //Aquí está la lógica principal del comando
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //mensaje inicial
        $output->writeln('Iniciando servidor WebSocket en el puerto 9000...');

        // Instancia del manejador, en el manejador se define cómo el servidor manejará los eventos
        $chatHandler = new ChatHandler();

        // Configuración del servidor Ratchet
        $server = IoServer::factory(
            new HttpServer(
                new WsServer($chatHandler)
            ),
            9000
        );

        $output->writeln('Servidor WebSocket iniciado en ws://localhost:9000');

        $server->run(); // Inicia el servidor (bloqueante, se quedará en ejecución hasta que sea detenido)
        return Command::SUCCESS; //Mensaje de exito
    }
}
