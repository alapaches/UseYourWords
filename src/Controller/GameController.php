<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function rand_string($length) {  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result = "";
        $size = strlen($chars);  
        for( $i = 0; $i < $length; $i++ ) {  
            $str= $chars[ rand( 0, $size - 1 ) ];  
            $result.=$str;
        }

        return $result;
    }
    /**
     * @Route("/game", name="game_index")
     */
    public function index(): Response
    {
        $code;
        $codeExists = $this->em->getRepository(Game::class)->findAll();
        if(count($codeExists) == 0) {
            $code = $this->rand_string(5);
            $game = new Game();
            $game->setToken($code);
            $this->em->persist($game);
            $this->em->flush();
        } else {
            $code = $codeExists[0]->getToken();
        }
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'code'  => $code
        ]);
    }
}
