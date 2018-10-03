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

    $notifications = $form_state->getValue('notification_type');

    $notify__price_change = $notifications['price_change'];
    if($notify__price_change === 'price_change')
      $notify__price_change = 1;
    else
      $notify__price_change = 0;

    $notify__on_sale = $notifications['on_sale'];
    if($notify__on_sale === 'on_sale')
      $notify__on_sale = 1;
    else
      $notify__on_sale = 0;

    $notify__promotion = $notifications['promotion'];
    if($notify__promotion === 'promotion')
      $notify__promotion = 1;
    else
      $notify__promotion = 0;

    $notify__in_stock = $notifications['in_stock'];
    if($notify__in_stock === 'in_stock')
      $notify__in_stock = 1;
    else
      $notify__in_stock = 0;

    $product_followed = $this->product;
    $update_notifier_container = $this->updateNotifierContainer;
    //xdebug_break();
    $update_notifier_container->follow($this->user, $product_followed, $notify__price_change, $notify__on_sale, $notify__promotion, $notify__in_stock);

    if($notify__price_change === 1)
      $this->messenger()->addMessage($this->t('The will be notified if %product has a price change.', ['%product' => $this->product->getTitle()]));
    if($notify__on_sale === 1)
      $this->messenger()->addMessage($this->t('The will be notified if %product is on sale.', ['%product' => $this->product->getTitle()]));
    if($notify__promotion === 1)
      $this->messenger()->addMessage($this->t('The will be notified if %product has a promotion.', ['%product' => $this->product->getTitle()]));
    if($notify__in_stock === 1)
      $this->messenger()->addMessage($this->t('The will be notified if %product is in stock.', ['%product' => $this->product->getTitle()]));

    $form_state->setRedirect('entity.user.canonical', ['user' => $this->user->id()]);

  }

}
