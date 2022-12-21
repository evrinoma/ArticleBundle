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

namespace Evrinoma\ArticleBundle\PreValidator\Classifier;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\ArticleBundle\Exception\Classifier\ClassifierInvalidException;

interface DtoPreValidatorInterface
{
    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @throws ClassifierInvalidException
     */
    public function onPost(ClassifierApiDtoInterface $dto): void;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @throws ClassifierInvalidException
     */
    public function onPut(ClassifierApiDtoInterface $dto): void;

    /**
     * @param ClassifierApiDtoInterface $dto
     *
     * @throws ClassifierInvalidException
     */
    public function onDelete(ClassifierApiDtoInterface $dto): void;
}
