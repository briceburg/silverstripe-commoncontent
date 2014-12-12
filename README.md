

Manage Blocks of Common Content through ModelAdmin. Accessible with shortcodes.

@todo documentation
@todo shortcode handler

DataObjects as CommonContent

```php
<?php

class Video extends DataObject
{
    private static $extensions = array(
        'CommonContentExtension',
        'SortableCommonContentExtension'
    );

    private static $db = array(
        'YouTubeID' => 'Varchar',
    );

}
```

Managing classes not extended by CommonContentExtension from the Common Content ModelAdmin area: 
```yaml
CommonContentAdmin:
  managed_models:
    CommonFooterBadge:
      title: Footer Badges
```
      