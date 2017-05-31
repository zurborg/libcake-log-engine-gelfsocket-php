Cake\Log\Engine\GelfSocketLog
===============

Class GelfSocketLog




* Class name: GelfSocketLog
* Namespace: Cake\Log\Engine
* Parent class: Cake\Log\Engine\BaseLog







Methods
-------


### __construct

    mixed Cake\Log\Engine\GelfSocketLog::__construct(array<mixed,mixed> $config)

Constructor



* Visibility: **public**


#### Arguments
* $config **array&lt;mixed,mixed&gt;**



### getGelfSocketEngine

    \Log\GelfSocket Cake\Log\Engine\GelfSocketLog::getGelfSocketEngine()

Return an instance of GelfSocket



* Visibility: **public**




### beginLogStack

    \Cake\Log\Engine\GelfSocketLog Cake\Log\Engine\GelfSocketLog::beginLogStack()

Stash all further log statements



* Visibility: **public**




### discardLogStack

    \Cake\Log\Engine\GelfSocketLog Cake\Log\Engine\GelfSocketLog::discardLogStack()

Discard all stashed log statements



* Visibility: **public**




### commitLogStack

    boolean Cake\Log\Engine\GelfSocketLog::commitLogStack()

Commit all stashed log statements



* Visibility: **public**




### log

    boolean Cake\Log\Engine\GelfSocketLog::log(string $level, string $message, array $context)

Logs a message through GelfSocket



* Visibility: **public**


#### Arguments
* $level **string** - &lt;p&gt;The severity level of log you are making.&lt;/p&gt;
* $message **string** - &lt;p&gt;The message you want to log.&lt;/p&gt;
* $context **array** - &lt;p&gt;Additional information about the logged message&lt;/p&gt;



### logThrowable

    boolean Cake\Log\Engine\GelfSocketLog::logThrowable($level, \Throwable $exception, array $context)

Log a thrown object (like an exception)



* Visibility: **public**


#### Arguments
* $level **mixed**
* $exception **Throwable**
* $context **array**


