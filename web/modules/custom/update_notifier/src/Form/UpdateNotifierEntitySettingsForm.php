<?php
namespace Drupal\update_notifier\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Class UpdateNotifierEntitySettingsForm.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEntitySettingsForm extends FormBase {
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
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
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
    $form['updatenotifierentity_settings']['#markup'] = 'Settings form for Update notifier entity entities. Manage field settings here.';

    $form['updatenotifierentity_settings']['message'] = [
      '#title' => $this->t('Messages'),
      '#type' => 'checkboxes',
      '#options' => array ([
        'message1' => $this->t('Message1'),
        'message2' => $this->t('Message2'),
      ]),
    ];

    return $form;
  }
}