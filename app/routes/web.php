<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 1) . '/middlewares/auth.php';

session_start();

use App\Controllers\ProductController;
use App\Controllers\LoginController;
use App\Controllers\OrderController;
use App\Controllers\CouponController;
use App\Controllers\WebhookController;
use Core\Auth;
use Core\Cart;
use Core\Buying;
use Core\Router;

function controller(string $class)
{
  return new $class();
}

$router = new Router();

// Products
$router->add('GET', '/products', fn() => controller(ProductController::class)->index());
$router->add('GET', '/products/create', middleware('admin', fn() => controller(ProductController::class)->create()));
$router->add('POST', '/products/create', middleware('admin', fn() => controller(ProductController::class)->create()));
$router->addRegex('GET', '#^/products/(\d+)/edit$#', middleware('admin', fn($id) => controller(ProductController::class)->edit((int)$id)));
$router->addRegex('POST', '#^/products/(\d+)/edit$#',  middleware('admin', fn($id) => controller(ProductController::class)->edit((int)$id)));
$router->addRegex('GET', '#^/products/(\d+)/delete$#', middleware('admin', fn($id) => controller(ProductController::class)->delete((int)$id)));

// Cart
$router->add('GET', '/cart', fn() => controller(Cart::class)->list());
$router->add('GET', '/cart/clear', fn() => controller(Cart::class)->clear());
$router->addRegex('POST', '#^/cart/(\d+)/add$#', fn($id) => controller(Cart::class)->add((int)$id));
$router->addRegex('GET', '#^/cart/(\d+)/remove$#', fn($id) => controller(Cart::class)->remove((int)$id));

// Buying
$router->add('GET', '/buy/address', fn() => controller(Buying::class)->address());
$router->add('POST', '/buy/address', fn() => controller(Buying::class)->address());
$router->add('POST', '/buy/finish', fn() => controller(Buying::class)->finish());

// Orders
$router->add('GET', '/orders', middleware('admin',  fn() => controller(OrderController::class)->index()));

// Coupons
$router->add('GET', '/coupons', middleware('admin',  fn() => controller(CouponController::class)->index()));
$router->add('GET', '/coupons/create', middleware('admin',  fn() => controller(CouponController::class)->create()));
$router->add('POST', '/coupons/create', middleware('admin',  fn() => controller(CouponController::class)->create()));
$router->addRegex('GET', '#^/coupons/(\d+)/edit$#', middleware('admin',  fn($id) => controller(CouponController::class)->edit((int)$id)));
$router->addRegex('POST', '#^/coupons/(\d+)/edit$#', middleware('admin',  fn($id) => controller(CouponController::class)->edit((int)$id)));
$router->addRegex('GET', '#^/coupons/(\d+)/delete$#', middleware('admin',  fn($id) => controller(CouponController::class)->delete((int)$id)));
$router->add('GET', '/coupons/apply', fn() => controller(CouponController::class)->apply());

// Webhook
$router->add('POST', '/update-order', fn() => controller(WebhookController::class)->updateOrder());
$router->add('GET', '/cancel-order', fn() => controller(WebhookController::class)->cancelOrder());


// Login
$router->add('GET', '/login', fn() => controller(LoginController::class)->login());
$router->add('POST', '/login', fn() => controller(LoginController::class)->login());
$router->add('POST', '/register', fn() => controller(LoginController::class)->register());
$router->add('GET', '/logout', fn() => controller(LoginController::class)->logout());

// Home page
$router->add('GET', '/', fn() => controller(ProductController::class)->index());

$publicRoutes = [
  '/login',
  '/register',
  '/logout',
  '/coupons/apply',
  '/update-order',
  '/cancel-order',
];

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!in_array($currentPath, $publicRoutes, true)) {
  Auth::requireLogin();
}

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
