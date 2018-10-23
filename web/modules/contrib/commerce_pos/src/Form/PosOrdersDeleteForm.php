<?php

namespace Drupal\commerce_pos\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a form removing commerce_pos orders before uninstallation.
 *
 * @internal
 */
class PosOrdersDeleteForm extends ConfirmFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a PosOrdersDeleteForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pos_orders_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete all Point of Sale order types?');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('This action cannot be undone.<br />Make a backup of your database if you want to be able to restore these items.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete all Point of Sale order types');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return Url::fromRoute('system.modules_uninstall');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);

    $order_entities = $this->entityTypeManager
      ->getStorage('commerce_order')
      ->loadByProperties(['type' => 'pos']);

    $ids = [];
    foreach ($order_entities as $order_entity) {
      $ids[] = $order_entity->id();
    }

    if ($ids) {
      $form['order_entity_ids'] = [
        '#theme' => 'item_list',
        '#items' => $ids,
      ];
    }

    $form['description']['#prefix'] = '<p>';
    $form['description']['#suffix'] = '</p>';
    $form['description']['#weight'] = 5;

    $form['actions']['submit']['#access'] = TRUE;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $batch = [
      'title' => t('Deleting Point of Sale orders'),
      'operations' => [
        [
          [__CLASS__, 'deleteContentEntities'], [],
        ],
      ],
      'finished' => [__CLASS__, 'moduleBatchFinished'],
      'progress_message' => '',
    ];
    xdebug_break();
    batch_set($batch);
  }

  /**
   * Deletes the Point of Sale orders.
   *
   * @param array|\ArrayAccess $context
   *   The batch context array, passed by reference.
   *
   * @internal
   *   This batch callback is only meant to be used by this form.
   */
  public static function deleteContentEntities(&$context) {

    $order_entities = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->loadByProperties(['type' => 'pos']);

    $order_entities_storage = \Drupal::entityTypeManager()
      ->getStorage('commerce_order');

    $order_entities_count = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->getQuery()
      ->condition('type', 'pos')
      ->count()
      ->execute();

    if (!isset($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = $order_entities_count;
    }

    foreach ($order_entities as $order_entity) {
      $order_entity->delete();
    }
    // Sometimes deletes cause secondary deletes. For example, deleting a
    // taxonomy term can cause its children to be be deleted too.
    $context['sandbox']['progress'] = $context['sandbox']['max'] - $order_entities_storage->getStorage('commerce_order')->getQuery()->condition('type', 'pos')->count()->execute();

    // Inform the batch engine that we are not finished and provide an
    // estimation of the completion level we reached.
    if (count($order_entities) > 0 && $context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
      $context['message'] = t('Deleting items... Completed @percentage% (@current of @total).', ['@percentage' => round(100 * $context['sandbox']['progress'] / $context['sandbox']['max']), '@current' => $context['sandbox']['progress'], '@total' => $context['sandbox']['max']]);

    }
    else {
      $context['finished'] = 1;
    }
  }

  /**
   * Implements callback_batch_finished().
   *
   * Finishes the module batch, redirect to the uninstall page and output the
   * successful data deletion message.
   */
  public static function moduleBatchFinished($success, $results, $operations) {

    drupal_set_message(t('All Point of Sale orders have been deleted.'));

    return new RedirectResponse(Url::fromRoute('system.modules_uninstall')->setAbsolute()->toString());
  }

}
