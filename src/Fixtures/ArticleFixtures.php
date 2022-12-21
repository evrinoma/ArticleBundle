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
use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Entity\Article\BaseArticle;
use Evrinoma\TestUtilsBundle\Fixtures\AbstractFixture;

class ArticleFixtures extends AbstractFixture implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected static array $data = [
        [
            ArticleApiDtoInterface::TITLE => 'ite',
            ArticleApiDtoInterface::BODY => 'http://ite',
            ArticleApiDtoInterface::POSITION => 1,
            ArticleApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2008-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ArticleApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
        ],
        [
            ArticleApiDtoInterface::TITLE => 'kzkt',
            ArticleApiDtoInterface::BODY => 'http://kzkt',
            ArticleApiDtoInterface::POSITION => 2,
            ArticleApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2015-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
        ],
        [
            ArticleApiDtoInterface::TITLE => 'c2m',
            ArticleApiDtoInterface::BODY => 'http://c2m',
            ArticleApiDtoInterface::POSITION => 3,
            ArticleApiDtoInterface::ACTIVE => 'a',
            'created_at' => '2020-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ArticleApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
        ],
        [
            ArticleApiDtoInterface::TITLE => 'kzkt2',
            ArticleApiDtoInterface::BODY => 'http://kzkt2',
            ArticleApiDtoInterface::POSITION => 1,
            ArticleApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2015-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ],
        [
            ArticleApiDtoInterface::TITLE => 'nvr',
            ArticleApiDtoInterface::BODY => 'http://nvr',
            ArticleApiDtoInterface::POSITION => 2,
            ArticleApiDtoInterface::ACTIVE => 'b',
            'created_at' => '2010-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ArticleApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
        ],
        [
            ArticleApiDtoInterface::TITLE => 'nvr2',
            ArticleApiDtoInterface::BODY => 'http://nvr2',
            ArticleApiDtoInterface::POSITION => 3,
            ArticleApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2010-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ],
        [
            ArticleApiDtoInterface::TITLE => 'nvr3',
            ArticleApiDtoInterface::BODY => 'http://nvr3',
            ArticleApiDtoInterface::POSITION => 1,
            ArticleApiDtoInterface::ACTIVE => 'd',
            'created_at' => '2011-10-23 10:21:50',
            ArticleApiDtoInterface::IMAGE => 'PATH://TO_IMAGE',
            ArticleApiDtoInterface::PREVIEW => 'PATH://TO_IMAGE_PREV',
            ArticleApiDtoInterface::ATTACHMENT => 'PATH://TO_IMAGE_ATTACHMENT',
        ],
    ];

    protected static string $class = BaseArticle::class;

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

        foreach (static::$data as $record) {
            $entity = new static::$class();
            $entity
                ->setPreview($record[ArticleApiDtoInterface::PREVIEW])
                ->setActive($record[ArticleApiDtoInterface::ACTIVE])
                ->setTitle($record[ArticleApiDtoInterface::TITLE])
                ->setBody($record[ArticleApiDtoInterface::BODY])
                ->setPosition($record[ArticleApiDtoInterface::POSITION])
                ->setCreatedAt(new \DateTimeImmutable($record['created_at']))
                ->setImage($record[ArticleApiDtoInterface::IMAGE]);

            if (\array_key_exists(ArticleApiDtoInterface::ATTACHMENT, $record)) {
                $entity
                    ->setAttachment($record[ArticleApiDtoInterface::ATTACHMENT]);
            }

            $this->addReference($short.$i, $entity);
            $manager->persist($entity);
            ++$i;
        }

        return $this;
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::ARTICLE_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 100;
    }
}
