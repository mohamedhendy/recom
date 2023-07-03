<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 *
 * @property string             $tvc_id
 * @property string             $tvc_notes
 * @property string             $tvc_user_id
 * @property string             $tvc_group_id
 * @property string             $tvc_device_id
 * @property string             $tvc_user_name
 * @property string             $tvc_group_name
 * @property string             $tvc_device_name
 * @property string             $tvc_contact_id
 * @property double             $tvc_fee
 * @property string             $tvc_currency
 * @property string             $tvc_billing_state
 * @property Datetime           $tvc_start_date
 * @property Datetime           $tvc_end_date
 * @property integer            $rec_id
 * @property Datetime           $tvc_created_at
 * @property Datetime           $tvc_updated_at
 * @property integer            $tvc_created_by
 * @property integer            $tvc_updated_by
 * @property integer            $tvc_updated_revision
 * @property string             $tvc_duration
 * @property string             $tvc_status
 * @property boolean            $tvc_ignored
 * @property string             $tvc_internal_comment
 * @property string             $tvc_outlook_entry_id
 * @property integer            $tvc_seq
 * @property string             $tvc_type
 * @property integer            $cu_id
 * @property integer            $tvc_ae
 * @property integer            $ae_mode_id
 * @property                    $tvc_extra_data
 */
class TeamviewerConnection extends Model
{

}
