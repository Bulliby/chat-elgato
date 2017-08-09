<?php

namespace Bulliby\ChatBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use Bulliby\ChatBundle\Command\Chat;


class LaunchRatchetServerCommand extends Command
{

    protected function configure()
    {
		 $this
        ->setName('chat:launch-ratchet-server')
        ->setDescription('Launch the Ratchet Server')
        ->setHelp('Documentation at : http://socketo.me/docs/')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //require dirname(__DIR__) . '/vendor/autoload.php';

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080,
            '127.0.0.1'
        );

        $server->run();
    }
}
