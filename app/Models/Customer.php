<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int          $id
 * @property int          $number
 * @property string       $name
 * @property string       $note
 * @property string       $type
 * @property string       $address_salutation
 * @property string       $address_name
 * @property string       $address_first_name
 * @property string       $address_addon1
 * @property string       $address_addon2
 * @property string       $address_street
 * @property string       $address_zip_code
 * @property string       $address_city
 * @property string       $address_country
 * @property string       $delivery_address_salutation
 * @property string       $delivery_address_name
 * @property string       $delivery_address_first_name
 * @property string       $delivery_address_addon1
 * @property string       $delivery_address_addon2
 * @property string       $delivery_address_street
 * @property string       $delivery_address_zip_code
 * @property string       $delivery_address_city
 * @property string       $delivery_address_country
 * @property string       $contact_phone1
 * @property string       $contact_phone2
 * @property string       $contact_fax
 * @property string       $contact_mobile
 * @property string       $contact_email
 * @property string       $contact_homepage
 * @property int          $easy_bill_id
 *
 * @property SaleOrder[]  $saleOrders
 * @property Project[]    $projects
 * @property Deployment[] $deployments
 */
class Customer extends Base
{
    /**
     * @return SaleOrder[]|morphMany
     */
    public function saleOrders(): morphMany
    {
        return $this->morphMany(SaleOrder::class, 'identity')->orderBy('id', 'desc');
    }

    /**
     * @return Project[]|HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'customer_id', 'id');
    }

    /**
     * @return Deployment[]|MorphMany
     */
    public function deployments(): MorphMany
    {
        return $this->morphMany(Deployment::class, 'identity');
    }
}
