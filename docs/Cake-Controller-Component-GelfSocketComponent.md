Cake\Controller\Component\GelfSocketComponent
===============

Class GelfSocketComponent




* Class name: GelfSocketComponent
* Namespace: Cake\Controller\Component
* Parent class: Cake\Controller\Component







Methods
-------


### initialize

    mixed Cake\Controller\Component\GelfSocketComponent::initialize(array $config)





* Visibility: **public**


#### Arguments
* $config **array**



### engine

    \Cake\Log\Engine\GelfSocketLog|null Cake\Controller\Component\GelfSocketComponent::engine()

Return an instance of GelfSocketLog (or null)



* Visibility: **public**




### logger

    \Log\GelfSocket|null Cake\Controller\Component\GelfSocketComponent::logger()

Return an instance of GelfSocket (or null)



* Visibility: **public**




### set

    boolean Cake\Controller\Component\GelfSocketComponent::set(string $key, mixed $value)

Set a extra log parameter for all following log statements



* Visibility: **public**


#### Arguments
* $key **string**
* $value **mixed**



### defer

    boolean Cake\Controller\Component\GelfSocketComponent::defer()

Defer flushing log messages to the end of script execution



* Visibility: **public**




### begin

    boolean Cake\Controller\Component\GelfSocketComponent::begin()

Begin a LogStack stash



* Visibility: **public**




### commit

    boolean Cake\Controller\Component\GelfSocketComponent::commit()

Commit all stashed log statements



* Visibility: **public**




### discard

    boolean Cake\Controller\Component\GelfSocketComponent::discard()

Discard all stashed log statements



* Visibility: **public**




### logThrowable

    boolean Cake\Controller\Component\GelfSocketComponent::logThrowable($level, \Throwable $exception, array $context)

Log a thrown object (like an exception)



* Visibility: **public**


#### Arguments
* $level **mixed**
* $exception **Throwable**
* $context **array**



### flush

    boolean Cake\Controller\Component\GelfSocketComponent::flush(boolean $quiet)

Flush all pending log statements



* Visibility: **public**


#### Arguments
* $quiet **boolean**


