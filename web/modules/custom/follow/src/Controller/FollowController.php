<?php

namespace Drupal\follow\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\follow\Entity\FollowInterface;

/**
 * Class FollowController.
 *
 *  Returns responses for Follow routes.
 */
class FollowController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    ];
  }

  /**
   * Displays a Follow  revision.
   *
   * @param int $follow_revision
   *   The Follow  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($follow_revision) {
    $follow = $this->entityManager()->getStorage('follow')->loadRevision($follow_revision);
    $view_builder = $this->entityManager()->getViewBuilder('follow');

    return $view_builder->view($follow);
  }

  /**
   * Page title callback for a Follow  revision.
   *
   * @param int $follow_revision
   *   The Follow  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($follow_revision) {
    $follow = $this->entityManager()->getStorage('follow')->loadRevision($follow_revision);
    return $this->t('Revision of %title from %date', ['%title' => $follow->label(), '%date' => format_date($follow->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Follow .
   *
   * @param \Drupal\follow\Entity\FollowInterface $follow
   *   A Follow  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FollowInterface $follow) {
    $account = $this->currentUser();
    $langcode = $follow->language()->getId();
    $langname = $follow->language()->getName();
    $languages = $follow->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $follow_storage = $this->entityManager()->getStorage('follow');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $follow->label()]) : $this->t('Revisions for %title', ['%title' => $follow->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all follow revisions") || $account->hasPermission('administer follow entities')));
    $delete_permission = (($account->hasPermission("delete all follow revisions") || $account->hasPermission('administer follow entities')));

    $rows = [];

    $vids = $follow_storage->revisionIds($follow);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\follow\FollowInterface $revision */
      $revision = $follow_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $follow->getRevisionId()) {
          $link = $this->l($date, new Url('entity.follow.revision', ['follow' => $follow->id(), 'follow_revision' => $vid]));
        }
        else {
          $link = $follow->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.follow.translation_revert', ['follow' => $follow->id(), 'follow_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.follow.revision_revert', ['follow' => $follow->id(), 'follow_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.follow.revision_delete', ['follow' => $follow->id(), 'follow_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['follow_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
