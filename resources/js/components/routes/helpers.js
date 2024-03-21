export function isNewAdmin() {
  const newAdminDomain = process.env.MIX_PREFIX_NEW_ADMIN_DOMAIN_URL ?? 'new.admin';
  return location.host.includes(newAdminDomain);
}
