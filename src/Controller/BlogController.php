<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\Blog\BlogType as BlogBlogType;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Repository\BlogRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
  

    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository, Security $security): Response
    {
        $user = $security->getUser();

        $blogs = $blogRepository->findBy(['userId' => $user]); // Fetch blogs for logged-in user

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }


    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a blog post.');
        }

        $blog = new Blog();
        $form = $this->createForm(BlogBlogType::class, $blog);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $blog->setUserId($this->getUser());
            $blog->setCreatedAt(new \DateTimeImmutable());

            $imageFile = $form->get('imageFile')->getData();
            
        
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                
                try {
                    $imageFile->move(
                        $this->getParameter('blog_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('File upload error: ' . $e->getMessage());
                }
                if ($blog->getImage()) {
                    $oldFilePath = $this->getParameter('blog_images_directory') . '/' . $blog->getImage();
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $blog->setImage($newFilename);
            }

            $entityManager->persist($blog);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Blog $blog, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBlog($blog);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'commentForm' => $form->createView(),
        ]);
    }




    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        // Ensure only the owner can edit
        if ($blog->getUserId() !== $this->getUser()) {
            throw $this->createAccessDeniedException("You are not allowed to edit this blog.");
        }

        $form = $this->createForm(BlogBlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
        ]);
    }


    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager):Response
    {
        if ($blog->getUserId() !== $this->getUser()) {
            throw $this->createAccessDeniedException("You are not allowed to delete this blog.");
        }

        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_index');
    }

    
}
