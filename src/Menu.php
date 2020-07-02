<?php 

namespace dynamikaweb\adaptive;

use yii\helpers\Html;

class Menu extends \yii\base\Widget
{

    /**
     * @var string the widget container element
     * Defaults to div
     */
    public $container;

    /**
     * @var array the HTML attributes for the widget container
     * Defaults to an auto generated id and class => "owl-carousel"
     */
    public $containerOptions = [];


    /**
     * Initializes the widget.
     *
     */
    public function init()
    {
        parent::init();
        if (!isset($this->container)):
            $this->container = 'div';
        endif;
        if (!is_array($this->containerOptions)):
            $this->containerOptions = ['class' => 'container'];
        endif;

        ob_start();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        MenuAsset::register($this->view);
        $content = ob_get_clean();

        if (empty($content)) {
            return null;
        }

        return $this->view->render('@vendor/dynamikaweb/yii2-adaptive-menu/src/views/_menuBase', [
            'containerOptions' => $this->containerOptions,
            'container' => $this->container,
            'content' => $content,
        ]);        
    }
}