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

namespace Concentrix\CodeChallenge\Model\ResourceModel;

use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

class CorporateGroup extends AbstractDb
{
    /** @var string */
    public const ATTRIBUTE_CODE = 'corporate_group_id';

    /** @var string */
    public const ENTITY_TYPE = 'customer';

    /** @var int */
    private int $attributeId;

    /** @var DateTime */
    private DateTime $datetime;

    /** @var ResourceConnection */
    private ResourceConnection $resourceConnection;

    /**
     * Constructor of Corporate Group
     *
     * @param Context $context
     * @param DateTime $date
     * @param ResourceConnection $resourceConnection
     * @param Attribute $eavAttribute
     * @param string|null $resourcePrefix
     */
    public function __construct(
        Context $context,
        DateTime $date,
        ResourceConnection $resourceConnection,
        Attribute $eavAttribute,
        string $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->datetime = $date;
        $this->resourceConnection = $resourceConnection;
        $this->attributeId = (int)$eavAttribute->getIdByCode(
            self::ENTITY_TYPE,
            self::ATTRIBUTE_CODE
        );
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('concentrix_codechallenge_corporate_group', 'entity_id');
    }

    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $storeTime = $this->datetime->gmtDate();
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt($storeTime);
        }

        $object->setUpdatedAt($storeTime);

        return parent::_beforeSave($object);
    }
}
