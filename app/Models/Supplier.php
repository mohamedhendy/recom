<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int             $id
 * @property int             $number
 * @property string          $name
 * @property string          $description
 * @property string          $address_salutation
 * @property string          $address_name
 * @property string          $address_first_name
 * @property string          $address_addon1
 * @property string          $address_addon2
 * @property string          $address_street
 * @property string          $address_zip_code
 * @property string          $address_city
 * @property string          $address_country
 * @property string          $tax_id
 * @property string          $vat_id
 * @property string          $customer_number
 * @property string          $payment_terms
 * @property string          $contact_name1
 * @property string          $contact_function1
 * @property string          $contact_name2
 * @property string          $contact_function2
 * @property string          $contact_details1_phone1
 * @property string          $contact_details1_phone2
 * @property string          $contact_details1_fax
 * @property string          $contact_details1_mobile
 * @property string          $contact_details1_email
 * @property string          $contact_details1_homepage
 * @property string          $contact_details2_phone1
 * @property string          $contact_details2_phone2
 * @property string          $contact_details2_fax
 * @property string          $contact_details2_mobile
 * @property string          $contact_details2_email
 * @property string          $contact_details2_homepage
 * @property string          $bank_name
 * @property string          $bank_iban
 * @property string          $bank_bic
 *
 * @property PurchaseOrder[] $purchaseOrders
 */
class Supplier extends Base
{
    /**
     * @return PurchaseOrder[]|HasMany
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id', 'id');
    }
}
