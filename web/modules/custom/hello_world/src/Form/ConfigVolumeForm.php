<?php
namespace Drupal\hello_world\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
/**
 * Configure example settings for this site.
 */
class ConfigVolumeForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_world_config_volume_form';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'hello_world.settings',
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('hello_world.settings');
    $form['please_select_message'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Please Select Message'),
      '#description' => $this->t('Set a message for when a user has not selected the type of calculation they want to perform'),
      '#default_value' => $config->get('please_select_message'),
    );
    $form['rectangle_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Rectangle'),
      '#description' => $this->t('Form to calculate volume of a rectangle'),
      '#default_value' => $config->get('rectangle_checkbox'),
    );
    $form['sphere_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Sphere'),
      '#description' => $this->t('Form to calculate volume of a sphere'),
      '#default_value' => $config->get('sphere_checkbox'),
    );
    $form['cone_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Cone'),
      '#description' => $this->t('Form to calculate volume of a cone'),
      '#default_value' => $config->get('cone_checkbox'),
    );
    $form['cylinder_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Cylinder'),
      '#description' => $this->t('Form to calculate volume of a cylinder'),
      '#default_value' => $config->get('cylinder_checkbox'),
    );
    $form['submit_message'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Submit Message'),
      '#description' => $this->t('Set the message on the "submit" button'),
      '#default_value' => $config->get('submit_message'),
    );
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hello_world.settings')
      ->set('please_select_message', $form_state->getValue('please_select_message'))
      ->set('rectangle_checkbox', $form_state->getValue('rectangle_checkbox'))
      ->set('sphere_checkbox', $form_state->getValue('sphere_checkbox'))
      ->set('cone_checkbox', $form_state->getValue('cone_checkbox'))
      ->set('cylinder_checkbox', $form_state->getValue('cylinder_checkbox'))
      ->set('submit_message', $form_state->getValue('submit_message'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
