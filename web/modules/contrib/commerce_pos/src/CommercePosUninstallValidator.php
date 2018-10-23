<?php

namespace Drupal\commerce_pos;

use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Url;

/**
 * Prevents commerce_pos module from being uninstalled if there are orders depending on registers.
 */
class CommercePosUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * Constructs a new CommercePosUninstallValidator.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(TranslationInterface $string_translation) {
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module == 'commerce_pos') {
      if ($this->posOrdersExist()) {
        $reasons[] = $this->t('To uninstall Commerce POS, <a href=":url">delete all orders of type Point of Sale.</a>', [
          ':url' => Url::fromRoute('commerce_pos.delete_pos_orders')->toString(),
          ]);
      }
    }
    return $reasons;
  }

  /**
   * Checks if there are any orders of type "pos".
   *
   * @return bool
   *   TRUE if there are pos type orders, FALSE if not.
   */
  protected function posOrdersExist() {
    $orderIds = \Drupal::entityQuery('commerce_order')
      ->condition('type', 'pos')
      ->execute();
    if($orderIds)
      return TRUE;
    else
      return FALSE;
  }

}
