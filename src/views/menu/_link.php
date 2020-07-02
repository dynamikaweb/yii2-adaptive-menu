<?php 

use yii\helpers\Html;

?>

<!-- MENU LINK <?=Yii::$app->session->get('__menu_count', null)?>-->
<li><?=Html::a($menu->nome, $menu->link, ['title' => $menu->nome])?></li>