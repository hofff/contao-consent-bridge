<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\BackendUser;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\Exception\PaletteNotFoundException;
use Contao\CoreBundle\Framework\Adapter;
use Contao\DataContainer;
use Contao\Input;
use Doctrine\DBAL\Connection;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Symfony\Component\Translation\TranslatorInterface;
use function count;

final class ContentDcaListener
{
    /** @var Bridge */
    private $bridge;

    /** @var ConsentToolManager  */
    private $consentToolManager;

    /** @var Connection */
    private $connection;

    /** @var BackendUser */
    private $backendUser;

    /** @var Adapter */
    private $messageAdapter;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        Bridge $bridge,
        ConsentToolManager $consentToolManager,
        Connection $connection,
        TranslatorInterface $translator,
        BackendUser $backendUser,
        Adapter $messageAdapter
    ) {
        $this->bridge             = $bridge;
        $this->consentToolManager = $consentToolManager;
        $this->connection         = $connection;
        $this->backendUser        = $backendUser;
        $this->messageAdapter     = $messageAdapter;
        $this->translator         = $translator;
    }

    public function initializePalettes() : void
    {
        if (count($this->consentToolManager->consentTools()) === 0) {
            return;
        }

        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField('hofff_consent_bridge_tag', 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->bridge->supportedContentElements() as $element) {
            try {
                $paletteManipulator->applyToPalette($element, 'tl_content');
            } catch (PaletteNotFoundException $exception) {
                // Do nothing. Required for RSCE support which does not always append their palettes.
            }
        }
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    public function showConsentInfo(DataContainer $dataContainer) : void
    {
        if ($_POST || Input::get('act') !== 'edit') {
            return;
        }

        // User has no access to consent tag field
        if (! $this->backendUser->hasAccess('tl_content::hofff_consent_bridge_tag', 'alexf')) {
            return;
        }

        $sql       = 'SELECT type FROM tl_content WHERE id=? AND hofff_consent_bridge_tag IS NULL';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$dataContainer->id]);

        if ($statement->rowCount() === 0) {
            return;
        }

        /**
         * @psalm-suppress DeprecatedMethod
         * TODO: Change when requiring doctrine/dbal ^2.11
         */
        if (! $this->bridge->supportsContentElement((string) $statement->fetchColumn())) {
            return;
        }

        /** @psalm-suppress InternalMethod */
        $this->messageAdapter->__call(
            'addInfo',
            [$this->translator->trans('tl_content.hofff_consent_bridge_tag_missing', [], 'contao_tl_content')]
        );
    }
}
