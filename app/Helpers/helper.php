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

?>