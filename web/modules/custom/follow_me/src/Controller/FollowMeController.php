<?php

namespace Drupal\follow_me\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\follow_me\Entity\FollowMeInterface;

/**
 * Class FollowMeController.
 *
 *  Returns responses for Follow me routes.
 */
class FollowMeController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Follow me  revision.
   *
   * @param int $follow_me_revision
   *   The Follow me  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($follow_me_revision) {
    $follow_me = $this->entityManager()->getStorage('follow_me')->loadRevision($follow_me_revision);
    $view_builder = $this->entityManager()->getViewBuilder('follow_me');

    return $view_builder->view($follow_me);
  }

  /**
   * Page title callback for a Follow me  revision.
   *
   * @param int $follow_me_revision
   *   The Follow me  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($follow_me_revision) {
    $follow_me = $this->entityManager()->getStorage('follow_me')->loadRevision($follow_me_revision);
    return $this->t('Revision of %title from %date', ['%title' => $follow_me->label(), '%date' => format_date($follow_me->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Follow me .
   *
   * @param \Drupal\follow_me\Entity\FollowMeInterface $follow_me
   *   A Follow me  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FollowMeInterface $follow_me) {
    $account = $this->currentUser();
    $langcode = $follow_me->language()->getId();
    $langname = $follow_me->language()->getName();
    $languages = $follow_me->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $follow_me_storage = $this->entityManager()->getStorage('follow_me');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $follow_me->label()]) : $this->t('Revisions for %title', ['%title' => $follow_me->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all follow me revisions") || $account->hasPermission('administer follow me entities')));
    $delete_permission = (($account->hasPermission("delete all follow me revisions") || $account->hasPermission('administer follow me entities')));

    $rows = [];

    $vids = $follow_me_storage->revisionIds($follow_me);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\follow_me\FollowMeInterface $revision */
      $revision = $follow_me_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $follow_me->getRevisionId()) {
          $link = $this->l($date, new Url('entity.follow_me.revision', ['follow_me' => $follow_me->id(), 'follow_me_revision' => $vid]));
        }
        else {
          $link = $follow_me->link($date);
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
              Url::fromRoute('entity.follow_me.translation_revert', ['follow_me' => $follow_me->id(), 'follow_me_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.follow_me.revision_revert', ['follow_me' => $follow_me->id(), 'follow_me_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.follow_me.revision_delete', ['follow_me' => $follow_me->id(), 'follow_me_revision' => $vid]),
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

    $build['follow_me_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
