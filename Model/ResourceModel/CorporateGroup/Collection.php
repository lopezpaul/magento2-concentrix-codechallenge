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

namespace Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup;

use Concentrix\CodeChallenge\Model\CorporateGroup as CorporateGroupModel;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as CorporateGroupResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Constructor of CorporateGroup Collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(CorporateGroupModel::class, CorporateGroupResourceModel::class);
    }
}
