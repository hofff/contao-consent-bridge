<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\EventListener\Dca;

use Contao\DataContainer;
use Hofff\Contao\Consent\Bridge\ConsentToolManager;

use function count;
use function current;
use function is_numeric;

final class ConsentIdOptions
{
    public function __construct(private readonly ConsentToolManager $consentToolManager)
    {
    }

    /** @return array<string, string>|array<string, array<string, string>> */
    public function __invoke(DataContainer|null $dataContainer = null): array
    {
        /** @var array<string, array<string, string>> $options */
        $options = [];

        foreach ($this->consentToolManager->consentTools() as $consentTool) {
            $toolOptions                   = $consentTool->consentIdOptions($dataContainer);
            $options[$consentTool->name()] = [];

            foreach ($toolOptions as $label => $consentId) {
                $options[$consentTool->name()][$consentId->serialize()] = is_numeric($label)
                    ? $consentId->toString()
                    : $label;
            }
        }

        if (count($options) === 1) {
            return current($options);
        }

        return $options;
    }
}
