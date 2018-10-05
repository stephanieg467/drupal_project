<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\update_notifier\UpdateNotifierContainerInterface;


/**
 * Class UpdateNotifierEdit.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierEdit extends FormBase {

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
    return 'update_notifier_edit';
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

    $form['#prefix'] = '<div id="update_notifier_edit_form">';
    $form['#suffix'] = '</div>';

    $form['greeting'] = [
      '#markup' => $this->t("Hello, @user", ['@user' => $this->user->getDisplayName()]),
    ];

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t(
        "Please confirm that you would no longer like to follow %product_title.",
        ['%product_title' => $product_title]),
    ];

    $form['confirm_unfollow'] = [
      '#type' => 'checkbox',
      '#description' => $this->t('Select to stop following %product_title', ['%product_title' => $product_title])
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
      $this->messenger()->addMessage($this->t('You are no longer following %product', ['%product' => $this->product->getTitle()]));

      $form_state->setRedirect('entity.user.canonical', ['user' => $this->user->id()]);
    }
    else {
      $this->messenger()->addMessage($this->t('Something went wrong, please try again later.'));

      $form_state->setRedirect('entity.user.canonical', ['user' => $this->user->id()]);
    }


  }

}
