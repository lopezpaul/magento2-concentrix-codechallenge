<?php declare(strict_types=1);
/**
 * Copyright © 2022 Concentrix. All rights reserved.
 *
 * @package  Concentrix_CodeChallenge
 * @author   Concentrix <info@concentrix.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.concentrix.com/
 */
namespace Concentrix\CodeChallenge\Api;

interface CorporateGroupApiInterface
{
    /**
     * Retrieve CorporateGroup by entity_id
     *
     * @param int $id
     * @return string
     * @api
     */
    public function getById(
        int $id
    ):string;

    /**
     * Get CorporateGroup by group_id
     *
     * @param string $groupId
     * @return string
     * @api
     */
    public function getByGroupId(string $groupId):string;

    /**
     * Create CorporateGroup
     *
     * @param string $groupId
     * @param string $groupName
     * @param string $email
     * @param string $telephone
     * @return string
     * @api
     */
    public function create(
        string $groupId,
        string $groupName,
        string $email,
        string $telephone
    ):string;

    /**
     * Remove CorporateGroup by group_id
     *
     * @param string $groupId
     * @return string
     * @api
     */
    public function deleteByGroupId(string $groupId):string;

    /**
     * Link corporate group to customer by customer id
     *
     * @param string $groupId
     * @param int $customerId
     * @throws NoSuchEntityException
     * @return string
     * @api
     */
    public function linkToCustomerById(string $groupId, int $customerId):string;

    /**
     * Link a corporate group to a customer by customer email
     *
     * @param string $groupId
     * @param string $email
     * @throws NoSuchEntityException
     * @return string
     * @api
     */
    public function linkToCustomerByEmail(string $groupId, string $email):string;
}
