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
    return 'volume_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['height'] = [
      '#type' => 'number',
      '#title' => $this->t('Height'),
    ];
    $form['length'] = [
      '#type' => 'number',
      '#title' => $this->t('Length'),
    ];
    $form['width'] = [
      '#type' => 'number',
      '#title' => $this->t('Width'),
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

    if (empty($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('The value of height cannot be zero.'));
    }
    if (empty($form_state->getValue('length'))) {
      $form_state->setErrorByName('length', $this->t('The value of length cannot be zero.'));
    }
    if (empty($form_state->getValue('width'))) {
      $form_state->setErrorByName('width', $this->t('The value of width cannot be zero.'));
    }

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
    drupal_set_message($this->t('The rectangle volume is @volume', ['@volume' => $form_state->getValue('height')*$form_state->getValue('length')*$form_state->getValue('width') ]));
  }

}