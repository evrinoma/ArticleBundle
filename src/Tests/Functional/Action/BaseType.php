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

namespace Evrinoma\ArticleBundle\Tests\Functional\Action;

use Evrinoma\ArticleBundle\Dto\TypeApiDto;
use Evrinoma\ArticleBundle\Dto\TypeApiDtoInterface;
use Evrinoma\ArticleBundle\Tests\Functional\Helper\BaseTypeTestTrait;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Type\Active;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Type\Brief;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Type\Description;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Type\Id;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseType extends AbstractServiceTest implements BaseTypeTestInterface
{
    use BaseTypeTestTrait;

    public const API_GET = 'evrinoma/api/article/type';
    public const API_CRITERIA = 'evrinoma/api/article/type/criteria';
    public const API_DELETE = 'evrinoma/api/article/type/delete';
    public const API_PUT = 'evrinoma/api/article/type/save';
    public const API_POST = 'evrinoma/api/article/type/create';

    protected static function getDtoClass(): string
    {
        return TypeApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
            TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            TypeApiDtoInterface::ID => Id::value(),
            TypeApiDtoInterface::BRIEF => Brief::default(),
            TypeApiDtoInterface::DESCRIPTION => Description::value(),
            TypeApiDtoInterface::ACTIVE => Active::value(),
        ];
    }

    public function actionPost(): void
    {
        $this->createType();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(), TypeApiDtoInterface::ACTIVE => Active::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(), TypeApiDtoInterface::ID => Id::value(), TypeApiDtoInterface::ACTIVE => Active::block(), TypeApiDtoInterface::DESCRIPTION => Description::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(), TypeApiDtoInterface::ACTIVE => Active::value(), TypeApiDtoInterface::ID => Id::value()]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(), TypeApiDtoInterface::ACTIVE => Active::delete()]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([TypeApiDtoInterface::DTO_CLASS => static::getDtoClass(), TypeApiDtoInterface::ACTIVE => Active::delete(), TypeApiDtoInterface::DESCRIPTION => Description::default()]);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $find = $this->assertGet(Id::value());

        $updated = $this->put(static::getDefault([TypeApiDtoInterface::ID => Id::value(), TypeApiDtoInterface::DESCRIPTION => Description::value(), TypeApiDtoInterface::BRIEF => Brief::value()]));
        $this->testResponseStatusOK();

        Assert::assertEquals($find[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ID], $updated[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ID]);
        Assert::assertEquals(Description::value(), $updated[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::DESCRIPTION]);
        Assert::assertEquals(Brief::value(), $updated[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::BRIEF]);
    }

    public function actionGet(): void
    {
        $find = $this->assertGet(Id::value());
    }

    public function actionGetNotFound(): void
    {
        $response = $this->get(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteNotFound(): void
    {
        $response = $this->delete(Id::wrong());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusNotFound();
    }

    public function actionDeleteUnprocessable(): void
    {
        $response = $this->delete(Id::empty());
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPutNotFound(): void
    {
        $this->put(static::getDefault([
            TypeApiDtoInterface::ID => Id::wrong(),
            TypeApiDtoInterface::DESCRIPTION => Description::wrong(),
            TypeApiDtoInterface::BRIEF => Brief::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createType();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([TypeApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ID], TypeApiDtoInterface::DESCRIPTION => Description::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([TypeApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ID], TypeApiDtoInterface::BRIEF => Brief::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $created = $this->createType();
        $this->testResponseStatusCreated();

        $query = static::getDefault([TypeApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][TypeApiDtoInterface::ID], TypeApiDtoInterface::BRIEF => Brief::value()]);

        $this->put($query);
        $this->testResponseStatusConflict();
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankBrief();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankDescription();
        $this->testResponseStatusUnprocessable();
    }
}
