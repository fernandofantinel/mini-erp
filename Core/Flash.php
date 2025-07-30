<?php

namespace Core;

class Flash
{
  public static function push($key, $value)
  {
    $_SESSION["flash_$key"] = $value;
  }

  public static function get($key)
  {
    if (!isset($_SESSION["flash_$key"])) {
      return false;
    }

    $value = $_SESSION["flash_$key"];
    unset($_SESSION["flash_$key"]);
    return $value;
  }
}
