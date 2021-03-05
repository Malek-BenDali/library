<?php

namespace App\Controller;

use App\Entity\Comment;
use  App\Entity\Article;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_home")
     */
    public function index(ArticleRepository $repo)
    {
         
        return $this->render('article/index.html.twig', [
            'articles'=>$repo->findAll(),
        ]);
    }


    /**
     * @Route("/ajouter", name="article_create")
     * @Route("/{id}/editer", name="article_edit")
     */
    public function create(Article $article = null , Request $request, ObjectManager $manager ){

        if(!$article)   //ken article = null maaneha raw fonction jeyha appel mel /new else jeyha appel men edit
            $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getCreatedAt())
            $article->setCreatedAt(new \DateTime());

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_show',['id' => $article->getId()]);
        }
        

        return $this->render("article/create.html.twig", [
            'formArticle' => $form->createView(),
            'editMode' =>  $article->getId() == null
        ]);
    }


    /**
     * @Route("/blog/{id}", name="article_show")
     */
    public function show(Article $article , Request $request, ObjectManager $manager){

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime());
            $comment->setArticle( $article);

            $manager->persist($comment);
            $manager->flush();

            //return $this->redirectToRoute('article_show',['id' => $article->getId()]);
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'formComment' => $form->createView(),
        ]);
    }




}