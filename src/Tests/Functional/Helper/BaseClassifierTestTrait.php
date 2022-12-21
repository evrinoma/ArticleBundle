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

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait BaseClassifierTestTrait
{
    protected function assertGet(string $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createClassifier(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankBrief(): array
    {
        $query = static::getDefault([ClassifierApiDtoInterface::DESCRIPTION => '']);

        return $this->post($query);
    }

    protected function createConstraintBlankDescription(): array
    {
        $query = static::getDefault([ClassifierApiDtoInterface::BRIEF => '']);

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        Assert::assertCount(1, $entity[PayloadModel::PAYLOAD]);
        $this->checkArticleClassifier($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function checkArticleClassifier($entity): void
    {
        Assert::assertArrayHasKey(ClassifierApiDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(ClassifierApiDtoInterface::DESCRIPTION, $entity);
        Assert::assertArrayHasKey(ClassifierApiDtoInterface::BRIEF, $entity);
        Assert::assertArrayHasKey(ClassifierApiDtoInterface::ACTIVE, $entity);
    }
}
