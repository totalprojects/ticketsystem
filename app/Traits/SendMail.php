<?php
namespace App\Traits;
use Mail;
use Auth;
use EmployeeMappings;
use ModuleHead;
use SAPRequest;
use SAPApprovalLogs;
use Moderators;

trait SendMail
{
    protected $namespace = '';

    public static function send($data = [], $class = '', $type = '', $approval_type = 1) {
        /** Approval Type means Request / Approve / Reject */
        /* Type means which Approver -> 1 -> RM 2 -> Module Head 3 - .... */
        $userId = Auth::user()->employee_id;
   
        $dataArray = self::getData($type, $class, $data, $approval_type);
        $namespace = 'App\Mail';
        $email = [];
        $classname = $namespace . '\\' . $class;
        if(empty($type)) {
            return response(['message' => 'Type not found', 'status' => 400],400);
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
    public static function changeStatusMyModerators($type, $approval_type, $requested, $data, $status, $moderator_id) {

        $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => $type])->first();
        $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => $status, 'approval_stage' => $type])->with('created_by_user')->get();
        $templateHTML_1 = [];
        $dataArray = [];
            if($template) {
                $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                $templateID = $template->id;
                $userId = $data[0]['user_id'];
                $user = \Users::where('id',$userId)->first();
                $email = $user->email;
                $username = $user->name;
                $requester_id = $username;
                $variables = \MailVariables::where('template_id',$templateID)->get();
                $sap_lead = \Moderators::where('type_id', $moderator_id)->first();
                $employee_id = $sap_lead->employee_id;
                $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                $sapLeadEmail = $sapLeadEmail->email;
                $request_id = $data[0]['request_id'];
                $module_id = $requested->modules->name;
                $tcode_id = $requested->tcodes->t_code;
                $user_id = $username;
                $actions = '';
                foreach($requested->action as $ea) {
                    $actions .= $ea->name.',';
                }
                $remarks = $logs[0]->remarks ?? '-';
                $created_by = $logs[0]->created_by_user->name ?? '-';
                $approval_stage = $logs[0]->approval_stage;
                $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                $approval_stage = $approval_stage->approval_type ?? '-';
                $actions = substr($actions,0,-1);
                $templateHTML_1['template'] = $template->html_template;
                foreach($variables as $each) {
                    $var = str_replace("##", "", $each->variable_name);
                    if(isset($$var)) {
                        $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                    }
                }
                $templateHTML_1['email'] = $sapLeadEmail;
                array_push($dataArray, $templateHTML_1);

                // to the user ( requester )
                $template1 = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first(); 
                if($template1) {
                        $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                        $templateID = $template->id;
                        $userId = $data[0]['user_id'];
                        $user = \Users::where('id',$userId)->first();
                        $email = $user->email;
                        $username = $user->name;
                        // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                        $variables = \MailVariables::where('template_id',$templateID)->get();
                        $request_id = $data[0]['request_id'];
                        $module_id = $requested->modules->name;
                        $tcode_id = $requested->tcodes->t_code;
                        $user_id = $username;
                        $actions = '';
                        foreach($requested->action as $ea) {
                            $actions .= $ea->name.',';
                        }
                        $actions = substr($actions,0,-1);
                        $remarks = $logs[0]->remarks ?? '-';
                        $created_by = $logs[0]->created_by_user->name ?? '-';
                        $approval_stage = $logs[0]->approval_stage;
                        $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                        $approval_stage = $approval_stage->approval_type ?? '-';
                        $templateHTML_1['template'] = $template1->html_template;
                        foreach($variables as $each) {
                            $var = str_replace("##", "", $each->variable_name);
                            if(isset($$var)) {
                                $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                            }
                        }
                        $templateHTML_1['email'] = $email;
                        array_push($dataArray, $templateHTML_1);
                        // next moderator
                        $dataArray[] = SendMail::getNextModerator($requested->modules->id, $logs[0]->approval_stage, $templateHTML_1['template'],$user_id);
                }
            }
            
            return $dataArray;
    }

    public static function requestToModerators($requested, $approval_type, $type, $moderator_id) {
        $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => $type])->first();
        $templateHTML_1 = [];                        
            if($template) {
                $templateID = $template->id;
                $variables = \MailVariables::where('template_id',$templateID)->get();
                $request_id = $data[0]['request_id'];
                $module_id = $requested->modules->name;
                $tcode_id = $requested->tcodes->t_code;
                $sap_lead = Moderators::where('type_id',$moderator_id)->with('employee')->first();
                $user_id = $sap_lead->employee->first_name;
                $actions = '';
                foreach($requested->action as $ea) {
                    $actions .= $ea->name.',';
                }
                $actions = substr($actions,0,-1);
                $templateHTML_1['template'] = $template->html_template;
                foreach($variables as $each) {
                    $var = str_replace("##", "", $each->variable_name);
                    if(isset($$var)) {
                        $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                    }
                   
                }
                $templateHTML_1['email'] = $sap_lead->employee->email;
                array_push($dataArray, $templateHTML_1);
            }

            return $dataArray;
    }
    public static function requestMail($type, $approval_type, $requested,$data){
        $dataArray = [];
        switch($type) {
            // EMPLOYEE & reporting manager of EMPLOYEE
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
                            $templateID = $template->id;
                           
                            $variables = \MailVariables::where('template_id',$templateID)->get();
                            $request_id = $data[0]['request_id'];
                            $module_id = $requested->modules->name;
                            $tcode_id = $requested->tcodes->t_code;
                            $user_id = $username;
                            $actions = '';
                            foreach($requested->action as $ea) {
                                $actions .= $ea->name.',';
                            }
                            $actions = substr($actions,0,-1);
                            $templateHTML['template'] = $template->html_template;
                            $template = $template->html_template;
                            foreach($variables as $each) {
                                $var = str_replace("##", "", $each->variable_name);
                                if(isset($$var)) {
                                    $templateHTML['template'] = str_replace($each->variable_name, $$var, $templateHTML['template']);
                                }
                            }
                            $templateHTML['email'] = $usermail;
                        }
                        
                        // TO RM
                        $dataArray[0] = $templateHTML;
                        $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 1])->first();
                        $templateHTML_1 = [];
                        if($template) {
                            $templateID = $template->id;
                            $variables = \MailVariables::where('template_id',$templateID)->get();
                            $request_id = $data[0]['request_id'];
                            $module_id = $requested->modules->name;
                            $tcode_id = $requested->tcodes->t_code;
                            $user_id = $reportToName;
                            $requester_id = $username;
                            $actions = '';
                            foreach($requested->action as $ea) {
                                $actions .= $ea->name.',';
                            }
                            $actions = substr($actions,0,-1);
                            $templateHTML_1['template'] = $template->html_template;
                           // echo json_encode($variables); exit;
                            foreach($variables as $each) {
                                $var = str_replace("##", "", $each->variable_name);
                                if(isset($$var)) {
                                    $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                                }
                               
                            }

                            $templateHTML_1['email'] = $reportToEmail;
                        }

                        $dataArray[1] = $templateHTML_1;
                    } 
                    break;
            // module head
            case 2:
                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                    $templateHTML_1 = [];
                        if(!empty($requested->modules->module_head->user_details)) {
                            if($template) {
                               
                                $templateID = $template->id;
                              
                                $variables = \MailVariables::where('template_id',$templateID)->get();
                                $request_id = $data[0]['request_id'];
                                $module_id = $requested->modules->name;
                                $tcode_id = $requested->tcodes->t_code;
                                $user_id = $requested->modules->module_head->user_details->name;
                                $actions = '';
                                foreach($requested->action as $ea) {
                                    $actions .= $ea->name.',';
                                }
                                $actions = substr($actions,0,-1);
                                $templateHTML_1['template'] = $template->html_template;
                                foreach($variables as $each) {
                                    
                                    $var = str_replace("##", "", $each->variable_name);
                                    if(isset($$var)) {
                                        $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                                    }
                                   
                                }
                                $templateHTML_1['email'] = $requested->modules->module_head->user_details->email;
                                array_push($dataArray, $templateHTML_1);
                            }
                        }
                    break;
            // sap Lead       
            case 3:
                $dataArray = SendMail::requestToModerators($requested, $approval_type, $type, 1);
                break;

            case 4:
                //directors
                $dataArray = SendMail::requestToModerators($requested, $approval_type, $type, 2);
            break;

            case 5:
                // IT Head
                $dataArray = SendMail::requestToModerators($requested, $approval_type, $type, 3);
            break;

            case 6:
                // final approval
                $dataArray = SendMail::requestToModerators($requested, $approval_type, $type, 4);
            break;

        }

        return $dataArray;
    }

   
    public static function statusChangeMail($status,$type, $approval_type, $requested, $data){

        switch($type) {
            // reporting manager 
            case 1:
                    $userId = $data[0]['user_id'];
                    $user = \Users::where('id',$userId)->first();
                    $email = $user->email;
                    $employee_id = $user->employee_id;
                    // find log for approved transactions
                    if($status==3) {
                        $status = 0;
                    } else {
                        $status = 1;
                    }
                    $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => $status, 'approval_stage' => 1])->with('created_by_user')->get();
                   // echo json_encode($logs); exit;
                    $model = EmployeeMappings::where('employee_id', $employee_id)->with('report_employee','employee')->first();
                    if($model) {
                        $usermail = $email;
                        $username = $model->employee->first_name.' '.$model->employee->last_name;
                        $reportToEmail = $model->report_employee->email;
                        $reportToName = $model->report_employee->first_name.' '.$model->report_employee->last_name;
                        $modules = [];
                        $modules = $requested->modules->name ?? '-';
        
                        // get template and variables to be replaced
                        $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first();
                        $templateHTML = [];
                        if($template && $logs->Count()>0) {
                              $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                            
                              $templateID = $template->id;
                                // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                $variables = \MailVariables::where('template_id',$templateID)->get();
                               // echo json_encode($variables); exit;
                                $request_id = $data[0]['request_id'];
                                $module_id = $requested->modules->name;
                                $tcode_id = $requested->tcodes->t_code;
                                
                                $user_id = $username;
                                $requester_id = $username;
                                $actions = '';
                                foreach($requested->action as $ea) {
                                    $actions .= $ea->name.',';
                                }
                                $remarks = $logs[0]->remarks ?? '-';
                                $created_by = $logs[0]->created_by_user->name ?? '-';
                                $approval_stage = $logs[0]->approval_stage;
                                $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                $approval_stage = $approval_stage->approval_type ?? '-';
                                $actions = substr($actions,0,-1);
                                $templateHTML['template'] = $template->html_template;
                                foreach($variables as $each) {
                                    $var = str_replace("##", "", $each->variable_name);
                                    if(isset($$var)) {
                                        $templateHTML['template'] = str_replace($each->variable_name, $$var, $templateHTML['template']);
                                    }
                                   
                                }
                           // echo $logs[0]->status; exit;
                            
                            // $templateHTML['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $username, $template->html_template))))));
                            $templateHTML['email'] = $usermail;
                        } 
                        
                        $dataArray[0] = $templateHTML;

                        $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 1])->first();
                        $templateHTML_1 = [];
                        if($template) {
                            $templateID = $template->id;
                            $userId = $data[0]['user_id'];
                            $user = \Users::where('id',$userId)->first();
                           // $email = $user->email;
                            $username = $user->name;
                                // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                $variables = \MailVariables::where('template_id',$templateID)->get();
                                $request_id = $data[0]['request_id'];
                                $module_id = $requested->modules->name;
                                $tcode_id = $requested->tcodes->t_code;
                                $user_id = $reportToName;
                                $requester_id = $username;
                                $actions = '';
                                foreach($requested->action as $ea) {
                                    $actions .= $ea->name.',';
                                }
                                $actions = substr($actions,0,-1);
                                $templateHTML_1['template'] = $template->html_template;
                               // echo json_encode($templateID); exit;
                                foreach($variables as $each) {
                                    $var = str_replace("##", "", $each->variable_name);
                                    if(isset($$var)) {
                                        $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                                    }
                                   
                                }
                            //echo $reportToEmail; exit;
                            //$templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template))))));
                            $templateHTML_1['email'] = $reportToEmail;
                        }

                        $dataArray[1] = $templateHTML_1;
                      //  echo json_encode($logs); exit;
                        $dataArray[2] = SendMail::getNextModerator($requested->modules->id, $logs[0]->approval_stage, $templateHTML_1['template'],$user_id);

                    }                   
                break;
            // module head
            case 2:
                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 2])->with('created_by_user')->get(); 
                    // to the module head                        
                    if($template) {
                        
                        // $templateHTML_1['template'] = $template->html_template;
                        $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                        $templateID = $template->id;
                        $userId = $data[0]['user_id'];
                        $user = \Users::where('id',$userId)->first();
                        $email = $user->email;
                        $username = $user->name;
                        // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                        $variables = \MailVariables::where('template_id',$templateID)->get();
                        $request_id = $data[0]['request_id'];
                        $module_id = $requested->modules->name;
                        $tcode_id = $requested->tcodes->t_code;
                        $user_id = $username;
                        $requester_id = $username;
                        $actions = '';
                        foreach($requested->action as $ea) {
                            $actions .= $ea->name.',';
                        }
                        $actions = substr($actions,0,-1);
                        $remarks = $logs[0]->remarks ?? '-';
                        $created_by = $logs[0]->created_by_user->name ?? '-';
                        $approval_stage = $logs[0]->approval_stage;
                        $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                        $approval_stage = $approval_stage->approval_type ?? '-';
                        $templateHTML_1['template'] = $template->html_template;
                        foreach($variables as $each) {
                            $var = str_replace("##", "", $each->variable_name);
                            if(isset($$var)) {
                                $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                            }
                        }
    
                        
                        // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                        $templateHTML_1['email'] = $requested->modules->module_head->user_details->email;
                        array_push($dataArray, $templateHTML_1);


                        // to the user ( requester )
                        $template1 = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first(); 
                        if($template1) {
                            // $templateHTML_1['template'] = $template->html_template;
                                $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                $templateID = $template->id;
                                $userId = $data[0]['user_id'];
                                $user = \Users::where('id',$userId)->first();
                                $email = $user->email;
                                $username = $user->name;
                                // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                $variables = \MailVariables::where('template_id',$templateID)->get();
                                $request_id = $data[0]['request_id'];
                                $module_id = $requested->modules->name;
                                $tcode_id = $requested->tcodes->t_code;
                                $user_id = $requested->modules->module_head->user_details->name;
                                $actions = '';
                                foreach($requested->action as $ea) {
                                    $actions .= $ea->name.',';
                                }
                                $actions = substr($actions,0,-1);
                                $remarks = $logs[0]->remarks ?? '-';
                                $created_by = $logs[0]->created_by_user->name ?? '-';
                                $approval_stage = $logs[0]->approval_stage;
                                $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                $approval_stage = $approval_stage->approval_type ?? '-';
                                $templateHTML_1['template'] = $template1->html_template;
                                foreach($variables as $each) {
                                    $var = str_replace("##", "", $each->variable_name);
                                    if(isset($$var)) {
                                        $templateHTML_1['template'] = str_replace($each->variable_name, $$var, $templateHTML_1['template']);
                                    }
                                }
            
                                // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                $templateHTML_1['email'] = $email;
                                array_push($dataArray, $templateHTML_1);
                                $dataArray[] = SendMail::getNextModerator($requested->modules->id, $logs[0]->approval_stage, $templateHTML_1['template'],$user_id);
                        }
                    }
                break;
            case 3:
                // sap lead
                  $dataArray = SendMail::changeStatusMyModerators($type, $approval_type, $requested, $data, $status, 1);
                break;    
            case 4:
                  // director
                  $dataArray = SendMail::changeStatusMyModerators($type, $approval_type, $requested, $data, $status, 2);
               break;
            case 5: 
                  // IT head
                  $dataArray = SendMail::changeStatusMyModerators($type, $approval_type, $requested, $data, $status, 3);
              break;
            case 6:
                  // Basis
                  $dataArray = SendMail::changeStatusMyModerators($type, $approval_type, $requested, $data, $status, 4);
              break;
            
        }

        return $dataArray;
    }

    public static function getData($type, $classname, $data, $approval_type) {
        // Approval Type: 1 - Request 2 - Approve 3 -> Reject
       // type says if its RM or MH or SAP Lead etc
        $dataArray = [];
        switch($classname) {
            case 'SapRequestMail':
                $requested = SAPRequest::where('id', $data[0]['id'])->with('user', 'modules.module_head.user_details', 'tcodes', 'action')->first();
               switch($approval_type) {
                    case 1:
                        //request
                        $dataArray = SendMail::requestMail($type, $approval_type, $requested, $data);
                    break;

                    case 2:
                        //approve
                        $dataArray = SendMail::statusChangeMail(2, $type, $approval_type, $requested, $data);
                        break;

                    case 3:
                        //reject
                        $dataArray = SendMail::statusChangeMail(3, $type, $approval_type, $requested, $data);
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
        //echo json_encode($dataArray); exit;
        return $dataArray;
    
    }

    public static function getNextModerator($module_id, $index, $template, $user_id) {

      
        $stage = \ModuleApprovalStages::where('module_id', $module_id)->with('module')->orderBy('approval_matrix_id','asc')->get();
        
        $stage_1 = $stage[$index]->approval_matrix_id;
      
        $dataArray = [];

        switch($stage_1) {

            case 2: 
                // module head
               $head =  ModuleHead::where('permission_id', $module_id)->with('user_details')->first();
               $module_email_id = $head->user_details->email;
               $mh_name = $head->user_details->name;
               $dataArray = [
                'template' => str_replace($user_id, $mh_name, $template),
                'email' => $module_email_id
               ];
               break;

            case 3: 
            // sap lead
            $head =  Moderators::where('type_id', 1)->with('employee')->first();
            $module_email_id = $head->employee->email;
            $dataArray = [
            'template' => $template,
            'email' => $module_email_id
            ];
            break;

            case 4: 
            // director
            $head =  Moderators::where('type_id', 2)->with('employee')->first();
            $module_email_id = $head->employee->email;
            $dataArray = [
            'template' => $template,
            'email' => $module_email_id
            ];
            break;

            case 5: 
             // IT Head
            $head =  Moderators::where('type_id', 3)->with('employee')->first();
            $module_email_id = $head->employee->email;
            $dataArray = [
            'template' => $template,
            'email' => $module_email_id
            ];
            break;

            case 6: 
            // BASIS
            $head =  Moderators::where('type_id', 4)->with('employee')->first();
            $module_email_id = $head->employee->email;
            $dataArray = [
            'template' => $template,
            'email' => $module_email_id
            ];
            break;
        }

        return $dataArray;
    
    }
}

?>