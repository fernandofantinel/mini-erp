<?php

namespace App\Controllers;

use App\Models\Login;
use Core\View;
use Core\Flash;
use Core\Validation;

class LoginController
{
  public function login(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'];

      $validation = Validation::validate([
        "email" => ["required", "email"],
        "password" => ["required"]
      ], $_POST);

      if ($validation->notPassed("login")) {
        header("location: /login");
        exit;
      }

      $user = Login::login($email);

      if (!$user || !password_verify($_POST["password"], $user["password"])) {
        Flash::push("validations_login", ["Dados incorretos."]);
        header("Location: /");
        exit;
      }

      $_SESSION['auth'] = [
        'email' => $email,
        'role' => $email === 'admin@email.com' ? 'admin' : 'user'
      ];

      Flash::push("message", "Seja bem-vindo, " . $user["name"]);
      header('Location: /products');
      exit;
    }

    View::render('login/login-form');
  }

  public function register(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      $validation = Validation::validate([
        "name" => ["required"],
        "email" => ["required", "email", "unique:users"],
        "password" => ["required"]
      ], $_POST);

      if ($validation->notPassed("register")) {
        header("Location: /");
        exit;
      }

      Login::register($name, $email, $password);

      $_SESSION['auth'] = [
        'email' => $email,
        'role' => $email === 'admin@email.com' ? 'admin' : 'user'
      ];

      Flash::push("message", "Registrado com sucesso.");
      header("Location: /products");
      exit;
    }

    header("Location: /");
    exit;
  }

  public function logout(): void
  {

    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    unset($_SESSION);
    session_destroy();
    Flash::push("message", "Você saiu com sucesso.");
    header('Location: /');
    exit;
  }
}
