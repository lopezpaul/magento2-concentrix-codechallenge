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

namespace Concentrix\CodeChallenge\Api;

use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterface;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupSearchResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

interface CorporateGroupRepositoryInterface
{
    /**
     * Save CorporateGroup Record
     *
     * @param CorporateGroupInterface $corporateGroupRecord
     * @return CorporateGroupInterface|CouldNotSaveException
     * @throws InputException
     * @throws StateException
     * @throws CouldNotSaveException
     * @api
     */
    public function save(CorporateGroupInterface $corporateGroupRecord);

    /**
     * Get info about CorporateGroup by entity_id
     *
     * @param int $id
     * @return CorporateGroupInterface|NoSuchEntityException
     * @api
     */
    public function get(int $id);

    /**
     * Get CorporateGroup by group_id
     *
     * @param string $groupId
     * @return CorporateGroupInterface|NoSuchEntityException
     * @api
     */
    public function getByGroupId(string $groupId);

    /**
     * Delete CorporateGroup by group_id
     *
     * @param string $groupId
     * @return bool
     * @api
     */
    public function deleteByGroupId(string $groupId): bool;
}
