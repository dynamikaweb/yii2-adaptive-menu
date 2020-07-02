<?php 

namespace dynamikaweb\adpative;

class MenuIcon
{
    public static function show($name, ...$arguments)
    {
        if (class_exists('\kartik\icons\Icon')){
            switch (count($arguments))
            {
                case 1: \kartik\icons\Icon::show($name, $arguments[0]); break;
                case 2: \kartik\icons\Icon::show($name, $arguments[0], $arguments[1]); break;
                case 3: \kartik\icons\Icon::show($name, $arguments[0], $arguments[1], $arguments[2]); break;
                case 4: \kartik\icons\Icon::show($name, $arguments[0], $arguments[1], $arguments[2], $arguments[3]); break;
                case 5: \kartik\icons\Icon::show($name, $arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]); break;
                case 6: \kartik\icons\Icon::show($name, $arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]); break;
                default: \kartik\icons\Icon::show($name); break;
            }
        }

        return null;
    }
}