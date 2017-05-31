<?php

/**
 * GelfSocket controller component
 * @copyright 2017 David Zurborg <zurborg@cpan.org>
 * @license https://opensource.org/licenses/ISC The ISC License
 */

namespace Cake\Controller\Component;

use Cake\Controller\Component;
use Cake\Log\Log;
use Cake\Log\Engine\GelfSocketLog;
use Pirate\Hooray\Arr;
use Pirate\Hooray\Str;
use InvalidArgumentException;

/**
 * Class GelfSocketComponent
 * @package Cake\Controller\Component
 */
final class GelfSocketComponent extends Component
{
    /**
     * @var \Cake\Log\Engine\GelfSocketLog
     */
    private $engine;
    
    /**
     * @var \Log\GelfSocket
     */
    private $logger;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        Arr::assert($config, 'name', 'Need configuration name for GelfSocket engine');
        parent::initialize($config);
        $name = $this->getConfig('name');
        $engine = Log::engine($name);
        if ($engine instanceof GelfSocketLog) {
            $this->engine = $engine;
            $this->logger = $engine->getGelfSocketEngine();
        }
    }

    /**
     * Return an instance of GelfSocketLog (or null)
     *
     * @return \Cake\Log\Engine\GelfSocketLog|null
     */
    public function engine()
    {
        return $this->engine;
    }

    /**
     * Return an instance of GelfSocket (or null)
     *
     * @return \Log\GelfSocket|null
     */
    public function logger()
    {
        return $this->logger;
    }

    /**
     * Set a extra log parameter for all following log statements
     *
     * @see \Log\GelfSocket::setDefault()
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set(string $key, $value)
    {
        if (!is_null($this->logger)) {
            $this->logger->setDefault($key, $value);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Defer flushing log messages to the end of script execution
     *
     * @see \Log\GelfSocket::defer()
     * @return bool
     */
    public function defer()
    {
        if (!is_null($this->logger)) {
            $this->logger->defer();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Begin a LogStack stash
     *
     * @see \Cake\Log\Engine\GelfSocketLog::beginLogStack()
     * @return bool
     */
    public function begin()
    {
        if (!is_null($this->engine)) {
            $this->engine->beginLogStack();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Commit all stashed log statements
     *
     * @see \Cake\Log\Engine\GelfSocketLog::commitLogStack()
     * @return bool
     */
    public function commit()
    {
        if (!is_null($this->engine)) {
            $this->engine->commitLogStack();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Discard all stashed log statements
     *
     * @see \Cake\Log\Engine\GelfSocketLog::discardLogStack()
     * @return bool
     */
    public function discard()
    {
        if (!is_null($this->engine)) {
            $this->engine->discardLogStack();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Log a thrown object (like an exception)
     *
     * @param $level
     * @param \Throwable $exception
     * @param array $context
     * @see \Cake\Log\Engine\GelfSocketLog::logThrowable()
     * @return bool
     */
    public function logThrowable($level, \Throwable $exception, array $context = [])
    {
        if (!is_null($this->engine)) {
            $this->engine->logThrowable($level, $exception, $context);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Flush all pending log statements
     *
     * @param bool $quiet
     * @see \Log\GelfSocket::flush()
     * @return bool
     */
    public function flush(bool $quiet = false)
    {
        if (!is_null($this->logger)) {
            $this->logger->flush($quiet);
            return true;
        } else {
            return false;
        }
    }
}
