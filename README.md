# php-feed-generator

Generates feed files for testing purposes.

Used by [Nextcloud News](https://github.com/nextcloud/news)

```
php feed-generator.php -h

Help for feed-generator.php
-a provide the amount of items e.g. -a 100
-s provide the start number of the first item, should be smaller than -a e.g -s 50, default 0
-f provide a path to the resulting feed file
-o (optional) use an older date for the items, -o true; -o yes; -o y
```
-o will create Items that were created 3 days ago.