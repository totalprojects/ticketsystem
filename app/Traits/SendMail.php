<?php
namespace App\Traits;
use Mail;
use Auth;
use EmployeeMappings;
use ModuleHead;
use SAPRequest;
use SAPApprovalLogs;

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
               
                // return $requested;
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
                                   // echo json_encode($template); exit;
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
                                        //$templateHTML['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $username, $template->html_template));
                                        $templateHTML['email'] = $usermail;
                                    }
                                    
                                    $dataArray[0] = $templateHTML;

                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 1])->first();
                                    $templateHTML_1 = [];
                                    if($template) {
                                        $templateID = $template->id;
                                        // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                        $variables = \MailVariables::where('template_id',$templateID)->get();
                                        $request_id = $data[0]['request_id'];
                                        $module_id = $requested->modules->name;
                                        $tcode_id = $requested->tcodes->t_code;
                                        $user_id = $reportToName;
                                        $requester_id = $username;
                                        //echo $requester_id; exit;
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

                               // echo json_encode($dataArray); exit;
                            break;

                            // module head
                            case 2:

                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                //echo json_encode($template); exit;    
                                $templateHTML_1 = [];
                                    if(!empty($requested->modules->module_head->user_details)) {
                                        
                                        if($template) {
                                           
                                            $templateID = $template->id;
                                           // echo json_encode($requested); exit; 
                                            // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                            $variables = \MailVariables::where('template_id',$templateID)->get();
                                           // echo json_encode($variables); exit;
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
                                
                                case 3:
                                    // sap Lead
                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                          
                                    if(!empty($requested->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateID = $template->id;
                                            // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                            $variables = \MailVariables::where('template_id',$templateID)->get();
                                            $request_id = $data[0]['request_id'];
                                            $module_id = $requested->modules->name;
                                            $tcode_id = $requested->tcodes->t_code;
                                            $user_id = $reportToName;
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
                                            $templateHTML_1['email'] = $each->modules->module_head->user_details->email;
                                            array_push($dataArray, $templateHTML_1);
                                        }
                                    
                                    }
                                    
                                
                                break;

                                case 4:
                                    //directors
                                    $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                                    
                           
                                    if(!empty($requested->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateID = $template->id;
                                            // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                            $variables = \MailVariables::where('template_id',$templateID)->get();
                                            $request_id = $data[0]['request_id'];
                                            $module_id = $requested->modules->name;
                                            $tcode_id = $requested->tcodes->t_code;
                                            $user_id = $reportToName;
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

                            case 5:
                                // IT Head
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                             
                                    if(!empty($requested->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateID = $template->id;
                                            // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                            $variables = \MailVariables::where('template_id',$templateID)->get();
                                            $request_id = $data[0]['request_id'];
                                            $module_id = $requested->modules->name;
                                            $tcode_id = $requested->tcodes->t_code;
                                            $user_id = $reportToName;
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
                            case 6:
                                // final approval
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                    $templateHTML_1 = [];
                                    
                               
                                    if(!empty($requested->modules->module_head->user_details)) {
                                        if($template) {
                                            $templateID = $template->id;
                                            // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                            $variables = \MailVariables::where('template_id',$templateID)->get();
                                            $request_id = $data[0]['request_id'];
                                            $module_id = $requested->modules->name;
                                            $tcode_id = $requested->tcodes->t_code;
                                            $user_id = $reportToName;
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

                        }
                break;

               
                case 2:
                    //approve
                    switch($type) {
                        // reporting manager 
                        case 1:
                                $userId = $data[0]['user_id'];
                                $user = \Users::where('id',$userId)->first();
                                $email = $user->email;
                                $employee_id = $user->employee_id;
                              //  echo 'Yes'; exit;
                                // find log for approved transactions
                                $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 1])->with('created_by_user')->get();
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
                                } 

                                //echo json_encode($dataArray); exit;
                               
                            break;
                            // module head
                            case 2:
                               // echo 'Type: '.$approval_type; exit;
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first();
                                $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 2])->with('created_by_user')->get(); 
                                    // to the module head
                               // echo json_encode($template); exit;
                                    
                                        
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
                                            }
                                        }
                                       
                                       // echo json_encode($dataArray); exit;
                                    
                                
                                break; 
                            case 3:
                                // sap lead
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 3])->first();
                                $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 3])->with('created_by_user')->get();
                                $templateHTML_1 = [];
                                        
                                    if($template) {
                                       // $templateHTML_1['template'] = $template->html_template;
                                        $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                        $templateID = $template->id;
                                        $userId = $data[0]['user_id'];
                                        $user = \Users::where('id',$userId)->first();
                                        $email = $user->email;
                                        $username = $user->name;
                                        $requester_id = $username;
                                        // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                        $variables = \MailVariables::where('template_id',$templateID)->get();
                                        $sap_lead = \Moderators::where('type_id', 1)->first();
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
                    
                                       
                                        // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                        $templateHTML_1['email'] = $sapLeadEmail;
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
                         
                                             // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                             $templateHTML_1['email'] = $email;
                                             array_push($dataArray, $templateHTML_1);
                                        }
                                    }
                                   
                                
                                
                            
                            break;    
                            
                        case 4:
                              // sap lead
                              $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 4])->first();
                              $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 4])->with('created_by_user')->get();
                              $templateHTML_1 = [];
                              
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
                                 $sap_lead = \Moderators::where('type_id', 2)->first();
                                 $employee_id = $sap_lead->employee_id;
                                 $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                                 $sapLeadEmail = $sapLeadEmail->email;
                                 $request_id = $data[0]['request_id'];
                                 $module_id = $requested->modules->name;
                                 $requester_id = $username;
                                 $tcode_id = $requested->tcodes->t_code;
                                 $user_id = $reportToName;
                                 $remarks = $logs[0]->remarks ?? '-';
                                 $created_by = $logs[0]->created_by_user->name ?? '-';
                                 $approval_stage = $logs[0]->approval_stage;
                                 $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                 $approval_stage = $approval_stage->approval_type ?? '-';
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
             
                                
                                 // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                 $templateHTML_1['email'] = $sapLeadEmail;
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
                                      $user_id = $reportToName;
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
                                 }
                             }
                            
                         
                         
                          break;
                        case 5: 
                              // IT head
                              $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 5])->first();
                              $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 5])->with('created_by_user')->get();
                              $templateHTML_1 = [];
                              
                              if($template) {
                                // $templateHTML_1['template'] = $template->html_template;
                                 $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                 $templateID = $template->id;
                                 $userId = $data[0]['user_id'];
                                 $user = \Users::where('id',$userId)->first();
                                 $email = $user->email;
                                 $username = $user->name;
                                 $requester_id = $username;
                                 // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                 $variables = \MailVariables::where('template_id',$templateID)->get();
                                 $sap_lead = \Moderators::where('type_id', 3)->first();
                                 $employee_id = $sap_lead->employee_id;
                                 $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                                 $sapLeadEmail = $sapLeadEmail->email;
                                 $request_id = $data[0]['request_id'];
                                 $module_id = $requested->modules->name;
                                 $tcode_id = $requested->tcodes->t_code;
                                 $user_id = $reportToName;
                                 $actions = '';
                                 $remarks = $logs[0]->remarks ?? '-';
                                 $created_by = $logs[0]->created_by_user->name ?? '-';
                                 $approval_stage = $logs[0]->approval_stage;
                                 $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                 $approval_stage = $approval_stage->approval_type ?? '-';
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
             
                              
                                 // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                 $templateHTML_1['email'] = $sapLeadEmail;
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
                                      $user_id = $reportToName;
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
                                 }
                             }
                            
                         
                         
                          break;
                        case 6:
                              // Basis
                              $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 6])->first();
                              $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 1, 'approval_stage' => 6])->with('created_by_user')->get();
                              $templateHTML_1 = [];
                              
                              if($template) {
                                // $templateHTML_1['template'] = $template->html_template;
                                 $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                 $templateID = $template->id;
                                 $userId = $data[0]['user_id'];
                                 $user = \Users::where('id',$userId)->first();
                                 $email = $user->email;
                                 $username = $user->name;
                                 $requester_id = $username;
                                 // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                 $variables = \MailVariables::where('template_id',$templateID)->get();
                                 $sap_lead = \Moderators::where('type_id', 4)->first();
                                 $employee_id = $sap_lead->employee_id;
                                 $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                                 $sapLeadEmail = $sapLeadEmail->email;
                                 $request_id = $data[0]['request_id'];
                                 $module_id = $requested->modules->name;
                                 $tcode_id = $requested->tcodes->t_code;
                                 $user_id = $reportToName;
                                 $remarks = $logs[0]->remarks ?? '-';
                                 $created_by = $logs[0]->created_by_user->name ?? '-';
                                 $approval_stage = $logs[0]->approval_stage;
                                 $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                 $approval_stage = $approval_stage->approval_type ?? '-';
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
             
                                
                                 // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                 $templateHTML_1['email'] = $sapLeadEmail;
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
                                      $user_id = $reportToName;
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
                                 }
                             }
                            
                         
                         
                          break;
                        
                    }
                    break;


                case 3:
                // reject
                switch($type) {
                    // reporting manager 
                    case 1:
                            $userId = $data[0]['user_id'];
                            $user = \Users::where('id',$userId)->first();
                            $email = $user->email;
                            $employee_id = $user->employee_id;
                           // echo 'Yes'; exit;
                            // find log for approved transactions
                            $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 1])->with('created_by_user')->get();
                           // echo json_encode($logs); exit;
                            $model = EmployeeMappings::where('employee_id', $employee_id)->with('report_employee','employee')->first();
                            if($model) {
                                $usermail = $email;
                                $username = $user->name;
                                $reportToEmail = $model->report_employee->email;
                                $reportToName = $model->report_employee->first_name.' '.$model->report_employee->last_name;
                                $modules = [];
                                $modules = $requested->modules->name ?? '-';                          
                                // get template and variables to be replaced
                                $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 0])->first();
                                $templateHTML = [];
                                //echo json_encode($logs); exit;
                                if($template) {
                                      $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                    
                                      $templateID = $template->id;
                                     // echo $templateID; exit;
                                        // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                        $variables = \MailVariables::where('template_id',$templateID)->get();
                                        $request_id = $data[0]['request_id'];
                                        $module_id = $requested->modules->name;
                                        $tcode_id = $requested->tcodes->t_code;
                                        $remarks = $logs[0]->remarks ?? '-';
                                        $created_by = $username;
                                        $requester_id = $username;
                                        $approval_stage = $logs[0]->approval_stage;
                                        $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                        $approval_stage = $approval_stage->approval_type ?? '-';
                                        $user_id = $logs[0]->created_by_user->name ?? '-';
                                        //echo $user_id; exit;
                                        $actions = '';
                                        foreach($requested->action as $ea) {
                                            $actions .= $ea->name.',';
                                        }
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
                                        $created_by = $logs[0]->created_by_user->name ?? '-';
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
                                    //echo $reportToEmail; exit;
                                    //$templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template))))));
                                    $templateHTML_1['email'] = $reportToEmail;
                                }

                                $dataArray[1] = $templateHTML_1;
                            } 
                            //echo json_encode($dataArray); exit;
                        break;
                        // module head
                    case 2:
                        $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 2])->with('created_by_user')->get();
                            $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 2])->first(); 
                                // to the module head
                               
                                    
                                    if($template) {
                                       // $templateHTML_1['template'] = $template->html_template;
                                        $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                        $templateID = $template->id;
                                        $userId = $data[0]['user_id'];
                                        $user = \Users::where('id',$userId)->first();
                                        $email = $user->email;
                                        $username = $user->name;
                                        $requester_id = $username;
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
                                        }
                                    
                                   
                                }
                                
                            
                            break; 
                        case 3:
                            // sap lead
                            $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 3])->with('created_by_user')->get();
                            $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 3])->first();
                            $templateHTML_1 = [];
                                    
                                if($template) {
                                   // $templateHTML_1['template'] = $template->html_template;
                                    $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                                    $templateID = $template->id;
                                    $userId = $data[0]['user_id'];
                                    $user = \Users::where('id',$userId)->first();
                                    $email = $user->email;
                                    $username = $user->name;
                                    $requester_id = $username;
                                    // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                                    $variables = \MailVariables::where('template_id',$templateID)->get();
                                    $sap_lead = \Moderators::where('type_id', 1)->first();
                                    $employee_id = $sap_lead->employee_id;
                                    $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                                    $sapLeadEmail = $sapLeadEmail->email;
                                    $request_id = $data[0]['request_id'];
                                    $module_id = $requested->modules->name;
                                    $tcode_id = $requested->tcodes->t_code;
                                    $user_id = $reportToName;
                                    $remarks = $logs[0]->remarks ?? '-';
                                    $created_by = $logs[0]->created_by_user->name ?? '-';
                                    $approval_stage = $logs[0]->approval_stage;
                                    $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                                    $approval_stage = $approval_stage->approval_type ?? '-';
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
                
                                  
                                    // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                                    $templateHTML_1['email'] = $sapLeadEmail;
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
                                         $user_id = $reportToName;
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
                                    }
                                }
                               
                            
                            
                        
                        break;    
                        
                    case 4:
                          // sap lead
                          $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 4])->with('created_by_user')->get();
                          $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 4])->first();
                          $templateHTML_1 = [];
                          
                          if($template) {
                            // $templateHTML_1['template'] = $template->html_template;
                             $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                             $templateID = $template->id;
                             $userId = $data[0]['user_id'];
                             $user = \Users::where('id',$userId)->first();
                             $email = $user->email;
                             $username = $user->name;
                             $requester_id = $username;
                             // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                             $variables = \MailVariables::where('template_id',$templateID)->get();
                             $sap_lead = \Moderators::where('type_id', 2)->first();
                             $employee_id = $sap_lead->employee_id;
                             $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                             $sapLeadEmail = $sapLeadEmail->email;
                             $request_id = $data[0]['request_id'];
                             $module_id = $requested->modules->name;
                             $tcode_id = $requested->tcodes->t_code;
                             $user_id = $reportToName;
                             $actions = '';
                             $remarks = $logs[0]->remarks ?? '-';
                             $created_by = $logs[0]->created_by_user->name ?? '-';
                             $approval_stage = $logs[0]->approval_stage;
                             $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                             $approval_stage = $approval_stage->approval_type ?? '-';
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
         
                            
                             // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                             $templateHTML_1['email'] = $sapLeadEmail;
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
                                  $user_id = $reportToName;
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
                             }
                         }
                        
                     
                     
                      break;
                    case 5: 
                          // IT head
                          $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 5])->with('created_by_user')->get();
                          $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 5])->first();
                          $templateHTML_1 = [];
                          
                          if($template) {
                            // $templateHTML_1['template'] = $template->html_template;
                             $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                             $templateID = $template->id;
                             $userId = $data[0]['user_id'];
                             $user = \Users::where('id',$userId)->first();
                             $email = $user->email;
                             $username = $user->name;
                             $requester_id = $username;
                             // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                             $variables = \MailVariables::where('template_id',$templateID)->get();
                             $sap_lead = \Moderators::where('type_id', 3)->first();
                             $employee_id = $sap_lead->employee_id;
                             $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                             $sapLeadEmail = $sapLeadEmail->email;
                             $request_id = $data[0]['request_id'];
                             $module_id = $requested->modules->name;
                             $tcode_id = $requested->tcodes->t_code;
                             $user_id = $reportToName;
                             $actions = '';
                             $remarks = $logs[0]->remarks ?? '-';
                             $created_by = $logs[0]->created_by_user->name ?? '-';
                             $approval_stage = $logs[0]->approval_stage;
                             $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                             $approval_stage = $approval_stage->approval_type ?? '-';
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
         
                           
                             // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                             $templateHTML_1['email'] = $sapLeadEmail;
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
                                  $user_id = $reportToName;
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
                             }
                         }
                        
                     
                     
                      break;
                    case 6:
                          // Basis
                          $logs = SAPApprovalLogs::where(['request_id' => $data[0]['id'], 'status' => 0, 'approval_stage' => 6])->with('created_by_user')->get();
                          $template = \MailTemplates::where(['type_id' => $approval_type, 'approval_matrix_id' => 6])->first();
                          $templateHTML_1 = [];
                          
                          if($template) {
                            // $templateHTML_1['template'] = $template->html_template;
                             $status = ($logs[0]->status==1) ? 'Approved' : 'Rejected';
                             $templateID = $template->id;
                             $userId = $data[0]['user_id'];
                             $user = \Users::where('id',$userId)->first();
                             $email = $user->email;
                             $username = $user->name;
                             $requester_id = $username;
                             // $templateHTML_1['template'] = str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $reportToName, $template->html_template));
                             $variables = \MailVariables::where('template_id',$templateID)->get();
                             $sap_lead = \Moderators::where('type_id', 4)->first();
                             $employee_id = $sap_lead->employee_id;
                             $sapLeadEmail = \Users::where('employee_id',$employee_id)->first();
                             $sapLeadEmail = $sapLeadEmail->email;
                             $request_id = $data[0]['request_id'];
                             $module_id = $requested->modules->name;
                             $tcode_id = $requested->tcodes->t_code;
                             $user_id = $reportToName;
                             $actions = '';
                             $remarks = $logs[0]->remarks ?? '-';
                             $created_by = $logs[0]->created_by_user->name ?? '-';
                             $approval_stage = $logs[0]->approval_stage;
                             $approval_stage = \ApprovalMatrix::where('id', $approval_stage)->first();
                             $approval_stage = $approval_stage->approval_type ?? '-';
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
         
                           
                             // $templateHTML_1['template'] = str_replace("##remarks##", $remarks, str_replace("##status##", $status, str_replace("##approval_stage##", $approval_stage, str_replace("##created_by##", $created_by, str_replace("##request_id##",$data[0]['request_id'],str_replace("##user_id##", $each->modules->module_head->user_details->name, $template->html_template))))));
                             $templateHTML_1['email'] = $sapLeadEmail;
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
                                  $user_id = $reportToName;
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
                             }
                         }
                        
                     
                     
                      break;
                    
                }
                
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