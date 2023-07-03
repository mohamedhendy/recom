<?php

namespace App\Jobs\Customer\EasyBill;

use App\Models\Customer;
use App\Support\EasyBillHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;

class AsyncCustomersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;


    /**
     * @var integer
     */
    private $requestCounter = 1;

    private $loggedUser;

    /**
     * @var integer
     */

    private $page = 1;

    private $totalpages = null;

    private $customersToUpdate = [];

    /**
     * Create a new job instance.
     *
     * @param null   $loggedUser
     * @param string $uuid
     *
     */
    public function __construct($loggedUser = null, string $uuid = "")
    {

        // $this->prepareStatus(['uuid' => $uuid]);
        $this->loggedUser = $loggedUser ?? auth()->user();
    }

    /**
     * Execute the job.
     *
     * @return array customerDetailsToUpdate
     */
    public function handle(): array
    {
        $this->handleData();

        return $this->handleUpdatedCustomers();
    }

    public function handleData()
    {
        if ($this->page) {
            if ($this->requestCounter % 10 == 0) {
                sleep(1.5 * 1000);
            }

            $data = app('EasyBill')->request('GET', 'customers?page=' . $this->page);

            if (!$this->totalpages) {
                $this->totalpages = $data['pages'];
                // $this->setProgressMax($this->totalpages);
            }

            // $data = json_decode($data);
            foreach ((array)$data['items'] as $easyBillCustomer) {
                $easyBillCustomer = (array)$easyBillCustomer;
                Customer::withoutEvents(function () use ($easyBillCustomer) {
                    $customerEasybillId = $easyBillCustomer['id'];
                    $dbCustomer = Customer::where('easy_bill_id', $customerEasybillId)
                        ->orWhere('number', $easyBillCustomer['number'])
                        ->first();
                    if (!$dbCustomer) {
                        $this->createCustomerInDb($easyBillCustomer);
                    } else {
                        array_push($this->customersToUpdate, $easyBillCustomer);
                    }
                });
            }


            $this->requestCounter++;

            // If we have more pages left.
            if ($data['pages'] >= $this->page) {
                $this->setProgressNow($data['page']);

                // we request for data again.
                $this->page++;
                $this->handleData();
            } else {
                // we set the status to complete.
                $this->setOutput(['total' => $this->totalpages]);
            }
        }
    }

    private function createCustomerInDb(array $easyBillCustomer)
    {

        Customer::create([
            'created_by_id' => $this->loggedUser->id,
            'updated_by_id' => $this->loggedUser->id,
            'number' => $easyBillCustomer['number'],
            'name' => $easyBillCustomer['display_name'],
            'note' => $easyBillCustomer['note'],
            'address_salutation' => EasyBillHelper::convertSalutationToInt($easyBillCustomer['salutation']),
            'address_name' => $easyBillCustomer['last_name'],
            'address_first_name' => $easyBillCustomer['first_name'],
            'address_addon1' => $easyBillCustomer['suffix_1'],
            'address_addon2' => $easyBillCustomer['suffix_2'],
            'address_street' => $easyBillCustomer['street'],
            'address_zip_code' => $easyBillCustomer['zip_code'],
            'address_city' => $easyBillCustomer['city'],
            'address_country' => $easyBillCustomer['country'],
            'delivery_address_salutation' => EasyBillHelper::convertSalutationToInt($easyBillCustomer['delivery_salutation']),
            'delivery_address_name' => $easyBillCustomer['delivery_title'],
            'delivery_address_first_name' => $easyBillCustomer['delivery_first_name'],
            'delivery_address_addon1' => $easyBillCustomer['delivery_suffix_1'],
            'delivery_address_addon2' => $easyBillCustomer['delivery_suffix_2'],
            'delivery_address_street' => $easyBillCustomer['delivery_street'],
            'delivery_address_zip_code' => $easyBillCustomer['delivery_zip_code'],
            'delivery_address_city' => $easyBillCustomer['delivery_city'],
            'delivery_address_country' => $easyBillCustomer['delivery_country'],
            'contact_phone1' => $easyBillCustomer['phone_1'],
            'contact_phone2' => $easyBillCustomer['phone_2'],
            'contact_fax' => $easyBillCustomer['fax'],
            'contact_mobile' => $easyBillCustomer['mobile'],

            'contact_homepage' => '',
            'easy_bill_id' => $easyBillCustomer['id'],
        ]);
    }

    private function handleUpdatedCustomers(): array
    {
        $customerDetailsToUpdate = [];
        $id = 1;

        foreach ($this->customersToUpdate as $easyBillCustomer) {
            $customerDetailToUpdate = [];

            $dbCustomer = Customer::where('easy_bill_id', $easyBillCustomer['id'])
                ->orWhere('number', $easyBillCustomer['number'])
                ->first();

            $dbCustomerArray = $dbCustomer->toArray();

            $details = [
                [
                    'db' => 'number',
                    'easyBill' => $easyBillCustomer['number'],
                    'name' => 'number'
                ],
                [
                    'db' => 'name',
                    'easyBill' => $easyBillCustomer['display_name'],
                    'name' => 'display_name'
                ],
                [
                    'db' => 'address_first_name',
                    'easyBill' => $easyBillCustomer['first_name'],
                    'name' => 'first_name'
                ],
                [
                    'db' => 'address_name',
                    'easyBill' => $easyBillCustomer['last_name'],
                    'name' => 'last_name'
                ],
                [
                    'db' => 'note',
                    'easyBill' => $easyBillCustomer['note'],
                    'name' => 'note'
                ],
                [
                    'db' => 'address_salutation',
                    'easyBill' => EasyBillHelper::convertSalutationToString($easyBillCustomer['salutation']),
                    'name' => 'address_salutation'
                ],
                [
                    'db' => 'address_addon1',
                    'easyBill' => $easyBillCustomer['suffix_1'],
                    'name' => 'address_addon1'
                ],
                [
                    'db' => 'address_addon2',
                    'easyBill' => $easyBillCustomer['suffix_2'],
                    'name' => 'address_addon2'
                ],
                [
                    'db' => 'address_street',
                    'easyBill' => $easyBillCustomer['street'],
                    'name' => 'address_street'
                ],
                [
                    'db' => 'address_zip_code',
                    'easyBill' => $easyBillCustomer['zip_code'],
                    'name' => 'address_zip_code'
                ],
                [
                    'db' => 'address_city',
                    'easyBill' => $easyBillCustomer['city'],
                    'name' => 'address_city'
                ],
                [
                    'db' => 'address_country',
                    'easyBill' => $easyBillCustomer['country'],
                    'name' => 'address_country'
                ],
                [
                    'db' => 'delivery_address_salutation',
                    'easyBill' => EasyBillHelper::convertSalutationToString($easyBillCustomer['delivery_salutation']),
                    'name' => 'delivery_address_salutation'
                ],
                [
                    'db' => 'delivery_address_name',
                    'easyBill' => $easyBillCustomer['delivery_title'],
                    'name' => 'delivery_address_name'
                ],
                [
                    'db' => 'delivery_address_first_name',
                    'easyBill' => $easyBillCustomer['delivery_first_name'],
                    'name' => 'delivery_address_first_name'
                ],
                [
                    'db' => 'delivery_address_addon1',
                    'easyBill' => $easyBillCustomer['delivery_suffix_1'],
                    'name' => 'delivery_address_addon1'
                ],
                [
                    'db' => 'delivery_address_addon2',
                    'easyBill' => $easyBillCustomer['delivery_suffix_2'],
                    'name' => 'delivery_address_addon2'
                ],
                [
                    'db' => 'delivery_address_street',
                    'easyBill' => $easyBillCustomer['delivery_street'],
                    'name' => 'delivery_address_street'
                ],
                [
                    'db' => 'delivery_address_zip_code',
                    'easyBill' => $easyBillCustomer['delivery_zip_code'],
                    'name' => 'delivery_address_zip_code'
                ],
                [
                    'db' => 'delivery_address_city',
                    'easyBill' => $easyBillCustomer['delivery_city'],
                    'name' => 'delivery_address_city'
                ],
                [
                    'db' => 'delivery_address_country',
                    'easyBill' => $easyBillCustomer['delivery_country'],
                    'name' => 'delivery_address_country'
                ],
                [
                    'db' => 'contact_phone1',
                    'easyBill' => $easyBillCustomer['phone_1'],
                    'name' => 'contact_phone1'
                ],
                [
                    'db' => 'contact_phone2',
                    'easyBill' => $easyBillCustomer['phone_2'],
                    'name' => 'contact_phone2'
                ],
                [
                    'db' => 'contact_fax',
                    'easyBill' => $easyBillCustomer['fax'],
                    'name' => 'contact_fax'
                ],
                [
                    'db' => 'contact_mobile',
                    'easyBill' => $easyBillCustomer['mobile'],
                    'name' => 'contact_mobile'
                ],
            ];

            foreach ($details as $detail) {
                $easyBillData = $detail['easyBill'];
                $dbData = $dbCustomerArray[$detail['db']];
                if (
                    $dbData != $easyBillData
                    && $this->isNotNull($dbData)
                    && $this->isNotNull($easyBillData)
                ) {
                    array_push($customerDetailToUpdate, [
                        'id' => $id++,
                        'name' => $detail['name'],
                        'database' => [
                            'data' => $dbData,
                            'name' => $detail['db'],
                            'id' => $dbCustomerArray['id']
                        ],
                        'easyBill' => [
                            'data' => $easyBillData,
                            'name' => $detail['easyBill'],
                            'id' => $easyBillCustomer['id']
                        ]
                    ]);
                }
            }

            if ($customerDetailToUpdate) {
                array_push($customerDetailsToUpdate, [
                    'dbCustomer' => $dbCustomerArray,
                    'ebCustomer' => $easyBillCustomer,
                    'detailsToUpdate' => $customerDetailToUpdate
                ]);
            }
        }

        return $customerDetailsToUpdate;
    }

    private function isNotNull($value): bool
    {
        return !is_null($value) && $value !== '' && $value !== [];
    }
}
