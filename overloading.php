<?php

class ClassA
{
    public function foo($arg1)
    {
        return "ClassA: foo dengan $arg1";
    }

    public function bar($arg1, $arg2)
    {
        return "ClassA: bar dengan $arg1 dan $arg2";
    }
}

class ClassB
{
    public function foo($arg1, $arg2)
    {
        return "ClassB: foo dengan $arg1 dan $arg2";
    }

    public function bar($arg1)
    {
        return "ClassB: bar dengan $arg1";
    }
}
/** 
 * @method string foo(string $arg1)
 * @method string foo(string $arg1, string $arg2)
 * @method string bar(string $arg1)
 * @method string bar(string $arg1, string $arg2)
 */
class OverLoad
{
    private array $instances = [];

    public function __construct()
    {
        $this->instances[] = new ClassA();
        $this->instances[] = new ClassB();
    }

    public function __call($name, $arguments)
    {
        $argCount = count($arguments);

        foreach ($this->instances as $instance) {
            if (method_exists($instance, $name)) {
                $method = new ReflectionMethod($instance, $name);
                if ($method->getNumberOfParameters() === $argCount) {
                    return $method->invoke($instance, ...$arguments);
                }
            }
        }

        throw new Exception("method '$name' dengan $argCount parameter.");
    }
}


$handler = new OverLoad();

echo $handler->foo("Hello") . PHP_EOL;
echo $handler->foo("A", "B") . PHP_EOL;
echo $handler->bar("Test") . PHP_EOL;
echo $handler->bar("X", "Y") . PHP_EOL;
