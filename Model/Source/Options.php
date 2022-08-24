<?php declare(strict_types=1);
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
    /**
     * @var CorporateGroupFactory
     */
    protected CorporateGroupFactory $corporateGroupFactory;

    /**
     * @var LoggerInterface $logger
     */
    protected LoggerInterface $logger;

    /**
     * Options constructor
     *
     * @param CorporateGroupFactory $corporateGroupFactory
     */
    public function __construct(
        CorporateGroupFactory $corporateGroupFactory,
        LoggerInterface $logger
    ) {
        $this->corporateGroupFactory = $corporateGroupFactory;
        $this->logger = $logger;
    }

    /**
     * @param Retreive all corporate group options
     *
     * @return array $options
     */
    public function getAllOptions()
    {
        $this->_options =[];
        $options = $this->getOptions();
        if (!empty($options)) {
            array_push($this->_options, ['label' => 'Please select an option', 'value' => '']);
            foreach ($options as $id => $label) {
                array_push($this->_options, ['label' => $label, 'value' => $id]);
            }
        }
        return $this->_options;
    }

    /**
     * Retrieve list of Corporate Groups in array: group_id => group_name
     *
     * @return array $options
     */
    public function getOptions():array
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
