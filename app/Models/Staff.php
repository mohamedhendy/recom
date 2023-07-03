<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int          $id
 * @property string       $staff_number
 * @property string       $salutation
 * @property string       $name
 * @property string       $first_name
 * @property string       $address
 * @property string       $zip_code
 * @property string       $city
 * @property string       $gender
 * @property string       $nationality
 * @property DateTime     $date_of_birth
 * @property string       $birth_city
 * @property string       $birth_country
 * @property bool         $disability
 * @property string       $disability_degree
 * @property string       $tax_id
 * @property string       $tax_class
 * @property string       $child_allowances
 * @property string       $religion
 * @property string       $social_security_number
 * @property string       $health_insurance
 * @property bool         $parent
 * @property string       $time_model
 * @property double       $weekly_working_time
 * @property double       $daily_working_time
 * @property string       $salary_type
 * @property double       $hourly_rate
 * @property double       $monthly_rate
 * @property string       $contact_phone1
 * @property string       $contact_phone2
 * @property string       $contact_fax
 * @property string       $contact_mobile
 * @property string       $contact_email
 * @property string       $contact_homepage
 * @property string       $bank_name
 * @property string       $bank_iban
 * @property string       $bank_bic
 * @property DateTime     $working_time_monday_from
 * @property DateTime     $working_time_monday_to
 * @property DateTime     $working_time_tuesday_from
 * @property DateTime     $working_time_tuesday_to
 * @property DateTime     $working_time_wednesday_from
 * @property DateTime     $working_time_wednesday_to
 * @property DateTime     $working_time_thursday_from
 * @property DateTime     $working_time_thursday_to
 * @property DateTime     $working_time_friday_from
 * @property DateTime     $working_time_friday_to
 * @property DateTime     $working_time_saturday_from
 * @property DateTime     $working_time_saturday_to
 * @property DateTime     $working_time_sunday_from
 * @property DateTime     $working_time_sunday_to
 * @property bool         $legal_break_regulation
 * @property DateTime     $working_start_date
 * @property DateTime     $working_end_date
 *
 * @property SaleOrder[]  $saleOrder
 * @property User[]       $users
 * @property Deployment[] $deployments
 */
class Staff extends Base
{
    protected $table = 'staffs';

    /**
     * @return SaleOrder[]|morphMany
     */
    public function saleOrders(): morphMany
    {
        return $this->morphMany(SaleOrder::class, 'identity')->orderBy('id', 'desc');
    }

    /**
     * @return User[]|HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'staff_id', 'id');
    }

    /**
     * @return Deployment[]|MorphMany
     */
    public function deployments(): MorphMany
    {
        return $this->morphMany(Deployment::class, 'identity');
    }
}
