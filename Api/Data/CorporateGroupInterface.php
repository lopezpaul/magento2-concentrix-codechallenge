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

namespace Concentrix\CodeChallenge\Api\Data;

interface CorporateGroupInterface
{
    /** @var string */
    public const GROUP_ID = 'group_id';

    /** @var string */
    public const GROUP_NAME = 'group_name';

    /** @var string */
    public const EMAIL = 'email';

    /** @var string */
    public const TELEPHONE = 'telephone';

    /** @var string */
    public const CREATED_AT = 'created_at';

    /** @var string */
    public const UPDATED_AT = 'updated_at';

    /**
     * Retrieve group_id
     *
     * @return string|null
     */
    public function getGroupId(): ?string;

    /**
     * Store group_id
     *
     * @param string $groupId
     * @return void
     */
    public function setGroupId(string $groupId): void;

    /**
     * Retrieve group_name
     *
     * @return string|null
     */
    public function getGroupName(): ?string;

    /**
     * Store group_name
     *
     * @param string $groupName
     * @return void
     */
    public function setGroupName($groupName): void;

    /**
     * Retrieve email
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Store email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void;

    /**
     * Retrieve telephone
     *
     * @return string|null
     */
    public function getTelephone(): ?string;

    /**
     * Store telephone
     *
     * @param string $telephone
     * @return void
     */
    public function setTelephone(string $telephone): void;

    /**
     * Retrieve created_at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Store created_at
     *
     * @param string $createdAt
     * @return void
     */
    public function setCreatedAt(string $createdAt): void;

    /**
     * Retrieve updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Store updated_at
     *
     * @param string $updatedAt
     * @return void
     */
    public function setUpdatedAt(string $updatedAt): void;
}
