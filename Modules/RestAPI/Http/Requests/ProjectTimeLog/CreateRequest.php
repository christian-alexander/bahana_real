<?php

namespace Modules\RestAPI\Http\Requests\ProjectTimeLog;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

      public function authorize()
      {
            $user = api_user();

            // Either user has role admin or has permission view_projects
            // Plus he needs to have projects module enabled from settings
            return true;
            return in_array('timelogs', $user->modules);
      }

      public function rules()
      {
            return [
                  'company_id' => 'required',
                  // 'project_id' =>'required',
                  'user_id' => 'required',
                  'start_time' => 'required',
                  'end_time' => 'required',
                  'memo' => 'required',
                  'total_hours' => 'required',
                  'total_minutes' => 'required',
            ];
      }
}
