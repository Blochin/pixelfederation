<?php

namespace App\Tests\Controller;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    private $entityManager;
    private $blogRepository;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->blogRepository = $this->entityManager->getRepository(Blog::class);

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
    }

    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $schemaTool->dropSchema($metadata);
        }

        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testAllBlogs()
    {
        $this->client->request('GET', '/blog');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateBlog()
    {
        $crawler = $this->client->request('GET', '/blog/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Create')->form([
            'blog[user][email]' => 'some@email.com',
            'blog[title]' => 'Test Blog',
            'blog[description]' => 'Test Description',
            'blog[content]' => 'Test blog content',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/blog');
        $this->client->followRedirect();

        $createdBlog = $this->blogRepository->findOneBy(['title' => 'Test Blog']);
        $this->assertNotNull($createdBlog);
        $this->assertSame('Test Blog', $createdBlog->getTitle());
        $this->assertSame('Test Description', $createdBlog->getDescription());
        $this->assertSame('Test blog content', $createdBlog->getContent());
    }

    public function testShowBlog()
    {
        $user = new User();
        $user->setEmail('some@email.com');
        $this->entityManager->persist($user);
        $blog = new Blog();
        $blog->setTitle('Test Blog');
        $blog->setContent('Test blog content');
        $blog->setDescription('Description');
        $blog->setUser($user);
        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        $this->client->request('GET', '/blog/'.$blog->getId());

        $this->assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/blog/'.$blog->getId());
        $form = $crawler->selectButton('Submit Comment')->form([
            'comment[user][email]' => 'some2@email.com',
            'comment[content]' => 'This is test comment',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/blog/'.$blog->getId());
        $this->client->followRedirect();

        $commentRepository = $this->entityManager->getRepository(Comment::class);
        $createdComment = $commentRepository->findOneBy(['content' => 'This is test comment']);
        $this->assertNotNull($createdComment);
        $this->assertSame('This is test comment', $createdComment->getContent());
    }
}