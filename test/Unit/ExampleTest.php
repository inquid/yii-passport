<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Inquid
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/inquid/php-library-template
 */

namespace Inquid\Library\Test\Unit;

use Ergebnis\Test\Util;
use Inquid\Library\Example;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Inquid\Library\Example
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
