<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class StatusFixtures
 */
class StatusFixtures extends Fixture
{
    public static array $dataStatuses
        = [
            1 => 'DRAFT',
            2 => 'REVIEW',
            3 => 'PUBLISH',
        ];


    public function load(ObjectManager $manager)
    {
        foreach (self::$dataStatuses as $id => $name) {
            $status = new Status();

            $status->setId($id);
            $status->setName($name);

            $manager->persist($status);

            $statuses[] = $status;
        }

        $manager->flush();
    }
}
