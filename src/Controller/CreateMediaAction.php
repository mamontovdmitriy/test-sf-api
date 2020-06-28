<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateMediaAction
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Request $request): Media
    {
        $articleId = $request->request->get('article_id');
        if (!$articleId) {
            throw new BadRequestHttpException('"article_id" is required');
        }

        $article = $this->entityManager->getRepository(Article::class)->find($articleId);
        if (!$article instanceof Article) {
            throw new NotFoundHttpException('Article not found');
        }

        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $media = new Media();
        $media->setFile($uploadedFile);
        $media->setArticle($article);

        return $media;
    }
}
