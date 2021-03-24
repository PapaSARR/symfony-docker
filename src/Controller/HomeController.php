<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Post;
use App\Form\Type\ContactType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $postRepo;
    private $em;
    public function __construct(PostRepository $postRepo, EntityManagerInterface $em){
        $this->postRepo = $postRepo;
        $this->em = $em;
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
    public function contact(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();
            $contact->setDate(new \DateTime());
            $this->em->persist($contact);
            $this->em->flush();
            $this->addFlash('success', 'Votre message vient d\'être envoyé!');
            //dd($contact);
        }
        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact
        ]);

    }
}
