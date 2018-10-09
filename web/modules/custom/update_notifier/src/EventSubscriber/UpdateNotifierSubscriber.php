<?php

namespace Drupal\update_notifier\EventSubscriber;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\Renderer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\commerce_product\Event\ProductEvent;
use Drupal\commerce_product\Event\ProductEvents;
use Drupal\update_notifier\Entity\UpdateNotifierEntity;
use Drupal\update_notifier\UpdateNotifierContainerInterface;

/**
 * Sends an email when a product changes.
 */
class UpdateNotifierSubscriber implements EventSubscriberInterface {

  /**
   * The update notifier container service.
   *
   * @var \Drupal\update_notifier\UpdateNotifierContainerInterface
   */
  protected $updateNotifierContainer;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new UpdateNotifierSubscriber object.
   *
   * @param \Drupal\update_notifier\UpdateNotifierContainerInterface $update_notifier_container
   *   The update notifier container.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   The renderer.
   */
  //fix this
  public function __construct(UpdateNotifierContainerInterface $update_notifier_container,LanguageManagerInterface $language_manager, MailManagerInterface $mail_manager, Renderer $renderer) {
    $this->updateNotifierContainer = $update_notifier_container;
    $this->languageManager = $language_manager;
    $this->mailManager = $mail_manager;
    $this->renderer = $renderer;
  }

  /**
   * Send notification email.
   *
   * @param \Drupal\commerce_product\Event\ProductEvent $event
   *    The event we subscribed to.
   */
  public function onProductChange(ProductEvent $event) {

    // The product that has been updated.
    /** @var \Drupal\commerce_product\Entity\ProductInterface $product */
    $product = $event->getProduct();

    // Get all users who are following this product.
    $users_following_product = \Drupal::entityQuery('update_notifier_entity')
      ->condition('product_followed', $product->id())
      ->execute();
    $update_notifier_entities = UpdateNotifierEntity::loadMultiple($users_following_product);

    // Prepare email.
    $system_site_config = \Drupal::config('system.site');
    $site_email = $system_site_config->get('mail');
    $params = [
      'headers' => [
        'Content-Type' => 'text/html; charset=UTF-8;',
        'Content-Transfer-Encoding' => '8Bit',
      ],
      'from' => $site_email,
      'subject' => t('Product @product updated', ['@product' => $product->getTitle()]),
      'product' => $product,
    ];

    // Send email to all users following product.
    foreach ($update_notifier_entities as $update_notifier_entity) {
      $update_notifier_entity_notifications = $this->updateNotifierContainer->getSelectedNotifications($update_notifier_entity->getOwner(), $product);
      $build = [
        '#theme' => 'update_notifier_email_template',
        '#product' => $product,
        '#notifications' => $update_notifier_entity_notifications,
      ];
      $params['body'] = $this->renderer->executeInRenderContext(new RenderContext(), function () use ($build) {
        return $this->renderer->render($build);
      });
      $to = $update_notifier_entity->getOwner()->getEmail();
      $langcode = $update_notifier_entity->getOwner()->getPreferredLangcode();
      $this->mailManager->mail('update_notifier', 'update_notifier_email_notification', $to, $langcode, $params);
    }

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ProductEvents::PRODUCT_UPDATE][] = ['onProductChange'];
    return $events;
  }
}
