<?php declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */

namespace Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Concentrix\CodeChallenge\Model\CorporateGroup as CorporateGroupModel;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as CorporateGroupResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(CorporateGroupModel::class, CorporateGroupResourceModel::class);
    }
}
