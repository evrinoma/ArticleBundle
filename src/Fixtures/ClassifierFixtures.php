<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * (c) Nikolay Nikolaev <evrinoma@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evrinoma\ArticleBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Classifier\BaseClassifier;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class ClassifierFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            ClassifierApiDtoInterface::BRIEF => 'ite',
            ClassifierApiDtoInterface::DESCRIPTION => 'description ite',
            ClassifierApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2008-10-23 10:21:50',
        ],
        [
            ClassifierApiDtoInterface::BRIEF => 'kzkt',
            ClassifierApiDtoInterface::DESCRIPTION => 'description kzkt',
            ClassifierApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2015-10-23 10:21:50',
        ],
        [
            ClassifierApiDtoInterface::BRIEF => 'c2m',
            ClassifierApiDtoInterface::DESCRIPTION => 'description c2m',
            ClassifierApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2020-10-23 10:21:50',
        ],
        [
            ClassifierApiDtoInterface::BRIEF => 'kzkt2',
            ClassifierApiDtoInterface::DESCRIPTION => 'description kzkt2',
            ClassifierApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2015-10-23 10:21:50',
            ],
        [
            ClassifierApiDtoInterface::BRIEF => 'nvr',
            ClassifierApiDtoInterface::DESCRIPTION => 'description nvr',
            ClassifierApiDtoInterface::ACTIVE => 'b',
            'created_at' => '2010-10-23 10:21:50',
        ],
        [
            ClassifierApiDtoInterface::BRIEF => 'nvr2',
            ClassifierApiDtoInterface::DESCRIPTION => 'description nvr2',
            ClassifierApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2010-10-23 10:21:50',
            ],
        [
            ClassifierApiDtoInterface::BRIEF => 'nvr3',
            ClassifierApiDtoInterface::DESCRIPTION => 'description nvr3',
            ClassifierApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2011-10-23 10:21:50',
        ],
    ];

    protected static string $class = BaseClassifier::class;

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function create(ObjectManager $manager): self
    {
        $short = self::getReferenceName();
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = $this->getEntity();
            $entity
                ->setActive($record[ClassifierApiDtoInterface::ACTIVE])
                ->setBrief($record[ClassifierApiDtoInterface::BRIEF])
                ->setDescription($record[ClassifierApiDtoInterface::DESCRIPTION])
            ;

            $this->expandEntity($entity);

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::CLASSIFIER_FIXTURES, FixtureInterface::ARTICLE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 0;
    }
}
