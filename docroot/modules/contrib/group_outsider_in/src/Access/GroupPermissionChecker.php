<?php

namespace Drupal\group_outsider_in\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\group\Access\CalculatedGroupPermissionsItemInterface;
use Drupal\group\Access\ChainGroupPermissionCalculatorInterface;
use Drupal\group\Access\GroupPermissionCheckerInterface;
use Drupal\group\Entity\GroupInterface;

/**
 * Calculates group permissions for an account.
 */
class GroupPermissionChecker implements GroupPermissionCheckerInterface {

  /**
   * Decorated service.
   *
   * @var \Drupal\group\Access\GroupPermissionCheckerInterface
   */
  protected $innerService;

  /**
   * Group permission calculator.
   *
   * @var \Drupal\group\Access\ChainGroupPermissionCalculatorInterface
   */
  protected $groupPermissionCalculator;

  /**
   * Constructor.
   */
  public function __construct(GroupPermissionCheckerInterface $inner_service, ChainGroupPermissionCalculatorInterface $permission_calculator) {
    $this->innerService = $inner_service;
    $this->groupPermissionCalculator = $permission_calculator;
  }

  /**
   * {@inheritDoc}
   *
   * @see \Drupal\group\Access\GroupPermissionChecker
   * @see https://www.drupal.org/project/group/issues/2884662
   */
  public function hasPermissionInGroup($permission, AccountInterface $account, GroupInterface $group) {
    $usual_result = $this->innerService->hasPermissionInGroup($permission, $account, $group);
    // We do not intend to remove any access.
    if ($usual_result) {
      return TRUE;
    }

    $calculated_permissions = $this->groupPermissionCalculator->calculatePermissions($account);

    // We check per-group-bundle permissions ("advanced outsider permissions"),
    // even if the account has member permissions in this group.
    $item = $calculated_permissions->getItem(CalculatedGroupPermissionsItemInterface::SCOPE_GROUP_TYPE, $group->bundle());
    return $item->hasPermission($permission);
  }

}
