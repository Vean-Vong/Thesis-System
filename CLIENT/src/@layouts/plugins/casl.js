/* eslint-disable import/no-unresolved */
// eslint-disable-next-line import/no-unresolved
import { useAuthStore } from '@/plugins/auth.module'
// eslint-disable-next-line import/extensions
import ability from '@/plugins/casl/ability'

/**
 * Returns ability result if ACL is configured or else just return true
 * We should allow passing string | undefined to can because for admin ability we omit defining action & subject
 *
 * Useful if you don't know if ACL is configured or not
 * Used in @core files to handle absence of ACL without errors
 *
 * @param {String} action CASL Actions // https://casl.js.org/v4/en/guide/intro#basics
 * @param {String} subject CASL Subject // https://casl.js.org/v4/en/guide/intro#basics
 */
export const can = (action, subject) => {
  const vm = getCurrentInstance()

  if (!vm) return false
  const localCan = vm.proxy && '$can' in vm.proxy

  return localCan ? vm.proxy?.$can(action, subject) : true
}

// export const can = (permission) => {
//   if (!permission) return false;

//   if (permission == "public") return true;

//   return useAuthStore()._user.permissions.includes(permission);
// };

/**
 * Check if user can view item based on it's ability
 * Based on item's action and subject & Hide group if all of it's children are hidden
 * @param {Object} item navigation object item
 */
export const canViewNavMenuGroup = item => {
  const hasAnyVisibleChild = item.children.some(i => can(i.action, i.subject))

  // If subject and action is defined in item => Return based on children visibility (Hide group if no child is visible)
  // Else check for ability using provided subject and action along with checking if has any visible child
  if (!(item.action && item.subject)) return hasAnyVisibleChild

  return can(item.action, item.subject) && hasAnyVisibleChild
}

// export const canViewNavMenuGroup = (item) => {
//   const hasAnyVisibleChild = item.children.some((i) => can(i.permission));

//   // If subject and action is defined in item => Return based on children visibility (Hide group if no child is visible)
//   // Else check for ability using provided subject and action along with checking if has any visible child
//   if (!item.permission) return hasAnyVisibleChild;

//   return can(item.permission) && hasAnyVisibleChild;
// };

export const canNavigate = to => {
  return to.matched.some(route => ability.can(route.permission))
}
