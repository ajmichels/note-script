<?php

namespace NoteScript;

use PHPUnit_Framework_TestCase as TestCase;

class StringUtilTest extends TestCase
{

    /**
     * @dataProvider simplifyData
     */
    public function testSimplify($input, $expectedResult)
    {
        $result = StringUtil::simplify($input);

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
            ["Lorem ipsum dolor sit amet, consectetur adipiscing elit. Illa videamus, quae a te de amicitia dicta sunt. Non enim iam stirpis bonum quaeret, sed animalis. Claudii libidini, qui tum erat summo ne imperio, dederetur. Recte, inquit, intellegis. Duo Reges: constructio interrete.", "lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-illa-videamus-quae-a-te-de-amicitia-dicta-sunt-non-enim-iam-stirpis-bonum-quaeret-sed-animalis-claudii-libidini-qui-tum-erat-summo-ne-imperio-dederetur-recte-inquit-intellegis-duo"],
        ];
    }

}
