<?php

namespace Drupal\update_notifier\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field widget "update_notifier_notifications_widget".
 *
 * @FieldWidget(
 *   id = "update_notifier_notifications_widget",
 *   label = @Translation("Update Notifier Notifications Widget"),
 *   field_types = {
 *     "update_notifier_notifications",
 *   },
 *   multiple_values = TRUE
 * )
 */
class UpdateNotifierNotificationsWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    /*
    $element += array(
      '#type' => 'fieldset',
    );
    $element['notifications'] = array(
      '#title' => t('Notifications'),
      '#type' => 'fieldset',
    );
    $element['notifications']['price_change'] = array(
      '#title' => t('Price change'),
      '#type' => 'checkbox',
    );
    $element['notifications']['on_sale'] = array(
      '#title' => t('On sale'),
      '#type' => 'checkbox',
    );
    $element['notifications']['promotion'] = array(
      '#title' => t('Promotion'),
      '#type' => 'checkbox',
    );
    $element['notifications']['in_stock'] = array(
      '#title' => t('In stock'),
      '#type' => 'checkbox',
    );
    */
    $element += array(
      '#type' => 'fieldset',
    );
    $element['price_change'] = array(
      '#type' => 'checkbox',
      '#title' => t('Price change'),
    );
    $element['on_sale'] = array(
      '#type' => 'checkbox',
      '#title' => t('On sale'),
    );
    $element['promotion'] = array(
      '#type' => 'checkbox',
      '#title' => t('Promotion'),
    );
    $element['in_stock'] = array(
      '#type' => 'checkbox',
      '#title' => t('In stock'),
    );
    return $element;
  }
}
