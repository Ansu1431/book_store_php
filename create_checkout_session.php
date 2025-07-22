<?php
require 'vendor/autoload.php';
include_once('./includes/config.php');
include_once('./stripeConfig.php');
session_start();

\Stripe\Stripe::setApiKey($secretKey);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = $_SESSION['purchase_cart'] ?? [];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_address = $_POST['customer_address'];

    $line_items = [];
    foreach ($cart as $book_id => $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $item['book_title'],
                ],
                'unit_amount' => $item['book_price'] * 100,
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'customer_email' => $customer_email,
        'success_url' => 'http://localhost/bookstore/purchase.php?success=1',
        'cancel_url' => 'http://localhost/bookstore/purchase.php?canceled=1',
        'metadata' => [
            'customer_name' => $customer_name,
            'customer_address' => $customer_address,
        ],
    ]);

    echo json_encode(['id' => $session->id]);
    exit;
}
?> 