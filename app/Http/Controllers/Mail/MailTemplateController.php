<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MailTemplates;
use ApprovalMatrix;
use SAPRequest;
use SAPApprovalLogs;


class MailTemplateController extends Controller
{
    //

    public function index() {
        $data['page_title'] = 'Mail Templetes';
        $approvals = ApprovalMatrix::all();
        
        $data['approvals'] = $approvals;
   
        return view('mail_templates.index')->with($data);
    }

    public function fetchMailTemplates(Request $request) {
        try{
            $templates = MailTemplates::with('approval_matrix','variables')->get();
        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
        return response(['status' => 200, 'data' => $templates],200);
    }

    public function generateFields(Request $request){
        $bundle = '';
        $type = $request->type_id;
        try {

            switch($type) {
                case 1: // Request
                    $SAPRequest = new SAPRequest;
                    $columns = $SAPRequest->getTableColumns();
                    $columnsArray[] = [
                        'value' => $columns[1],
                        'name' => 'Request ID'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[4],
                        'name' => 'User Name'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[5],
                        'name' => 'Company Code'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[6],
                        'name' => 'Plant Code'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[15],
                        'name' => 'Module'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[16],
                        'name' => 'TCode'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[17],
                        'name' => 'Actions'
                    ];
                   
                break;

                case 2: // Approve / Reject
                    $GetLog = new SAPApprovalLogs;
                    $columns = $GetLog->getTableColumns();
                    $columnsArray[] = [
                        'value' => $columns[1],
                        'name' => 'Request ID'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[3],
                        'name' => 'Status'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[5],
                        'name' => 'Remarks'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[6],
                        'name' => 'Status Changed By'
                    ];
                  
                    $columnsArray[] = [
                        'value' => 'requester_id',
                        'name' => 'Requester'
                    ];
                    $columnsArray[] = [
                        'value' => 'module_id',
                        'name' => 'Module'
                    ];
                    $columnsArray[] = [
                        'value' => 'tcode_id',
                        'name' => 'TCode'
                    ];
                break;

                case 3: // Reject
                    $GetLog = new SAPApprovalLogs;
                    $columns = $GetLog->getTableColumns();
                    $columnsArray[] = [
                        'value' => $columns[1],
                        'name' => 'Request ID'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[3],
                        'name' => 'Status'
                    ];
                  
                    $columnsArray[] = [
                        'value' => $columns[5],
                        'name' => 'Remarks'
                    ];
                    $columnsArray[] = [
                        'value' => $columns[6],
                        'name' => 'Status Changed By'
                    ];
                    $columnsArray[] = [
                        'value' => 'requester_id',
                        'name' => 'Requester'
                    ];
                    $columnsArray[] = [
                        'value' => 'module_id',
                        'name' => 'Module'
                    ];
                    $columnsArray[] = [
                        'value' => 'tcode_id',
                        'name' => 'TCode'
                    ];
                break;
            }
       
        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }

            return response(['data' => $columnsArray, 'status' => 200],200);
    }

    public function create(Request $request) {

        //return $request->all();
        $type_id = $request->type_id;
        $approver_id = $request->approver_id;
        $template = $request->templateValue;

        $variables = json_decode($request->variables, true);


       // return false;

        try {
            $isDuplicate = MailTemplates::where([
                'type_id' => $type_id,
                'approval_matrix_id' => $approver_id
            ])->get();

            if($isDuplicate->Count()==0) {

                $create = MailTemplates::create([
                    'type_id' => $type_id,
                    'approval_matrix_id' => $approver_id,
                    'html_template' => $template,
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW()
                ]);

                $getID = MailTemplates::where([
                    'type_id' => $type_id,
                    'approval_matrix_id' => $approver_id
                ])->get();
                
                    $id = $getID[0]->id;

                    foreach($variables as $each) {
                        $isDuplicate = \MailVariables::where([ 'template_id' => $id,
                        'variable_name' => $each])->get();

                        if($isDuplicate->Count()==0) {
                            \MailVariables::create([
                                'template_id' => $id,
                                'variable_name' => $each,
                                'created_at' => NOW(),
                                'updated_at' => NOW()
                            ]);
                        }
                       
                    }
                   
                return response(['message' => 'Template was added successfully', 'status' => 200], 200);
            
            } else {
                return response(['message' => 'Duplicate Template found', 'status' => 400], 200);
            }

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 500], 500);
        }

    }

    public function update(Request $request) {

        //return $request->all();
        $type_id = $request->etype_id;
        $id = $request->eid;
        $approver_id = $request->eapprover_id;
        $template = $request->etemplateValue;

        try {
            $isDuplicate = MailTemplates::where([
                'type_id' => $type_id,
                'approval_matrix_id' => $approver_id
            ])->get();

            if($isDuplicate->Count()==1) {

                $create = MailTemplates::where('id',$id)->update([
                    'type_id' => $type_id,
                    'approval_matrix_id' => $approver_id,
                    'html_template' => $template,
                    'status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW()
                ]);

                return response(['message' => 'Template was updated successfully', 'status' => 200], 200);
            
            } else {
                return response(['message' => 'Duplicate Template found', 'status' => 400], 200);
            }

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 500], 500);
        }

    }
}
