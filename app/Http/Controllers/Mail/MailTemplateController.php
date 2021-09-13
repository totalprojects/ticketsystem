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
            $templates = MailTemplates::with('approval_matrix')->get();
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
                break;

                case 2: // Approve / Reject
                    $GetLog = new SAPApprovalLogs;
                    $columns = $GetLog->getTableColumns();
                break;

                case 3: // Reject
                    $GetLog = new SAPApprovalLogs;
                    $columns = $GetLog->getTableColumns();
                break;
            }
       
        } catch(\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }

            return response(['data' => $columns, 'status' => 200],200);
    }

    public function create(Request $request) {

        //return $request->all();
        $type_id = $request->type_id;
        $approver_id = $request->approver_id;
        $template = $request->templateValue;

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

                return response(['message' => 'Template was added successfully', 'status' => 200], 200);
            
            } else {
                return response(['message' => 'Duplicate Template found', 'status' => 400], 200);
            }

        } catch(\Exception $e) {
            return response(['message' => $e->getMessage(), 'status' => 500], 500);
        }

    }
}
