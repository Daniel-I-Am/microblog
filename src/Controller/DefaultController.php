<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function post()
    {
        return $this->render('default/post.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/post_message/", name="New Message")
     */
    public function new_message()
    {
        $entityManager = $this->getDoctrine()->getManager();

        // check if an ID has been specifed
        if (!isset($_POST['id']))
        {
            // no ID: make a new post
            $newPost = new BlogPost();
            $newPost->setAuthor($_POST['user']);
            $newPost->setTimeStamp(new \DateTime());
            $newPost->setContent($_POST['content']);

            $entityManager->persist($newPost);
            $entityManager->flush();
        } else {
            // ID provided: update the message at ID
            $query = $entityManager->createQuery('SELECT p FROM App\Entity\BlogPost p WHERE p.id = :id');
            $query->setParameters(array(
                'id' => $_POST['id'],
            ));
            $posts = $query->getResult();
            if (sizeof($posts) == 0)
                return $this->edit($_POST['id']);
            $post = $posts[0];
            if ($post->getContent() != $_POST['content']) {
                $post->setContent($_POST['content']);
                $post->setLastEdited(new \DateTime());
            }
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('default');
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
