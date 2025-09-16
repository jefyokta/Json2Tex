<?php 
namespace Jefyokta\Json2Tex\Interface;
interface Customable {
    public  static function custom($name,$callback);
    public static function getCustom($name);

}; ?>