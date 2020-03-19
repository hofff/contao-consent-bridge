Generic Bridge for consent tools
--------------------------------

This extension provides a bridge for integrating consent tools into Contao. It abstracts the u of 


Requirements
------------

 - Contao `^4.4`
 - PHP `7.1`


Installation
------------

You can install `hofff/contao-consent-bridge` using Composer/Contao Manager.

Integration
------------

### As extension developer

If you provide an extension with custom content elements or frontend modules which might require consent to be rendered
you can register a plugin which provides extra information. You need to tag it as `hofff_contao_consent_bridge.plugin`.

```php

namespace Your\Bundle;

use Your\Bundle\ConsentId\DataProcessingServiceId;

class Plugin implements \Hofff\Contao\Consent\Bridge\Plugin
{
    public function providedConsentIds() : array
    {
        return [DataProcessingServiceId::class];
    }
    public function supportedContentElements() : array
    {
        return ['your_content_element_type_a', 'your_content_element_type_b'];
    }
    public function supportedFrontendModules() : array{
        return ['your_frontend_module_a', 'your_frontend_module_b'];
    }
}
```

Then there will be a new legend where you can assign a consent id to your configuration and the rendering of the 
html output will automatically adjusted depending the requirements of the consent tool.

### As consent tool developer

As consent tool developer you need to implement the interface `Hofff\Contao\Consent\Bridge\ConsentTool` and tag it 
as `hofff_contao_consent_bridge.consent_tool` in the dependency injection container.s
