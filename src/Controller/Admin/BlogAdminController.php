<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogAdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony Docker');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        //yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'home');
        yield MenuItem::linkToCrud('Post', 'fas fa-edit', Post::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
    }
}
