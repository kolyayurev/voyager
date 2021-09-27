<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $setting = $this->findSetting('site.title');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.title'),
                'value'        => __('voyager::seeders.settings.site.title'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Site',
            ])->save();
            
        }

        $setting = $this->findSetting('site.description');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.description'),
                'value'        => __('voyager::seeders.settings.site.description'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('site.logo');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.logo'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 3,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('site.google_analytics_tracking_id');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.site.google_analytics_tracking_id'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 4,
                'group'        => 'Site',
            ])->save();
        }

        $setting = $this->findSetting('admin.bg_image');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.background_image'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 5,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.title');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.title'),
                'value'        => 'Voyager',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.description');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.description'),
                'value'        => __('voyager::seeders.settings.admin.description_value'),
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.loader');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.loader'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 3,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.icon_image');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.icon_image'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 4,
                'group'        => 'Admin',
            ])->save();
        }

        $setting = $this->findSetting('admin.google_analytics_client_id');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.admin.google_analytics_client_id'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Admin',
            ])->save();
        }
        $setting = $this->findSetting('seo.meta_title');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.meta_title'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.meta_description');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.meta_description'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text_area',
                'order'        => 2,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.meta_keywords');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.meta_keywords'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 3,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.twitter_name');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.twitter_name'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 4,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.logo');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.logo'),
                'value'        => '',
                'details'      => '',
                'type'         => 'image',
                'order'        => 5,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.yandex_verification');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.yandex_verification'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 6,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('seo.yandex_metrica');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.seo.yandex_metrica'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 7,
                'group'        => 'SEO',
            ])->save();
        }
        $setting = $this->findSetting('contacts.phone');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.contacts.phone'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Contacts',
            ])->save();
        }
        $setting = $this->findSetting('contacts.email');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.contacts.email'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Contacts',
            ])->save();
        }
        $setting = $this->findSetting('social.facebook');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.social.facebook'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Social',
            ])->save();
        }
        $setting = $this->findSetting('social.instagram');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.social.instagram'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Social',
            ])->save();
        }
        $setting = $this->findSetting('social.whatsapp');
        if (!$setting->exists) {
            $setting->fill([
                'display_name' => __('voyager::seeders.settings.social.whatsapp'),
                'value'        => '',
                'details'      => '',
                'type'         => 'text',
                'order'        => 3,
                'group'        => 'Social',
            ])->save();
        }
    }

    /**
     * [setting description].
     *
     * @param [type] $key [description]
     *
     * @return [type] [description]
     */
    protected function findSetting($key)
    {
        return Setting::firstOrNew(['key' => $key]);
    }
}
