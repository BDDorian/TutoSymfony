<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//Intégration de la classe Article
use App\Entity\Article;
//Intégration de la classe ArticleType pour le formulaire
use App\Form\ArticleType;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        //Trouver un repository à l'aide de doctrine pour le faire correpondre avec celui désiré.
        $repo = $this->getDoctrine()->getRepository(Article::class);

        //Cherchr tous les articles crées avec la méthode findAll()
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => " Bienvenue sur cette page d'accueil",
            'age' => 32
        ]);
    }

    //On place cette fonction avant afin que symfony ne se trompe pas avec le new.
    
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function formCreateOrUpdate(Article $article = null, Request $request, ObjectManager $manager)
    {
        //Condition si l'article est inexistant, il en génère un nouveau.
        if(!$article){
            $article = new Article();
        }
        
        /*$form =$this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm();
        */
        $form = $this->createForm(ArticleType::class, $article);            
        //analyse de la requête du formulaire.
        $form->handleRequest($request);
        
        //Vérification si le formulaire a été soumis et est valide.
        if($form->isSubmitted() && $form->isValid()) {
            //Si l'article ne possède aucun identifiant alors, on lui assigne une date de création.
            if(!$article->getId()){
            //Ajout de la date pour l'article crée.
                $article->setCreatedAt( new \DateTime());
            }
            

            //Préparation à la persistence en envoyant l'article en question.
            $manager->persist($article);

            //Execution de la commande.
            $manager->flush();

            //Redirection vers la page de notre article nouvellement crée.
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' =>$article->getId() !== null
        ]);

    }

    //On passe un paramètre id pour trouver la page correpondant à l'article
    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show($id)
    {
        $repo =$this->getDoctrine()->getRepository(Article::class);
        //find($id) pour récupérer les articles avec le bon id.
        $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

}

