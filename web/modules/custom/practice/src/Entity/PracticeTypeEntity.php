<?php

namespace Drupal\practice\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Practice Type entity. A configuration entity used to manage
 * bundles for the Practice entity.
 *
 * @ConfigEntityType(
 *   id = "practice_type",
 *   label = @Translation("Practice Type"),
 *   bundle_of = "practice",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_prefix = "practice_type",
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *   },
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\practice\PracticeTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\practice\Form\PracticeTypeEntityForm",
 *       "add" = "Drupal\practice\Form\PracticeTypeEntityForm",
 *       "edit" = "Drupal\practice\Form\PracticeTypeEntityForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer practice types",
 *   links = {
 *     "canonical" = "/admin/structure/practice_type/{practice_type}",
 *     "add-form" = "/admin/structure/practice_type/add",
 *     "edit-form" = "/admin/structure/practice_type/{practice_type}/edit",
 *     "delete-form" = "/admin/structure/practice_type/{practice_type}/delete",
 *     "collection" = "/admin/structure/practice_type",
 *   }
 * )
 */
class PracticeTypeEntity extends ConfigEntityBundleBase implements PracticeTypeEntityInterface {
  /**
   * The machine name of the practical type.
   *
   * @var string
   */
  protected $id;
  /**
   * The human-readable name of the practical type.
   *
   * @var string
   */
  protected $label;
  /**
   * A brief description of the practical type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }
  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }
}
