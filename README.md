Generic Bridge for consent tools
--------------------------------

This extension provides a bridge for integrating consent tools into Contao.

It's designed for consent tools which handles consent management on the client side but may require modified html
output.

Requirements
------------

 - Contao `^4.4`
 - PHP `7.1`


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

Then there will be a new legend where you can assign a consent id to your configuration and the rendering of the 
html output will automatically adjusted depending the requirements of the consent tool.

### As consent tool developer

As consent tool developer you need to implement the interface `Hofff\Contao\Consent\Bridge\ConsentTool` and tag it 
as `hofff_contao_consent_bridge.consent_tool` in the dependency injection container.s
