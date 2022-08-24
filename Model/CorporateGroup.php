<?php declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */

namespace Concentrix\CodeChallenge\Model;

use Magento\Framework\Model\AbstractModel;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterface;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as CorporateGroupResourceModel;

class CorporateGroup extends AbstractModel implements CorporateGroupInterface
{
    /**
     * Initialize CorporateGroup Model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(CorporateGroupResourceModel::class);
    }

    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->_getData(self::GROUP_ID);
    }

    /**
     * @param string $groupId
     * @return void
     */
    public function setGroupId(string $groupId)
    {
        $this->setData(self::GROUP_ID, strtoupper($groupId));
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->_getData(self::GROUP_NAME);
    }

    /**
     * @param string $groupName
     * @return void
     */
    public function setGroupName($groupName)
    {
        $this->setData(self::GROUP_NAME, $groupName);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_getData(self::EMAIL);
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->_getData(self::TELEPHONE);
    }

    /**
     * @param string $telephone
     * @return void
     */
    public function setTelephone(string $telephone)
    {
        $this->setData(self::TELEPHONE, $telephone);
    }

    /**
     * @param string $createdAt
     * @return CorporateGroup|mixed
     */
    public function setCreatedAt(string $createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return mixed|string|null
     */
    public function getCreatedAt()
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return CorporateGroup
     */
    public function setUpdatedAt(string $updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return mixed|string|null
     */
    public function getUpdatedAt()
    {
        return $this->_getData(self::UPDATED_AT);
    }
}
