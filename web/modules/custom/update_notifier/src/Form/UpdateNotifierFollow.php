<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityInterface;


/**
 * Class UpdateNotifierFollow.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierFollow extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'update_notifier_follow';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    drupal_set_message($this->t('You chose to be notified for the following: @notifications', ['@notifications' => $values ]));
  }

  /**
   * Defines the settings .
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state, AccountInterface $user = NULL, EntityInterface $entity = NULL) {
    $form['#prefix'] = '<div id="follow_form">';
    $form['#suffix'] = '</div>';

    $form['test'] = [
      '#markup' => $this->t("Hello @user", ['@user' => $user->getDisplayName()]),
    ];

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t("Choose the types of notifications you would like to receive for @product.", ['@product' => $entity->id()]),
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

}
