import '../styl/admin.styl'

/**
 * Redirect user to true admin URL if accessed from a different URL. Admin does not work
 * properly when accessed through WDS.
 *
 * NOTE: This does not work as-is if your site doesn't live in webroot, but that's pretty rare, esp. for new sites.
 */
if (window.location.origin !== window.wptheme.wpurl) {
  const message = `WARNING!

Looks like you're accessing wp-admin from the wrong domain,
as window.location.origin doesn't match window.wptheme.wpurl.
Expected ${window.wptheme.wpurl}, got ${window.location.origin}.

Press OK to be redirected to the correct domain.
`
  if (confirm(message)) {
    const url = window.location.href.replace(
      window.location.origin,
      window.wptheme.wpurl,
    )

    window.location.href = url
  }
}
