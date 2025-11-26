<?php
namespace GP\EmailGroup\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;

class AssignGroupByEmailDomain implements ObserverInterface
{
    protected $customerRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

   public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if (!$customer || !$customer->getEmail()) {
            return;
        }

        // Extract domain from email
        $email = $customer->getEmail();
        $domain = strtolower(substr(strrchr($email, "@"), 1));

        // Check if email domain matches Grameenphone
        if ($domain === 'grameenphone.com') {
            // Set Grameenphone group ID (example: 5)
            $customer->setGroupId(5);
            $customer->setWebsiteId($customer->getWebsiteId() ?: 1); // ensure website is set
            $this->customerRepository->save($customer);
        }
    }
}
