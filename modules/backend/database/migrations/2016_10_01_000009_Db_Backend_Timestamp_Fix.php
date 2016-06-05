<?php

use October\Rain\Database\Updates\Migration;
use Backend\Models\Preference as PreferenceModel;
use Backend\Models\BrandSetting as BrandSettingModel;

/**
 * This migration addresses a MySQL specific issue around STRICT MODE.
 * In past versions, Laravel would give timestamps a bad default value
 * of "0" considered invalid by MySQL. Strict mode is disabled and the
 * the timestamps are patched up. Credit for this work: Dave Shoreman.
 */
class DbBackendTimestampFix extends Migration
{
    protected $backendTables = [
        'backend_users',
        'backend_user_groups',
        'backend_access_log',
    ];

    public function up()
    {
        DbDongle::disableStrictMode();

        foreach ($this->backendTables as $table) {
            DbDongle::convertTimestamps($table);
        }

        // Use this opportunity to reset backend preferences and styles for stable
        PreferenceModel::instance()->resetDefault();
        BrandSettingModel::instance()->resetDefault();
    }

    public function down()
    {
        // ...
    }
}
