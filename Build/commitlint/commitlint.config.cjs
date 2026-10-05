// Shared commitlint configuration — used by .github/workflows/commitlint.yml.
// CJS on purpose: no "type": "module" anywhere in this repo.
module.exports = { extends: ['@commitlint/config-conventional'] };
