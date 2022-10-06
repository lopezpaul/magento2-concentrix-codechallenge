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

use Concentrix\CodeChallenge\Api\CorporateGroupApiInterface;
use Concentrix\CodeChallenge\Api\Data\CorporateGroupInterface;
use Concentrix\CodeChallenge\Model\ResourceModel\CorporateGroup as ResourceCorporateGroup;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class CorporateGroupApi implements CorporateGroupApiInterface
{
    /** @var CorporateGroupFactory */
    private CorporateGroupFactory $corporateGroupFactory;

    /** @var CustomerFactory */
    private CustomerFactory $customerFactory;

    /** @var CustomerRepositoryInterface */
    private CustomerRepositoryInterface $customerRepository;

    /** @var ResourceCorporateGroup */
    private ResourceCorporateGroup $resourceModel;

    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    /**
     * CorporateGroup API Constructor
     *
     * @param CorporateGroupFactory $corporateGroupFactory
     * @param CustomerFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param ResourceCorporateGroup $resourceModel
     * @param LoggerInterface $logger
     */
    public function __construct(
        CorporateGroupFactory $corporateGroupFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        ResourceCorporateGroup $resourceModel,
        LoggerInterface $logger
    ) {
        $this->corporateGroupFactory = $corporateGroupFactory;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->resourceModel = $resourceModel;
        $this->logger = $logger;
    }

    /**
     * Search CorporateGroup by group_id
     *
     * @param string $groupId
     * @return array
     */
    public function getByGroupId(string $groupId): array
    {
        $data = [
            'success' => false,
            'error' => '',
            'results' => []
        ];
        try {
            $name = strtoupper(trim($groupId));
            $corporateGroupCollection = $this->corporateGroupFactory->create()->getCollection();
            $corporateGroupCollection->setOrder(corporateGroupInterface::GROUP_ID, 'ASC');
            $corporateGroupCollection->addFieldToFilter(
                [
                    corporateGroupInterface::GROUP_ID
                ],
                [
                    ['eq' => $name]
                ]
            );
            if ($corporateGroupCollection->getSize() === 0) {
                throw new LocalizedException(__('No results found'));
            }
            $results = [];
            foreach ($corporateGroupCollection as $corporateGroupRecord) {
                if (empty($corporateGroupRecord)) {
                    continue;
                }
                $id = $corporateGroupRecord->getEntityId();
                if (empty($id)) {
                    continue;
                }
                $results[$id] = $corporateGroupRecord->getData();
            }
            $data = [
                'success' => true,
                'results' => $results
            ];
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }

    /**
     * Retrieve by entity_id
     *
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $results = [];
        $data = [
            'success' => false,
            'error' => '',
            'results' => $results
        ];
        try {
            $corporateGroup = $this->corporateGroupFactory->create()->load($id);
            if (!empty($corporateGroup->getId())) {
                $results[$id] = $corporateGroup->getData();
            }
            $data = [
                'success' => true,
                'results' => $results
            ];
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }

    /**
     * Create CorporateGroup
     *
     * @param string $groupId
     * @param string $groupName
     * @param string $email
     * @param string $telephone
     * @return array
     * @throws \Exception
     */
    public function create(
        string $groupId,
        string $groupName,
        string $email,
        string $telephone
    ): array {
        $results = [];
        $data = [
            'success' => false,
            'error' => '',
            'results' => $results
        ];
        try {
            $errors = [];
            if (empty($groupId)) {
                $errors[] = __('Field %1 is empty.', 'group_id')->render();
            }
            if (empty($groupName)) {
                $errors[] = __('Field %1 is empty.', 'group_name')->render();
            }
            if (empty($email)) {
                $errors[] = __('Field %1 is empty.', 'email')->render();
            }
            if (empty($telephone)) {
                $errors[] = __('Field %1 is empty.', 'telephone')->render();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter valid email.';
            }
            if (!$this->isValidPhone($telephone)) {
                $errors[] = 'Please enter valid telephone like: (123) 456-7890 or 123-456-7890.';
            }
            $newRecord = [
                corporateGroupInterface::GROUP_NAME => $groupName ?? '',
                corporateGroupInterface::EMAIL => $email ?? '',
                corporateGroupInterface::TELEPHONE => $telephone
            ];

            $corporateGroup = $this->corporateGroupFactory->create()->load($groupId, 'group_id');
            if (!empty($corporateGroup->getId())) {
                $errors[] = __('The group_id "%1" already exists.', $groupId);
            }
            if (!empty($errors)) {
                throw new LocalizedException(__(implode('. ', $errors)));
            }

            if (!empty($groupId)) {
                $newRecord[corporateGroupInterface::GROUP_ID] = $groupId;
            }
            $newCorporateGroupRecord = $this->corporateGroupFactory->create();
            $newCorporateGroupRecord->addData($newRecord);
            $newCorporateGroupRecord->save();
            if (!empty($newRecord)) {
                $results[$newCorporateGroupRecord->getId()] = $newRecord;
            }
            $data = [
                'success' => true,
                'results' => $results
            ];
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }

    /**
     * Remove by group_id
     *
     * @param string $groupId
     * @return array
     * @throws \Exception
     */
    public function deleteByGroupId(string $groupId): array
    {
        $data = [
            'success' => false,
            'error' => ''
        ];
        try {
            $corporateGroup = $this->corporateGroupFactory->create()->load($groupId, 'group_id');
            if (!empty($corporateGroup->getId())) {
                $this->resourceModel->delete($corporateGroup);
            }
            $data['success'] = true;
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }

    /**
     * Validate phone number in format: (123) 456-7890 or 123-456-7890
     *
     * @param string $phone
     * @return bool
     */
    protected function isValidPhone(string $phone): bool
    {
        $pattern = "/(\()?\d{3}(\))?(-|\s)?\d{3}(-|\s)\d{4}/i";
        return preg_match($pattern, $phone) === 1;
    }

    /**
     * Link a corporate group to a customer by customer id.
     *
     * @param string $groupId
     * @param int $customerId
     * @return array
     */
    public function linkToCustomerById(string $groupId, int $customerId): array
    {
        $data = [
            'success' => false,
            'error' => '',
            'group_id' => $groupId,
            'customer_id' => $customerId
        ];
        try {
            $customer = $this->customerRepository->getById($customerId);
            $customer->setCustomAttribute(ResourceCorporateGroup::ATTRIBUTE_CODE, $groupId);
            $this->customerRepository->save($customer);
            $message = __('Group #%1 was successfully associated to customer id: %2', $groupId, $customerId)->render();
            $data['error'] = $message;
            $data['success'] = true;
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }

    /**
     * Link a corporate group to a customer by customer email.
     *
     * @param string $groupId
     * @param string $email
     * @return array
     */
    public function linkToCustomerByEmail(string $groupId, string $email): array
    {
        $data = [
            'success' => false,
            'error' => ''
        ];
        try {
            $customer = $this->customerRepository->get($email);
            $customer->setCustomAttribute(ResourceCorporateGroup::ATTRIBUTE_CODE, $groupId);
            $this->customerRepository->save($customer);
            $message = __('Group #%1 was successfully associated to customer email: %2', $groupId, $email)->render();
            $data['error'] = $message;
            $data['success'] = true;
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
            $this->logger->error($errorMessage);
            $data['error'] = $errorMessage;
        }
        return $data;
    }
}
