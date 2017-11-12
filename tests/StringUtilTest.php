<?php

/**
 * Copyright 2016, AJ Michels
 *
 * This file is part of Note-Script.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class StringUtilTest extends TestCase
{
    /**
     * @test
     * @dataProvider simplifyData
     */
    public function simplify($input, $expectedResult)
    {
        $stringUtil = new StringUtil();
        $result = $stringUtil->simplify($input);
        $this->assertEquals($expectedResult, $result);
    }

    public function simplifyData()
    {
        return [
            ["test", "test"],
            ["Test", "test"],
            ["Foo Bar", "foo-bar"],
            ["Foo Bar %$#@", "foo-bar"],
            ["Foo      Bar", "foo-bar"],
            [
                "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Illa videamus, " .
                "quae a te de amicitia dicta sunt. Non enim iam stirpis bonum quaeret, sed " .
                "animalis. Claudii libidini, qui tum erat summo ne imperio, dederetur. " .
                "Recte, inquit, intellegis. Duo Reges: constructio interrete.",
                "lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-illa-videamus-quae-" .
                "a-te-de-amicitia-dicta-sunt-non-enim-iam-stirpis-bonum-quaeret-sed-animalis-" .
                "claudii-libidini-qui-tum-erat-summo-ne-imperio-dederetur-recte-inquit-" .
                "intellegis-duo"
            ],
        ];
    }
}
