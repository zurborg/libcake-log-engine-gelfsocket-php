<?php

namespace TestNamespace;

use PHPUnit\Framework\TestCase;

use Cake\Core\Configure;
use Cake\Log\Log;
use Pirate\Hooray\Arr;
use Wrap\JSON;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\GelfSocketComponent;

Configure::write('App.name', 'GelfSocketTest');

class GelfSocketLoggerTest extends TestCase
{
    public $sockfile = 'test.sock';
    public $listener;

    public function setUp()
    {
        $this->listener = socket_create(AF_UNIX, SOCK_DGRAM, 0);
        socket_bind($this->listener, $this->sockfile);
    }

    private function readMsg()
    {
        $msg = '';
        socket_recv($this->listener, $msg, 65536, MSG_DONTWAIT);
        if (is_null($msg)) {
            return null;
        }
        return JSON::decodeArray($msg);
    }

    private function getLogger()
    {
        $this->assertSame(null, $this->readMsg());
        $logger = new \Log\GelfSocket($this->sockfile);
        $this->assertSame(null, $this->readMsg());
        return $logger;
    }

    public function test000()
    {
        Log::setConfig('testlogger', [
            'className' => 'GelfSocket',
            'sockfile'  => $this->sockfile,
        ]);
    }

    public function test001()
    {
        Log::info('foobar');

        $gelf = $this->readMsg();

        $this->assertSame('foobar', Arr::get($gelf, 'message'));
        $this->assertSame('GelfSocketTest', Arr::get($gelf, '_cake_application'));
        $this->assertSame(7, Arr::get($gelf, 'level'));
    }

    public function test002()
    {
        Log::info('foobar', [ 'foo' => 'bar' ]);

        $gelf = $this->readMsg();
        $this->assertSame('bar', Arr::get($gelf, '_foo'));
    }

    public function test003()
    {
        $R = new ComponentRegistry();
        $C = new GelfSocketComponent($R, ['name' => 'testlogger']);
        $C->set('foo', 'bar');
        Log::info('foobar');
        $gelf = $this->readMsg();
        $this->assertSame('bar', Arr::get($gelf, '_foo'));
    }

    public function tearDown()
    {
        Log::engine('testlogger')->getGelfSocketEngine()->close();
        socket_close($this->listener);
        unlink($this->sockfile);
    }
}
