<?php

namespace DieterHolvoet\UpdateWidget\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Backend\Facades\Backend;
use DieterHolvoet\UpdateWidget\Models\Settings;
use System\Classes\PluginManager;
use System\Classes\UpdateManager;
use System\Classes\VersionManager;

class PluginUpdates extends ReportWidgetBase
{
    protected $defaultAlias = 'plugin_update_widget';

    /** @var PluginManager */
    protected $pluginManager;
    /** @var VersionManager */
    protected $versionManager;
    /** @var UpdateManager */
    protected $updateManager;
    /** @var Settings */
    protected $settings;

    /** @var array */
    protected $updateList;

    public function init(): void
    {
        $this->pluginManager = PluginManager::instance();
        $this->versionManager = VersionManager::instance();
        $this->updateManager = UpdateManager::instance();
        $this->settings = Settings::instance();

        $this->updateList = $this->updateManager->requestUpdateList();
    }

    public function render(): string
    {
        $this->vars['plugins'] = [];

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
            "#plugin-updates-{$this->vars['plugin']['safeId']}" => $this->makePartial('plugin')
        ];
    }

    protected function loadWidgetData(): void
    {
        $this->vars['plugins'] = $this->getPlugins();
        $this->vars['updatesPage'] = Backend::url('system/updates');
    }

    protected function loadPluginData(string $id, bool $isUpdateSuccess): void
    {
        $info = $this->toInfoArray($id);
        $info['isUpdateSuccess'] = $isUpdateSuccess;

        $this->vars['plugin'] = $info;
    }

    protected function getPlugins(): array
    {
        foreach ($this->pluginManager->getPlugins() as $id => $plugin) {
            $plugins[$id] = $this->toInfoArray($id, $plugin->pluginDetails());
        }

        if (!$this->settings->show_all) {
            $plugins = array_filter(
                $plugins,
                static function (array $info) {
                    return !empty($info['updates']);
                }
            );
        }

        return $plugins;
    }

    protected function toInfoArray(string $id, array $info = []): array
    {
        if (empty($info)) {
            $plugins = $this->pluginManager->getPlugins();
            $info = $plugins[$id]->pluginDetails();
        }

        if ($localUpdates = $this->versionManager->listNewVersions($id)) {
            $updates = $localUpdates;
        } else {
            $updates = $this->updateList['plugins'][$id]['updates'] ?? [];
        }

        foreach ($updates as &$description) {
            if (is_array($description)) {
                $lines = array_map(static function (string $line) {
                    return str_finish($line, '.');
                }, $description);
                $description = implode(' ', $lines);
            }

            if (strpos($description, '!!!') !== false) {
                $description = str_replace('!!!', '', $description);
            }
        }

        $safeId = str_replace('.', '-', $id);
        $safeId = strtolower($safeId);

        return [
            'id' => $id,
            'safeId' => $safeId,
            'name' => $info['name'],
            'url' => Backend::url('system/updates/details/' . $safeId),
            'updates' => $updates,
            'hasLocalUpdates' => !empty($localUpdates),
        ];
    }
}
