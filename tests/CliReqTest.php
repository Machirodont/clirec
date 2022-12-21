<?php

use Clirec\CliRequest;
use Clirec\CliRequestParser;
use PHPUnit\Framework\TestCase;

class CliReqTest extends TestCase
{
    public function testParse()
    {
        $parser = new CliRequestParser();
        $this->assertEquals(
            $parser->parse(['script.php', 'value', "--abc", '-abc', 'value2']),
            ['value', 'abc' => true, 'a' => true, 'b' => true, 'c' => true, 'value2']
        );

        $this->assertEquals(
            $parser->parse(['script.php', "-abc", '-de-f']),
            ['a' => true, 'b' => true, 'c' => true, 'd' => true, 'e' => true]
        );

        $this->assertEquals(
            $parser->parse(['script.php', "--abc-", "--def=", "--ghi=val",]),
            ['abc' => true, 'def' => '', 'ghi' => 'val']
        );
    }

    public function testRequest()
    {
        $_SERVER['argv'] = ['script.php', "--abc-", "--def=", "--ghi=val", 'dddd'];
        $req = CliRequest::getRequest();
        $this->assertEquals($req->get('abc'), true);
        $this->assertEquals($req->get('abcd'), null);
        $this->assertEquals($req->get('def'), '');
        $this->assertEquals($req->get('ghi'), 'val');
        $this->assertEquals($req->get(0), 'dddd');
        $this->assertEquals($req->get(1), null);
    }
}