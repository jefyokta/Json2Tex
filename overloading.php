<?php

class A
{
    static $contract;
    public function a($param)
    {
        if ($param instanceof static::$contract) {
            echo 'ok';
            return;
        }
        throw new Error("must be instance of " . static::$contract);
    }

    static function setContract($contract)
    {

        self::$contract = $contract;
    }
};

interface myInterface
{
    public function get();
}
class b implements myInterface
{
    public function get() {}
}

A::setContract(myInterface::class);



(new A)->a(new b);
