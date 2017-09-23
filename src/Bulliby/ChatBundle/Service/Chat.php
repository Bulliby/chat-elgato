<?php

namespace Bulliby\ChatBundle\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
 
class Chat implements MessageComponentInterface
{
    protected $clients;
    private $em;
    private $msg;

    public function __construct(EntityManager $em) 
    {
        $this->clients = new \SplObjectStorage;
        $this->em = $em;
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        echo "New connection! ({$conn->resourceId})\n";
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
        $this->canISendTheMessage($msg);
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    /**
     * Function who check if the message can be send. We can have for example
     * a test to see that the sender can contact this user.
     *
     * This function is intended to be overwriten
     */
    private function canISendTheMessage($msg)
    {
        $this->msg  = json_decode($msg);
        if (!isset($this->msg->token))
            return;
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('token' => $this->msg->token));
        if (empty($user))
            throw new NotFoundHttpException("Petit hacker ne modifie pas tes requetes!!");
        $to = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $this->msg->to));
        if ($to->getFamilly() != $user->getFamilly())
            throw new NotFoundHttpException("Petit hacker ne modifie pas tes requetes!!");
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
