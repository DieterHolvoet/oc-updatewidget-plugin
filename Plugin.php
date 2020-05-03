<?php

namespace DieterHolvoet\UpdateWidget;

use DieterHolvoet\UpdateWidget\ReportWidgets\PluginUpdates;
use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;

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
}
