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
    /* @var $entity \Drupal\update_notifier\Entity\UpdateNotifierEntity */
    $form = parent::buildForm($form, $form_state);

    /*
    Can't do this, need another solution
    $form['notifications']['widget'] = [
      '#type' => 'checkboxes',
      '#options' => array('price_change' => $this->t('Price Change'), 'on_sale' => $this->t('On Sale'), 'promotion' => $this->t('Promotion'), 'in_stock' => $this->t('In Stock')),
      '#title' => $this->t('Types of Notifications'),
      '#required' => 'TRUE',
    ];
    */

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
