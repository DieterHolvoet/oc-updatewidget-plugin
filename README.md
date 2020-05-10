oc-updatewidget-plugin
======================

[![Latest Stable Version](https://poser.pugx.org/dieterholvoet/oc-updatewidget-plugin/v/stable)](https://packagist.org/packages/dieterholvoet/oc-updatewidget-plugin)
[![Total Downloads](https://poser.pugx.org/dieterholvoet/oc-updatewidget-plugin/downloads)](https://packagist.org/packages/dieterholvoet/oc-updatewidget-plugin)
[![License](https://poser.pugx.org/dieterholvoet/oc-updatewidget-plugin/license)](https://packagist.org/packages/dieterholvoet/oc-updatewidget-plugin)

> A report widget showing available updates to installed plugins

![Screenshot](https://i.imgur.com/UaN1Icy.png)

## Installation

This OctoberCMS plugin requires PHP 7.0.8 or higher. It can be
installed using Composer:

```bash
 composer require dieterholvoet/oc-updatewidget-plugin
```

## How does it work?
This plugin provides a dashboard widget showing available updates to 
 installed plugins. If a plugin has an update available, you can click on 
 _Update now_ to start updating immediately.

### Permissions
Make sure users who want to use this widget are granted the _View dashboard widget_ permission.                                                      

### Configuration
To configure the plugin, go to the settings page in the backend. This 
 plugin's settings can be found under the Backend section.
 
Configuration can also be provided through code by creating a configuration 
 file `config/dieterholvoet/updatewidget/config.php`, or `config/dieterholvoet/updatewidget/dev/config.php` for environment-specific configuration. Inside the overridden configuration file you can return only values you want to override.

```php
<?php

return [
    'show_all' => true,
];
```  

## Security
If you discover any security-related issues, please email
[dieter.holvoet@gmail.com](mailto:dieter.holvoet@gmail.com) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
