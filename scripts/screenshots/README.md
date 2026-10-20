# Anonymous HTML Cache capture

This optional screenshot profile runs the real CMS route in a prepared starter-app worktree. Its hook belongs to this App source root, as required by the shared runner. It imports the guarded Packages capture hook from the explicitly pinned source repository.

The capture lane prepares an owned SQLite database, published assets and a copied-package Composer installation in this worktree. The private `composer.capture.json` and lock contain the minimal HTML Cache/Foundation dependency graph; they do not replace the starter app's normal Composer requirements. No Capell vendor package may be a symlink. Reuse the prepared installation with the runner's `--skip-build` option.

Supply `CAPELL_PACKAGES_REPO_PATH`, `CAPELL_CORE_REPO_PATH`, the owning Packages worktree's `CAPELL_SCREENSHOT_OWNED_APP_KEY`, and an unused `CAPELL_FRONTEND_URL`. Run the installed `@capell-app/screenshot-tools` CLI with `--config screenshots.cache.config.mjs --skip-build --only html-cache:html-cache-public-cache-hit`.

The Packages hook rejects other entries, authenticated selections, a different database, unexpected installed packages or loaded cache-blocking providers. It retains the real HTTP HIT, cookie, authoring-state, image-readiness and review checks. Inspect the original and runner diagnostics before accepting its receipt. This background evidence does not authorise release or deployment changes.
