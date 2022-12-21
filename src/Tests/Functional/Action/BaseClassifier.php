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

use Evrinoma\ArticleBundle\Dto\ClassifierApiDto;
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Tests\Functional\Helper\BaseClassifierTestTrait;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Classifier\Active;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Classifier\Brief;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Classifier\Description;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Classifier\Id;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseClassifier extends AbstractServiceTest implements BaseClassifierTestInterface
{
    use BaseClassifierTestTrait;

    public const API_GET = 'evrinoma/api/article/classifier';
    public const API_CRITERIA = 'evrinoma/api/article/classifier/criteria';
    public const API_DELETE = 'evrinoma/api/article/classifier/delete';
    public const API_PUT = 'evrinoma/api/article/classifier/save';
    public const API_POST = 'evrinoma/api/article/classifier/create';

    protected static function getDtoClass(): string
    {
        return ClassifierApiDto::class;
    }

    protected static function defaultData(): array
    {
        return [
            ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ClassifierApiDtoInterface::ID => Id::value(),
            ClassifierApiDtoInterface::BRIEF => Brief::default(),
            ClassifierApiDtoInterface::DESCRIPTION => Description::value(),
            ClassifierApiDtoInterface::ACTIVE => Active::value(),
        ];
    }

    public function actionPost(): void
    {
        $this->createClassifier();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(), ClassifierApiDtoInterface::ACTIVE => Active::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(), ClassifierApiDtoInterface::ID => Id::value(), ClassifierApiDtoInterface::ACTIVE => Active::block(), ClassifierApiDtoInterface::DESCRIPTION => Description::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(), ClassifierApiDtoInterface::ACTIVE => Active::value(), ClassifierApiDtoInterface::ID => Id::value()]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(), ClassifierApiDtoInterface::ACTIVE => Active::delete()]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([ClassifierApiDtoInterface::DTO_CLASS => static::getDtoClass(), ClassifierApiDtoInterface::ACTIVE => Active::delete(), ClassifierApiDtoInterface::DESCRIPTION => Description::default()]);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $find = $this->assertGet(Id::value());

        $updated = $this->put(static::getDefault([ClassifierApiDtoInterface::ID => Id::value(), ClassifierApiDtoInterface::DESCRIPTION => Description::value(), ClassifierApiDtoInterface::BRIEF => Brief::value()]));
        $this->testResponseStatusOK();

        Assert::assertEquals($find[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ID], $updated[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ID]);
        Assert::assertEquals(Description::value(), $updated[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::DESCRIPTION]);
        Assert::assertEquals(Brief::value(), $updated[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::BRIEF]);
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
            ClassifierApiDtoInterface::ID => Id::wrong(),
            ClassifierApiDtoInterface::DESCRIPTION => Description::wrong(),
            ClassifierApiDtoInterface::BRIEF => Brief::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createClassifier();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([ClassifierApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ID], ClassifierApiDtoInterface::DESCRIPTION => Description::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([ClassifierApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ID], ClassifierApiDtoInterface::BRIEF => Brief::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $created = $this->createClassifier();
        $this->testResponseStatusCreated();

        $query = static::getDefault([ClassifierApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ClassifierApiDtoInterface::ID], ClassifierApiDtoInterface::BRIEF => Brief::value()]);

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
