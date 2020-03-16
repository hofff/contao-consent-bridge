Generic Bridge for consent tools
--------------------------------

This extension provides a bridge for integrating consent tools into Contao. It abstracts the u of 


Requirements
------------

 - Contao `^4.4`
 - PHP `7.1`


Installation
------------

You can `hofff/contao-consent-bridge`

Integration
------------

### As extension developer

If you provide an extension with custom content elements or frontend modules which might require consent to be rendered
you can register you custom modules using the bundle configuration of this bundle:

```php

namespace Your\Bundle;

use Contao\ManagerPlugin\Config\ContainerBuilder;use Contao\ManagerPlugin\Config\ExtensionPluginInterface

class Plugin implements ExtensionPluginInterface
{
    public function getExtensionConfig($extensionName,array $extensionConfigs,ContainerBuilder $container) : array
    {
        if ($extensionName === 'hofff_contao_consent_bridge') {
            $extensionConfigs[] = [
                'content_elements' => ['your_custom_element_a', 'your_custom_element_b'],
                'frontend_modules' => ['your_custom_module_a', 'your_custom_module_b']
            ];
        }

        return  $extensionConfigs;
    }
}
```

Then there will be a new legend where you can assign a consent id to your configuration and the rendering of the 
html output will automatically adjusted depending the requirements of the consent tool.


### As consent tool developer

As consent tool developer you need to implement the interface `Hofff\Contao\Consent\Bridge\ConsentTool` and tag it 
as `hofff_contao_consent_bridge.consent_tool` in the dependency injection container.s
