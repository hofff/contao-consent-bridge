<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\BackendUser;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DataContainer\PaletteNotFoundException;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Framework\Adapter;
use Contao\DataContainer;
use Contao\Input;
use Contao\Message;
use Doctrine\DBAL\Connection;
use Hofff\Contao\Consent\Bridge\Bridge;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;
use Symfony\Contracts\Translation\TranslatorInterface;

use function count;
use function is_string;

final class ContentDcaListener
{
    /**
     * @param Adapter<Message> $messageAdapter
     * @param Adapter<Input>   $inputAdapter
     */
    public function __construct(
        private readonly Bridge $bridge,
        private readonly ConsentToolManager $consentToolManager,
        private readonly Connection $connection,
        private readonly TranslatorInterface $translator,
        private readonly BackendUser $backendUser,
        private readonly Adapter $messageAdapter,
        private readonly Adapter $inputAdapter,
    ) {
    }

    public function initializePalettes(DataContainer $dataContainer): void
    {
        if (count($this->consentToolManager->consentTools()) === 0) {
            return;
        }

        $fields = ['hofff_consent_bridge_tag'];

        if ($this->inputAdapter->get('act') === 'edit') {
            $result = $this->connection->executeQuery(
                'SELECT type FROM tl_content WHERE id=:id LIMIT 0,1',
                ['id' => $dataContainer->id],
            );

            /** @psalm-var string|false $type */
            $type = $result->fetchOne();

            if (is_string($type)) {
                $placeholderTemplates = $this->bridge->contentElementPlaceholderTemplates($type);
                if (count($placeholderTemplates) > 0) {
                    $fields[] = 'hofff_consent_bridge_placeholder_template';
                }
            }
        } else {
            $fields[] = 'hofff_consent_bridge_placeholder_template';
        }

        $paletteManipulator = PaletteManipulator::create()
            ->addLegend('hofff_consent_bridge_legend', 'expert_legend')
            ->addField($fields, 'hofff_consent_bridge_legend', PaletteManipulator::POSITION_APPEND);

        foreach ($this->bridge->supportedContentElements() as $element) {
            try {
                $paletteManipulator->applyToPalette($element, 'tl_content');
            } catch (PaletteNotFoundException) {
                // Do nothing. Required for RSCE support which does not always append their palettes.
            }
        }
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    public function showConsentInfo(DataContainer $dataContainer): void
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
        $result    = $statement->executeQuery([$dataContainer->id]);

        if ($result->rowCount() === 0) {
            return;
        }

        if (! $this->bridge->supportsContentElement((string) $result->fetchOne())) {
            return;
        }

        /** @psalm-suppress InternalMethod */
        $this->messageAdapter->__call(
            'addInfo',
            [$this->translator->trans('tl_content.hofff_consent_bridge_tag_missing', [], 'contao_tl_content')],
        );
    }

    /** @return list<string> */
    #[AsCallback('tl_content', 'fields.hofff_consent_bridge_placeholder_template.options')]
    public function placeholderTemplate(DataContainer $dataContainer): array
    {
        /**
         * @psalm-var string|null $type
         * @psalm-suppress UndefinedMagicPropertyFetch
         * @psalm-suppress MixedPropertyFetch
         */
        $type = $dataContainer->activeRecord?->type;
        if ($type === null) {
            return [];
        }

        return $this->bridge->contentElementPlaceholderTemplates($type);
    }
}
