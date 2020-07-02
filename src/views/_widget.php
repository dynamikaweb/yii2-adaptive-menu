<?php

use yii\helpers\Html;

?>

<section id="menu" class="bg-menu">
    <?=Html::beginTag($container, $containerOptions)?>
    <nav>
        <?=Html::tag('ul', $content, ['class' => 'menu'])?>
    </nav>
    <?=Html::endTag($container)?>
</section>