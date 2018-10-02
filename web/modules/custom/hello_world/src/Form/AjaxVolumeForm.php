<?php

/**
 * @file
 * Contains \Drupal\hello_world\Form\AjaxVolumeForm.
 */

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 *
 * Example of a dynamic form, field inputs change according to shape selected.
 *
 */
class AjaxVolumeForm extends FormBase {

  /**
   * The shape variable holds the value of the selected shape, if it has been
   * selected, or else it's an empty string.
   *
   * @var string
   *
   */
  public $shape;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_world_ajax_volume_form';
  }

  /**
   * {@inheritdoc}
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('hello_world.settings');

    $shape_options = static::getShapeOptions();

    $form['#prefix'] = '<div id="ajax_volume_form">';
    $form['#suffix'] = '</div>';
    // The status messages that will contain any form errors.
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    $form['calculation_type_selector'] = [
      '#type' => 'select',
      '#default_value' => $form_state->getValue('calculation_type_selector'),
      '#options' => $shape_options,
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

    $form['shape_fieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Calculate the volume of...'),
      '#open' => TRUE,
      // Set the ID of this fieldset to shape-fieldset-wrapper so the
      // AJAX command can replace it.
      '#attributes' => ['id' => 'shape-fieldset-wrapper'],
    ];

    // Set value of shape.
    $this->shape = $form_state->getValue('calculation_type_selector');

    if ( !empty($this->shape) ) {
      // Message which states which item from the select list was chosen.
      $form['shape_fieldset']['shape'] = [
        '#markup' =>
          $this->t('<p>Shape: @shape</p>', ['@shape' => $this->shape ]),
      ];

      // Create form according to value of shape selected
      switch ($this->shape) {
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

      // Submit button
      $form['shape_fieldset']['actions']['#type'] = 'actions';
      $form['shape_fieldset']['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t($config->get('submit_message')),
        '#button_type' => 'primary',
        '#attributes' => [
          'class' => [
            'use-ajax',
          ],
        ],
        '#ajax' => [
          'callback' => [$this, 'submitAjax'],
          'event' => 'click',
        ],
      ];

    }
    else {
      // Message which states that nothing has been selected.
      $form['shape_fieldset']['message'] = [
        '#markup' => $this->t($config->get('please_select_message')),
      ];
    }

    // Attach the library for pop-up dialogs/modals.
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;

  }

  /**
   * AJAX callback handler that displays any errors or a success message.
   */
  public function submitAjax(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    // If there are any form errors, AJAX replace the form.
    if ($form_state->hasAnyErrors()) {
      $response->addCommand(new ReplaceCommand('#ajax_volume_form', $form));
    }
    else {
      // Volume calculation will depend on type of shape that has been selected.
      if ($this->shape  == 'Rectangle') {
        $height = $form_state->getValue('height');
        $length = $form_state->getValue('length');
        $width = $form_state->getValue('width');
        $volume = round($height * $length * $width, 2);
        $result = $this->t('The rectangle volume is @volume cm^3', ['@volume' => $volume]);
        $response->addCommand(new OpenModalDialogCommand("Success!", $result, ['width' => '700']));
      }
      elseif ($this->shape  == 'Sphere') {
        $radius = $form_state->getValue('radius');
        $volume = round((4/3) * pi() * pow($radius, 3), 2);
        $result = $this->t('The sphere volume is @volume cm^3', ['@volume' => $volume ]);
        $response->addCommand(new OpenModalDialogCommand("Success!", $result, ['width' => '700']));

      }
      elseif ($this->shape  == 'Cone') {
        $radius = $form_state->getValue('radius');
        $height = $form_state->getValue('height');
        $volume = round(pi() * pow($radius, 2) * ($height/3), 2);
        $result = $this->t('The cone volume is @volume cm^3', ['@volume' => $volume ]);
        $response->addCommand(new OpenModalDialogCommand("Success!", $result, ['width' => '700']));
      }
      elseif ($this->shape  == 'Cylinder') {
        $radius = $form_state->getValue('radius');
        $height = $form_state->getValue('height');
        $volume = round(pi() * pow($radius,2) * $height, 2);
        $result = $this->t('The cylinder volume is @volume cm^3', ['@volume' => $volume ]);
        $response->addCommand(new OpenModalDialogCommand("Success!", $result, ['width' => '700']));
      }

    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Use appropriate validation according to type of shape that has been
    // selected.
    if ($this->shape == 'Rectangle') {

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
    elseif ($this->shape  == 'Sphere') {

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
    elseif ($this->shape  == 'Cone' || $this->shape  == 'Cylinder') {

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

    // Volume calculation will depend on type of shape that has been selected.
    /*
    if ($this->shape  == 'Rectangle') {
      $height = $form_state->getValue('height');
      $length = $form_state->getValue('length');
      $width = $form_state->getValue('width');
      $volume = round($height * $length * $width, 2);
      drupal_set_message($this->t('The rectangle volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($this->shape  == 'Sphere') {
      $radius = $form_state->getValue('radius');
      $volume = round((4/3) * pi() * pow($radius, 3), 2);
      drupal_set_message($this->t('The sphere volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($this->shape  == 'Cone') {
      $radius = $form_state->getValue('radius');
      $height = $form_state->getValue('height');
      $volume = round(pi() * pow($radius, 2) * ($height/3), 2);
      drupal_set_message($this->t('The cone volume is @volume cm', ['@volume' => $volume ]));
    }
    elseif ($this->shape  == 'Cylinder') {
      $radius = $form_state->getValue('radius');
      $height = $form_state->getValue('height');
      $volume = round(pi() * pow($radius,2) * $height, 2);
      drupal_set_message($this->t('The cylinder volume is @volume cm', ['@volume' => $volume ]));
    }
    */
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

  /**
   * Helper function to populate the first dropdown.
   *
   * This would normally be pulling data from the database.
   *
   * @return array
   *   Dropdown options.
   */
  public static function getShapeOptions() {

    $config = \Drupal::config('hello_world.settings');

    return [
      'Rectangle' => $config->get('rectangle_checkbox') == 1 ? 'Rectangle' : '',
      'Sphere' => $config->get('sphere_checkbox') == 1 ? 'Sphere' : '',
      'Cone' => $config->get('cone_checkbox') == 1 ? 'Cone' : '',
      'Cylinder' => $config->get('cylinder_checkbox') == 1 ? 'Cylinder' : '',
    ];
  }

}