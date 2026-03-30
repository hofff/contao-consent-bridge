Bridge between consent tools and third party extensions for Contao
------------------------------------------------------------------

This extension provides a bridge for integrating consent tools into Contao.

It's designed for consent tools which handles consent management on the client side but may require modified html
output.

Requirements
------------

 - Contao `^4.9`
 - PHP `^7.1 || ^8.0`


Installation
------------

You can install `hofff/contao-consent-bridge` using Composer/Contao Manager.

Changelog
---------

See [changelog](CHANGELOG.md)

Features
--------

 - Provides activation for consent tools in root page
 - Assign required consent ids for content elements and adjust output to fullfill requirements of consent tool 
 - Assign required consent ids for frontend modules and adjust output to fullfill requirements of consent tool
 - Provides interface so that rendering for specific frontend templates can be adjusted to fullfill requirements of 
   consent tools  
 - Built in support to google webfonts defined in the page layout
 - Provides plugin infrastructure for extensions so that they are supported
 - Provides interface for consent tools 


Integration
------------

### As extension developer

If you provide an extension with custom content elements or frontend modules which might require consent to be rendered
you can register a plugin which provides extra information. You need to tag it as `hofff_contao_consent_bridge.plugin`.

```php

namespace Your\Bundle;

use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\Bridge\Plugin;
use Hofff\Contao\Consent\Bridge\Render\RenderInformation;

class MyPlugin implements \Hofff\Contao\Consent\Bridge\Plugin
{
    public function load(Bridge $bridge): void
    {
        $bridge->supportFrontendModule('custom_1', RenderInformation::autoRenderWithoutPlaceholder());
        $bridge->supportFrontendModule('custom_2', RenderInformation::autoRenderWithPlaceholder('custom_placeholder_template'));
        $bridge->supportFrontendModule('custom_3', RenderInformation::customRender());

        $bridge->supportContentElement('custom_1', RenderInformation::autoRenderWithoutPlaceholder());
        $bridge->supportContentElement('custom_2', RenderInformation::autoRenderWithPlaceholder('custom_placeholder_template'));
        $bridge->supportContentElement('custom_3', RenderInformation::customRender());
    }
}
```

Then there will be a new legend where you can assign a consent id to your configuration, and the rendering of the 
HTML output will automatically adjust depending on the requirements of the consent tool.

### As consent tool developer

As consent tool developer you need to implement the interface `Hofff\Contao\Consent\Bridge\ConsentTool` and tag it
as `hofff_contao_consent_bridge.consent_tool` in the dependency injection container. Use the
`\Hofff\Contao\Consent\Bridge\WithGenericContextSupport` interface if the consent tools should support twig.

### Twig integration

The extension provides Twig filters and functions to use consent functionality directly in Twig templates.
The consent tool must implement `\Hofff\Contao\Consent\Bridge\WithGenericContextSupport` to support Twig rendering.

#### `hofff_consent_required` — Function

Checks whether consent is required for a given consent ID. Returns `true` if the active consent tool requires
consent, `false` otherwise (also when no consent tool is active or the ID is invalid).

```twig
{# Check by consent ID string #}
{% if hofff_consent_required('my-consent-id') %}
    {# Consent is required, render placeholder or alternative content #}
{% endif %}

{# Check using the Twig context (reads hofff_consent_bridge_tag automatically) #}
{% if hofff_consent_required(_context) %}
    {# Consent is required #}
{% endif %}
```

#### `hofff_consent_content` — Filter

Wraps HTML content according to the requirements of the active consent tool. If consent is required, the output
is transformed by the consent tool (e.g. replaced with a placeholder). An optional custom placeholder template
and additional data can be passed.

If no consent tool is active or the consent ID is invalid, the original HTML is returned unchanged.

```twig
{# Basic usage #}
{{ html_content|hofff_consent_content('my-consent-id') }}

{# With a custom placeholder template #}
{{ html_content|hofff_consent_content('my-consent-id', 'my_placeholder_template') }}

{# With a custom placeholder template and additional data passed to the consent tool #}
{{ html_content|hofff_consent_content('my-consent-id', 'my_placeholder_template', {title: 'My Title'}) }}
```

The filter can also be applied to larger blocks using the `{% apply %}` tag:

```twig
{% apply hofff_consent_content('my-consent-id') %}
    <iframe src="https://example.com/embed" width="560" height="315"></iframe>
{% endapply %}

{# With a custom placeholder template #}
{% apply hofff_consent_content('my-consent-id', 'my_placeholder_template') %}
    <iframe src="https://example.com/embed" width="560" height="315"></iframe>
{% endapply %}

{# With additional data #}
{% apply hofff_consent_content('my-consent-id', 'my_placeholder_template', {title: 'My Title'}) %}
    <iframe src="https://example.com/embed" width="560" height="315"></iframe>
{% endapply %}
```

#### `hofff_consent_raw` — Filter

Applies consent requirements to raw HTML content (e.g. inline scripts or tracking code) without adding a visual
placeholder. This is intended for hidden markup that should be managed by the consent tool but does not need
a visible fallback.

If no consent tool is active or the consent ID is invalid, the original HTML is returned unchanged.

```twig
{# Basic usage #}
{{ raw_script|hofff_consent_raw('my-consent-id') }}

{# With additional data passed to the consent tool #}
{{ raw_script|hofff_consent_raw('my-consent-id', {key: 'value'}) }}
```

The filter can also be applied to blocks using the `{% apply %}` tag:

```twig
{% apply hofff_consent_raw('my-consent-id') %}
    <script type="text/javascript">
        // tracking code
    </script>
{% endapply %}

{# With additional data #}
{% apply hofff_consent_raw('my-consent-id', {key: 'value'}) %}
    <script type="text/javascript">
        // tracking code
    </script>
{% endapply %}
```
