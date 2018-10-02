<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\update_notifier\UpdateNotifierContainerInterface;


/**
 * Class UpdateNotifierFollow.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierFollow extends FormBase {

  /**
   * The update notifier container service.
   *
   * @var \Drupal\update_notifier\UpdateNotifierContainerInterface
   */
  protected $updateNotifierContainer;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * The product being followed.
   *
   * @var \Drupal\commerce_product\Entity\Product
   */
  protected $product;

  /**
   * Constructs a new UpdateNotifierFollow object.
   *
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *   The current route match.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(CurrentRouteMatch $current_route_match, UpdateNotifierContainerInterface $update_notifier_container, AccountInterface $user) {
    $this->product = $current_route_match->getParameter('product');
    $this->updateNotifierContainer = $update_notifier_container;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('update_notifier.update_notifier_container'),
      $container->get('current_user')
    );

  }

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
  public function buildForm(array $form, FormStateInterface $form_state) {

    $product_title = $this->product->getTitle();

    $form['#prefix'] = '<div id="follow_form">';
    $form['#suffix'] = '</div>';

    $form['greeting'] = [
      '#markup' => $this->t("Hello, @user", ['@user' => $this->user->getDisplayName()]),
    ];

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t("Choose the type(s) of notification(s) you would like to receive for %product_title.", ['%product_title' => $product_title]),
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

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    xdebug_break();

    $product_followed = $this->product;
    $notifications = $form_state->getValue('notification_type');
    $update_notifier_container = $this->updateNotifierContainer;
    $update_notifier_container->follow($this->user, $product_followed, $notifications);
    drupal_set_message($this->t('You chose to be notified for the following: %notifications', ['%notifications' => $notifications ]));
  }

}
