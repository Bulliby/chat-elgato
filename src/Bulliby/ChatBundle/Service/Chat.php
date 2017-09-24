<?php

namespace Bulliby\ChatBundle\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Bulliby\ChatBundle\Inter\SecurityCheckInterface;
 
class Chat implements MessageComponentInterface,  SecurityCheckInterface
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
        $this->clients->attach($conn);
        $params = $conn->WebSocket->request->getQuery()->toArray();
        $user = $this->TokenIdCheck($params['token'], $params['user']);
        $this->clients->attach($user);
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
		if(($sender = $this->getUserWhoSendMsg($from)) === NULL)
			throw new NotFoundHttpException('Vous n\'êtes pas enregistré dans la base');
		if ($to = $this->canISendTheMessage($sender['user'], $msg))
        {
            $receiver = $this->getReceiver($to);
            $receiver['conn']->send($msg); 
        }
        $this->clients->rewind();
    }

	public function getUserWhoSendMsg($from)
	{
        $sender = [];
		$this->clients->rewind();
        while($this->clients->valid()) 
        {
			if ($this->clients->current() === $from)
            {
                $sender['conn'] = $this->clients->current(); 
                $this->clients->next();
                $sender['user'] = $this->clients->current();
                return $sender;
            }
			$this->clients->next();
		}
		return NULL;
	}

    public function getReceiver($to)
    {
        $receiver = [];
		$this->clients->rewind();
        while($this->clients->valid()) 
        {
            $receiver['conn'] = $this->clients->current(); 
            $this->clients->next();
			if ($this->clients->current() === $to)
            {
                $receiver['user'] = $this->clients->current();
                return $receiver;
            }
			$this->clients->next();
		}
		return NULL;
    }

    public function TokenIdCheck($token, $id)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('token' => $token));
        if (empty($user) || $user->getEmail() != $id)
            throw new AccessDeniedHttpException('Votre identité n\'est pas vérifiée');
        return $user;
    }
    
    private function canISendTheMessage($sender, $msg)
    {
		$this->msg = json_decode($msg);
        $to = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $this->msg->to));
        if (empty($to) || $to->getFamilly() != $sender->getFamilly())
            throw new AccessDeniedHttpException('Vous ne pouvez contacter cette personne');
        
        return $to;
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
