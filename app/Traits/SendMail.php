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

    public static function send($data = [], $class = '', $type = '', $approval_type = 1) {
        /** Approval Type means Request / Approve / Reject */
        /* Type means which Approver -> 1 -> RM 2 -> Module Head 3 - .... */
        $userId = Auth::user()->employee_id;
   
        $dataArray = self::getData($type, $class, $data, $approval_type);
       // return $dataArray;
        $namespace = 'App\Mail';
        $email = [];
       // $dataArray = [];
        $classname = $namespace . '\\' . $class;
        if(empty($type)) {
            return response(['message' => 'Type not found', 'status' => 400],400);
        }

            try {
                if(class_exists($classname)) {
                    $d = [];
                 //return $dataArray;
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

    public static function getData($type, $classname, $data, $approval_type) {
        $dataArray = [];

        switch($classname) {
            case 'SapRequestMail':
                $requested = SAPRequest::where('id', $data[0]['id'])->with('user', 'modules.module_head.user_details', 'tcodes', 'action')->first();
               // 1 - Request 2 - Approve 3 -> Reject
               switch($approval_type) {

                case 1:
                // type says if its RM or MH or SAP Lead etc
                    switch($type) {
                        // reporting manager 
                        case 1:
                                $userId = Auth::user()->employee_id;
                                $model = EmployeeMappings::where('employee_id', $userId)->with('report_employee','employee')->first();
                                if($model) {
                                    $usermail = Auth::user()->email;
                                    $username = $model->employee->first_name.' '.$model->employee->last_name;
                                    $reportToEmail = $model->report_employee->email;
                                    $reportToName = $model->report_employee->first_name.' '.$model->report_employee->last_name;
                                    $modules = [];
                                    $modules = $requested->modules->name ?? '-';                          
                                    // get template and variables to be replaced
                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first();
                                    $templateHTML = [];
                                    if($template) {
                                        $templateHTML['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $username, $template->html_template));
                                        $templateHTML['email'] = $usermail;
                                    }
                                    
                                    $dataArray[0] = $templateHTML;

                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 1])->first();
                                    $templateHTML_1 = [];
                                    if($template) {
                                        $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                        $templateHTML_1['email'] = $reportToEmail;
                                    }

                                    $dataArray[1] = $templateHTML_1;
                                } 
                            
                            break;
                            // module head
                            case 2:
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                                    
                                foreach($requested as $each) {
                                    if(!empty($each->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],
                                                str_replace("##user_id##",
                                                    $each->modules->module_head->user_details->name,
                                                    $template->html_template
                                                )
                                            );

                                            $templateHTML_1['email'] = $each->modules->module_head->user_details->email;
                                            array_push($dataArray, $templateHTML_1);
                                        }
                                    
                                    }
                                    
                                }
                                break;                
                        }
                break;

               
                case 2:
                    //approve
                    switch($type) {
                        // reporting manager 
                        case 1:
                                $userId = Auth::user()->employee_id;
                                // find log for approved transactions
                                $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 1])->with('created_by_user')->get();
                                
                                $model = EmployeeMappings::where('employee_id', $userId)->with('report_employee','employee')->first();
                                if($model) {
                                    $usermail = Auth::user()->email;
                                    $username = $model->employee->first_name.' '.$model->employee->last_name;
                                    $reportToEmail = $model->report_employee->email;
                                    $reportToName = $model->report_employee->first_name.' '.$model->report_employee->last_name;
                                    $modules = [];
                                    $modules = $requested->modules->name ?? '-';                          
                                    // get template and variables to be replaced
                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first();
                                    $templateHTML = [];
                                    if($template && $logs->Count()>0) {
                                        $status = $logs[0]->status ?? '-';
                                        $status = 'Rejected';
                                        if($status == 1) {
                                            $status = 'Approved';
                                        } 
                                        $remarks = $logs[0]->remarks ?? '-';
                                        $created_by = $logs[0]->created_by_user->name ?? '-';
                                        $approval_stage = $logs[0]->approval_stage;
                                        $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->with('approval_matrix')->first();
                                        $approval_stage = $approval_stage->approval_type ?? '-';
                                        $templateHTML['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $username, $template->html_template))))));
                                        $templateHTML['email'] = $usermail;
                                    } 
                                    
                                    $dataArray[0] = $templateHTML;
    
                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 1])->first();
                                    $templateHTML_1 = [];
                                    if($template) {
                                        $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                        $templateHTML_1['email'] = $reportToEmail;
                                    }
    
                                    $dataArray[1] = $templateHTML_1;
                                } 
                               
                            break;
                            // module head
                            case 2:
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                                    
                                foreach($requested as $each) {
                                    if(!empty($each->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],
                                                str_replace("##user_id##",
                                                    $each->modules->module_head->user_details->name,
                                                    $template->html_template
                                                )
                                            );
    
                                            $templateHTML_1['email'] = $each->modules->module_head->user_details->email;
                                            array_push($dataArray, $templateHTML_1);
                                        }
                                       
                                    }
                                    
                                }
                                break;                
                        }
                    break;


                case 3:
                // reject
                    break;
            }
                    break;

                case 'EmailRequest':
                    break;
                
                case 'CRMRequest':
                    break;
                
                default:
                    $dataArray = [];
        }

        return $dataArray;
    
    }
}

?>