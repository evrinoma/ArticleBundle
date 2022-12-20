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

namespace Evrinoma\ArticleBundle\Constraint\Complex\Constraint\Article;

use Evrinoma\ArticleBundle\Constraint\Complex\Validator\Article\PathOrUrlValidator;
use Evrinoma\UtilsBundle\Constraint\Complex\AbstractConstraint;

/**
 * @Annotation
 */
class PathOrUrl extends AbstractConstraint
{
    public const INVALID_PATH_OR_URL_ERROR = 'b2b438ae-9f8f-41b0-aa8b-84622fbb4c5c';

    public string $message = 'Should be set path to video file or url';

    public function validatedBy()
    {
        return PathOrUrlValidator::class;
    }
}
