export function isNewAdmin() {
  // TODO: delete this condition as it's a temporary solution made special
  //  to allow opening the modal for editing images on the dev environments
  if (location.pathname.includes('admin2')) {
    return true;
  }

  const newAdminDomain = process.env.MIX_PREFIX_NEW_ADMIN_DOMAIN_URL ?? 'new.admin';
  return location.host.includes(newAdminDomain);
}
