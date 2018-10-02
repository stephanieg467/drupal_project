<?php

/**
* @file
* Contains \Drupal\update_notifier\Plugin\Field\FieldType\UpdateNotifierNotifications.
*/

namespace Drupal\update_notifier\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldItemInterface;

/**
 * Plugin implementation of the 'update_notifier_notifications' field type.
 *
 * @FieldType(
 *   id = "update_notifier_notifications",
 *   label = @Translation("Update Notifier Notifications"),
 *   description = @Translation("Stores the types of notifications the user wants to receive."),
 *   category = @Translation("Update Notifier"),
 *   default_widget = "update_notifier_notifications_widget",
 *   default_formatter = "update_notifier_notifications_formatter",
 * )
 */
class UpdateNotifierNotifications extends FieldItemBase implements FieldItemInterface{

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    return array(
      'columns' => array(
        'price_change' => array(
          'type' => 'int',
          'length' => 1,
        ),
        'on_sale' => array(
          'type' => 'int',
          'length' => 1,
        ),
        'promotion' => array(
          'type' => 'int',
          'length' => 1,
        ),
        'in_stock' => array(
          'type' => 'int',
          'length' => 1,
        ),
      ),
    );

    /*
    $output = array();
    // Make a column for every type of notification.
    $output['columns']['price_change'] = array(
      'type' => 'int',
      'length' => 1,
    );
    $output['columns']['on_sale'] = array(
      'type' => 'int',
      'length' => 1,
    );
    $output['columns']['promotion'] = array(
      'type' => 'int',
      'length' => 1,
    );
    $output['columns']['in_stock'] = array(
      'type' => 'int',
      'length' => 1,
    );
    return $output;
    */

  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $item = $this->getValue();

    $has_stuff = FALSE;

    if (
      (isset($item['price_change']) && $item['price_change'] == 1) ||
      (isset($item['on_sale']) && $item['on_sale'] == 1) ||
      (isset($item['promotion']) && $item['promotion'] == 1) ||
      (isset($item['in_stock']) && $item['in_stock'] == 1)
      ) {
      $has_stuff = TRUE;
    }

    return !$has_stuff;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Add our properties.
    $properties['price_change'] = DataDefinition::create('boolean')
      ->setLabel(t('Price change'));
    $properties['on_sale'] = DataDefinition::create('boolean')
      ->setLabel(t('On sale'));
    $properties['promotion'] = DataDefinition::create('boolean')
      ->setLabel(t('Promotion'));
    $properties['in_stock'] = DataDefinition::create('boolean')
      ->setLabel(t('In stock'));
    return $properties;
  }

  /**
   * Returns an array of notifications chosen.
   *
   * @return array
   *   An associative array of all notifications chosen.
   */
  public function getNotifications() {
    $output = array();
    $output['price_change'] = 'Price change';
    $output['on_sale'] = 'On sale';
    $output['promotion'] = 'Promotion';
    $output['in_stock'] = 'In stock';
    return $output;
  }

}
