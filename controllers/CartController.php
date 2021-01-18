<?php


namespace app\controllers;


use app\models\Cart;
use app\models\Good;
use app\models\Order;
use app\models\OrderGood;
use Yii;
use yii\web\Controller;

class CartController extends Controller
{
    public function actionAdd($name)
    {
        $good = new Good();
        $good = $good->getOneGood($name);
        $session = new \Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->addToCart($good);
        return $this->renderPartial('cart', compact('good', 'session'));
    }

    public function actionOpen()
    {
        $session = new Yii::$app->session;
        $session->open();
        return $this->renderPartial('cart', compact('session'));
    }

    public function actionClear()
    {
        $session = new Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.totalQuantity');
        $session->remove('cart.totalSum');
        return $this->renderPartial('cart', compact('session'));
    }

    public function actionDelete($id)
    {
        $session = new Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalcCart($id);
        return $this->renderPartial('cart', compact('session'));
    }

    public function actionOrder()
    {
        $session = new Yii::$app->session;
        $session->open();
        if (!$session['cart.totalSum']) {
            return Yii::$app->response->redirect(Url::to('/'));
        }
        $order = new Order();
        if ($order->load(Yii::$app->request->post())) {
            $order->date = date('Y-m-d H:i:s');
            $order->sum = $session['cart.totalSum'];
            if ($order->save()) {
                $currentId = $order->id;
                $this->saveOrderInfo($session['cart'], $currentId);
                Yii::$app->mailer->compose('order-mail', ['session' => $session, 'order' => $order]) //формирует письмо
                ->setFrom(['hulevicheugene@mail.ru' => 'php-shop']) //от кого письмо
                ->setTo($order->email) //кому письмо
                ->setSubject('ваш заказ принят')//текст сообщения
                ->send();
                $session->remove('cart');
                $session->remove('cart.totalQuantity');
                $session->remove('cart.totalSum');
                return $this->render('success', compact('session', 'currentId'));
            }
        }
        $this->layout = 'empty-layout';
        return $this->render('order', compact('session', 'order'));
    }

    protected function saveOrderInfo($goods, $orderId)
    {
        foreach ($goods as $id => $good) {
            $orderInfo = new OrderGood();
            $orderInfo->product_id = $id;
            $orderInfo->name = $good['name'];
            $orderInfo->price = $good['price'];
            $orderInfo->quantity = $good['goodQuantity'];
            $orderInfo->sum = $good['price'] * $good['goodQuantity'];
            $orderInfo->save();
        }
    }

}