<?php

namespace DieterHolvoet\UpdateWidget\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Backend\Facades\Backend;
use System\Classes\PluginManager;
use System\Classes\VersionManager;

class PluginUpdates extends ReportWidgetBase
{
    protected $defaultAlias = 'plugin_update_widget';

    /** @var PluginManager */
    protected $pluginManager;
    /** @var VersionManager */
    protected $versionManager;

    public function init(): void
    {
        $this->pluginManager = PluginManager::instance();
        $this->versionManager = VersionManager::instance();
    }

    public function render(): string
    {
        try {
            $this->loadWidgetData();
        } catch (\Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties(): array
    {
        return [
            'title' => [
                'title'             => 'dieterholvoet.updatewidget::app.widget.plugin_updates',
                'default'           => 'dieterholvoet.updatewidget::app.widget.plugin_updates',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ]
        ];
    }

    public function onUpdatePlugin(): array
    {
        $id = post('id');

        // Update plugin
        $isUpdateSuccess = $this->versionManager->updatePlugin($id);

        // Load new data
        try {
            $this->loadPluginData($id, $isUpdateSuccess);
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return [
            "#plugin-updates-{$this->vars['plugin']['data']['safeId']}" => $this->makePartial('plugin')
        ];
    }

    protected function loadWidgetData(): void
    {
        $plugins = $this->pluginManager->getPlugins();

        $this->vars['plugins'] = array_map(function ($id) {
            return $this->getPluginUpdateInfo($id);
        }, array_keys($plugins));
    }

    protected function loadPluginData(string $id, bool $isUpdateSuccess): void
    {
        $info = $this->getPluginUpdateInfo($id);
        $info['data']['isUpdateSuccess'] = $isUpdateSuccess;

        $this->vars['plugin'] = $info;
    }

    protected function getPluginUpdateInfo(string $id): array
    {
        return [
            'newVersions' => $this->versionManager->listNewVersions($id),
            'data' => array_merge(
                $this->pluginManager->getPlugins()[$id]->pluginDetails(),
                [
                    'id' => $id,
                    'safeId' => str_replace('.', '-', $id),
                    'url' => Backend::url('system/updates/details/' . strtolower(str_replace('.', '-', $id))),
                ]
            ),
        ];
    }
}
