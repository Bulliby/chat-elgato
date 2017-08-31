<?php

namespace Bulliby\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{
    /**
     * @Route("/chat", name="chat")
     */
    public function renderAction()
    {
        return $this->render('BullibyChatBundle:Chat:render.html.twig');
    }

}
