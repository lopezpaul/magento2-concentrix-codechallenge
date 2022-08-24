<?php declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */

namespace Concentrix\CodeChallenge\Setup\Patch\Data;

use Concentrix\CodeChallenge\Model\Source\Options;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCorporateGroupAttributesV1 implements DataPatchInterface, PatchRevertableInterface
{
    const ATTRIBUTE_CODE = 'corporate_group_id';
    const ENTITY_TYPE = Customer::ENTITY;
    const SCOPE = ScopedAttributeInterface::SCOPE_STORE;
    const FORM_PERMISSIONS = [
        'adminhtml_checkout',
        'adminhtml_customer',
        'customer_account_create',
        'customer_account_edit',
    ];
    const ATTRIBUTE_POSITION = 900;

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private CustomerSetupFactory $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory     $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->addAttribute(
            self::ENTITY_TYPE,
            self::ATTRIBUTE_CODE,
            [
                'label' => 'Corporate Group',
                'type' => 'int',
                'input' => 'select',
                'source' => Options::class,
                'required' => false,
                'visible' => true,
                'global' => self::SCOPE,
                'visible_on_front' => true,
                'system' => false,
                'user_defined' => true,
                'unique' => false,
                'used_in_product_listing' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => true,
                'default' => '',
                'backend' => '',
                'sort_order' => self::ATTRIBUTE_POSITION,
                'position' => self::ATTRIBUTE_POSITION
            ]
        );
        $attribute = $customerSetup->getEavConfig()->getAttribute(self::ENTITY_TYPE, self::ATTRIBUTE_CODE);
        $attribute->addData(['used_in_forms' => self::FORM_PERMISSIONS])->save();

        $this->moduleDataSetup->endSetup();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $this->moduleDataSetup->startSetup();
        $this->eavSetup->removeAttribute(self::ENTITY_TYPE, self::ATTRIBUTE_CODE);
        $this->moduleDataSetup->endSetup();
    }
}
