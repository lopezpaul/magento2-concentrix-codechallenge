<?php

declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */

namespace Concentrix\CodeChallenge\Model\Source;

use Concentrix\CodeChallenge\Model\CorporateGroupFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Psr\Log\LoggerInterface;

class Options extends AbstractSource
{
    /** @var CorporateGroupFactory */
    private CorporateGroupFactory $corporateGroupFactory;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /**
     * Options constructor
     *
     * @param CorporateGroupFactory $corporateGroupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CorporateGroupFactory $corporateGroupFactory,
        LoggerInterface $logger
    ) {
        $this->corporateGroupFactory = $corporateGroupFactory;
        $this->logger = $logger;
    }

    /**
     * Retreive all corporate group options
     *
     * @return array|null
     */
    public function getAllOptions(): ?array
    {
        $this->_options = [];
        $options = $this->getOptions();
        if (!empty($options)) {
            $this->_options[] = ['label' => 'Please select an option', 'value' => ''];
            foreach ($options as $id => $label) {
                $this->_options[] = ['label' => $label, 'value' => $id];
            }
        }
        return $this->_options;
    }

    /**
     * Retrieve list of Corporate Groups in array: group_id => group_name
     *
     * @return array $options
     */
    public function getOptions(): array
    {
        $options = [];
        $corporateGroupsCollection = $this->corporateGroupFactory->create()->getCollection();
        foreach ($corporateGroupsCollection as $corporateGroup) {
            $id = (string)$corporateGroup->getGroupId();
            if (empty($id)) {
                continue;
            }
            $groupName = (string)$corporateGroup->getGroupName();
            if (!empty($groupName)) {
                $options[$id] = $groupName;
            }
        }
        return $options;
    }
}
