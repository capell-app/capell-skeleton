<?php

use Illuminate\Support\Facades\Route;

// The Capell frontend package resolves all public routes, including the
// homepage ('/'), from CMS content. The stock Laravel welcome route has been
// removed so Capell can serve the homepage. The installer's
// RemoveWelcomeRoutePatch performs this edit for non-cloud installs; it is
// baked in here because Laravel Cloud serves from an immutable build artifact
// where deploy-time edits to this file do not persist.
