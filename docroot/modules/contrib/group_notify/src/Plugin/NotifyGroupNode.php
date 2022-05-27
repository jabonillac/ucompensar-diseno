<?php

namespace Drupal\group_notify\Plugin;

use Drupal\gnode\Plugin\GroupContentEnabler\GroupNode;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\group\Entity\GroupContentInterface;

/**
 * A Group content plugin that enables sending notification e-mails.
 */
class NotifyGroupNode extends GroupNode implements ContainerFactoryPluginInterface {

  /**
   * Email recipients.
   *
   * @var array
   */
  protected $recipients = [];

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The lanugage manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MailManagerInterface $mail_manager,
    ModuleHandlerInterface $module_handler,
    LanguageManagerInterface $language_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->mailManager = $mail_manager;
    $this->moduleHandler = $module_handler;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.mail'),
      $container->get('module_handler'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['notify'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Notify group members'),
      '#description' => $this->t('Send an email when group content is added.'),
      '#default_value' => $this->configuration['notify'] ?? FALSE,
    ];

    $form['options'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Notification options'),
      '#states' => ['visible' => [':input[name="notify"]' => ['checked' => TRUE]]],
    ];

    $form['options']['notify_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Notification mode'),
      '#default_value' => $this->configuration['notify_mode'] ?? 'enforce',
      '#options' => [
        'enforce' => $this->t('Enforced: Always send notification when a group node is created.'),
        'toggle' => $this->t('Toggled: Optionally send notification when a group node is created.'),
      ],
    ];

    $form['options']['notify_who'] = [
      '#title' => $this->t('Who to notify?'),
      '#type' => 'radios',
      '#default_value' => $this->configuration['notify_who'] ?? 'all',
      '#options' => [
        'all' => $this->t('Email all group members'),
        'role' => $this->t('Email group members with permission'),
      ],
    ];

    $form['options']['notify_subject_insert'] = [
      '#title' => $this->t('E-mail subject for new posts'),
      '#type' => 'textfield',
      '#default_value' => $this->getEmailSubjectConfiguration(FALSE),
      '#description' => $this->t('The e-mail subject when a new group node is created. This field supports tokens, including both [node] and [group].'),
    ];

    $form['options']['notify_subject_update'] = [
      '#title' => $this->t('E-mail subject for updated posts'),
      '#type' => 'textfield',
      '#default_value' => $this->getEmailSubjectConfiguration(TRUE),
      '#description' => $this->t('The e-mail subject when an existing group node is updated. This field supports tokens, including both [node] and [group].'),
    ];

    if ($this->moduleHandler->moduleExists('comment')) {
      $form['options']['comments'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable comment notifications'),
        '#description' => $this->t('Notify members of a group when a new comment is posted to a group node.'),
        '#default_value' => $this->configuration['comments'] ?? FALSE,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupContentPermissions() {
    $permissions = parent::getGroupContentPermissions();

    if ($this->configuration['notify_who'] === 'role') {
      $permissions['allow email notifications']['title'] = 'Group Notify: Receive email notifications';
    }

    return $permissions;
  }

  /**
   * Send notification to all recipients.
   *
   * @param array $params
   *   An array of mail parameters.
   */
  public function sendNotification(array $params) {
    foreach ($this->recipients as $recipient) {
      $result = $this->mailManager->mail(
        'group_notify',
        $params['mail_key'],
        $recipient['email'],
        $recipient['langcode'] ?: $this
          ->languageManager
          ->getCurrentLanguage()
          ->getId(),
        $params
      );

      // @todo Do something with $result['result'] perhaps?
    }
  }

  /**
   * Returns the list of recipients.
   *
   * @return array
   *   The recipients.
   */
  public function getRecipients() {
    return $this->recipients;
  }

  /**
   * Are there any recipients?
   *
   * @return bool
   *   TRUE if there are recipients, otherwise FALSE.
   */
  public function hasRecipients() {
    return count($this->recipients) > 0;
  }

  /**
   * Add a recipient for notifcation.
   *
   * @param array $recipient
   *   Array needs to include email address and langcode.
   *
   * @return $this
   */
  public function addRecipient(array $recipient) {
    $this->recipients[] = $recipient;
    return $this;
  }

  /**
   * Are comment notifcations enabled?
   *
   * @return bool
   *   TRUE if comment notifications are enabled, otherwise FALSE.
   */
  public function isNotifyCommentEnabled() {
    $config = $this->getConfiguration();
    return $config['comments'] ?? FALSE;
  }

  /**
   * Are notifications toggled?
   *
   * @return bool
   *   TRUE if notifications are toggled, otherwise FALSE.
   */
  public function isNotifyToggled() {
    $config = $this->getConfiguration();
    return $config['notify_mode'] === 'toggle';
  }

  /**
   * Are notifications enforced?
   *
   * @return bool
   *   TRUE if notifications are enforced, otherwise FALSE.
   */
  public function isNotifyEnforced() {
    $config = $this->getConfiguration();
    return $config['notify_mode'] === 'enforce';
  }

  /**
   * Are notifications enabled?
   *
   * @return bool
   *   TRUE if notifications are enforced, otherwise FALSE.
   */
  public function isNotifyEnabled() {
    $config = $this->getConfiguration();
    return $config['notify'] ?? FALSE;
  }

  /**
   * Returns the e-mail subject configuration for a given notification.
   *
   * @param bool $update
   *   Is this an update notification? Defaults to FALSE.
   *
   * @return string
   *   The raw e-mail subject configuration, without token replacement.
   */
  protected function getEmailSubjectConfiguration($update = FALSE) {
    if ($update) {
      $subject = $this->configuration['notify_subject_update'] ?? $this->t('Group content update: [node:title]');
    }
    else {
      $subject = $this->configuration['notify_subject_insert'] ?? $this->t('New group content: [node:title]');
    }
    return $subject;
  }

  /**
   * Returns the appropriate e-mail subject to use for a given notification.
   *
   * @param \Drupal\group\Entity\GroupContentInterface $entity
   *   The GroupContent entity to get the subject for.
   * @param bool $update
   *   Is this an update notification? Defaults to FALSE.
   *
   * @return string
   *   The full e-mail subject string, with tokens replaced.
   */
  public function getEmailSubject(GroupContentInterface $entity, $update = FALSE) {
    $subject_config = $this->getEmailSubjectConfiguration($update);
    $context = [
      'node' => $entity->getEntity(),
      'group' => $entity->getGroup(),
    ];
    return \Drupal::token()->replace($subject_config, $context);
  }

}
