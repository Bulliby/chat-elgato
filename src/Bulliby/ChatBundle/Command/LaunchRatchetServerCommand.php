<?php

namespace Bulliby\ChatBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Bulliby\ChatBundle\Services\Chat;

use Ratchet\Session\SessionProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Ratchet\App;



class LaunchRatchetServerCommand extends ContainerAwareCommand
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
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->getContainer()->get('test')
                )
            ),
            8080,
            '127.0.0.1'
        );

        $server->run();
    }
}
