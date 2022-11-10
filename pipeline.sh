#!/usr/bin/env bash

# Get the changes
files=$(mktemp)
diff=$(mktemp)

git diff --cached --name-only --diff-filter=ACMR -- "*.php" > ${files}
git diff --cached > ${diff}

# Run the phpcs report
phpcs=$(mktemp)
./vendor/bin/phpcs --file-list=${files} --parallel=2 --standard=phpcs.xml --report=json > ${phpcs} || true

# check for differences
./vendor/bin/diffFilter --phpcs ${diff} ${phpcs}