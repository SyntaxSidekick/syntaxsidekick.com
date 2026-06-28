# SyntaxSidekick Theme Repository

This repository tracks only the SyntaxSidekick child theme used by the local site.

## Tracked Paths

- wp-content/themes/syntaxsidekick-child/

## Excluded Paths

WordPress core, plugins, uploads, cache, backups, local configuration, and other environment-specific files are ignored through .gitignore.

## Local Development Context

- Local WordPress root: C:\\xampp\\htdocs\\syntaxsidekick.com
- Parent theme: wp-content/themes/syntax-sidekick/
- Child theme: wp-content/themes/syntaxsidekick-child/

## Repository Goal

Keep version control focused on child theme development and deployment.

## GitHub Actions Deployment (Production)

This repository includes a production deployment workflow at `.github/workflows/deploy.yml`.

### What Gets Deployed

- Source: `wp-content/themes/syntaxsidekick-child/`
- Destination: `/public_html/wp-content/themes/syntaxsidekick-child/`
- Scope: child theme only (never the full WordPress install)

### Required Repository Secrets

Add the following secrets in GitHub:

- `FTP_HOST`
- `FTP_USERNAME`
- `FTP_PASSWORD`
- `FTP_TARGET`
- `PROD_SITE_URL`

Set `FTP_TARGET` to:

`/public_html/wp-content/themes/syntaxsidekick-child/`

The workflow validates this path before deployment and fails if it does not match.

Set `PROD_SITE_URL` to your production origin, for example:

`https://syntaxsidekick.com`

### How Deployment Works

- Automatic trigger: push to `main`
- Optional manual trigger: Actions tab -> Deploy SyntaxSidekick Child Theme -> Run workflow
- Runner: GitHub-hosted `ubuntu-latest`
- Deployment method: FTP via `SamKirkland/FTP-Deploy-Action`
- Incremental sync: uploads changed files whenever possible using action state tracking
- Safety: `dangerous-clean-slate` is disabled
- Deploy metadata: each run writes `wp-content/themes/syntaxsidekick-child/assets/deploy-meta.json` with the current commit/run
- Live verification: workflow fails if production HTML does not include the expected deploy marker for the pushed commit

### Safety Guarantees

- Only files under the child theme directory are uploaded.
- No uploads, plugins, database content, or WordPress core files are touched.
- No files outside the configured theme destination are targeted.
- Deploy verification catches stale production responses before the workflow reports success.

### Excluded From Deployment

The workflow excludes development-only files such as:

- `.git/`, `.github/`, `.vscode/`, `.idea/`
- `node_modules/`, `vendor/`
- `package-lock.json`, `composer.json`, `composer.lock`
- `README.md`, `*.zip`, `.DS_Store`, `Thumbs.db`, `*.log`

### How To Disable Deployment

Use one of these options:

- Disable the workflow in the GitHub Actions UI.
- Protect `main` and stop direct pushes.
- Remove one required secret temporarily to force preflight validation failure.

### Troubleshooting

- Missing secret error: ensure all required secrets exist and are non-empty.
- FTP target path error: ensure `FTP_TARGET` is exactly `/public_html/wp-content/themes/syntaxsidekick-child/`.
- Authentication failure: verify FTP credentials and host.
- No files uploaded: this can be expected when no theme files changed.
- Deploy verification failed: production is serving stale content. Purge LiteSpeed page cache and CSS/JS optimization cache, then re-run the workflow.
