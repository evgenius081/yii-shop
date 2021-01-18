<?php
/* @var $this View
 *
 * @var $content string
 */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);
?>
<?php
$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    $this->registerCsrfMetaTags()
    ?>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<section class="body">
    <header>
        <div class="container">
            <div class="header">
                <a href="/">На главную</a>
                <? if (!Yii::$app->user->isGuest) { ?>
                    <a href="/admin">Панель администрирования</a>
                <? } ?>
                <? if (Yii::$app->user->isGuest) { ?>
                    <a href="/admin/login">Вход в админку</a>
                <? } else { ?>
                    <a href="/admin/logout">Выход из админки</a><?
                } ?>
                <a href="#" onclick="openCart(event)">Корзина <span class='menu-quantity'>(<?=isset($_SESSION['cart.totalQuantity']) ? $_SESSION['cart.totalQuantity'] : 0 ?>)</span></a>
                <form method="get" action="<?= Url::to(['category/search']) ?>">
                    <input type="text" style="padding: 5px" placeholder="Поиск..." name="search">
                </form>

            </div>
        </div>
    </header>
    <div class="container"><?= $content ?></div>
    <footer>
        <div class="container">
            <div class="footer"> &copy; Все права защищены или типа того</div>
        </div>
    </footer>
</section>
<div id="cart" class="modal fade bg-example-modal-xl" tabindex="-1" role="dialog"
           aria-labelledby="myExtraLargeModalLabel"
           aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="order" class="modal fade bg-example-modal-xl" tabindex="-1" role="dialog"
     aria-labelledby="myExtraLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
