<?php 

use yii\helpers\Html;
use dynamikaweb\adaptive\MenuIcon;

$linhas = 6;
$subMenus = $menu->menusAtivos;
$menuPai = $menu->menuPai;
?>

<?php if(!$subMenus && !$menu->resumo):?>
    <?php if($menuPai && $menuPai->id_pai == null):?>
        <?php if(!Yii::$app->session->has('__menu_open')):?>
            <?php Yii::$app->session->set('__menu_max', count($menuPai->menusAtivos))?>
            <?php Yii::$app->session->set('__menu_open', $menu->id_pai)?>
            <?php Yii::$app->session->set('__menu_count', 1)?>
            <?="\n\t<!-- MENU SUB AUTO AFTER MENU ROOT ({$menuPai->nome}) -->".$this->render('_autoOpen')?>
        <?php elseif (Yii::$app->session->get('__menu_count') >= $linhas):?>
            <?php Yii::$app->session->set('__menu_max', Yii::$app->session->get('__menu_max') - $linhas)?>
            <?php Yii::$app->session->set('__menu_count', 1)?>
            <?=$this->render('_autoClose', ['now' => true])?>
            <?="\n\t<!-- MENU SUB AUTO AFTER MENU ROOT ({$menuPai->nome}) (AFTER {$linhas}) -->   ".$this->render('_autoOpen')?>
        <?php else:?>
            <?php Yii::$app->session->set('__menu_count', Yii::$app->session->get('__menu_count') + 1)?>
        <?php endif?>
    <?php endif?>
        <?="\n\t\t"?><!-- MENU LINK <?=Yii::$app->session->get('__menu_count', null)?>-->
        <li><?=Html::a($menu->nome, $menu->link, ['title' => $menu->nome])?></li>
        <?=$this->render('_autoClose')?>
    <?php return ?>
<?php endif?>

<?=$this->render('_autoClose', ['new_menu' => true])?>

<?php if($menu->id_pai == null):?>
<!-- MENU ROOT (<?=$menu->nome?>) -->
<li>
    <?=Html::a($menu->nome.'&nbsp'.Icon::show('arrow-alt-circle-down'), $menu->link, ['title' => $menu->nome])?>
    <ul>
        <?php foreach($subMenus as $subMenu):?>
            <?=$this->render('_menuItem', ['menu' => $subMenu])?>
        <?php endforeach?>
    <?=$this->render('_autoClose', ['new_menu' => true])?>
    <?="\n\t</ul>\n"?>
</li>
    <?php return ?>
<?php endif?>

<?="\n\t<!-- MENU SUB ({$menu->nome}) -->"?>
<?=$this->render('_autoOpen')?>
    <?php if($menu->nome_visivel):?>
        <?=Html::tag('h3', $menu->nome)?>
    <?php endif?>

    <?php if ($menu->resumo):?>
        <?=Html::tag('p', Yii::$app->formatter->asNtext($menu->resumo), ['class' => 'text-left'])?>
    <?php endif ?> 

    <?php foreach($subMenus as $subMenu):?>
        <?=$this->render('_menuItem', ['menu' => $subMenu])?>
    <?php endforeach?>
<?=$this->render('_autoClose', ['now' => true])?>