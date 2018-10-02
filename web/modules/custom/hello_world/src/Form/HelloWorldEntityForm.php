<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Hello world entity edit forms.
 *
 * @ingroup hello_world
 */
class HelloWorldEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\hello_world\Entity\HelloWorldEntity */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Hello world entity.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Hello world entity.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.hello_world_entity.canonical', ['hello_world_entity' => $entity->id()]);
  }

}
