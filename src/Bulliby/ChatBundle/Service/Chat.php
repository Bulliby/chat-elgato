<?php

namespace Bulliby\ChatBundle\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Bulliby\ChatBundle\Inter\SecurityCheckInterface;
 
class Chat implements MessageComponentInterface,  SecurityCheckInterface
{
    protected $clients;
    private $em;
    private $msg;

    public function __construct(EntityManagerInterface $em) 
    {
        $this->clients = new \SplObjectStorage;
        $this->em = $em;
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        $params = $conn->WebSocket->request->getQuery()->toArray();
        try 
        {
            $user = $this->TokenIdCheck($params['token'], $params['user']);
        } 
        catch (\Exception $e)
        {
            //TODO: Log the attempt.
            //Redirect on Error page and Logout
        }
        $this->clients->attach($conn);
        $this->clients->attach($user);
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
		if(($sender = $this->getUserWhoSendMsg($from)) === NULL)
			throw new NotFoundHttpException('Une erreur inconnue c\'est produite');
        try 
        {
            if ($to = $this->canISendTheMessage($sender['user'], $msg))
            {
                $receiver = $this->getReceiver($to);
                $receiver['conn']->send($msg); 
            }
        } 
        catch(\Exception $e)
        {
            //TODO: Log the attempt.
            //Redirect on Error page and Logout
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
        return $to;
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()} {$e}\n";
        $conn->close();
    }
}
