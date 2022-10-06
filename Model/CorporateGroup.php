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

namespace Concentrix\CodeChallenge\Model;

use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterface;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as CorporateGroupResourceModel;
use Magento\Framework\Model\AbstractModel;

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
     * Retrieve group_id
     *
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->_getData(self::GROUP_ID);
    }

    /**
     * Store group_id
     *
     * @param string $groupId
     * @return void
     */
    public function setGroupId(string $groupId): void
    {
        $this->setData(self::GROUP_ID, strtoupper($groupId));
    }

    /**
     * Retrieve group_name
     *
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->_getData(self::GROUP_NAME);
    }

    /**
     * Store group_name
     *
     * @param string $groupName
     * @return void
     */
    public function setGroupName($groupName): void
    {
        $this->setData(self::GROUP_NAME, $groupName);
    }

    /**
     * Retrieve email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->_getData(self::EMAIL);
    }

    /**
     * Store email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->setData(self::EMAIL, $email);
    }

    /**
     * Retrieve telephone
     *
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->_getData(self::TELEPHONE);
    }

    /**
     * Store telephone
     *
     * @param string $telephone
     * @return void
     */
    public function setTelephone(string $telephone): void
    {
        $this->setData(self::TELEPHONE, $telephone);
    }

    /**
     * Store created_at
     *
     * @param string $createdAt
     * @return void
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Retrieve created_at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * Store updated_at
     *
     * @param string $updatedAt
     * @return void
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Retrieve updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->_getData(self::UPDATED_AT);
    }
}
