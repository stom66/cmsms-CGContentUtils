# cmsms-CGContentUtils

Maintained fork of the **CGContentUtils** module for CMS Made Simple.

Calguy's content utility module, providing user-defined content blocks, global content blocks, aliases, page relationships, code export/import, and Smarty helpers for working with CMSMS content.

This repository is a community-maintained fork of CGContentUtils for [CMS Made Simple](https://www.cmsmadesimple.org) (CMSMS), originally written by Robert Campbell (*calguy1000*).

## Why this fork exists

The original author left the CMSMS project and the CG ("Calguy") modules will not receive further official releases. Many sites still depend on them, while the last releases predate PHP 8.

Rather than patching the copy inside every site separately, fixes live here so every site can use the same tested code.

## Compatibility

| | |
|---|---|
| Based on | CGContentUtils **2.5.5** (final upstream release) |
| Tested with | CMS Made Simple 2.2.24, Smarty 4.5.5, **PHP 8.5.12**, AlmaLinux 9 / Apache 2.4 |
| Minimum CMSMS | 2.2.9 (unchanged from upstream) |
| Requires | **CGExtensions** >= 1.63 and **CGSimpleSmarty** >= 2.2.1. |

## Version number

The module still reports version **2.5.5**, deliberately. Raising `moduleinfo.ini` / `GetVersion()` makes CMSMS mark the module as requiring an upgrade and it will not load until Module Manager runs that upgrade. Fork changes are tracked by this repository's commits and tags instead.

## Installing / updating a site

The repository is called `cmsms-CGContentUtils`, but the module folder inside a site must be named exactly **`CGContentUtils`**.

```sh
# Replace an existing module with the fork.
cd /path/to/site/modules
mv CGContentUtils CGContentUtils.orig
git clone https://github.com/stom66/cmsms-CGContentUtils.git CGContentUtils

# Later updates.
cd /path/to/site/modules/CGContentUtils && git pull --ff-only
```

Clear the CMSMS cache after replacing or updating the module. Ensure the web server blocks access to `.git` directories.

## Changes in this fork

The first commit is the upstream release. Later commits contain one fix each:

- fix(CGContentUtils): use `continue 2` when an unavailable module should skip the enclosing imported-content row on PHP 8.

## Contributing

- One fix per commit, using [Conventional Commits](https://www.conventionalcommits.org).
- Keep behaviour and rendered output unchanged unless the change is the fix.
- Test against the PHP versions in the compatibility table. `php -l` alone is not enough for runtime compatibility.
- Do not change the module version without an upgrade path.

## Licence

GNU General Public License, version 2 or later, with the upstream author's additional condition in the source-file headers.

Copyright © Robert Campbell (calguy1000) and contributors. The full licence text is in [`doc/COPYING`](doc/COPYING).
