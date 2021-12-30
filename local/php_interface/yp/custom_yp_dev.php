<?php

use Bitrix\Main\ModuleManager;

// for cli
if (!class_exists('Yp'))
{
	include_once('custom_yp.php');
}

/** Код который нужен для разработки и не нужен на продакшне
 * Class YpDev
 */
class YpDev
{
	/*
	 * При чистке от вирусов чтобы не закачивать кэш файлы
	 *
	 */
	public static function clear_cache_files()
	{
		$path='allphp.list';
		$full_path=__DIR__.'/'.$path;
		$lines=file($full_path);
		$lines_out=[];


		// ^.*/cache/.*$
		// ^.*/managed_cache/.*$
		foreach ($lines as $line)
		{
			$a=1;
			// wip
		}
	}

	public static function setup_dev_environment()
	{
		if (!Yp::is_dev_server()) {
			Yp::log('error: setup_dev_environment called not on dev server');
			return;
		}

		COption::SetOptionString('main', 'check_agents', 'N');

		COption::SetOptionString('main', 'optimize_css_files', 'N');
		COption::SetOptionString('main', 'optimize_js_files', 'N');
		COption::SetOptionString('main', 'use_minified_assets', 'N');
		COption::SetOptionString('main', 'move_js_to_body', 'N');

		/** @noinspection SpellCheckingInspection */
		COption::SetOptionString('main', 'compres_css_js_files', 'N');

		COption::SetOptionString('main', 'component_cache_on', 'N');

		ModuleManager::unRegisterModule('jivosite.jivosite');
		ModuleManager::unRegisterModule('security');
		ModuleManager::unRegisterModule('wsrubi.smtp');
		ModuleManager::unRegisterModule('adwex.minified');

		$settings_path = Yp::get_site_root() . '/bitrix/.settings_extra.php';
		Yp::file_put_content($settings_path,
			'<?php
return [
	\'exception_handling\' =>
		[
			\'value\' =>
				[
					\'debug\' => true,
					\'handled_errors_types\' => 4437,
					\'exception_errors_types\' => 4437,
					\'ignore_silence\' => false,
					\'assertion_throws_exception\' => true,
					\'assertion_error_type\' => 256,
					\'log\' => [
						\'settings\' => [
							\'file\' => \'exceptions.log\',
							\'log_size\' => 67108864,
						]
					],
				],
			\'readonly\' => false,
		],
];
		
		');

		Bitrix\Main\Config\Configuration::setValue('https_request', false);

		// todo robots deny
	}

    // todo: make this cmd runnable
    /**
     * @param bool $is_delete также удалить ненужные файлы (modules/install)
     */
    public static function setup_phpstorm($is_delete)
    {
        // phpstorm: <excludeFolder url="file://$MODULE_DIR$/bitrix/modules/form/install" />
        // $paths_to_ignore:  /bitrix/modules/form/install
        $paths_to_ignore=[];

        // ignore modules install
        $doc_root=Yp::doc_root();
        echo $doc_root;
        $modules_dir=$doc_root.'/bitrix/modules';
        $module_dir_items = scandir($modules_dir);

        foreach ($module_dir_items as $module_dir_item)
        {
            if ($module_dir_item==='.' || $module_dir_item==='..' || $module_dir_item==='.htaccess') continue;

            $module_full_path=$modules_dir.'/'.$module_dir_item;
            if (!is_dir($module_full_path))
                continue;

            $install_dir=$module_full_path.'/install';
            if (!is_dir($install_dir))
                continue;


            if ($is_delete)
            {
                Yp::delete_directory($install_dir);
            }
            else {
                $install_dir_relative = substr($install_dir, strlen($doc_root));
                $paths_to_ignore[] = $install_dir_relative;
            }
        }

        $extra_ignore=[
        	'/bitrix/wizards/',
        	'/bitrix/cache/',
        	'/bitrix/managed_cache/',
        	'/bitrix/managed_cache1/',
        	'/bitrix/managed_cache2/',
        	'/bitrix/managed_cache3/',
        	'/bitrix/managed_cache4/',
        	'/bitrix/managed_cache5/',
        	'/bitrix/admin/',
        	'/bitrix/backup/',
        	'/bitrix/js/fileman/',
			
		];

        foreach($extra_ignore as $to_check)
		{
			$full_path=$doc_root.$to_check;
			if (is_dir($full_path))
			{
				$paths_to_ignore[]=$to_check;
			}
		}

        //

        $xml_lines='';
        foreach($paths_to_ignore as $path_to_ignore)
        {
            $xml_line="\t\t".'<excludeFolder url="file://$MODULE_DIR$'.$path_to_ignore.'" />';
            $xml_lines.=$xml_line.PHP_EOL;
        }

        // todo apply ignored paths. manual now.

        echo PHP_EOL.$xml_lines;
        $a=1;

        // todo set breakpoint on exceptions / inject logging

    }
}


if (Yp::get_request('setup_dev_environment')) {
	YpDev::setup_dev_environment();
	echo('ok');
	die();
}

if (Yp::get_request('setup_phpstorm')) {
	YpDev::setup_phpstorm();
	echo('ok');
	die();
}

if (php_sapi_name() == 'cli')
{
	if (count($argv) > 1)
	{
		if ($argv[1]==='setup_phpstorm')
		{
			YpDev::setup_phpstorm(false);
			echo 'ok';
		}
		else
		{
			echo 'todo: help';
		}
	}
	else
	{
		echo 'todo: help';

	}
}