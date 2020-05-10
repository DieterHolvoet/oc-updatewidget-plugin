<?php

namespace DieterHolvoet\UpdateWidget;

use DieterHolvoet\UpdateWidget\Models\Settings;
use DieterHolvoet\UpdateWidget\ReportWidgets\PluginUpdates;
use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => Lang::get('dieterholvoet.updatewidget::app.name'),
            'description' => Lang::get('dieterholvoet.updatewidget::app.description'),
            'author'      => 'DieterHolvoet',
            'icon'        => 'icon-cloud-download'
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'dieterholvoet.updatewidget.access_settings' => [
                'label' => Lang::get('dieterholvoet.updatewidget::app.permission.access_settings'),
                'tab' => Lang::get('dieterholvoet.updatewidget::app.name'),
            ],
            'dieterholvoet.updatewidget.view_widget' => [
                'label' => Lang::get('dieterholvoet.updatewidget::app.permission.view_widget'),
                'tab' => Lang::get('dieterholvoet.updatewidget::app.name'),
            ],
        ];
    }

    public function registerReportWidgets(): array
    {
        return [
            PluginUpdates::class => [
                'label'   => Lang::get('dieterholvoet.updatewidget::app.widget.plugin_updates'),
                'context' => 'dashboard',
                'permissions' => ['dieterholvoet.updatewidget.view_widget'],
            ],
        ];
    }

    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label'       => Lang::get('dieterholvoet.updatewidget::app.name'),
                'description' => Lang::get('dieterholvoet.updatewidget::app.settings'),
                'category'    => SettingsManager::CATEGORY_BACKEND,
                'icon'        => 'icon-cloud-download',
                'class'       => Settings::class,
                'order'       => 500,
                'keywords'    => 'settings plugin updates',
                'permissions' => ['dieterholvoet.updatewidget.access_settings'],
            ],
        ];
    }
}
