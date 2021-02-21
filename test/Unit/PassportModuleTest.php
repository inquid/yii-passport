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

use Inquid\YiiPassport\passport;
use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Inquid\YiiPassport\
 */
final class PassportModuleTest extends Framework\TestCase
{
    use Util\Helper;

    public function testModuleExist(): void
    {
        $this->assertTrue(true);
    }
}
