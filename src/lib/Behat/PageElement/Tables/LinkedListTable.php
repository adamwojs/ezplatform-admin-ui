<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformAdminUi\Behat\PageElement\Tables;

use EzSystems\Behat\Browser\Context\BrowserContext;

class LinkedListTable extends Table
{
    /** @var string Name by which Element is recognised */
    public const ELEMENT_NAME = 'Linked List Table';

    public function __construct(BrowserContext $context, $containerLocator)
    {
        parent::__construct($context, $containerLocator);
        $this->fields['horizontalHeaders'] = $this->fields['list'] . ' .ez-table-header + .table thead th, .ez-table-header + form thead th';
        $this->fields['listElement'] = sprintf('%s %s', $this->fields['list'], '.ez-table__cell--has-checkbox+ td.ez-table__cell a');
        $this->fields['checkboxInput'] = ' .form-check-input';
        $this->fields['assignButton'] = $this->fields['list'] . ' tr:nth-child(%s) a[data-original-title*=Assign]';
    }

    public function getTableCellValue(string $header, ?string $secondHeader = null): string
    {
        $rowPosition = $this->context->getElementPositionByText(
            $header,
            $this->fields['listElement']
        );
        $columnPosition = $this->context->getElementPositionByText(
            $secondHeader,
            $this->fields['horizontalHeaders']
        );

        return $this->getCellValue($rowPosition, $columnPosition);
    }

    /**
     * Click link element with given name.
     *
     * @param string $name
     */
    public function clickListElement(string $name): void
    {
        $this->context->getElementByText($name, $this->fields['listElement'])->click();
    }

    /**
     * Check checkbox left to link element with given name.
     *
     * @param string $name
     */
    public function selectListElement(string $name): void
    {
        $this->selectElement($name, $this->fields['listElement']);
    }

    /**
     * Check if checkbox left to link element if active.
     *
     * @param string $name
     *
     * @return bool 'true' for enabled and 'false' for disabled
     */
    public function isElementSelectable(string $name): bool
    {
        $position = $this->context->getElementPositionByText($name, $this->fields['listElement']);
        $checkbox = $this->context->findElement(sprintf($this->fields['tableCell'], $position, 1) . $this->fields['checkboxInput'])->getAttribute('disabled');

        return $checkbox !== 'disabled';
    }

    public function clickEditButton(string $listItemName): void
    {
        $this->clickEditButtonByElementLocator($listItemName, $this->fields['listElement']);
    }

    public function clickAssignButton(?string $listItemName = null): void
    {
        $position = $this->context->getElementPositionByText($listItemName, $this->fields['listElement']);
        $this->context->findElement(sprintf($this->fields['assignButton'], $position))->click();
    }

    public function verifyVisibility(): void
    {
        $this->context->waitUntilElementIsVisible('.ez-table-header__headline');
    }
}
