<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\update_notifier\UpdateNotifierContainerInterface;
//use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Form controller for Update notifier entity edit forms.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntityForm extends ContentEntityForm {

  /*
  /**
   * The update notifier container service.
   *
   * @var \Drupal\update_notifier\UpdateNotifierContainerInterface
   */
  /*
  protected $updateNotifierContainer;
  */

  /*
  /**
   * Constructs a new UpdateNotifierEntityForm object.
   *
   * @param \Drupal\update_notifier\UpdateNotifierContainerInterface $update_notifier_container
   *   The update notifier container service.
   */
  /*
  public function __construct(UpdateNotifierContainerInterface $update_notifier_container) {

    $this->updateNotifierContainer = $update_notifier_container;
  }

  /**
   * {@inheritdoc}
   */
  /*
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('update_notifier.update_notifier_container')
    );
  }
  */

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    xdebug_break();
    $entity = $this->entity;
    $status = parent::save($form, $form_state);
    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Update notifier entity.', [
          '%label' => $entity->label(),
        ]));
        break;
      default:
        drupal_set_message($this->t('Saved the %label Update notifier entity.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.update_notifier_entity.canonical', ['update_notifier_entity' => $entity->id()]);
  }

}
