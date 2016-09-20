<?php

namespace LiveLife\MainBundle\DataFixtures\ORM;

use Dig\ApiBundle\Entity\Album;
use Dig\ApiBundle\Entity\Image;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadAlbumsWithImagesData extends AbstractFixture implements OrderedFixtureInterface
{
    private $fixtures = [
        0 => [
            'id' => 1,
            'name' => 'Album 1',
            'images' => [
                '1.jpg',
                '2.jpg',
                '3.jpg',
                '4.jpg',
                '5.jpg',
            ],
        ],

    ];

    public function getOrder()
    {
        return 10;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        #despite of generator is reset with schema recreate action, we will stick to defined ids via direct setter (important for Behat tests)
        $metadata = $manager->getClassMetaData('Dig\ApiBundle\Entity\Album');
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

        foreach ($this->fixtures as $album) {
            $newAlbum = $this->createAlbum($album);
            $manager->persist($newAlbum);
        }
        $manager->flush();
    }

    /**
     * @param array $albumData
     *
     * @return Album
     */
    public function createAlbum($albumData)
    {
        $album = new Album();
        $album->setId($albumData['id']);
        $album->setName($albumData['name']);

        if (!empty($albumData['images'])) {
            foreach ($albumData['images'] as $imageName) {
                $image = new Image();
                $image->setFileName($imageName);
                $image->setAlbum($album);
                $album->addImage($image);
            }
        }

        return $album;
    }
}
