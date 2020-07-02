<?php 

use yii\helpers\Html;

?>
<li>
    <?=Html::a($menu->nome.'&nbsp'.Icon::show('arrow-alt-circle-down'), $menu->link, ['title' => $menu->nome])?>
    <ul>
        <?php foreach($subMenus as $subMenu):?>
            <?=$this->render('_menuItem', ['menu' => $subMenu])?>
        <?php endforeach?>
    <?=$this->render('_autoClose', ['new_menu' => true])?>
    <?="\n\t</ul>\n"?>
</li>