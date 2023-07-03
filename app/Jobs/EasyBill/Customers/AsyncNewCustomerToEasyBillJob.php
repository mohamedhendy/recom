<?php

namespace App\Jobs\EasyBill\Customers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AsyncNewCustomerToEasyBillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $customer;

    /**
     * Create a new job instance.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $response = app('EasyBill')->request('POST', 'customers', [
            "acquire_options" => null,
            "bank_account" => null,
            "bank_account_owner" => null,
            "bank_bic" => null,
            "bank_code" => null,
            "bank_iban" => null,
            "bank_name" => null,
            "birth_date" => Carbon::now()->toDateString(),
            "cash_allowance" => null,
            "cash_allowance_days" => 7,
            "cash_discount" => null,
            "cash_discount_type" => null,
            "city" => $this->customer->address_city,
            "state" => "",
            "company_name" => config('app.name'),
            "country" => $this->customer->address_country,
            "delivery_title" => $this->customer->delivery_address_name,
            "delivery_city" => $this->customer->delivery_address_city,
            "delivery_state" => "",
            "delivery_company_name" => config('app.name'),
            "delivery_country" => "DE", //$this->customer->delivery_address_country
            "delivery_first_name" => $this->customer->delivery_address_first_name,
            "delivery_last_name" => "somename",
            "delivery_personal" => "",
            "delivery_salutation" => 1, //$this->customer->delivery_address_salutation
            "delivery_street" => $this->customer->delivery_address_street,
            "delivery_suffix_1" => $this->customer->delivery_address_addon1,
            "delivery_suffix_2" => $this->customer->delivery_address_addon2,
            "delivery_zip_code" => $this->customer->delivery_address_zip_code,
            "emails" => [
                $this->customer->contact_email,
            ],
            "fax" => $this->customer->contact_fax,
            "first_name" => $this->customer->name,
            "grace_period" => null,
            "due_in_days" => null,
            "group_id" => null,
            "info_1" => null,
            "info_2" => null,
            "internet" => "https://www.easybill.de",
            "last_name" => $this->customer->name,
            "login_id" => 0,
            "mobile" => $this->customer->contact_mobile,
            "note" => $this->customer->note,
            "number" => $this->customer->number,
            "payment_options" => null,
            "personal" => false,
            "phone_1" => $this->customer->contact_phone1,
            "phone_2" => $this->customer->contact_phone2,
            "postbox" => "",
            "postbox_city" => null,
            "postbox_state" => null,
            "postbox_country" => null,
            "postbox_zip_code" => null,
            "sale_price_level" => null,
            "salutation" => 2, // $this->customer->address_salutation
            "sepa_agreement" => "",
            "sepa_agreement_date" => "",
            "sepa_mandate_reference" => "",
            "since_date" => "",
            "street" => $this->customer->address_street,
            "suffix_1" => $this->customer->address_addon1,
            "suffix_2" => $this->customer->address_addon2,
            "tax_number" => "",
            "court" => "",
            "court_registry_number" => "",
            "tax_options" => "",
            "title" => $this->customer->address_name,
            "vat_identifier" => "",
            "zip_code" => $this->customer->address_zip_code,
            "documentPdfType" => "default",
        ]);
        $result = json_encode($response);
        $this->customer->update(
            [
                'easy_bill_id' => $response['id'],
            ]
        );
    }
}
