<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UpdateNotifierEntitySettingsForm.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntitySettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['update_notifier.settings'];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'updatenotifierentity_settings';
  }

  /**
   * Defines the settings form for Update notifier entity entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('update_notifier.settings');

    $form['updatenotifierentity_settings'] = [
      '#title' => $this->t('Notification Messages'),
      '#description' => $this->t('Set the messages that are sent for each type of notification.'),
      '#type' => 'details',
      '#open' => TRUE,
    ];

    $form['updatenotifierentity_settings']['price_change_message'] = [
      '#title' => $this->t('Price Change Message'),
      '#type' => 'textarea',
      '#rows' => 5,
      '#cols' => 3,
      '#default_value' => $config->get('price_change'),
    ];

    $form['updatenotifierentity_settings']['on_sale_message'] = [
      '#title' => $this->t('On Sale Message'),
      '#type' => 'textarea',
      '#rows' => 5,
      '#cols' => 3,
      '#default_value' => $config->get('on_sale'),
    ];

    $form['updatenotifierentity_settings']['promotion_message'] = [
      '#title' => $this->t('Promotion Message'),
      '#type' => 'textarea',
      '#rows' => 5,
      '#cols' => 3,
      '#default_value' => $config->get('promotion'),
    ];

    $form['updatenotifierentity_settings']['in_stock_message'] = [
      '#title' => $this->t('In Stock Message'),
      '#type' => 'textarea',
      '#rows' => 5,
      '#cols' => 3,
      '#default_value' => $config->get('in_stock'),
    ];

    return parent::buildForm($form, $form_state);
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
    $this->config('update_notifier.settings')
      ->set('price_change', $form_state->getValue('price_change_message'))
      ->set('on_sale', $form_state->getValue('on_sale_message'))
      ->set('promotion', $form_state->getValue('promotion_message'))
      ->set('in_stock', $form_state->getValue('in_stock_message'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
