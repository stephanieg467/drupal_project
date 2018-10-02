<?php

namespace Drupal\burrito_maker\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field formatter "update_notifier_notifications_formatter".
 *
 * @FieldFormatter(
 *   id = "update_notifier_notifications_formatter",
 *   label = @Translation("Update Notifier Notifications Formatter"),
 *   field_types = {
 *     "update_notifier_notifications",
 *   }
 * )
 */
class UpdateNotifierNotificationsFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
        $output = array();
    // Iterate over every field item and build a renderable array
    foreach ($items as $delta => $item) {
      $build = array();
      $build['notifications'] = array(
        '#type' => 'container',
        '#attributes' => array(
          'class' => array('update_notifier_notifications'),
        ),
        'label' => array(
          '#type' => 'container',
          '#attributes' => array(
            'class' => array('field__label'),
          ),
          '#markup' => t('Notifications'),
        ),
        'value' => array(
          '#type' => 'container',
          '#attributes' => array(
            'class' => array('field__item'),
          ),
          'text' => array(
            '#theme' => 'item_list',
            '#items' => $item->getNotifications(),
          ),
        ),
      );
      $output[$delta] = $build;
    }
    return $output;
  }
}
