<?php

namespace dynamikaweb\adaptive;

class MenuAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/dynamikaweb/yii2-adaptive-menu/assets';

    public $files = [
        'forest' => 'menu-forest.html',
        'link' => 'menu-link.html',
        'root' => 'menu-root.html',
        'sub' => 'menu-sub.html'
    ];
    
    public $css = [
        'style.css'
    ];
    
    public $js = [
        'script.js',
    ];

    /**
     * return file exactly patch
     *
     * @param string $view
     * @return string
     */
    public function getFile($view) 
    {
        return "{$this->sourcePath}/{$this->files[$view]}"; 
    }

    /**
     * Concat asset file modified times
     *
     * @return string
     */
    public function getTouchTimes()
    {
        return implode('-', array_map(function($file) {
            return filemtime($this->getFile($file));
        },
            array_keys($this->files)
        ));
    }
}