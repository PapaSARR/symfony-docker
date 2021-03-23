<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $postRepo;
    public function __construct(PostRepository $postRepo){
        $this->postRepo = $postRepo;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $threeLastPosts = $this->postRepo->findThreeLastPosts();
        //var_dump($threeLastPosts);
        return $this->render('home/index.html.twig', [
            'threeLastPosts' => $threeLastPosts,
        ]);
    }
}
