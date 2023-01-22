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

namespace Evrinoma\ArticleBundle\DtoCommon\ValueObject\Immutable;

use Evrinoma\ArticleBundle\Dto\ClassifierApiDto;
use Evrinoma\ArticleBundle\Dto\ClassifierApiDtoInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Symfony\Component\HttpFoundation\Request;

trait ClassifierApiDtoTrait
{
    protected ?ClassifierApiDtoInterface $classifierApiDto = null;

    protected static string $classClassifierApiDto = ClassifierApiDto::class;

    public function genRequestClassifierApiDto(?Request $request): ?\Generator
    {
        if ($request) {
            $classifier = $request->get(ClassifierApiDtoInterface::CLASSIFIER);
            if ($classifier) {
                $newRequest = $this->getCloneRequest();
                $classifier[DtoInterface::DTO_CLASS] = static::$classClassifierApiDto;
                $newRequest->request->add($classifier);

                yield $newRequest;
            }
        }
    }

    public function hasClassifierApiDto(): bool
    {
        return null !== $this->classifierApiDto;
    }

    public function getClassifierApiDto(): ClassifierApiDtoInterface
    {
        return $this->classifierApiDto;
    }
}
