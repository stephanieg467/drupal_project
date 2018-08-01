<?php

/**
 * @file
 * Contains \Drupal\hello_world\Form\VolumeForm.
 */

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class VolumeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_world_volume_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['height'] = [
      '#type' => 'number',
      '#title' => $this->t('Height'),
      '#default_value' => $form_state->getValue('height'),
      '#required' => TRUE,
      '#field_suffix' => t('cm'),
    ];
    $form['length'] = [
      '#type' => 'number',
      '#title' => $this->t('Length'),
      '#default_value' => $form_state->getValue('length'),
      '#required' => TRUE,
      '#field_suffix' => t('cm'),
    ];
    $form['width'] = [
      '#type' => 'number',
      '#title' => $this->t('Width'),
      '#default_value' => $form_state->getValue('width'),
      '#required' => TRUE,
      '#field_suffix' => t('cm'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calculate'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    //Make sure that values are non-zero
    if (empty($form_state->getValue('height')) || $form_state->getValue('height') == 0) {
      $form_state->setErrorByName('height', $this->t('The value of height cannot be zero.'));
    }
    if (empty($form_state->getValue('length')) || $form_state->getValue('length') == 0) {
      $form_state->setErrorByName('length', $this->t('The value of length cannot be zero.'));
    }
    if (empty($form_state->getValue('width')) || $form_state->getValue('width') == 0) {
      $form_state->setErrorByName('width', $this->t('The value of width cannot be zero.'));
    }

    //Make sure values are greater than zero
    if ($form_state->getValue('height') < 0 ) {
      $form_state->setErrorByName('height', $this->t('The value of height must be greater than zero.'));
    }
    if ($form_state->getValue('length') < 0 ) {
      $form_state->setErrorByName('length', $this->t('The value of length must be greater than zero.'));
    }
    if ($form_state->getValue('width') < 0) {
      $form_state->setErrorByName('width', $this->t('The value of width must be greater than zero.'));
    }

    //Make sure values are numeric
    if (!is_numeric($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('The value of height is not a number.'));
    }
    if (!is_numeric($form_state->getValue('width'))) {
      $form_state->setErrorByName('width', $this->t('The value of width is not a number.'));
    }
    if (!is_numeric($form_state->getValue('length'))) {
      $form_state->setErrorByName('length', $this->t('The value of length is not a number.'));
    }


  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $volume = $form_state->getValue('height')*$form_state->getValue('length')*$form_state->getValue('width');
    drupal_set_message($this->t('The rectangle volume is @volume cm', ['@volume' => $volume ]));
  }

}