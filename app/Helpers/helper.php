<?php

function get_user_info($employee_id) {
    try {
        $employee = \Employees::where('id', $employee_id)->get();
        return $employee;
    } catch (\Exception $e) {
        return "Error " . $e->getMessage();
    }

}

/** track and store all user activities */
function storeActivity(Array $data) {
    /* *
    @ ** user_id, activity_type, meta = Not Important, timestamps mandatory
     */
    $log = ActivityLog::insert($data);

    return $log;
}

/** SAP Request approval stages */

function requestApprovalStages($stage_id = 0) {
    try {
        $stage = \ApprovalMatrix::when($stage_id > 0, function($Q) use($stage_id) {
            $Q->where('id', $stage_id);
        })->where('id', '!=', 0)->get();
        return $stage;
    } catch (\Exception $e) {
        return "Error " . $e->getMessage();
    }
}

function isModerator($type) {

    $user_id = \Auth::user()->employee_id;
    $moderator = Moderators::where(['type_id' => $type, 'employee_id' => $user_id])->get();
    
    $flag = ($moderator->Count() > 0) ? true : false;

    return $flag;
}

function webBaseURL() {
    return "http://125.22.105.181:33066/audit_compliance/public/";
}

?>