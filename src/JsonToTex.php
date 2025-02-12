<?php 
namespace Jefyokta\Json2Tex;


class JsonToTex {

    private array|object $json;

    private string $result;

    public function __construct(string $json) {

        $this->json = json_decode($json);

    }

    public function save($file){
        file_put_contents($file,$this->json);
        return $this;
    }

    public function compile(){

     return   self::getContent($this->json->content);

    }

    public static function getContent($contents) {
        $result = '';
        $converter = new Converter();
        foreach ($contents as $content) {
            if (method_exists($converter, $content->type)) {
                $result .= call_user_func([$converter, $content->type], $content);
            }
        }
        return $result;
    }
    

}; 