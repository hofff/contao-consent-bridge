<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge;

use Contao\DataContainer;
use Contao\LayoutModel;
use Contao\Model;
use Contao\PageModel;
use Netzmacht\Html\Attributes;

interface ConsentTool
{
    /**
     * Get the name of the consent tool.
     *
     * @return string
     */
    public function name() : string;

    /**
     * Activate the consent tool.
     *
     * A consent tool might cancel activation by returning false. So a consent tool might depend on further settings.
     *
     * This method is used to activate the consent tool for a given page. To apply the configuration the root page
     * model, the current page model and the layout model is passed.
     */
    public function activate(
        PageModel $rootPageModel,
        ?PageModel $pageModel = null,
        ?LayoutModel $layoutModel = null
    ) : bool;

    /**
     * Get consent id options by name and consent ids.
     *
     * To add a custom label for the option use the array key.
     *
     * @param DataContainer|object|null $context
     *
     * @return ConsentId[]|array<string|int, ConsentId>
     */
    public function consentIdOptions($context = null) : array;

    /**
     * Check if a given consent requires consent.
     */
    public function requiresConsent(ConsentId $consentId) : bool;

    /**
     * For some features a generic name is used and needs to be translated to a ConsentId
     *
     * This method can be used to map a template name to a consent id. Also "google_webfonts" is passed to this
     * method to determine a given consent id.
     */
    public function determineConsentIdByName(string $serviceOrTemplateName) : ?ConsentId;

    /**
     * Render content so that consent tool requirements for given consent id is applied.
     *
     * This method might add a placeholder content as it's used for content elements and frontend modules.
     */
    public function renderContent(string $buffer, ConsentId $consentId, Model $model = null) : string;

    /**
     * Apply consent for given html output. Do not add placeholder content here as it might be header code or
     * hidden javascript.
     */
    public function renderRaw(string $buffer, ConsentId $consentId, Model $model = null) : string;

    /**
     * Create a script tag with given attributes which applies restrictions for the consent id.
     */
    public function renderScript(Attributes $attributes, ConsentId $consentId) : string;

    /**
     * Create a style tag with given attributes which applies restrictions for the consent id.
     */
    public function renderStyle(Attributes $attributes, ConsentId $consentId) : string;
}
