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

namespace Evrinoma\ArticleBundle\Tests\Functional\ValueObject\Type;

use Evrinoma\TestUtilsBundle\ValueObject\Common\AbstractIdentity;

class Brief extends AbstractIdentity
{
    protected static string $value = 'ite';
    protected static string $default = 'kzkt3';
}
