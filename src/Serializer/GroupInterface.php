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

namespace Evrinoma\ArticleBundle\Serializer;

interface GroupInterface
{
    public const API_POST_ARTICLE = 'API_POST_ARTICLE';
    public const API_PUT_ARTICLE = 'API_PUT_ARTICLE';
    public const API_GET_ARTICLE = 'API_GET_ARTICLE';
    public const API_CRITERIA_ARTICLE = self::API_GET_ARTICLE;
    public const APP_GET_BASIC_ARTICLE = 'APP_GET_BASIC_ARTICLE';

    public const API_POST_ARTICLE_TYPE = 'API_POST_ARTICLE_TYPE';
    public const API_PUT_ARTICLE_TYPE = 'API_PUT_ARTICLE_TYPE';
    public const API_GET_ARTICLE_TYPE = 'API_GET_ARTICLE_TYPE';
    public const API_CRITERIA_ARTICLE_TYPE = self::API_GET_ARTICLE_TYPE;
    public const APP_GET_BASIC_ARTICLE_TYPE = 'APP_GET_BASIC_ARTICLE_TYPE';
}
