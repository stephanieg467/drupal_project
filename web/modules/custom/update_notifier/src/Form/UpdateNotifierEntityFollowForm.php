<?php

/**
 * @file
 * Contains \Drupal\update_notifier\Form\UpdateNotifierFollowForm.
 */

namespace Drupal\update_notifier\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller to follow products and select notification types.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntityFollowForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'update_notifier_entity_follow_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#prefix'] = '<div id="follow_form">';
    $form['#suffix'] = '</div>';

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Choose the types of notifications you would like to receive.'),
    ];

    $form['notification_type'] = [
      '#type' => 'checkboxes',
      '#options' => array('price_change' => $this->t('Price Change'), 'on_sale' => $this->t('On Sale'), 'promotion' => $this->t('Promotion'), 'in_stock' => $this->t('In Stock')),
      '#title' => $this->t('Types of Notifications'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Follow Product'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();
    drupal_set_message($this->t('You chose to be notified for the following: @notifications', ['@notifications' => $values ]));

  }

}
