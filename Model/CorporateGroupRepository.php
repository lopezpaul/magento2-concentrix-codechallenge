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
    const LOADED_KEY_REGISTRY = 'concentrix_codechallenge_loaded';

    /**
     * @var CorporateGroupInterfaceFactory
     */
    protected CorporateGroupInterfaceFactory $corporateGroupFactory;

    /**
     * @var ResourceCorporateGroup
     */
    protected ResourceCorporateGroup $resourceCorporateGroup;

    /**
     * @var CorporateGroupCollectionFactory
     */
    protected CorporateGroupCollectionFactory $CorporateGroupCollectionFactory;

    /**
     * @var mixed|null
     */
    protected $loadedRecord = null;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @param CorporateGroupInterfaceFactory $corporateGroupFactory
     * @param ResourceCorporateGroup $resourceCorporateGroup
     * @param CorporateGroupCollectionFactory $CorporateGroupCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CorporateGroupInterfaceFactory  $corporateGroupFactory,
        ResourceCorporateGroup          $resourceCorporateGroup,
        CorporateGroupCollectionFactory $CorporateGroupCollectionFactory,
        DataPersistorInterface          $dataPersistor,
        CustomerRepositoryInterface     $customerRepository
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
     * @param array $corporateGroupRecords
     * @param bool $merge
     * @return $this
     */
    protected function setloadedRecordRecords(array $corporateGroupRecords = [], bool $merge = false)
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

        return $this;
    }

    /**
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
     * @param $id
     * @return CorporateGroupInterface|NoSuchEntityException|mixed
     */
    public function get($id)
    {
        $corporateGroup = $this->corporateGroupFactory->create();
        $corporateGroup->getResource()->load($corporateGroup, $id, 'entity_id');
        if (!$corporateGroup->getEntityId()) {
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
                $this->setloadedRecordRecords([$corporateGroupRecord], true);
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
     * @inheritDoc
     */
    public function getAll($forceReload = false)
    {
        if (empty($this->loadedRecord) || $forceReload) {
            $this->CorporateGroupCollectionFactory->addOrder('`order`', 'ASC');
            $corporateGroupRecords = $this->CorporateGroupCollectionFactory->load();
            $this->setloadedRecordRecords($corporateGroupRecords);
        }
        return $this->loadedRecord;
    }

    /**
     * Create CorporateGroup
     *
     * @param  $data
     * @return false|CorporateGroupInterface
     * @throws CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createCorporateGroupRecord($data)
    {
        if ($data instanceof CorporateGroupInterface) {
            $data = $data->getData();
        }

        if (!is_array($data) || !$this->validateStructure($data)) {
            return false;
        }
        //Check if CorporateGroup already exists
        $groupId = $data[CorporateGroupInterface::GROUP_ID];
        $corporateGroup = $this->corporateGroupFactory->create();
        $corporateGroup->getResource()->load($corporateGroup, $groupId, 'group_id');
        if ($corporateGroup->getEntityId() > 0) {
            return false;
        }

        $corporateGroupRecord = $this->corporateGroupFactory->create();
        $corporateGroupRecord->setGroupId($groupId);
        $corporateGroupRecord->setGroupName($data[CorporateGroupInterface::GROUP_NAME]);
        $corporateGroupRecord->setTelephone($data[CorporateGroupInterface::TELEPHONE]);
        $corporateGroupRecord->setEmail($data[CorporateGroupInterface::EMAIL]);
        $corporateGroupRecord = $this->save($corporateGroupRecord);
        $this->loadedRecord[$corporateGroupRecord->getGroupId()] = $corporateGroupRecord;

        return $corporateGroupRecord;
    }

    /**
     * Evaluate if CorporateGroup has all mandatory fields
     *
     * @param array $data
     * @return bool
     */
    protected function validateStructure(array $data): bool
    {
        if (!isset($data[CorporateGroupInterface::GROUP_NAME])) {
            return false;
        }
        if (!isset($data[CorporateGroupInterface::GROUP_ID])) {
            return false;
        }
        if (!isset($data[CorporateGroupInterface::EMAIL])) {
            return false;
        }
        if (!isset($data[CorporateGroupInterface::TELEPHONE])) {
            return false;
        }
        return true;
    }
}
