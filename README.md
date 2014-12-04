

Manage Blocks of Common Content through ModelAdmin. Accessible with shortcodes.

@todo documentation
@todo shortcode handler


Say you have some items that don't extend content blocks, but seem naturally 
managed through the "Common Content" area. No problem, use YAML; 

```yaml
CommonContentAdmin:
  managed_models:
    CommonFooterBadge:
      title: Footer Badges
```
      