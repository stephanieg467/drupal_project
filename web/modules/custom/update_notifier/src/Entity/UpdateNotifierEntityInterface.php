<?php

namespace Drupal\update_notifier\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\commerce_product\Entity\Product;

/**
 * Provides an interface for defining Update notifier entity entities.
 *
 * @ingroup update_notifier
 */
interface UpdateNotifierEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Stop following the product.
   */
  public function unfollow();

  /**
   * Gets the product followed.
   *
   * @return \Drupal\commerce_product\Entity\Product
   *   The product entity
   */
  public function getProductFollowed();
  /**
   * Sets the product followed.
   *
   * @param \Drupal\commerce_product\Entity\Product $product_followed
   *   The product followed.
   *
   * @return $this
   */
  public function setProductFollowed($product_followed);

  /**
   * Gets notify__price_change.
   *
   * @return bool
   *   TRUE if the user wants price change notifications.
   */
  public function getNotifyPriceChange();

  /**
   * Sets notify__price_change.
   *
   * @param bool $notify__price_change
   *   Whether the user wants price change notifications.
   *   TRUE to get notified if product price changes, FALSE to not get notified if product price changes.
   *
   *   @return $this
   */
  public function setNotifyPriceChange($notify__price_change);

  /**
 * Gets notify__on_sale.
 *
 * @return bool
 *   TRUE if the user wants on sale notifications.
 */
  public function getNotifyOnSale();

  /**
   * Sets notify__on_sale.
   *
   * @param bool $notify__on_sale
   *   Whether the user wants on sale notifications.
   *   TRUE to get notified if the product is on sale, FALSE to not get notified if the product is on sale.
   *
   *   @return $this
   */
  public function setNotifyOnSale($notify__on_sale);

  /**
   * Gets notify__promotion.
   *
   * @return bool
   *   TRUE if the user wants product promotion notifications.
   */
  public function getNotifyPromotion();

  /**
   * Sets notify__promotion.
   *
   * @param bool $notify__promotion
   *   Whether the user wants product promotion notifications.
   *   TRUE to get notified if the product has a promotion, FALSE to not get notified if the product has a promotion.
   *
   *   @return $this
   */
  public function setNotifyPromotion($notify__promotion);

  /**
   * Gets notify__in_stock.
   *
   * @return bool
   *   TRUE if the user wants to get notified if product is in stock.
   */
  public function getNotifyInStock();

  /**
   * Sets notify__in_stock.
   *
   * @param bool $notify__in_stock
   *   Whether the user wants to get notified if product is in stock.
   *   TRUE to get notified if the product is in stock, FALSE to not get notified if the product is in stock.
   *
   *   @return $this
   */
  public function setNotifyInStock($notify__in_stock);

  /**
   * Gets the Update notifier entity name.
   *
   * @return string
   *   Name of the Update notifier entity.
   */
  public function getName();
  /**
   * Sets the Update notifier entity name.
   *
   * @param string $name
   *   The Update notifier entity name.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setName($name);

  /**
   * Gets the Update notifier entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Update notifier entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Update notifier entity creation timestamp.
   *
   * @param int $timestamp
   *   The Update notifier entity creation timestamp.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Update notifier entity published status indicator.
   *
   * Unpublished Update notifier entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Update notifier entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Update notifier entity.
   *
   * @param bool $published
   *   TRUE to set this Update notifier entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\update_notifier\Entity\UpdateNotifierEntityInterface
   *   The called Update notifier entity entity.
   */
  public function setPublished($published);

}
