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