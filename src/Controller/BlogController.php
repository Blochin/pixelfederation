<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Service\BlogService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    private BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    #[Route('/', name: 'redirect')]
    public function index()
    {
        return $this->redirectToRoute('blogs_all');
    }


    #[Route('/blog', name: 'blogs_all')]
    public function all(Request $request, BlogRepository $blogRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $blogRepository->createQueryBuilder('b');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('blog/show_all.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/blog/create', name: 'blog_create')]
    public function create(Request $request): RedirectResponse|Response
    {
        $result = $this->blogService->createBlog($request);

        if ($result['success']) {
            return $this->redirectToRoute('blogs_all');
        }

        return $this->render('blog/create.html.twig', [
            'form' => $result['form']
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Request $request, Blog $blog): Response
    {
        $result = $this->blogService->createComment($request, $blog);

        if ($result['success']) {
            return $this->redirectToRoute('blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'form' => $result['form']
        ]);
    }

}