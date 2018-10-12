<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\update_notifier\UpdateNotifierContainerInterface;
use Drupal\update_notifier\Entity\UpdateNotifierEntity;

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

    $notifications = $this->updateNotifierContainer->getSelectedNotifications($this->user, $this->product);

    $form['#attached']['library'][] = 'update_notifier/update_notifier.styling';

    $form['#prefix'] = '<div id="unfollow_form">';
    $form['#suffix'] = '</div>';

    $form['title'] = [
      '#type' => 'item',
      '#title' => $this->t("Unfollow %product_title", ['%product_title' => $product_title]),
    ];

    $form['notifications'] = [
      '#type' => 'item',
      '#markup' => $this->t(
        "You are currently registered to be notified about the following changes for %product_title :",
        ['%product_title' => $product_title]),
    ];

    foreach($notifications as $notification) {
      $form['notifications'.$notification] = [
        '#type' => 'item',
        '#markup' => $this->t(
          "- %notification",
          ['%notification' => str_replace('_', ' ', $notification)]),
      ];
    }

    $form['edit_update_notifier_entity'] = [
      '#type' => 'item',
      '#markup' => $this->t(
        "If you would like to edit the types of notifications you have chosen, then click the 
          'Edit Notifications' button below."),
    ];

    $form['edit_notifications'] = [
      '#type' => 'submit',
      '#value' => $this->t('Edit Notifications'),
      '#submit' => ['::editNotificationsRedirect'],
    ];

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t(
        "By selecting the checkbox below you are confirming that you no longer wish to be notified
         about any of these changes to %product_title.",
        ['%product_title' => $product_title]),
    ];

    $form['confirm_unfollow'] = [
      '#type' => 'checkbox',
      '#description' => $this->t(
        'Select to stop following %product_title',
        ['%product_title' => $product_title])
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

    $confirm_unfollow = $form_state->getValue('confirm_unfollow');

    $update_notifier_container = $this->updateNotifierContainer;

    if($confirm_unfollow)
      $unfollow_success = $update_notifier_container->unfollow($this->user, $product_followed);

    if($unfollow_success) {
      $this->messenger()->addMessage($this->t('You are no longer following %product',
        ['%product' => $this->product->getTitle()]));
      $form_state->setRedirect('entity.user.canonical', ['user' => $this->user->id()]);
    }
    else {
      $this->messenger()->addError($this->t('You must select the checkbox in order to confirm you
        no longer wish to follow %product.',
        ['%product' => $this->product->getTitle()]));
      $form_state->setRedirect('update_notifier.unfollow_link',
        ['user' => $this->user->id(), 'product' => $this->product->id()]);
    }

  }

  public function editNotificationsRedirect(&$form, FormStateInterface $form_state) {

    $update_notifier_id = \Drupal::entityQuery('update_notifier_entity')
      ->condition('user_id', $this->user->id())
      ->condition('product_followed', $this->product->id())
      ->execute();
    $updateNotifierEntity = UpdateNotifierEntity::load(reset($update_notifier_id));
    $form_state->setRedirect('entity.update_notifier_entity.edit_form',
      ['update_notifier_entity' => $updateNotifierEntity->id()]);

  }

}
