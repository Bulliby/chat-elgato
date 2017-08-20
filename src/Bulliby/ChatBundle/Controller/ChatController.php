<?php

namespace Bulliby\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="chat")
     */
    public function renderAction()
    {
        var_dump($this->get('session'));
        return $this->render('BullibyChatBundle:Chat:render.html.twig');
    }
}
