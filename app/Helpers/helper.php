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
        $stage = \ApprovalMatrix::when($stage_id > 0, function ($Q) use ($stage_id) {
            $Q->where('id', $stage_id);
        })->where('id', '!=', 0)->get();
        return $stage;
    } catch (\Exception $e) {
        return "Error " . $e->getMessage();
    }
}

function isModerator($type) {

    $user_id   = \Auth::user()->employee_id;
    $moderator = Moderators::where(['type_id' => $type, 'employee_id' => $user_id])->get();

    $flag = ($moderator->Count() > 0) ? true : false;

    return $flag;
}

function checkIfAnyModerator() {

    $emp_id    = \Auth::user()->employee_id;
    $user_id   = \Auth::user()->id;
    $moderator = Moderators::where(['employee_id' => $emp_id, 'type_id' => 4])->get();
    // any moderator
    $flag = ($moderator->Count() > 0) ? true : false;

    // module head
    $is_module_head = ModuleHead::where('user_id', $user_id)->get();
    $flag2          = ($is_module_head->Count() > 0) ? true : false;

    // developer
    $role  = Auth::user()->roles[0]->name ?? '';
    $flag3 = ($role === 'ZM_DEVELOPER') ? true : false;

    return ($flag === true || $flag2 === true || $flag3 === true) ? true : false;
}

function webBaseURL() {
    return "http://125.22.105.181:33066/audit_compliance/public/";
}

function getDepartment($employee_id) {
    $department     = \Employees::with('departments')->where('id', $employee_id)->get();
    $departmentName = '-';

    if ($department->Count() > 0) {

        $departmentName = $department[0]->departments->department_name ?? '-';
    }

    return $departmentName;
}

function getNameFromAssoc($data, $field, $type = 0) {

    $value = '-';
    if (!is_null($data)) {

        $data1 = $data->toArray();

        if (!is_array($data1)) {
            return '-';
        }
        // /return $data1;
        if ($type == 1) {

            $value = $data1->$field;

        } else {

            $value = '';
            foreach ($data1 as $each) {

                $val = $each[$field] ?? $each->$field ?? '-';
                $value .= $val . ', ';
            }

            if (!empty($value)) {
                $value = substr($value, 0, -2);
            }
        }
    }

    return $value;
}

function getReporties($user_id = 0) {
    if ($user_id == 0) {
        $user_id = Auth::user()->employee_id;
    }
    $team = \EmployeeMappings::where('report_to', $user_id)->select('employee_id')->get();

    $user_ids = [];
    foreach ($team as $each) {
        $user_ids[] = $each->employee_id;
    }

    $userIds = \Users::whereIn('employee_id', $user_ids)->get();
    $userId  = [];
    foreach ($userIds as $each) {
        $userId[] = [
            'id'   => $each->id,
            'name' => $each->name
        ];
    }

    return $userId;
}

function has_children($rows, $id) {
    foreach ($rows as $row) {
        if ($row['parent_id'] == $id) {
            return true;
        }

    }
    return false;
}

$result = [];
function build_menu($rows, $parent = 0) {

    $I = 0;
    foreach ($rows as $row) {

        if ($row['parent_id'] == $parent) {

            $result[$I] = [
                'id'   => $row['id'],
                'text' => $row['menu_name'],
                'icon' => $row['icon']
            ];

            if (!empty($row['menu_slug']) && $row['menu_slug'] != 'null') {
                $result[$I]['route'] = $row['menu_slug'];
            }

            if (has_children($rows, $row['id'])) {
                $result[$I]['submenu'] = build_menu($rows, $row['id']);
            }

            $I++;
        }

    }

    return $result;
}

function build_menu_main_logic($rows, $parent = 0) {

    $result = "<ul>";
    foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
            $result .= "<li>{$row['menu_name']}";
            if (has_children($rows, $row['id'])) {
                $result .= build_menu2($rows, $row['id']);
            }

            $result .= "</li>";
        }
    }
    $result .= "</ul>";

    return $result;
}

?>