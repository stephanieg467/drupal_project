<?php

namespace Drupal\update_notifier\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\commerce_product\Entity\ProductInterface;

/**
 * Defines the Update notifier entity entity.
 *
 * @ingroup update_notifier
 *
 * @ContentEntityType(
 *   id = "update_notifier_entity",
 *   label = @Translation("Update notifier entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\update_notifier\UpdateNotifierEntityListBuilder",
 *     "views_data" = "Drupal\update_notifier\Entity\UpdateNotifierEntityViewsData",
 *     "translation" = "Drupal\update_notifier\UpdateNotifierEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\update_notifier\Form\UpdateNotifierEntityForm",
 *       "add" = "Drupal\update_notifier\Form\UpdateNotifierEntityForm",
 *       "edit" = "Drupal\update_notifier\Form\UpdateNotifierEntityForm",
 *       "delete" = "Drupal\update_notifier\Form\UpdateNotifierEntityDeleteForm",
 *     },
 *     "access" = "Drupal\update_notifier\UpdateNotifierEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\update_notifier\UpdateNotifierEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "update_notifier_entity",
 *   data_table = "update_notifier_entity_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer update notifier entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/update_notifier_entity/{update_notifier_entity}",
 *     "add-form" = "/admin/structure/update_notifier_entity/add",
 *     "edit-form" = "/admin/structure/update_notifier_entity/{update_notifier_entity}/edit",
 *     "delete-form" = "/admin/structure/update_notifier_entity/{update_notifier_entity}/delete",
 *     "collection" = "/admin/structure/update_notifier_entity",
 *   },
 *   field_ui_base_route = "update_notifier_entity.settings"
 * )
 */
class UpdateNotifierEntity extends ContentEntityBase implements UpdateNotifierEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function unfollow() {
    $this->set('product_followed', NULL);
  }

  /**
   * {@inheritdoc}
   */
  public function getProductFollowed() {
    return $this->get('product_followed')->value;
  }
  /**
   * {@inheritdoc}
   */
  public function setProductFollowed($product_followed) {
    $this->set('product_followed', $product_followed);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNotifyPriceChange() {
    return $this->get('notify__price_change')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNotifyPriceChange($notify__price_change) {
    $this->set('notify__price_change', $notify__price_change ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNotifyOnSale() {
    return $this->get('notify__on_sale')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNotifyOnSale($notify__on_sale) {
    $this->set('notify__on_sale', $notify__on_sale ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }
  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    //Stores the product that a user is following
    $fields['product_followed'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Product/Products followed'))
      ->setDescription(t('The product/products being followed.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'commerce_product')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'commerce_product_variation_title',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 0,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['notify__price_change'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Get notified if price changes'))
      ->setDescription(t('Notify the user if the price changes.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    /*
    $fields['notify__on_sale'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Get notified if product is on sale'))
      ->setDescription(t('Notify the user if the product is on sale.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'boolean',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);
    */


    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Customer'))
      ->setDescription(t('The user ID of customer that is following product.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Update notifier entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Update notifier entity is published.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 10,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    //$field_type_manager = \Drupal::service('plugin.manager.field.field_type');
    //$storage_settings = $field_type_manager->getDefaultStorageSettings('entity_reference');
    //$field_settings = $field_type_manager->getDefaultFieldSettings('entity_reference');
    //kint($field_settings);

    return $fields;
  }

}
