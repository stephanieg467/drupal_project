<?php

namespace Drupal\update_notifier\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\update_notifier\UpdateNotifierContainerInterface;
//use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class UpdateNotifierFollow2.
 *
 * @ingroup update_notifier
 */
class UpdateNotifierFollow2 extends ContentEntityForm {

  /**
   * The product being followed.
   *
   * @var \Drupal\commerce_product\Entity\Product
   */
  protected $product;

  /**
   * Constructs a new UpdateNotifierFollow2 object.
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
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    xdebug_break();
    $entity = $this->entity;
    $status = parent::save($form, $form_state);
    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Update notifier entity.', [
          '%label' => $entity->label(),
        ]));
        break;
      default:
        drupal_set_message($this->t('Saved the %label Update notifier entity.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.update_notifier_entity.canonical', ['update_notifier_entity' => $entity->id()]);
  }

}
