<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\update_notifier\UpdateNotifierContainerInterface;
use Drupal\Core\Render\Element\Checkboxes;


/**
* Class UpdateNotifierUnfollow.
*
* @ingroup update_notifier
*/
class UpdateNotifierUnfollow extends FormBase {

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
  * The product to be unfollowed.
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
    return 'update_notifier_unfollow';
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

    $form['#prefix'] = '<div id="unfollow_form">';
    $form['#suffix'] = '</div>';

    $form['greeting'] = [
      '#markup' => $this->t("Hello, @user", ['@user' => $this->user->getDisplayName()]),
    ];

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t(
        "Please deselect the types of notifications you no longer wish to be notified 
               about for %product_title. Deselect all to completely unfollow %product_title",
               ['%product_title' => $product_title]),
    ];

    $update_notifier_container = $this->updateNotifierContainer;
    $notifications = $update_notifier_container->getSelectedNotifications($this->user, $this->product);

    $form['notification_type'] = [
      '#type' => 'checkboxes',
      '#options' => array('price_change' => $this->t('Price Change'), 'on_sale' => $this->t('On Sale'), 'promotion' => $this->t('Promotion'), 'in_stock' => $this->t('In Stock')),
      '#title' => $this->t('Types of Notifications'),
      '#default_value' => $notifications,
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Unfollow Product'),
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
    $product_followed = $this->product;
    $notifications = $form_state->getValue('notification_type');
    $checked_notifications = Checkboxes::getCheckedCheckboxes($notifications);

    $update_notifier_container = $this->updateNotifierContainer;
    $update_notifier_container->unfollow($this->user, $product_followed, $checked_notifications);

    $this->messenger()->addMessage($this->t('Your notifications for %product have been changed.', ['%product' => $this->product->getTitle()]));

    $form_state->setRedirect('entity.user.canonical', ['user' => $this->user->id()]);

  }

}
