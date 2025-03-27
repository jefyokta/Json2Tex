<?php
namespace Jefyokta\Json2Tex;

/**
 * @method static JsonToTex setContent(array $contents)
 * @method static string getHtml(?array $contents = null)
 * @method static string getLatex(?array $contents = null)
 * @method static string compile(string $json)
 * 
 * @method JsonToTex setContent(array $contents)
 * @method string getHtml(?array $contents = null)
 * @method string getLatex(?array $contents = null)
 * @method string compile(string $json)
 */
class Converter {
    private static ?JsonToTex $instance = null;

    private static function getInstance(): JsonToTex {
        if (!self::$instance) {
            self::$instance = new JsonToTex();
        }
        return self::$instance;
    }

    public function __call($name, $arguments) {
        return self::getInstance()->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments) {
        return self::getInstance()->{$name}(...$arguments);
    }
}
