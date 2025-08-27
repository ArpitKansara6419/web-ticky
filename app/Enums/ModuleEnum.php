<?php

namespace App\Enums;

enum ModuleEnum : string
{
    case DASHBOARD_ACCESS = 'dashboard_access';

    case CUSTOMER_LIST = 'customer_list';
    case CUSTOMER_EDIT = 'customer_edit';
    case CUSTOMER_DELETE = 'customer_delete';
    case CUSTOMER_CREATE = 'customer_create';
    case CUSTOMER_DETAIL = 'customer_detail';
    case CUSTOMER_LEAD = 'customer_lead';

    case ENGINEER_LIST = "engineer_list";
    case ENGINEER_DETAIL = "engineer_detail";
    case ENGINEER_PAYOUT = "engineer_payout";
    case ENGINEER_DELETE = "engineer_delete";

    case LEAD_LIST = "lead_list";
    case LEAD_DELETE = "lead_delete";
    case LEAD_CREATE = "lead_create";
    case LEAD_DETAIL = "lead_detail";

    case TICKET_LIST = "ticket_list";
    case TICKET_CREATE = "ticket_create";
    case TICKET_EDIT = "ticket_edit";
    case TICKET_DELETE = "ticket_delete";
    case TICKET_DETAIL = "ticket_detail";

    case ATTENDANCE_LIST = "attendance_list";

    case LEAVES_LIST = "leaves_list";

    case EngineerPayouts_LIST = "engineerpayouts_list";
    // case EngineerPayouts_DETAIL = "engineerpayouts_detail";
    case EngineerPayouts_PAYSLIPS = "engineerpayouts_pay_slips";

    case CUSTOMER_RECEIVABLE_LIST = "receivable_customer_list";
    case CUSTOMER_INVOICE_RECEIVABLE_DETAIL = "receivable_invoices_customer_detail";
    case CUSTOMER_INVOICES_LIST = "receivable_invoices_customer_list";

    case NOTIFICATION_TEMPLATE_LIST = "NotificationTemplate_list";
    case NOTIFICATION_TEMPLATE_EDIT = "NotificationTemplate_edit";
    case NOTIFICATION_TEMPLATE_DELETE = "NotificationTemplate_delete";
    case NOTIFICATION_TEMPLATE_CREATE = "NotificationTemplate_create";

    case CUSTOM_NOTIFICATION_CREATE = "CustomNotification_Create";

    case SETTING_HOLIDAY_LIST = "SettingsHoliday_list";
    case SETTING_HOLIDAY_EDIT = "SettingsHoliday_edit";
    case SETTING_HOLIDAY_DELETE = "SettingsHoliday_delete";
    case SETTING_HOLIDAY_CREATE = "SettingsHoliday_create";

    case SETTING_SYSTEM_USERS_LIST = "SettingSystemUsers_list";
    case SETTING_SYSTEM_USERS_EDIT = "SettingSystemUsers_edit";
    case SETTING_SYSTEM_USERS_DELETE = "SettingSystemUsers_delete";
    case SETTING_SYSTEM_USERS_CREATE = "SettingSystemUsers_create";

    case SETTING_ROLE_LIST = "SettingsRoles_list";
    case SETTING_ROLE_EDIT = "SettingsRoles_edit";
    case SETTING_ROLE_DELETE = "SettingsRoles_delete";
    case SETTING_ROLE_CREATE = "SettingsRoles_create";
    case SETTING_ROLE_PERMISSION = "SettingsRoles_permission";

    case SETTING_BANK_LIST = "SettingsBank_list";
    case SETTING_BANK_EDIT = "SettingsBank_edit";
    case SETTING_BANK_DELETE = "SettingsBank_delete";
    case SETTING_BANK_CREATE = "SettingsBank_create";
    case SETTING_BANK_PERMISSION = "SettingsBank_permission";
}