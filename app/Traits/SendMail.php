<?php
namespace App\Traits;
use Mail;
use Auth;
use EmployeeMappings;
use ModuleHead;
use SAPRequest;

trait SendMail
{
    protected $namespace = '';

    public static function send($data = [], $class = '', $type = '') {

        $userId = Auth::user()->employee_id;

        $namespace = 'App\Mail';
        $email = [];
        $dataArray = [];
        $classname = $namespace . '\\' . $class;
        if(empty($type)) {
            return response(['message' => 'Type not found', 'status' => 400],400);
        }

        $type = strtoupper($type);
        $request_id = $data['request_id'];
        $requested = SAPRequest::where('request_id', $request_id)->with('user', 'modules.module_head.user_details', 'tcodes', 'action')->get();

        switch($type)
        {
            case 'RM':
                $model = EmployeeMappings::where('employee_id', $userId)->with('report_employee','employee')->first();
                if($model) {
                    $usermail = Auth::user()->email;
                    $username = $model->employee->first_name.' '.$model->employee->last_name;
                    $reportToEmail = $model->report_employee->email;
                    $reportToName = $model->report_employee->first_name.' '.$model->report_employee->last_name;
                    $modules = [];
                    foreach($requested as $each) {
                        $modules[] = $each->modules->name;
                    }
                    $modules = implode(",", $modules);

                    $dataArray[0] = [
                        'request_id' => $data['request_id'],
                        'type'=> 'requested_by',
                        'name' => $username,
                        'email' => $usermail,
                        'modules' => $modules
                    ];

                    $dataArray[1] = [
                        'request_id' => $data['request_id'],
                        'type'=> 'reporting_manager',
                        'for' => $username,
                        'name' => $reportToName,
                        'email' => $reportToEmail,
                        'modules' => $modules
                    ];
                } 
            break;

            case 'MH':
                if($requested->Count() > 0) {
                    foreach($requested as $each) {
                        if(!empty($each->modules->module_head->user_details)) {
                            array_push($dataArray, ['modules'=> $each->modules->name, 'request_id' => $data['request_id'], 'type' => 'module_head', 'name' => $each->modules->module_head->user_details->name, 'email' => $each->modules->module_head->user_details->email, 'for' => Auth::user()->name]);
                        }
                        
                    }
                }
            break;
            default:
                

        }


            try {
                if(class_exists($classname)) {
                    $d = [];
                    foreach($dataArray as $e) {
                        if(self::isValidEmail($e['email'])) {
                            $mail = Mail::to($e['email'])->send(new $classname($e));
                        } else {
                            return response(['message' => 'Mail was not fired, either the mail address is invalid or empty', 'status' => 400], 400);
                        }
                    }

                    return response(['status' => 200, 'message' => 'Success'], 200);
                }
                else {
                    return response(['message' => 'Class not found', 'status' => 400],400);
                }
            } catch(\Exception $e) {
                return response(['message' => "Mail error ".$e->getMessage(), 'status' => 500], 500);
            }
       
        
    }

    public static function isValidEmail($email){ 
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

?>