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
use Elastica\Client;
use Elastica\Mapping;
use Elastica\Document;
use Symfony\Component\Yaml\Yaml;


class HomeController extends AbstractController
{
    private $postRepo;
    private $em;
    private $client;
    public function __construct(PostRepository $postRepo, EntityManagerInterface $em, Client $client){
        $this->postRepo = $postRepo;
        $this->em = $em;
        $this->client = $client;
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

            //Ajout de l'objet contact dans Elasticsearch
            $index = $this->client->getIndex('contacts_blog');
            /*
            $settings = Yaml::parse(
                file_get_contents(
                    __DIR__."../../../config/elasticsearch_contact_blog.yaml"
                )
            );
            */
            
            // Mapping (Définition des types des champs)
            $mapping = new Mapping();
            $mapping->setProperties(array(
                'name'     => array('type' => 'text'),
                'email'  => array('type' => 'text'),
                'message'=> array('type' => 'text')
            ));

            // Send mapping to index
            $mapping->send($index);

            //$index->create($settings, true);
            $index->addDocuments(
                [new Document(
                    $contact->getId(), // Manually defined ID
                    [
                        'name' => $contact->getName(),
                        'email' => $contact->getEmail(),
                        'message' => $contact->getMessage(),

                        // Not indexed but needed for display
                        'date' => $contact->getDate()->format('M d, Y'),
                    ]
                   // "contact" // Types are deprecated, to be removed in Elastic 7
                )
                ]
            );
            $index->refresh();

            $this->addFlash('success', 'Votre message vient d\'être envoyé!');
            //dd($contact);
        }
        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact
        ]);

    }
}
