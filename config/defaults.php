<?php

return [

    /*
     * Expected work hours per day in minutes
     *
     * Example: 6 * 60 = 360 minutes = 6 hours
     * */
    'work_hours' => 6 * 60,
//    'work_hours' => 1, // testing


    /*
     * Default schedule time settings
     *
     * schedule_start_at: The default start time for work schedule (format: HH:mm:ss)
     * schedule_end_at: The default end time for work schedule (format: HH:mm:ss)
     * These times are used as default values for attendance tracking
     */
    'schedule_start_at' => '08:00:00',
    'schedule_end_at' => '14:00:00',

    /*
     * Workdays configuration
     * Array of days that are considered as workdays (1 = Monday, 7 = Sunday)
     * Example: [1,2,3,4,5] means Monday to Friday are workdays
     */
    'workdays' => [1, 2, 3, 4, 5],


];
