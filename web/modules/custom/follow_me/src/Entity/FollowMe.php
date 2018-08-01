<?php

namespace Drupal\follow_me\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Follow me entity.
 *
 * @ingroup follow_me
 *
 * @ContentEntityType(
 *   id = "follow_me",
 *   label = @Translation("Follow me"),
 *   handlers = {
 *     "storage" = "Drupal\follow_me\FollowMeStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\follow_me\FollowMeListBuilder",
 *     "views_data" = "Drupal\follow_me\Entity\FollowMeViewsData",
 *     "translation" = "Drupal\follow_me\FollowMeTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\follow_me\Form\FollowMeForm",
 *       "add" = "Drupal\follow_me\Form\FollowMeForm",
 *       "edit" = "Drupal\follow_me\Form\FollowMeForm",
 *       "delete" = "Drupal\follow_me\Form\FollowMeDeleteForm",
 *     },
 *     "access" = "Drupal\follow_me\FollowMeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\follow_me\FollowMeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "follow_me",
 *   data_table = "follow_me_field_data",
 *   revision_table = "follow_me_revision",
 *   revision_data_table = "follow_me_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer follow me entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/follow_me/{follow_me}",
 *     "add-form" = "/admin/structure/follow_me/add",
 *     "edit-form" = "/admin/structure/follow_me/{follow_me}/edit",
 *     "delete-form" = "/admin/structure/follow_me/{follow_me}/delete",
 *     "version-history" = "/admin/structure/follow_me/{follow_me}/revisions",
 *     "revision" = "/admin/structure/follow_me/{follow_me}/revisions/{follow_me_revision}/view",
 *     "revision_revert" = "/admin/structure/follow_me/{follow_me}/revisions/{follow_me_revision}/revert",
 *     "revision_delete" = "/admin/structure/follow_me/{follow_me}/revisions/{follow_me_revision}/delete",
 *     "translation_revert" = "/admin/structure/follow_me/{follow_me}/revisions/{follow_me_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/follow_me",
 *   },
 *   field_ui_base_route = "follow_me.settings"
 * )
 */
class FollowMe extends RevisionableContentEntityBase implements FollowMeInterface {

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
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the follow_me owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
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
  public function getFollowedEntityType() {
    return $this->get('followed_entity_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFollowedEntityType($followed_entity_type) {
    $this->set('followed_entity_type', $followed_entity_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityID() {
    return $this->get('entity_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityID($entity_id) {
    $this->set('entity_id', $entity_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Follow me entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Follow me entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Follow me is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    $fields['followed_entity_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Followed Entity Type'))
      ->setDescription(t('The type of entity followed.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['entity_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Entity ID'))
      ->setDescription(t('The id of the followed entity type.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'integer',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'integer',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

}
