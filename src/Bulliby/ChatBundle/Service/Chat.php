<?php

namespace Bulliby\ChatBundle\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Predis\Client;

 
class Chat implements MessageComponentInterface
{
    protected $clients;
    private $redis;

    public function __construct(Client $redis) {
        $this->clients = new \SplObjectStorage;
        $this->redis = $redis;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        $this->getUser();
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function getUser()
    {
        $sessionId = $this->redis->keys('*')[0];
        $user = $this->redis->get($sessionId);
        $user = strstr($user, '|');
        $user = substr($user, 1);
        $user = unserialize($user);
        $user = unserialize($user['_security_main']);
        var_dump($user);
        return $user;
    }
}
