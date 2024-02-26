<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article.index')]
    public function index(ArticleRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $articles = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/article/index.html.twig', [
            'articles' => $articles,

        ]);
    }

    #[Route('/article/nouveau', name: 'article.new')]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre article est créé'
            );

            return $this->redirectToRoute('article.index');
        }

        return $this->render('pages/article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/edition/{id}', name: 'article.edit')]
    public function edit(Article $article, Request $request,EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(ArticleType::class, $article);  
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'votre article est modifié'
            );

            return $this->redirectToRoute('article.index');
        }

        return $this->render('pages/article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/suppression/{id}', name: 'article.delete')]
    public function delete(EntityManagerInterface $manager,Article $article): Response
    {

        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            'votre article est supprimé'
        );

        return $this->redirectToRoute('article.index');
    }

}
