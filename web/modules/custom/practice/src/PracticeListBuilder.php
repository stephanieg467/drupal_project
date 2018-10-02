<?php

namespace Drupal\practice;

//use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
//use Drupal\Core\Entity\EntityStorageInterface;
//use Drupal\Core\Entity\EntityTypeInterface;
//use Drupal\Core\Render\RendererInterface;
//use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PracticeListBuilder
 */
class PracticeListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(){
    $header['id'] = $this->t('Linked Entity Id');
    $header['content_entity_label'] = $this->t('Content Entity Label');
    $header['content_entity_id'] = $this->t('Content Entity Id');
    $header['bundle_label'] = $this->t('Config Entity (Bundle) Label');
    $header['bundle_id'] = $this->t('Config Entity (Bundle) Id');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    $row['content_entity_label'] = $entity->getEntityType()->getLabel();
    $row['content_entity_id'] = $entity->getEntityType()->id();
    $row['bundle_label'] = $entity->bundle->entity->label();
    $row['bundle_id'] = $entity->bundle();
    return $row + parent::buildRow($entity);
  }
}
