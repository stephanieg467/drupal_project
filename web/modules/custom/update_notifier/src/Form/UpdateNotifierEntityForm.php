<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;


/**
 * Form controller for Update notifier entity edit forms.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntityForm extends ContentEntityForm {

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
    $entity = $this->entity;
    $status = parent::save($form, $form_state);
    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Now following %product.', [
          '%product' => $entity->getProductFollowed()->getTitle(),
        ]));
        break;
      default:
        drupal_set_message($this->t('Saved the notifications for %product.', [
          '%product' => $entity->getProductFollowed()->getTitle(),
        ]));
    }
    $form_state->setRedirect('entity.user.canonical', ['user' => $entity->getOwner()->id()]);
  }

}
