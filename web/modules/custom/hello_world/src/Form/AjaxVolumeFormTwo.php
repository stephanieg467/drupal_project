<?php

/**
 * @file
 * Contains \Drupal\hello_world\Form\AjaxVolumeForm.
 */

namespace Drupal\hello_world\Form;

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
    return 'hello_world_ajax_volume_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['calculation_type_selector'] = [
      '#type' => 'select',
      '#default_value' => $form_state->getValue('calculation_type_selector'),
      '#options' => [
        'Choose shape' => $this
          ->t('Choose shape'),
        'Rectangle' => $this
          ->t('Rectangle'),
        'Sphere' => $this
          ->t('Sphere'),
        'Cone' => $this
          ->t('Cone'),
        'Cylinder' => $this
          ->t('Cylinder'),
      ],
      '#title' => $this->t('Shape'),
      '#required' => TRUE,
      '#ajax' => [
        'wrapper' => 'shape-fieldset-wrapper',
        'callback' => '::promptCallback',
        'event' => 'change',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Selecting shape...'),
        ),
      ],
    ];

    // This fieldset just serves as a container for the part of the form
    // that gets rebuilt. It has a nice line around it so you can see it.
    $form['shape_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Calculate the volume of...'),
      '#open' => TRUE,
      // We set the ID of this fieldset to questions-fieldset-wrapper so the
      // AJAX command can replace it.
      '#attributes' => ['id' => 'shape-fieldset-wrapper'],
    ];

    $shape = $form_state->getValue('calculation_type_selector');

    if (!empty($shape) && $shape !== 'Choose shape') {
      // Message which states which item from the select list was chosen.
      $form['shape_fieldset']['shape'] = [
        '#markup' =>
          $this->t('<p>Shape: @shape</p>', ['@shape' => $shape ]),
      ];

      switch ($shape) {
        case 'Rectangle':
          $form['shape_fieldset']['height'] = [
            '#type' => 'number',
            '#title' => $this->t('Height'),
            '#default_value' => $form_state->getValue('height'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          $form['shape_fieldset']['length'] = [
            '#type' => 'number',
            '#title' => $this->t('Length'),
            '#default_value' => $form_state->getValue('length'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          $form['shape_fieldset']['width'] = [
            '#type' => 'number',
            '#title' => $this->t('Width'),
            '#default_value' => $form_state->getValue('width'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          break;

        case 'Sphere':
          $form['shape_fieldset']['radius'] = [
            '#type' => 'number',
            '#title' => $this->t('Radius'),
            '#default_value' => $form_state->getValue('radius'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          break;

        case 'Cone':
          $form['shape_fieldset']['radius'] = [
            '#type' => 'number',
            '#title' => $this->t('Radius'),
            '#default_value' => $form_state->getValue('radius'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          $form['shape_fieldset']['height'] = [
            '#type' => 'number',
            '#title' => $this->t('Height'),
            '#default_value' => $form_state->getValue('height'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          break;

        case 'Cylinder':
          $form['shape_fieldset']['radius'] = [
            '#type' => 'number',
            '#title' => $this->t('Radius'),
            '#default_value' => $form_state->getValue('radius'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          $form['shape_fieldset']['height'] = [
            '#type' => 'number',
            '#title' => $this->t('Height'),
            '#default_value' => $form_state->getValue('height'),
            '#required' => TRUE,
            '#field_suffix' => t('cm'),
          ];
          break;
      }

      $form['shape_fieldset']['actions']['#type'] = 'actions';
      $form['shape_fieldset']['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Calculate'),
        '#button_type' => 'primary',
      ];

    }
    else {
      // Message which states that nothing has been selected.
      $form['shape_fieldset']['message'] = [
        '#markup' => $this->t('Please choose a shape.'),
      ];
    }

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $shape = $form_state->getValue('calculation_type_selector');

    if ($shape == 'Rectangle') {
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
    elseif ($shape == 'Sphere') {
      //Make sure that radius is non-zero
      if (empty($form_state->getValue('radius')) || $form_state->getValue('radius') == 0) {
        $form_state->setErrorByName('radius', $this->t('The value of radius cannot be zero.'));
      }
      //Make sure that radius is greater than zero
      if ($form_state->getValue('radius') < 0 ) {
        $form_state->setErrorByName('radius', $this->t('The value of radius must be greater than zero.'));
      }
      //Make sure that radius is numeric
      if (!is_numeric($form_state->getValue('radius'))) {
        $form_state->setErrorByName('radius', $this->t('The value of radius is not a number.'));
      }
    }
    // Cone and Cylinder require height and radius for the volume equation, so can combine their validation code.
    elseif ($shape == 'Cone' || $shape == 'Cylinder') {
      //Make sure that values are non-zero
      if (empty($form_state->getValue('height')) || $form_state->getValue('height') == 0) {
        $form_state->setErrorByName('height', $this->t('The value of height cannot be zero.'));
      }
      if (empty($form_state->getValue('radius')) || $form_state->getValue('radius') == 0) {
        $form_state->setErrorByName('radius', $this->t('The value of radius cannot be zero.'));
      }
      //Make sure values are greater than zero
      if ($form_state->getValue('height') < 0 ) {
        $form_state->setErrorByName('height', $this->t('The value of height must be greater than zero.'));
      }
      if ($form_state->getValue('radius') < 0 ) {
        $form_state->setErrorByName('radius', $this->t('The value of radius must be greater than zero.'));
      }
      //Make sure values are numeric
      if (!is_numeric($form_state->getValue('height'))) {
        $form_state->setErrorByName('height', $this->t('The value of height is not a number.'));
      }
      if (!is_numeric($form_state->getValue('radius'))) {
        $form_state->setErrorByName('radius', $this->t('The value of radius is not a number.'));
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $shape = $form_state->getValue('calculation_type_selector');

    if ($shape == 'Rectangle') {
      $volume = round($form_state->getValue('height')*$form_state->getValue('length')*$form_state->getValue('width'), 2);
      drupal_set_message($this->t('The rectangle volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($shape == 'Sphere') {
      $volume = round((4/3)*pi()*pow($form_state->getValue('radius'), 3), 2);
      drupal_set_message($this->t('The sphere volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($shape == 'Cone') {
      $volume = round(pi()*pow($form_state->getValue('radius'), 2)*($form_state->getValue('height')/3), 2);
      drupal_set_message($this->t('The cone volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($shape == 'Cylinder') {
      $volume = round(pi()*pow($form_state->getValue('radius'),2)*$form_state->getValue('height'), 2);
      drupal_set_message($this->t('The cylinder volume is @volume cm', ['@volume' => $volume ]));
    }
  }

  /**
   * Callback for the select element.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function promptCallback(array $form, FormStateInterface $form_state) {
    return $form['shape_fieldset'];
  }

}