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

use Concentrix\CodeChallenge\Api\CorporateGroupRepositoryInterface;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterface;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterfaceFactory;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupSearchResultInterface;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as ResourceCorporateGroup;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup\CollectionFactory as CorporateGroupCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class CorporateGroupRepository implements CorporateGroupRepositoryInterface
{
    private const LOADED_KEY_REGISTRY = 'concentrix_codechallenge_loaded';

    /** @var CorporateGroupInterfaceFactory */
    private CorporateGroupInterfaceFactory $corporateGroupFactory;

    /** @var ResourceCorporateGroup */
    private ResourceCorporateGroup $resourceCorporateGroup;

    /** @var CorporateGroupCollectionFactory */
    private CorporateGroupCollectionFactory $CorporateGroupCollectionFactory;

    /** @var mixed|null */
    private $loadedRecord = null;

    /** @var DataPersistorInterface */
    private DataPersistorInterface $dataPersistor;

    /** @var CustomerRepositoryInterface */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * CorporateGroupRepository constructor
     *
     * @param CorporateGroupInterfaceFactory $corporateGroupFactory
     * @param ResourceCorporateGroup $resourceCorporateGroup
     * @param CorporateGroupCollectionFactory $CorporateGroupCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CorporateGroupInterfaceFactory $corporateGroupFactory,
        ResourceCorporateGroup $resourceCorporateGroup,
        CorporateGroupCollectionFactory $CorporateGroupCollectionFactory,
        DataPersistorInterface $dataPersistor,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->corporateGroupFactory = $corporateGroupFactory;
        $this->resourceCorporateGroup = $resourceCorporateGroup;
        $this->CorporateGroupCollectionFactory = $CorporateGroupCollectionFactory;
        $this->dataPersistor = $dataPersistor;
        $this->customerRepository = $customerRepository;
        if ($this->dataPersistor->get(self::LOADED_KEY_REGISTRY)) {
            $this->loadedRecord = $this->dataPersistor->get(self::LOADED_KEY_REGISTRY);
        }
    }

    /**
     * Store records on registry
     *
     * @param array $corporateGroupRecords
     * @param bool $merge
     * @return void
     */
    private function setloadedRecords(array $corporateGroupRecords = [], bool $merge = false): void
    {
        $loadedRecord = [];
        foreach ($corporateGroupRecords as $corporateGroupRecord) {
            $loadedRecord[$corporateGroupRecord->getGroupId()] = $corporateGroupRecord;
        }
        if ($merge && is_array($this->loadedRecord)) {
            $this->loadedRecord = array_merge($this->loadedRecord, $loadedRecord);
        } else {
            $this->loadedRecord = $loadedRecord;
        }

        $this->dataPersistor->clear(self::LOADED_KEY_REGISTRY);
        $this->dataPersistor->set(self::LOADED_KEY_REGISTRY, $this->loadedRecord);
    }

    /**
     * Store corporate group
     *
     * @param CorporateGroupInterface $corporateGroupRecord
     * @return CorporateGroupInterface
     * @throws CouldNotSaveException
     */
    public function save(CorporateGroupInterface $corporateGroupRecord)
    {
        try {
            $data = $corporateGroupRecord->getData();
            $corporateGroupRecord->setData($data);
            $this->resourceCorporateGroup->save($corporateGroupRecord);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $corporateGroupRecord;
    }

    /**
     * Load corporate group by entity_id
     *
     * @param int $id
     * @return CorporateGroupInterface|NoSuchEntityException
     */
    public function get(int $id): CorporateGroupInterface
    {
        $corporateGroup = $this->corporateGroupFactory->create()->load($id);
        if (empty($corporateGroup)) {
            throw new NoSuchEntityException(__('Unable to find Corporate Group with entity_id "%1"', $id));
        }
        return $corporateGroup;
    }

    /**
     * Get CorporateGroup by group_id
     *
     * @param string $groupId
     * @return CorporateGroupInterface|NoSuchEntityException
     * @throws NoSuchEntityException
     */
    public function getByGroupId(string $groupId)
    {
        $corporateGroupRecord = $this->corporateGroupFactory->create();
        if (!empty($groupId)) {
            if (isset($this->loadedRecord[$groupId])) {
                return $this->loadedRecord[$groupId];
            }
            $this->resourceCorporateGroup->load($corporateGroupRecord, $groupId, 'group_id');
            if ($corporateGroupRecord->getGroupId()) {
                $this->setloadedRecords([$corporateGroupRecord], true);
            }
        }
        return $corporateGroupRecord;
    }

    /**
     * Delete CorporateGroup
     *
     * @param CorporateGroupInterface $corporateGroupRecord
     * @return bool
     */
    public function delete(CorporateGroupInterface $corporateGroupRecord): bool
    {
        try {
            $this->resourceCorporateGroup->delete($corporateGroupRecord);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Delete CorporateGroup by group_id
     *
     * @param string $groupId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteByGroupId(string $groupId): bool
    {
        $corporateGroup = $this->getByGroupId($groupId);
        $entityId = $corporateGroup->getEntityId();
        if ($entityId > 0) {
            return $this->delete($corporateGroup);
        }
        return false;
    }

    /**
     * Get all records
     *
     * @param bool $forceReload
     * @return mixed|null
     */
    public function getAll(bool $forceReload = false)
    {
        if (empty($this->loadedRecord) || $forceReload) {
            $this->CorporateGroupCollectionFactory->addOrder('`order`', 'ASC');
            $corporateGroupRecords = $this->CorporateGroupCollectionFactory->load();
            $this->setloadedRecords($corporateGroupRecords);
        }
        return $this->loadedRecord;
    }
}
