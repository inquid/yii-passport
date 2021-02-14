<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Inquid
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/inquid/yii-passport
 */

namespace Inquid\YiiPassport\Test\Unit;

use Ergebnis\Test\Util;
use Inquid\YiiPassport\Example;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Inquid\YiiPassport\Example
 */
final class ExampleTest extends Framework\TestCase
{
    use Util\Helper;

    public function testFromNameReturnsExample(): void
    {
        $name = self::faker()->sentence;

        $example = Example::fromName($name);

        self::assertSame($name, $example->name());
    }
}
