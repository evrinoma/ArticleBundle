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

use Evrinoma\ArticleBundle\Dto\ArticleApiDto;
use Evrinoma\ArticleBundle\Dto\ArticleApiDtoInterface;
use Evrinoma\ArticleBundle\Tests\Functional\Helper\BaseArticleTestTrait;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Article\Active;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Article\Body;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Article\Id;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Article\Position;
use Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Article\Title;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\TestUtilsBundle\Browser\ApiBrowserTestInterface;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use Evrinoma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

class BaseArticle extends AbstractServiceTest implements BaseArticleTestInterface
{
    use BaseArticleTestTrait;

    public const API_GET = 'evrinoma/api/article';
    public const API_CRITERIA = 'evrinoma/api/article/criteria';
    public const API_DELETE = 'evrinoma/api/article/delete';
    public const API_PUT = 'evrinoma/api/article/save';
    public const API_POST = 'evrinoma/api/article/create';

    protected string $methodPut = ApiBrowserTestInterface::POST;

    protected static array $header = ['CONTENT_TYPE' => 'multipart/form-data'];
    protected bool $form = true;

    protected static function getDtoClass(): string
    {
        return ArticleApiDto::class;
    }

    protected static function defaultData(): array
    {
        static::initFiles();

        return [
            ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            ArticleApiDtoInterface::ID => Id::default(),
            ArticleApiDtoInterface::TITLE => Title::default(),
            ArticleApiDtoInterface::POSITION => Position::value(),
            ArticleApiDtoInterface::ACTIVE => Active::value(),
            ArticleApiDtoInterface::BODY => Body::default(),
            ArticleApiDtoInterface::TYPE => BaseType::defaultData(),
            ArticleApiDtoInterface::CLASSIFIER => BaseClassifier::defaultData(),
        ];
    }

    public function actionPost(): void
    {
        $this->createArticle();
        $this->testResponseStatusCreated();
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(), ArticleApiDtoInterface::ACTIVE => Active::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);

        $find = $this->criteria([ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(), ArticleApiDtoInterface::ID => Id::value(), ArticleApiDtoInterface::ACTIVE => Active::block(), ArticleApiDtoInterface::TITLE => Title::wrong()]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $find);
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(), ArticleApiDtoInterface::ACTIVE => Active::value(), ArticleApiDtoInterface::ID => Id::value()]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(), ArticleApiDtoInterface::ACTIVE => Active::delete()]);
        $this->testResponseStatusOK();
        Assert::assertCount(3, $find[PayloadModel::PAYLOAD]);

        $find = $this->criteria([ArticleApiDtoInterface::DTO_CLASS => static::getDtoClass(), ArticleApiDtoInterface::ACTIVE => Active::delete(), ArticleApiDtoInterface::TITLE => Title::value()]);
        $this->testResponseStatusOK();
        Assert::assertCount(2, $find[PayloadModel::PAYLOAD]);
    }

    public function actionDelete(): void
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ACTIVE]);

        $this->delete(Id::value());
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::DELETED, $delete[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ACTIVE]);
    }

    public function actionPut(): void
    {
        $find = $this->assertGet(Id::value());

        $updated = $this->put(static::getDefault([ArticleApiDtoInterface::ID => Id::value(), ArticleApiDtoInterface::TITLE => Title::value(), ArticleApiDtoInterface::BODY => Body::value(), ArticleApiDtoInterface::POSITION => Position::value()]));
        $this->testResponseStatusOK();

        Assert::assertEquals($find[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID], $updated[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID]);
        Assert::assertEquals(Title::value(), $updated[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::TITLE]);
        Assert::assertEquals(Body::value(), $updated[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::BODY]);
        Assert::assertEquals(Position::value(), $updated[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::POSITION]);
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
            ArticleApiDtoInterface::ID => Id::wrong(),
            ArticleApiDtoInterface::TITLE => Title::wrong(),
            ArticleApiDtoInterface::BODY => Body::wrong(),
            ArticleApiDtoInterface::POSITION => Position::wrong(),
        ]));
        $this->testResponseStatusNotFound();
    }

    public function actionPutUnprocessable(): void
    {
        $created = $this->createArticle();
        $this->testResponseStatusCreated();
        $this->checkResult($created);

        $query = static::getDefault([ArticleApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID], ArticleApiDtoInterface::TITLE => Title::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([ArticleApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID], ArticleApiDtoInterface::BODY => Body::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([ArticleApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID], ArticleApiDtoInterface::POSITION => Position::empty()]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        unset(static::$files[ArticleApiDtoInterface::IMAGE]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        unset(static::$files[ArticleApiDtoInterface::PREVIEW]);

        $this->put($query);
        $this->testResponseStatusUnprocessable();

        $query = static::getDefault([ArticleApiDtoInterface::ID => $created[PayloadModel::PAYLOAD][0][ArticleApiDtoInterface::ID]]);
        static::$files = [];

        $this->put($query);
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createArticle();
        $this->testResponseStatusCreated();
        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankTitle();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankBody();
        $this->testResponseStatusUnprocessable();
    }
}
