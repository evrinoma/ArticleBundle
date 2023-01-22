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
use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Type\BaseType;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class TypeFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            TypeApiDtoInterface::BRIEF => 'ite',
            TypeApiDtoInterface::DESCRIPTION => 'description ite',
            TypeApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2008-10-23 10:21:50',
        ],
        [
            TypeApiDtoInterface::BRIEF => 'kzkt',
            TypeApiDtoInterface::DESCRIPTION => 'description kzkt',
            TypeApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2015-10-23 10:21:50',
        ],
        [
            TypeApiDtoInterface::BRIEF => 'c2m',
            TypeApiDtoInterface::DESCRIPTION => 'description c2m',
            TypeApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2020-10-23 10:21:50',
        ],
        [
            TypeApiDtoInterface::BRIEF => 'kzkt2',
            TypeApiDtoInterface::DESCRIPTION => 'description kzkt2',
            TypeApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2015-10-23 10:21:50',
            ],
        [
            TypeApiDtoInterface::BRIEF => 'nvr',
            TypeApiDtoInterface::DESCRIPTION => 'description nvr',
            TypeApiDtoInterface::ACTIVE => 'b',
            'created_at' => '2010-10-23 10:21:50',
        ],
        [
            TypeApiDtoInterface::BRIEF => 'nvr2',
            TypeApiDtoInterface::DESCRIPTION => 'description nvr2',
            TypeApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2010-10-23 10:21:50',
            ],
        [
            TypeApiDtoInterface::BRIEF => 'nvr3',
            TypeApiDtoInterface::DESCRIPTION => 'description nvr3',
            TypeApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2011-10-23 10:21:50',
        ],
    ];

    protected static string $class = BaseType::class;

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
                ->setActive($record[TypeApiDtoInterface::ACTIVE])
                ->setBrief($record[TypeApiDtoInterface::BRIEF])
                ->setDescription($record[TypeApiDtoInterface::DESCRIPTION])
            ;

            $this->expandEntity($entity, $record);

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::TYPE_FIXTURES, FixtureInterface::ARTICLE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 0;
    }
}
