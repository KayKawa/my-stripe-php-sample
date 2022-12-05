<?php
require_once('../../vendor/autoload.php');

// 1. Stripeライブラリの初期化（サーバサイド）
$dotenv = Dotenv\Dotenv::create('../../');
$dotenv->load();

$secretKey = getenv('STRIPE_SECRET_KEY');
$publicKey = getenv('STRIPE_PUBLIC_KEY');

\Stripe\Stripe::setApiKey($secretKey);

// 2. 支払いフォームを構築するリクエストをStripe APIに送信する
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price' =>'price_1LoiuTCnH8HQSmYyWJ5Jpmho',
            'quantity' => 200,
        ],
        ],
        'mode' => 'payment',
    'success_url'          => 'https://example.com/checkout/success.php', // 成功時リダイレクトURL
    'cancel_url'           => 'https://example.com/checkout/cancel.php',  // 失敗時リダイレクトURL
]);

?>

<!-- 3. Stripeライブラリの初期化（フロントエンド） -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    const publicKey = '<?= $publicKey ?>';

    var stripe = Stripe(publicKey);

    // 4. 決済ボタンが押下されたら決済画面にリダイレクトする
    function onClick() {
        stripe.redirectToCheckout({
            sessionId: '<?= $session->id ?>'
        }).then(function (result) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer
            // using `result.error.message`.
        });
    }
</script>

<h1>決済フォーム(Checkout)</h1>

<button onclick="onClick()">決済画面へ</button>
