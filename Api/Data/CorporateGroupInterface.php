<?php declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */

namespace Concentrix\CodeChallenge\Api\Data;

interface CorporateGroupInterface
{
    const GROUP_ID = 'group_id';
    const GROUP_NAME = 'group_name';
    const EMAIL = 'email';
    const TELEPHONE = 'telephone';
    const CREATED_AT = 'created_at';
    const UPDATED_AT     = 'updated_at';

    /**
     * @return string
     */
    public function getGroupId();

    /**
     * @param string $groupId
     * @return void
     */
    public function setGroupId(string $groupId);

    /**
     * @return string
     */
    public function getGroupName();

    /**
     * @param string $groupName
     * @return void
     */
    public function setGroupName($groupName);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email);

    /**
     * @return string
     */
    public function getTelephone();

    /**
     * @param string $telephone
     * @return void
     */
    public function setTelephone(string $telephone);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return mixed
     */
    public function setCreatedAt(string $createdAt);

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return mixed
     */
    public function setUpdatedAt(string $updatedAt);
}
