<?php

/**
 * GelfSocket log engine for CakePHP
 * @copyright 2017 David Zurborg <zurborg@cpan.org>
 * @license https://opensource.org/licenses/ISC The ISC License
 */

namespace Cake\Log\Engine;

use Log\GelfSocket;
use Pirate\Hooray\Arr;
use Cake\Log\Engine\BaseLog;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Class GelfSocketLog
 * @package Cake\Log\Engine
 */
class GelfSocketLog extends BaseLog
{

    /**
     * Instance of \Log\GelfSocket
     *
     * @var \Log\GelfSocket
     */
    protected $engine;

    /**
     * @var array[]
     */
    protected $stack;

    /**
     * Constructor
     *
     * @param mixed[] $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $config = $this->_config;

        Arr::assert($config, 'sockfile', 'sockfile not specified');

        $this->engine = new GelfSocket(Arr::get($config, 'sockfile'));

        if (Arr::is($config, 'defer', true)) {
            $this->engine->defer();
        }
    }

    /**
     * Return an instance of GelfSocket
     *
     * @return GelfSocket
     */
    public function getGelfSocketEngine()
    {
        return $this->engine;
    }

    /**
     * Stash all further log statements
     *
     * @return self
     */
    public function beginLogStack()
    {
        if (!is_null($this->stack)) {
            $this->stack = [];
        }
        return $this;
    }

    /**
     * Discard all stashed log statements
     *
     * @return self
     */
    public function discardLogStack()
    {
        $this->stack = null;
        return $this;
    }

    /**
     * Commit all stashed log statements
     *
     * @return bool
     */
    public function commitLogStack()
    {
        if (is_null($this->stack)) {
            return false;
        }
        foreach ($this->stack as $entry) {
            list($level, $message, $context) = $entry;
            if ($message instanceof \Throwable) {
                $this->engine->logThrowable($level, $message, $context);
            } else {
                $this->engine->log($level, $message, $context);
            }
        }
        $this->stack = null;
        return true;
    }

    /**
     * @param array $context
     */
    protected function _prepareContext(array &$context)
    {
        $context['cake_application'] = Configure::read('App.name');

        $request = Router::getRequest();
        if (!is_null($request)) {
            $session = $request->session();
            if (!is_null($session)) {
                $context['cake_session'] = $session->id();
            }

            foreach (['plugin', 'controller', 'action', 'prefix', 'bare'] as $key) {
                $value = $request->getParam($key, null);
                if (!is_null($value)) {
                    $context["cake_$key"] = $value;
                }
            }
        }
    }

    /**
     * Logs a message through GelfSocket
     *
     * @see GelfSocket::log()
     * @param string $level The severity level of log you are making.
     * @param string $message The message you want to log.
     * @param array $context Additional information about the logged message
     * @return bool success of write.
     */
    public function log($level, $message, array $context = [])
    {
        $message = $this->_format($message, $context);

        foreach ($context as $key => $val) {
            if ($key === 'scope') {
                unset($context['scope']);
                if (count($val) === 1) {
                    $context['scope'] = $val[0];
                } elseif (count($val) > 1) {
                    $context['scopes'] = $val;
                }
            }
        }

        $this->_prepareContext($context);

        if (is_array($this->stack)) {
            $this->stack[] = [$level, $message, $context];
            return false;
        } else {
            $this->engine->log($level, $message, $context);
            return true;
        }
    }

    /**
     * Log a thrown object (like an exception)
     *
     * @see GelfSocket::logThrowable()
     * @param $level
     * @param \Throwable $exception
     * @param array $context
     * @return bool success of write.
     */
    public function logThrowable($level, \Throwable $exception, array $context = [])
    {
        $this->_prepareContext($context);

        if (is_array($this->stack)) {
            $this->stack[] = [$level, $exception, $context];
            return false;
        } else {
            $this->engine->logThrowable($level, $exception, $context);
            return true;
        }
    }
}
