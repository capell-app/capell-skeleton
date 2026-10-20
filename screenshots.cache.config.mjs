import { realpathSync } from 'node:fs'
import { dirname, join } from 'node:path'
import { fileURLToPath, pathToFileURL } from 'node:url'

const root = dirname(fileURLToPath(import.meta.url))
const packages = realpathSync(process.env.CAPELL_PACKAGES_REPO_PATH)
const core = realpathSync(process.env.CAPELL_CORE_REPO_PATH)
const { default: base } = await import(pathToFileURL(join(packages, 'screenshots.config.mjs')))
const frontend = process.env.CAPELL_FRONTEND_URL ?? 'http://127.0.0.1:8546'
const origin = new URL(frontend)

export default {
    ...base,
    repoRoots: [root, packages, core],
    outputRoots: [join(packages, 'packages')],
    app: {
        cwd: root,
        serve: {
            command: 'php',
            args: ['artisan', 'serve', `--host=${origin.hostname}`, `--port=${origin.port || '80'}`, '--no-reload', '--no-interaction'],
            readyUrl: `${frontend}/up`,
            environment: {
                APP_KEY: base.app.serve.environment.APP_KEY,
                DB_CONNECTION: 'sqlite',
                DB_DATABASE: join(root, 'database/database.sqlite'),
                DB_URL: undefined, APP_CONFIG_CACHE: undefined, PHPRC: undefined,
                COMPOSER: 'composer.capture.json', CAPELL_RUNTIME_ROLE: 'combined',
                PHP_CLI_SERVER_WORKERS: undefined,
            },
        },
    },
    urls: { frontend, admin: `${frontend}/admin` },
    capture: { concurrency: 1, serverWorkers: 1 },
    preCapture: 'scripts/screenshots/cache-public-pre-capture.mjs',
}
