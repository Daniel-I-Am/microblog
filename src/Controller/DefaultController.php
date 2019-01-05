<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\NewPost;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery('SELECT p FROM App\Entity\BlogPost p ORDER BY p.TimeStamp DESC');
        $query->setMaxResults(10);
        $posts = $query->getResult();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/", name="post")
     */
     public function post(Request $request)
     {
        $newPost = new BlogPost();

        $form = $this->createFormBuilder($newPost)
            ->add('author', TextType::class)
            ->add('content', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPost = $form->getData();
            $newPost->setTimeStamp(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newPost);
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('default/post.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{message_number}", name="Edit Message")
     */
    public function edit($message_number)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery('SELECT p FROM App\Entity\BlogPost p WHERE p.id = :id');
        $query->setParameters(array(
            'id' => $message_number,
        ));
        $posts = $query->getResult();
        $post = NULL;
        if (sizeof($posts) >= 0)
        {
            $post = $posts[0];
        }
        return $this->render('default/post.html.twig', [
            'controller_name' => 'DefaultController',
            'post' => $post,
        ]);
    }

    /**
     * @Route("/delete/{message_number}", name="Delete Message")
     */
    public function delete($message_number)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery('DELETE FROM App\Entity\BlogPost p WHERE p.id = :id');
        $query->setParameters(array(
            'id' => $message_number,
        ));
        $query->execute();

        return $this->redirectToRoute('default');
    }
}
