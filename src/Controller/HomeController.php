<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Post;
use App\Form\Type\ContactType;
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
        //dd($threeLastPosts);
        return $this->render('home/index.html.twig', [
            'threeLastPosts' => $threeLastPosts,
        ]);
    }


    /**
     * @Route("/posts/{id<[0-9]*>}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('home/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/posts}", name="posts", methods={"GET"})
     */
    public function posts(): Response
    {

        return $this->render('home/posts.html.twig', [
            'posts' => $this->postRepo->findAll(),
        ]);
    }

    /**
     * @Route("/contact}", name="contact", methods={"GET", "POST"})
     */
    public function contact(): Response
    {
        // creates a task object and initializes some data for this example
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
