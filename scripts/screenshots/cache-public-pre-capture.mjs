import { realpathSync } from 'node:fs'
import { join } from 'node:path'
import { pathToFileURL } from 'node:url'

export default async function preCapture(context) {
    const source = realpathSync(process.env.CAPELL_PACKAGES_REPO_PATH)
    if (!context.config.repoRoots.includes(source)) {
        throw new Error('Cache capture requires the pinned Packages source repository.')
    }
    const { default: prepare } = await import(pathToFileURL(join(source, 'scripts/screenshots/native-cache-pre-capture.mjs')))
    await prepare(context)
}
