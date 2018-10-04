<?php

namespace Drupal\color_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the color_field box widget.
 *
 * @FieldWidget(
 *   id = "color_field_widget_box",
 *   module = "color_field",
 *   label = @Translation("Color boxes"),
 *   field_types = {
 *     "color_field_type"
 *   }
 * )
 */
class ColorFieldWidgetBox extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'default_colors' => '
#AC725E,#D06B64,#F83A22,#FA573C,#FF7537,#FFAD46
#42D692,#16A765,#7BD148,#B3DC6C,#FBE983
#92E1C0,#9FE1E7,#9FC6E7,#4986E7,#9A9CFF
#B99AFF,#C2C2C2,#CABDBF,#CCA6AC,#F691B2
#CD74E6,#A47AE2',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['default_colors'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Default colors'),
      '#default_value' => $this->getSetting('default_colors'),
      '#required' => TRUE,
      '#description' => $this->t('Default colors for pre-selected color boxes'),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $default_colors = $this->getSetting('default_colors');

    if (!empty($default_colors)) {
      preg_match_all("/#[0-9a-fA-F]{6}/", $default_colors, $default_colors, PREG_SET_ORDER);
      foreach ($default_colors as $color) {
        $colors = $color[0];
        $summary[] = $colors;
      }
    }

    if (empty($summary)) {
      $summary[] = $this->t('No default colors');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // We are nesting some sub-elements inside the parent, so we need a wrapper.
    // We also need to add another #title attribute at the top level for ease in
    // identifying this item in error messages. We do not want to display this
    // title because the actual title display is handled at a higher level by
    // the Field module.
    $element['#theme_wrappers'] = ['color_field_widget_box'];
    $element['#attributes']['class'][] = 'container-inline';
    $box_id = Html::getUniqueId('color-box-' . $this->fieldDefinition->getName());

    $element['#attached']['library'][] = 'color_field/color-field-widget-box';

    // Set Drupal settings.
    $settings[$box_id] = [
      'required' => $this->fieldDefinition->isRequired(),
    ];
    $default_colors = $this->getSetting('default_colors');
    preg_match_all("/#[0-9a-fA-F]{6}/", $default_colors, $default_colors, PREG_SET_ORDER);
    foreach ($default_colors as $color) {
      $settings[$box_id]['palette'][] = $color[0];
    }
    $element['#attached']['drupalSettings']['color_field']['color_field_widget_box']['settings'] = $settings;

    // Retrieve field label and description.
    $element['#title'] = $this->fieldDefinition->getLabel();;
    $element['#description'] = $this->fieldDefinition->getDescription();

    // Prepare color.
    $color = NULL;
    if (isset($items[$delta]->color)) {
      $color = $items[$delta]->color;
      if (substr($color, 0, 1) !== '#') {
        $color = '#' . $color;
      }
    }

    $element['color'] = [
      '#maxlength' => 7,
      '#size' => 7,
      '#type' => 'textfield',
      '#default_value' => $color,
      '#attributes' => ['class' => ['visually-hidden']],
    ];
    $element['color']['#suffix'] = "<div class='color-field-widget-box-form' id='$box_id'></div>";

    if ($this->getFieldSetting('opacity')) {
      $element['opacity'] = [
        '#title' => $this->t('Opacity'),
        '#type' => 'number',
        '#min' => 0,
        '#max' => 1,
        '#step' => 0.01,
        '#default_value' => isset($items[$delta]->opacity) ? $items[$delta]->opacity : NULL,
        '#placeholder' => $this->getSetting('placeholder_opacity'),
      ];
    }

    return $element;
  }

}