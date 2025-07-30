<?php

namespace Core;

use Core\Database;
use Core\Flash;

class Validation
{
  public $validations = [];

  public static function validate($rules, $data)
  {
    $validation = new self;

    foreach ($rules as $field => $fieldRules) {
      foreach ($fieldRules as $rule) {
        $fieldValue = $data[$field] ?? null;

        if (str_contains($rule, ":")) {
          $temp = explode(":", $rule);
          $ruleName = $temp[0];
          $ruleArgument = $temp[1];
          $validation->$ruleName($ruleArgument, $field, $fieldValue);
        } else {
          $validation->$rule($field, $fieldValue);
        }
      }
    }

    return $validation;
  }

  private function unique($table, $field, $fieldValue)
  {
    if (strlen($fieldValue) == 0) {
      return;
    }

    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM $table WHERE $field = ?");
    $stmt->execute([$fieldValue]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;

    if ($result) {
      $this->validations[] = $this->getMessageForRule('unique', $field);
    }
  }

  private function required($field, $fieldValue)
  {
    if (strlen(trim((string)$fieldValue)) == 0) {
      $this->validations[] = $this->getMessageForRule('required', $field);
    }
  }

  private function email($field, $fieldValue)
  {
    if (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
      $this->validations[] = $this->getMessageForRule('email', $field);
    }
  }

  private function getMessageForRule($rule, $field)
  {
    $messages = [
      'required' => [
        'price' => 'O preço é obrigatório.',
        'variation' => 'A variação é obrigatória.',
        'quantity' => 'A quantidade é obrigatória.',
        'name' => 'O nome é obrigatório.',
        'password' => 'A senha é obrigatória.',
        'state' => 'O estado é obrigatório.',
        'zip_code' => 'O CEP é obrigatório.',
        'state' => 'O estado é obrigatório.',
        'city' => 'A cidade é obrigatória.',
        'neighborhood' => 'O bairro é obrigatório.',
        'street' => 'O logradouro é obrigatório.',
        'number' => 'O número é obrigatório.',
        'code' => 'O código é obrigatório.',
        'discount' => 'O desconto é obrigatório.',
        'minimum_amount' => 'O valor mínimo é obrigatório.',
        'expires_at' => 'A data de expiração é obrigatória.',
      ],
      'email' => [
        'email' => 'O email está em formato inválido.',
      ],
      'unique' => [
        'email' => 'O email já está cadastrado.',
      ],
    ];

    if (isset($messages[$rule][$field])) {
      return $messages[$rule][$field];
    }
  }

  public function notPassed($customName = null)
  {
    $key = "validations";

    if ($customName) {
      $key .= "_" . $customName;
    }

    Flash::push($key, $this->validations);

    return count($this->validations) > 0;
  }
}
