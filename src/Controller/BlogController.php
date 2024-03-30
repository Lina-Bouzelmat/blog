<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Article;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog.index')]
    public function index( Request $request , BlogRepository $repository ,PaginatorInterface $paginator): Response
    {

        $blogs = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('pages/blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }
    #[Route('/blog/nouveau', name: 'blog.new')]
    public function new( Request $request ,EntityManagerInterface $manager , BlogRepository $repository ,PaginatorInterface $paginator): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();

            $manager->persist($blog);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre blog est créé'
            );

            return $this->redirectToRoute('blog.index');
        }

        return $this->render('pages/blog/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/edition/{id}', name: 'blog.edit')]
    public function edit(Blog $blog, Request $request,EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(BlogType::class, $blog);  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();

            $manager->persist($blog);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre blog est modifié'
            );

            return $this->redirectToRoute('blog.index');
        }

        return $this->render('pages/blog/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/suppression/{id}', name: 'blog.delete')]
    public function delete(EntityManagerInterface $manager,Blog $blog): Response
    {

        $manager->remove($blog);
        $manager->flush();

        $this->addFlash(
            'success',
            'votre blog est supprimé'
        );

        return $this->redirectToRoute('blog.index');
    }

}
