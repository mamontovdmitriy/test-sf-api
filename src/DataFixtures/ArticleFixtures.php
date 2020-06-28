<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Status;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ArticleFixtures
 */
class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public static array $dataStatuses
        = [
            1 => 'DRAFT',
            2 => 'REVIEW',
            3 => 'PUBLISH',
        ];

    public function getDependencies()
    {
        return [StatusFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // statuses
        $statuses = $manager->getRepository(Status::class)->findAll();

        // tags
        $tags = [];
        for ($i = 0; $i < 15; $i++) {
            $tag = new Tag();

            $tag->setName($faker->unique()->word);
            if ($i >= 10) {
                $tag->setDeletedAt(new \DateTime());
            }

            $manager->persist($tag);

            $tags[] = $tag;
        }

        // articles
        for ($i = 0; $i < 30; $i++) {
            $article = new Article();

            $article->setTitle($faker->sentence());
            $article->setContent($faker->realText(1000));
            $article->setStatus($faker->randomElement($statuses));

            $createdAt = $faker->dateTimeThisYear();
            $article->setCreatedAt($createdAt);
            $article->setUpdatedAt($createdAt);

            if (Status::PUBLISH === $article->getStatus()->getId()) {
                $article->setPublishedAt($createdAt);
            }

            if ($i % 5 === 0) {
                $article->setDeletedAt(new \DateTime());
            }

            $articleTags = $faker->randomElements($tags, $faker->numberBetween(2, 6));
            foreach ($articleTags as $articleTag) {
                $article->addTag($articleTag);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }

    private function insertStatuses(ObjectManager $manager)
    {
        $statuses = [];

        foreach (self::$dataStatuses as $id => $name) {
            $status = new Status();

            $status->setId($id);
            $status->setName($name);

            $manager->persist($status);

            $statuses[] = $status;
        }

        return $statuses;
    }
}
