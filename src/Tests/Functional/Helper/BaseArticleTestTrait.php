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

namespace Evrinoma\ArticleBundle\Tests\Functional\Helper;

use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait BaseArticleTestTrait
{
    protected static function initFiles(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'http');

        file_put_contents($path, 'my_file');

        $fileImage = $fileAttachment = $filePreview = new UploadedFile($path, 'my_file');

        static::$files = [
            ArticleApiDtoInterface::IMAGE => $fileImage,
            ArticleApiDtoInterface::PREVIEW => $filePreview,
            ArticleApiDtoInterface::ATTACHMENT => $fileAttachment,
        ];
    }

    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createArticle(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankName(): array
    {
        $query = static::getDefault([ArticleApiDtoInterface::TITLE => '']);

        return $this->post($query);
    }

    protected function createConstraintBlankUrl(): array
    {
        $query = static::getDefault([ArticleApiDtoInterface::BODY => '']);

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkArticle($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkArticle($entity): void
    {
        Assert::assertArrayHasKey(ArticleApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(ArticleApiDtoInterface::TITLE, $entity);
        Assert::assertArrayHasKey(ArticleApiDtoInterface::BODY, $entity);
        Assert::assertArrayHasKey(ArticleApiDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(ArticleApiDtoInterface::PREVIEW, $entity);
        Assert::assertArrayHasKey(ArticleApiDtoInterface::POSITION, $entity);
    }
}
