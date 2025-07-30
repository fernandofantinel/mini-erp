<?php

namespace App\Controllers;

use App\Models\Product;
use Core\View;
use Core\Validation;

class ProductController
{
  public function index(): void
  {
    $products = Product::all();
    View::render('products/index', ['products' => $products]);
  }

  public function create(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $name      = $_POST['name'];
      $price     = $_POST['price'];
      $variation = $_POST['variation'];
      $quantity  = $_POST['quantity'];

      $validation = Validation::validate([
        "name" => ["required"],
        "price" => ["required"],
        "variation" => ["required"],
        "quantity" => ["required"]
      ], $_POST);

      if ($validation->notPassed("product")) {
        header("Location: /products/create");
        exit;
      }

      Product::create($name, $price, $variation, $quantity);
      header('Location: /products/create');
      exit;
    }

    View::render('products/create');
  }

  public function edit(int $id): void
  {
    $product = Product::find($id);

    if (!$product) {
      http_response_code(404);
      echo "Produto não encontrado.";
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name      = $_POST['name'];
      $price     = $_POST['price'];
      $variation = $_POST['variation'];
      $quantity  = $_POST['quantity'];

      $validation = Validation::validate([
        "name" => ["required"],
        "price" => ["required"],
        "variation" => ["required"],
        "quantity" => ["required"]
      ], $_POST);

      if ($validation->notPassed("product")) {
        header("Location: /products/$id/edit");
        exit;
      }

      Product::update($id, $name, $price, $variation, $quantity);
      header('Location: /products');
      exit;
    }

    View::render('products/edit', [
      'product' => $product
    ]);
  }

  public function delete(int $id): void
  {
    Product::delete($id);
    header('Location: /products');
    exit;
  }
}
