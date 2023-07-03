<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @property int      $id
 * @property string   $legal_form
 * @property string   $company
 * @property string   $name
 * @property string   $first_name
 * @property string   $address
 * @property string   $zip_code
 * @property string   $city
 * @property string   $po_zip_code
 * @property string   $po_city
 * @property string   $po_country
 * @property string   $tax_id
 * @property string   $vat_id
 * @property string   $sepa_creditor_id
 * @property string   $contact_phone1
 * @property string   $contact_phone2
 * @property string   $contact_fax
 * @property string   $contact_mobile
 * @property string   $contact_email
 * @property string   $contact_homepage
 * @property string   $bank1_name
 * @property string   $bank1_iban
 * @property string   $bank1_bic
 * @property string   $bank2_name
 * @property string   $bank2_iban
 * @property string   $bank2_bic
 * @property bool     $use_easy_bill
 * @property string   $easy_bill_api_key
 * @property int      $last_invoice_number
 * @property int      $invoice_year
 * @property int      $invoice_number_length
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 *
 * @property User[]   $users
 */
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * @return User[]|HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
