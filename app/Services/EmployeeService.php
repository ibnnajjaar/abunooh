<?php

namespace App\Services;

use App\DataObjects\EmployeeData;
use App\DataObjects\EmployeePasswordData;
use App\Models\Employee;

class EmployeeService
{
    public function store(EmployeeData $employeeData, ?EmployeePasswordData $passwordData = null): Employee
    {
        $employee = new Employee();
        $employee->fill($employeeData->toArray());

        if (config('auth.allow_employee_password_login') && $passwordData) {
            $employee->password = $passwordData->password;
        }

        $employee->save();

        return $employee;
    }

    public function update(Employee $employee, EmployeeData $employeeData, ?EmployeePasswordData $passwordData = null): Employee
    {
        $employee->fill($employeeData->toArray());

        if (config('auth.allow_employee_password_login') && $passwordData) {
            $employee->password = $passwordData->password;
            $employee->requires_password_update = $passwordData->requires_password_update;
        }

        $employee->save();

        return $employee;
    }
}
