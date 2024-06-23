<?php

namespace App\Service;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\BlogType;
use App\Form\CommentType;
use App\Helper\CreateUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BlogService
{
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function createBlog(Request $request): array|RedirectResponse
    {
        $blog = new Blog();
        $form = $this->formFactory->create(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser = new CreateUser($this->entityManager);
            $createUser->handle($blog);
            $this->entityManager->persist($blog);
            $this->entityManager->flush();

            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'form' => $form->createView()
        ];
    }

    public function createComment(Request $request, Blog $blog): array
    {
        $comment = new Comment();
        $form = $this->formFactory->create(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser = new CreateUser($this->entityManager);
            $createUser->handle($comment);
            $comment->setBlog($blog);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'form' => $form->createView()
        ];
    }
}