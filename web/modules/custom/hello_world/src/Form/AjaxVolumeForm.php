<?php

/**
 * @file
 * Contains \Drupal\hello_world\Form\AjaxVolumeForm.
 */

namespace Drupal\hello_world\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form.
 */
class AjaxVolumeForm extends FormBase {

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

    // Determine whether shape has been selected.
    if ($form_state->getValue('calculation_type_selector')) {

      // If shape has been selected, determine what shape type was chosen.
      // If rectangle was choosen, volume formula requires: height, length and width.
      if ($form_state->getValue('calculation_type_selector') == 'Rectangle') {
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
      }
      // If sphere was chosen, volume formula requires radius.
      elseif ($form_state->getValue('calculation_type_selector') == 'Sphere') {
        $form['radius'] = [
          '#type' => 'number',
          '#title' => $this->t('Radius'),
          '#default_value' => $form_state->getValue('radius'),
          '#required' => TRUE,
          '#field_suffix' => t('cm'),
        ];
      }
      // If cone was chosen, volume formula requires radius and height.
      elseif ($form_state->getValue('calculation_type_selector') == 'Cone') {
        $form['radius'] = [
          '#type' => 'number',
          '#title' => $this->t('Radius'),
          '#default_value' => $form_state->getValue('radius'),
          '#required' => TRUE,
          '#field_suffix' => t('cm'),
        ];
        $form['height'] = [
          '#type' => 'number',
          '#title' => $this->t('Height'),
          '#default_value' => $form_state->getValue('height'),
          '#required' => TRUE,
          '#field_suffix' => t('cm'),
        ];
      }
      // If cylinder was chosen, volume formula requires radius and height.
      elseif ($form_state->getValue('calculation_type_selector') == 'Cylinder') {
        $form['radius'] = [
          '#type' => 'number',
          '#title' => $this->t('Radius'),
          '#default_value' => $form_state->getValue('radius'),
          '#required' => TRUE,
          '#field_suffix' => t('cm'),
        ];
        $form['height'] = [
          '#type' => 'number',
          '#title' => $this->t('Height'),
          '#default_value' => $form_state->getValue('height'),
          '#required' => TRUE,
          '#field_suffix' => t('cm'),
        ];
      }

      return $form;

    }

    else {

      // Display message asking user to select type of shape.
      $form['calculation_type_selector'] = [
        '#markup' => t('Please select a shape.'),
        '#type' => 'select',
        '#default_value' => $form_state->getValue('calculation_type_selector'),
        '#options' => [
          'rectangle' => $this
            ->t('Rectangle'),
          'sphere' => $this
           ->t('Sphere'),
          'cone' => $this
            ->t('Cone'),
          'cylinder' => $this
            ->t('Cylinder'),
        ],
        '#title' => $this->t('Shape'),
        '#required' => TRUE,
        '#ajax' => [
          'callback' => array($this, 'validateEmailAjax'),
          'event' => 'change',
          'progress' => array(
            'type' => 'throbber',
            'message' => t('Verifying email...'),
          ),
        ],
      ];

    }

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