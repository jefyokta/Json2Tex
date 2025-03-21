<?php

namespace Jefyokta\Json2Tex;

use Error;

class Math
{

    public static function render($latex)
    {
        $runtime = self::jsRuntimeCheck();
        if (!$runtime) {
            throw new Error("Javascript runtime not found, please install node or bun");
        }

        $result = exec("$runtime " . __DIR__ . "/dist/index.js" . " $latex", $o, $code);

        if ($code !== 0) {
            throw new Error("error while rendeting latex!");
        }

        return $result;
    }

    private static function jsRuntimeCheck()
    {
        exec("bun -v", $buno, $bun);
        if ($bun === 0) {
            return "bun";
        }
        exec("node -v", $nodeo, $node);
        if ($node === 0) {
            return "node";
        }
        return false;
    }
}
