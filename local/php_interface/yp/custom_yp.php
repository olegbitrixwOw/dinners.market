<?php
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Mail\EventManager;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\SystemException;
use Bitrix\Sender\ContactTable;

// require_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/yp/custom_yp.php');

if (file_exists(__DIR__ . '/custom_yp_compat.php'))
	include_once('custom_yp_compat.php');

/**
 * Швейцарский нож.
 */
class Yp
{

	public static $error_count = 0;
	public static $debug = false;
	private static $is_bitrix = null;


	/*
	 * Bitrix error hook:
	 * bitrix/modules/main/lib/diag/exceptionhandler.php
	 *
	 */
	private static $code_generation_iblock_id;

	public static function get_protocol()
	{
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ?
			"https://" : "http://";
		return $protocol;
	}

	public static function trace()
	{
		$trace = debug_backtrace();
		$traceStr = Yp::nice($trace);
		Yp::log($traceStr);
	}

	public static function nice($obj)
	{
		return print_r($obj, true);
	}

	public static function log($message)
	{
		$dir = Yp::doc_root() . '/logs/';
		if (!file_exists($dir))
		{
			mkdir($dir);
			file_put_contents($dir . '.htaccess', 'Deny from All' . PHP_EOL);
		}

		$path = $dir . 'log_yp.txt';
		$size = filesize($path);
		$mb512 = 536870912;
		if ($size > $mb512)
			unlink($path);

		if (Yp::string_starts($message, 'error'))
		{
			// breakpoint to debug errors
			if (Yp::is_yp())
			{
				Yp::$error_count++;
				$step = (Yp::$error_count - 1) * 3;
				echo '<div class="error" style="
position: fixed;
top: ' . $step . 'em;
left: 1em;
color:red;
z-index:9000;
">' . $message . '</div>';

			}
			Yp::noop();
		}


		$prepared_message = date('Y-m-d H:i:s', time()) . "\t" . $message;
		file_put_contents($path, $prepared_message . PHP_EOL, FILE_APPEND);
	}

	/** Поддержка консоли
	 * @return false|mixed|string
	 */
	public static function doc_root()
	{
		if (php_sapi_name() == 'cli')
		{
			$curr_dir = __DIR__;
			$curr_dir = str_replace('\\', '/', $curr_dir);
			echo 'detecting doc root' . PHP_EOL;
			echo '$curr_dir:' . $curr_dir . PHP_EOL;

			$common_dir = '/bitrix/php_interface';
			if (Yp::string_ends($curr_dir, $common_dir))
			{
				$doc_root = substr($curr_dir, 0, strlen($curr_dir) - strlen($common_dir));
				return $doc_root;
			}

			$common_dir2 = $curr_dir . '/bitrix/php_interface';
			if (file_exists($common_dir2))
			{
				return $curr_dir;
			}

			$common_dir3 = '/local/php_interface/yp';
			if (Yp::string_ends($curr_dir, $common_dir3))
			{
				$doc_root = substr($curr_dir, 0, strlen($curr_dir) - strlen($common_dir3));
				return $doc_root;
			}
		}
		else
		{
			return $_SERVER['DOCUMENT_ROOT'];
		}
	}

	public static function string_ends($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0)
		{
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	public static function string_starts($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public static function is_yp()
	{
		if (Yp::is_dev_server())
			return true;

		$clientIp = Yp::get_client_ip();
		if ($clientIp === '109.207.194.113')
			return true;

		if ($_COOKIE && $_COOKIE['DEV_YP'] === 'Y')
			return true;

		return false;
	}

	public static function is_dev_server()
	{
		if (isset($_SERVER['SERVER_NAME']))
		{
			$serverName = $_SERVER['SERVER_NAME'];
		}
		else
		{
			if (Yp::string_starts(__FILE__, "C:\\Carry\\"))
			{
				return true;
			}
			$serverName = '';
		}

//		if ($serverName === 'devbox')
//			return true;

		if (Yp::string_ends($serverName, '.test'))
			return true;

		if (Yp::string_ends($serverName, '.o7.by'))
			return true;


//		$doc_root = Yp::doc_root();
//		if (Yp::string_starts($doc_root, '/srv/extra/'))
//			return true;

		return false;
	}

	public static function get_client_ip()
	{
		$result = null;
		if (isset($_SERVER['REMOTE_ADDR']))
		{
			$result = $_SERVER['REMOTE_ADDR'];
		}

		if ($result == null)
			$result = '127.0.0.1';

		return $result;
	}

	public static function noop()
	{
	}

	public static function string_contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== false;
	}

	public static function bx_test_email($to)
	{
		if (empty($to))
			$to = 'marvel.me@gmail.com';

		$result = bxmail(
			$to,
			'test',
			'test body',
			"",
			""
		);

		Yp::log('test_email bxmail result:' . Yp::nice($result) . ' to:' . $to);
	}

	public static function bx_test_mail_event($to)
	{
		if (empty($to))
			$to = 'marvel.me@gmail.com';

		$arFields = array(
			'ORDER_ID' => 'test',
			'ORDER_DATE' => 'ORDER_DATE',
			'EMAIL' => $to,
		);

		$result = CEvent::SendImmediate
		(
			'SALE_NEW_ORDER',
			's1',
			$arFields,
			"Y",
			""
		);

		Yp::log('test_mail_event result:' . Yp::nice($result) . ' to:' . $to);
	}

	/** @noinspection PhpUndefinedClassInspection */

	public static function adm_peek($obj)
	{
		if (!Yp::bx_is_admin())
			return;
		Yp::peek($obj);
	}

	public static function bx_is_admin()
	{
		global $USER;
		Yp::log('warn: user not exists');

		return $USER !== null && $USER->IsAdmin();
	}

	public static function peek($obj)
	{
		if (!Yp::is_yp())
			return false;

		$obj_str = Yp::nice($obj);
		echo PHP_EOL . '<pre class="peek">' . 'peek:' . $obj_str . '</pre>' . PHP_EOL;
		return true;
	}

	public static function array_insert(&$array, $position, $insert)
	{
		if (is_int($position))
		{
			array_splice($array, $position, 0, $insert);
		}
		else
		{
			$pos = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}

	/*
	 * создаёт папку если нету
	 */

	/**
	 * @param $dbRes CDBResult
	 * @param null $max
	 * @return array
	 */
	public static function bx_fetch_all($dbRes, $max = null)
	{
		$result = [];
		$count = 0;
		while ($value = $dbRes->Fetch())
		{
			$count++;
			if ($max && $count > $max)
				break;

			$result[] = $value;
		}

		return $result;
	} // file_put_content

	/*
	 * Не все хостинги могут запаковать upload из за ограничений по времени.
	 * поэтому эта функция выкачки файлов на дев сервер
	 *
	 * Не выкачивает файлы которые не зарегистрированы в CFile
	 * например ресайзы
	 */

	public static function array_first($arr)
	{
		return array_pop(array_reverse($arr));
	}

	public static function file_append_content($filepath, $content)
	{
		Yp::file_put_content($filepath, $content, FILE_APPEND);
	}

	public static function file_put_content(string $filepath, $content, $flags = 0)
	{
		try
		{
			$isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filepath, $filepathMatches);
			if ($isInFolder)
			{
				$folderName = $filepathMatches[1];
				// $fileName = $filepathMatches[2];
				if (!is_dir($folderName))
				{
					$mkDirResult = mkdir($folderName, 0777, true);
					if (!$mkDirResult)
						Yp::log('error: failed to mkdir:' . $folderName);

				}
			}
			$put_content_result = file_put_contents($filepath, $content, $flags);
			if (!$put_content_result)
			{
				Yp::log('error: failed to put content:' . $filepath);
			}
			return $put_content_result;
		} catch (Exception $e)
		{
			Yp::log("error: writing to '$filepath', " . $e->getMessage());
			return false;
		}
	}

	public static function download_files($fromUrl)
	{
		Yp::log('download_files start');
		$fileCursor = CFile::GetList();

		$uploadPath = Yp::doc_root() . '/upload/';
		if (!file_exists($uploadPath))
			mkdir($uploadPath);

		$failCount = 0;
		while ($fileRecord = $fileCursor->Fetch())
		{
			$pathrelToUpload = $fileRecord['SUBDIR'] . '/' . $fileRecord['FILE_NAME'];
			$path = $uploadPath . $pathrelToUpload;
			if (!file_exists($path))
			{
				$url = $fromUrl . '/upload/' . $pathrelToUpload;
				$content = file_get_contents($url);
				$putResult = Yp::file_put_content($path, $content);
				if (!$putResult)
				{
					Yp::log('error:cannot write file:' . $path);
					$failCount++;

					if ($failCount > 5)
						break;
				}
				Yp::log('downloaded:' . $path);

			}
		}

		Yp::log('download_files end');
	}

	public static function list_directory()
	{
		$uploadPath = Yp::doc_root() . '/upload/';
		$files = Yp::getDirContents($uploadPath);
		$dest = Yp::doc_root() . '/logs/filelist.txt';
		foreach ($files as $file)
		{
			if (is_dir($file))
				continue;

			Yp::file_put_content($dest, $file . PHP_EOL);
		}
	}

	/*
	пример использования в 404.php - докачка частичной копии

if (!class_exists('Yp'))
{
	include_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/yp/custom_yp.php');
}


if ($url==null)
	$url=$_SERVER['REQUEST_URI'];

if (Yp::is_dev_server())
{
	Yp::try_download($url);
	die('404 dev');
}

	*/

	/** Получить список файлов и папок в папке и подпапках
	 * @param $dir
	 * @param array $results
	 * @return array
	 */
	public static function getDirContents($dir, &$results = array())
	{
		$files = scandir($dir);

		foreach ($files as $key => $value)
		{
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path))
			{
				$results[] = $path;
			}
			else if ($value != "." && $value != "..")
			{
				Yp::getDirContents($path, $results);
				$results[] = $path;
			}
		}

		return $results;
	}

	public static function compress_all_images_from_dir()
	{
		Yp::log('compress_all_images start');

		$uploadPath = Yp::doc_root() . '/upload/';

		$files = Yp::getDirContents($uploadPath);
		foreach ($files as $file)
		{

			$lowerName = strtolower($file);
			if (!Yp::string_ends($lowerName, 'jpg') && !Yp::string_ends($lowerName, 'jpeg'))
				continue;

			// этот не принимается ResizeImageGet
			// $fileArray=CFile::MakeFileArray($file);
			$fileDescriptor = Yp::bx_get_file_descriptor_from_path($file);
			// $imageSize=CFile::GetImageSize($file);
			// $image=CFile::CreateImage($file);

			$w = $fileDescriptor['WIDTH'];
			$h = $fileDescriptor['HEIGHT'];

			if ($h < 100 && $w < 100)
				continue;

			if ($w > 1920)
				$w = 1920;

			if ($h > 1080)
				$w = 1080;


			// $image не принимает
			// $fileArray
			$arImgResized = CFile::ResizeImageGet(
				$fileDescriptor,
				array("width" => $w, "height" => $h),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				false,
				false,
				true,
				75
			// ,true
			);

			if (!$arImgResized['src'])
			{
				Yp::log('error: image not resized:' . $file);
				continue;
			}

			$destPath = Yp::doc_root() . '/upload/2/' . $fileDescriptor['SUBDIR'] . '/' . $fileDescriptor['FILE_NAME'];
			$resizedPath = Yp::doc_root() . $arImgResized['src'];

			$sizeFrom = $fileDescriptor['FILE_SIZE'];
			$sizeTo = filesize($resizedPath);

			if ($sizeFrom <= $sizeTo)
			{
				Yp::log('size not improved: ' . $destPath . " from:" . $sizeFrom . " to:" . $sizeTo);
				continue;
			}

			Yp::log('resized ' . $destPath . " from:" . $sizeFrom . " to:" . $sizeTo . ' resized path:' . $resizedPath);

			$content = file_get_contents($resizedPath);
			Yp::file_put_content($destPath, $content);

		}

		Yp::log('compress_all_images end');
	}

	public static function bx_get_file_descriptor_from_path($path)
	{
		// массив описания файла (Array(FILE_NAME, SUBDIR, WIDTH, HEIGHT, CONTENT_TYPE)), полученный методом GetFileArray.

		$imageSize = CFile::GetImageSize($path);

		$w = $imageSize[0];
		$h = $imageSize[1];

		$result['WIDTH'] = $w;
		$result['HEIGHT'] = $h;

		$pathInfo = pathinfo($path);
		$result['FILE_NAME'] = $pathInfo['basename'];

		$docRoot = Yp::doc_root();
		$docRootLen = strlen($docRoot);
		$dirName = $pathInfo['dirname'];

		$subirStart = $docRootLen + strlen('/upload/');
		$subdir = substr($dirName, $subirStart);
		$result['SUBDIR'] = $subdir;

		$result['FILE_SIZE'] = filesize($path);

		return $result;
	}

	public static function try_download($path)
	{
		if (!defined('YP_PRODUCTION_URL'))
			return;

		$server_name = $_SERVER['SERVER_NAME'];
		$server_name_pos = strpos($path, $server_name);
		if ($server_name_pos > 0)
		{
			$path = substr($path, $server_name_pos + strlen($server_name));
		}

		$fixed_rel_path = rtrim($path, "/");

		$prodPath = YP_PRODUCTION_URL . $fixed_rel_path;
		$content = file_get_contents($prodPath);
		if (!$content)
			return;

		$rel_path_fixed_for_local = Yp::string_before($fixed_rel_path, '?');
		$localPath = Yp::doc_root() . $rel_path_fixed_for_local;
		$putResult = Yp::file_put_content($localPath, $content);
		if ($putResult)
		{
			Yp::log('try_download success:' . $path);
		}
		else
		{
			Yp::log('try_download fail:' . $path);
		}
	}

		public static function string_before(string $str, string $delimiter)
	{
		$result = explode($delimiter, $str);
		return $result[0];
	} // get_page_cached

	public static function bx_section_is_active($sectionId)
	{
		$section = CIBlockSection::GetByID($sectionId)->Fetch();
		if (!$section) return false;

		return $section['ACTIVE'] == 'Y';
	}

	public static function bx_create_user($password)
	{
		$user = new CUSer();

		$arFields = Array(
			'NAME' => 'Yury',
			'LAST_NAME' => 'Pahomau',
			'EMAIL' => 'marvel.me@gmail.com',
			'LOGIN' => 'dev_yp',
			'LID' => 'ru',
			'ACTIVE' => 'Y',
			'GROUP_ID' => array(1),
			'PASSWORD' => $password,
			'CONFIRM_PASSWORD' => $password,
		);

		$new_id = $user->Add($arFields);
		if (!$new_id)
		{
			Yp::log('error: user not created');
		}
	}

	/** Если вставить в шапку каждого файла
	 * Yp::trace_in_output(__FILE__);
	 * то в html документе будет видно какая его часть из какого файла
	 * to_do: поискать пути сделать это автоматически
	 * 1 - в виде скрипта который дописывает файлы, да, изи, со списком исключений/включений
	 * @param $content
	 */
	public static function trace_in_output($content)
	{
		if (!Yp::is_yp())
			return;

		echo '<!-- file:' . $content . '-->';
	}

	public static function string_null_or_empty($str)
	{
		return (!isset($str) || trim($str) === '');
	}

	public static function bx_get_section_by_code($code, $arSelect = null)
	{
		$cursor = CIBlockSection::GetList(
			null,
			['CODE' => $code],
			false,
			$arSelect,
			false
		);

		$result = $cursor->Fetch();
		return $result;
	}

	/** То что можно вставить в PREVIEW_PICTURE, DETAIL_PICTURE при добавлении элемента
	 * @param $url string
	 * @param $name string под каким именем сохранять
	 * @param $folder string
	 * @return array|bool
	 */
	public static function bx_image_from_url($url, $name, $folder)
	{
		$imageContent = Yp::get_page_cached($url);
		if ($imageContent)
		{
			$destPath = Yp::get_site_root() . '/upload/' . $folder . '/' . $name . '.jpg';
			Yp::file_overwrite_content($destPath, $imageContent);
			if (CFIle::IsImage($destPath))
			{
				$picBxDescriptor = CFile::MakeFileArray($destPath);
				return $picBxDescriptor;
			}
		}
		return false;
	}

/**
	 * @param string $url
	 * @param int $days
	 * @param string $path Out параметр - путь к файлу
	 * @param callable $fnPreprocess
	 * @return bool|false|mixed|string
	 */
	public static function get_page_cached($url, $days = 3, &$path = null, $fnPreprocess = null)
	{
		if (rand(1, 256) === 1)
		{
			Yp::clean_cache();
		}

		$file = Yp::filename_from_url($url);
		$cacheKey = $file;

		$cacheTimeSeconds = $days * 24 * 60 * 60;

		$cachePath = Yp::get_site_root() . '/cache/' . $cacheKey;

		if (file_exists($cachePath))
		{
			$fileTime = filemtime($cachePath);
			$now = time();
			$secondsOld = $now - $fileTime;

			if ($secondsOld < $cacheTimeSeconds)
			{
				$path = $cachePath;
				return file_get_contents($cachePath);
			}
		}

		$content = file_get_contents($url);
		if ($content == null)
		{
			Yp::log('error: Failed to get content from:' . $url);
		}
		else
		{
			$path = $cachePath;
			if ($fnPreprocess != null)
			{
				$content = $fnPreprocess($content);
			}
			Yp::file_overwrite_content($cachePath, $content);
		}

		return $content;
	}

	public static function clean_cache($days = 10, $force = false)
	{
		$lastCleanFile = __DIR__ . '/cache_clean_time';

		$now = time();
		if (!$force)
		{
			$lastClean = file_get_contents($lastCleanFile);

			if ($lastClean > 0)
			{
				$secondsSinceLastClean = $now - $lastClean;
				if ($secondsSinceLastClean < 86400)
					return;
			}
		}

		self::file_put_content($lastCleanFile, $now);

		$daysInSeconds = $days * 24 * 60 * 60;
		$cachePath = Yp::get_site_root() . '/cache/';
		$files = Yp::getDirContents($cachePath);
		$now = time();

		$count = 0;
		foreach ($files as $file)
		{
			$fileTime = filemtime($file);
			$howOld = $now - $fileTime;
			if ($howOld > $daysInSeconds)
			{
				$count++;
				unlink($file);
			}
		}

		Yp::log('cache clean:' . $count);
	}

	public static function get_site_root()
	{
		return Yp::doc_root();
	}

		public static function filename_from_url($url)
	{
		$file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '_', $url);
		$file = mb_ereg_replace("([\.]{2,})", '_', $file);
		return $file;
	} // bx_build_section_path

	public static function file_overwrite_content($filepath, $content)
	{
		Yp::file_put_content($filepath, $content);
	}

	/*
Следующие значения воспринимаются как пустые:
"" (пустая строка)
0 (целое число)
0.0 (число с плавающей точкой)
"0" (строка)
NULL
FALSE
array() (пустой массив)
	 */

	public static function bx_delete_element($id)
	{
		$deleteResult = CIBlockElement::Delete($id);
		if (!$deleteResult)
		{
			Yp::log('error: element not deleted:' . $id);
		}

		return $deleteResult;
	}

/** Создаёт разделы если нету, возвращает код нижнего
	 * @param array $section_path
	 * @param $iblock_id
	 * @return string
	 */
	public static function bx_build_section_path(array $section_path, $iblock_id)
	{
		$parent_id = false;
		$bs = new CIBlockSection();
		foreach ($section_path as $category)
		{
			if (!$category)
				break;

			$cursor = CIBlockSection::GetList(
				null,
				[
					'NAME' => $category,
					'IBLOCK_ID' => $iblock_id,
					'SECTION_ID' => $parent_id
				]
			);
			$section = $cursor->Fetch();
			if (!$section)
			{
				$newId = null;

				$count = 0;
				$maxCount = 256;
				$baseCode = Yp::bx_code_by_name($category);
				while (true)
				{
					$count++;
					if ($count > $maxCount)
						break;

					if ($count === 1)
					{
						$code = $baseCode;
					}
					else if ($count == $maxCount)
					{
						$code = $baseCode . '_' . uniqid();
					}
					else
					{
						$code = $baseCode . '_' . $count;
					}

					$newId = $bs->Add([
						'NAME' => $category,
						'CODE' => $code,
						'IBLOCK_ID' => $iblock_id,
						'IBLOCK_SECTION_ID' => $parent_id
					]);

					if ($newId)
					{
						$str_path = implode('/', $section_path);
						Yp::log('section created:' . $newId . ':' . $code . ':' . $category . ' parent:' . $parent_id .
							' path:' . $str_path);
						break;
					}
				}

				if (!$newId)
				{
					Yp::log('error: cannot create section:' . $bs->LAST_ERROR);
				}


				$parent_id = $newId;
			}
			else
			{
				$parent_id = $section['ID'];
			}
		}

		return $parent_id;
	}

	/** Код элемента по наименованию
	 * @param $name string
	 * @return string
	 */
	public static function bx_code_by_name($name)
	{
		$arParams = array(
			"max_len" => "60", // обрезаем символьный код до 60 символов
			"change_case" => "L", // приводим к нижнему регистру
			"replace_space" => "-", // меняем пробелы на тире
			"replace_other" => "-", // меняем плохие символы на тире
			"delete_repeat_replace" => "true", // удаляем повторяющиеся тире
			"use_google" => "false", // отключаем использование google
		);

		$result = CUtil::translit($name, 'ru', $arParams);
		return $result;
	}

	public static function array_set_if_not_empty(&$arr, $k, $v)
	{
		if (empty($v))
			return;

		$arr[$k] = $v;
	}

	/** Умеет удалять дополнительные виды пробелов
	 * @param $str string
	 * @return string
	 */
	public static function trim($str)
	{
		$result = trim($str, "\t\n\r\0\x0B \xC2\xA0");
		return $result;
	}

	public static function get_file_if_fresh($path, $days = null)
	{
		if (Yp::file_is_fresh($path, $days))
			return file_get_contents($path);
		else
			return null;
	}

	public static function file_is_fresh($path, $days = null)
	{
		if ($days === null)
		{
			if (Yp::is_dev_server())
			{
				$days = 7;
			}
			else
			{
				$days = 1;
			}
		}

		if (!file_exists($path)) return null;
		$file_time = filemtime($path);
		$now = time();
		$how_old = $now - $file_time;
		$days_in_seconds = $days * 86400;
		if ($how_old > $days_in_seconds) return false;

		return true;
	}

	public static function var_export($var)
	{
		return var_export($var, true);
	}

	public static function var_import($str)
	{
		$result = null;
		eval('$result = ' . $str . ';');
		return $result;
	}

	public static function preg_first_match($str, $pattern)
	{
		preg_match($pattern, $str, $matches);
		return $matches[1];

	}

	public static function url_compine($part1, $part2)
	{
		if (Yp::string_ends($part1, '/'))
		{
			return $part1 . ltrim($part2, '/');
		}

		return $part1 . $part2;
	}

	public static function array_append(&$arrTo, $arrFrom)
	{
		foreach ($arrFrom as $item)
		{
			$arrTo[] = $item;
		}
	}

	public static function array_append_new(&$arrTo, $arrFrom)
	{
		foreach ($arrFrom as $item)
		{
			if (in_array($item, $arrTo))
				continue;

			$arrTo[] = $item;
		}
	}

	public static function bx_update_sitemap()
	{
		// to_do: implement
		// to_do: вызывать после парсинга
		// to_do: у товинт карта сайта в кастомной локации
	}

	public static function report_errors()
	{
		// to_do: раз в день собираем ошибки (при записи в лог ошибки и ворнинги пишем отдельно)
		// и шлём их мне.
	}

	public static function preg_named_match($name, string $pattern, string $group_name)
	{
		$matches = null;
		preg_match($pattern, $name, $matches);
		return $matches[$group_name];
	}

	public static function bx_get_product_offers($product_id, $iblock_id, $fields = [])
	{
		$result = CCatalogSKU::getOffersList(
			$product_id,
			$iblock_id,
			[],
			$fields,
			[]
		);

		return $result;
	}

	public static function bx_get_element_by_property(string $prop_name, $value, $iblock_id = null,
		$ar_select_fields = [])
	{
		$ar_filter = [
			$prop_name => $value
		];

		if ($iblock_id !== null)
		{
			$ar_filter['IBLOCK_ID'] = $iblock_id;
		}

		$cursor = CIBlockElement::GetList(null, $ar_filter, false, false,
			$ar_select_fields);

		$result = $cursor->Fetch();
		return $result;
	}

	/**
	 * @param $id
	 * @return Base
	 * @throws ArgumentException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public static function bx_hl_entity($id)
	{
		CModule::IncludeModule('highloadblock');
		$hl_data = Bitrix\Highloadblock\HighloadBlockTable::getById($id)->fetch();
		$hl_entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl_data);
		return $hl_entity;
	}

	public static function file_replace_str(string $path, string $search, string $replace)
	{
		$content = file_get_contents($path);
		$new = str_replace($search, $replace, $content);
		Yp::file_put_content($path, $new);
	}

	/*
	 * В списке рассылок могут бьть ящики не принадлежащие пользователям
	 */

	public static function sanitize_mail_list()
	{
		Yp::log('sanitize_mail_list start');
		CModule::IncludeModule('sender');
		$list = ContactTable::getList(array(
			'select' => ['ID', 'NAME', 'CODE', 'USER_ID'],
//			'filter' => [],
//			'offset' => null,
//			'limit' => null,
//			'count_total' => false,
//			'order' => null
		));


		$count = 0;
		while ($contact = $list->fetch())
		{
			$email = $contact['CODE'];
			$user = Yp::bx_user_by_email($email);

			if (!$user)
			{
				Yp::log('delete:' . Yp::nice($contact));

				$delete_result = ContactTable::delete($contact['ID']);
				if (!$delete_result->isSuccess())
				{
					Yp::log('error: delete failed');
				}
				else
				{
					$count++;
				}
			}
		}

		Yp::log('sanitize_mail_list end:' . $count);
		return $count;
	}

	public static function bx_user_by_email($email)
	{
		$filter = ['EMAIL' => $email, 'ACTIVE' => 'Y'];
		$by = 'id';
		$order = 'desc';
		$cursor = CUser::GetList($by, $order, $filter);
		$user = $cursor->Fetch();
		return $user;
	}

	public static function bx_execute_events()
	{
		EventManager::executeEvents();
	}

	/*
     * php delete function that deals with directories recursively
     */

	public static function get_url()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public static function delete_files($target)
	{
		if (is_dir($target))
		{
			$files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

			foreach ($files as $file)
			{
				Yp::delete_files($file);
			}

			rmdir($target);
		}
		elseif (is_file($target))
		{
			unlink($target);
		}
	}

	public static function post_deploy_dev()
	{
		// todo: robots deny
	}

	// режим подробной отладки

	public static function get_request($name)
	{
		if (!isset($_REQUEST))
			return null;

		if (!isset($_REQUEST[$name]))
			return null;

		return $_REQUEST[$name];
	}

	public static function init()
	{
		self::$is_bitrix = file_exists('bitrix');
		if (self::$is_bitrix)
		{
			CModule::IncludeModule('iblock');
		}
	}

	public static function current_link_with_get_parameter($name, $value)
	{
		if (Yp::is_bitrix())
		{
			return Yp::bx_current_link_with_get_parameter($name, $value);
		}
		$query = $_GET;
		$query[$name] = $value;
		return $_SERVER['PHP_SELF'] . '/' . http_build_query($query);
	}

	public static function is_bitrix()
	{
		return self::$is_bitrix;
	}

	public static function bx_current_link_with_get_parameter($name, $value)
	{
		$query = $_GET;
		$query[$name] = $value;

		global $APPLICATION;
		$uri = $APPLICATION->GetCurPage();
		return $uri . http_build_query($query);
	}

	public static function get_current_day_code()
	{
		return substr(date("l"), 0, 2);
	}

	public static function bx_iblock_id($code, $iblock_type = null)
	{
		if (!$code)
		{
			Yp::log('error: no code');
			return null;
		}

		$filter = [
			'CODE' => $code,
			'CHECK_PERMISSIONS' => 'N',
		];

		if ($iblock_type !== null)
		{
			$filter['TYPE'] = $iblock_type;
		}

		$iblock = (new CIBlock())->GetList([], $filter)->fetch();

		if (!$iblock['ID'])
		{
			Yp::log("Не удалось найти инфоблок с кодом '{$code}'");
			return null;
		}

		return $iblock['ID'];
	}

	/**
	 * Вывести DATE_CREATE пользователю
	 * @param $bx_date_with_time
	 * @return false|string
	 */
	public static function bx_date_readable($bx_date_with_time)
	{
		return substr($bx_date_with_time, 0, 10);
	}

	public static function bx_translit_element_code_add(&$arFields)
	{
		$iblock_id = $arFields['IBLOCK_ID'];
		$arr = Yp::$code_generation_iblock_id;
		if (!in_array($iblock_id, $arr))
			return;


		if (strlen($arFields["NAME"]) > 0 /* && strlen($arFields["CODE"]) <= 0*/)
		{
			$code = Yp::bx_translit($arFields["NAME"]);
			$code_unique = Yp::bx_code_unique($code);

			$arFields['CODE'] = $code_unique;
		}
	}

	public static function bx_translit($text)
	{
		$arParams = array(
			"max_len" => "100", // обрезаем символьный код до 100 символов
			"change_case" => "L", // приводим к нижнему регистру
			"replace_space" => "-", // меняем пробелы на тире
			"replace_other" => "-", // меняем плохие символы на тире
			"delete_repeat_replace" => "true", // удаляем повторяющиеся тире
			"use_google" => "false", // отключаем использование google
		);
		return Cutil::translit($text, "ru", $arParams);
	}

	public static function bx_code_unique($code_base)
	{
		$try_count = 0;
		$max_tries = 32;

		while (true)
		{
			$try_count++;

			$code = $code_base;
			if ($try_count > 1)
			{
				$code .= '_' . $try_count;
			}

			if ($try_count == $max_tries)
			{
				$code = $code_base . '_' . uniqid();
			}
			else if ($try_count > $max_tries)
			{
				// Yp::log('warn: cannot make unique code:' . $code_base);
				return uniqid();
				break;
			}

			$existing = Yp::bx_element_by_code($code);
			if (empty($existing))
				return $code;
		}

		return null;
	}

	public static function bx_element_by_code($code, $arSelectFields = null)
	{
		$cursor = CIBlockElement::GetList(null, ['CODE' => $code], false, false, $arSelectFields);
		$element = $cursor->Fetch();
		return $element;
	}

	public static function bx_translit_element_code_update(&$arFields)
	{
		$iblock_id = $arFields['IBLOCK_ID'];
		$arr = Yp::$code_generation_iblock_id;
		if (!in_array($iblock_id, $arr))
			return;


		if (strlen($arFields["NAME"]) > 0 && strlen($arFields["CODE"]) <= 0)
		{
			$code = Yp::bx_translit($arFields["NAME"]);
			$code_unique = Yp::bx_code_unique($code);

			$arFields['CODE'] = $code_unique;
		}
	}

	/**
	 * Автоматическая генерация кодов для элемента.
	 * чтобы юзер не ловил "ОшибкаЭлемент с таким символьным кодом уже существует." на сохранении,
	 * и можно поле кода тогда прятать.
	 * @param $ar_iblock_id array Для каких блоков код генерить
	 */
	public static function bx_enable_code_generation($ar_iblock_id)
	{
		Yp::$code_generation_iblock_id = $ar_iblock_id;
		/**
		 * Транслит имени в символьный код (для ЧПУ)
		 */
		$callback_add = ['Yp', 'bx_translit_element_code_add'];
		$callback_update = ['Yp', 'bx_translit_element_code_update'];

		// это происходит после проверки кода на уникальность, поэтому в настройках инфоблока нужно не заполнять код если используем это
		// todo: автонастройка
		AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', $callback_add);
		AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', $callback_update);
	}

	public static function is_test_server()
	{
		Yp::log("is_test_server:");
		$serverName = $_SERVER['SERVER_NAME'];
		if (Yp::string_ends($serverName, '.tmweb.ru'))
			return true;

		if (Yp::string_ends($serverName, '.o7.by'))
			return true;

		return false;
	}

	/**
	 * Чтобы это работало в bitrix/modules/main/classes/mysql/database_mysqli.php
	 * в protected function QueryInternal($strSql)
	 * добавляем
	 *
	 * if ($GLOBALS['yp_sql_debug'])
	 * {
	 * file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/sql.txt', $strSql.PHP_EOL.PHP_EOL, FILE_APPEND);
	 * }
	 */
	public static function sql_debug_start()
	{
		$GLOBALS['yp_sql_debug'] = true;
	}

	public static function sql_debug_end()
	{
		$GLOBALS['yp_sql_debug'] = false;
	}

	/** @noinspection PhpUnusedParameterInspection
	 * пишем
	 *
	 * Позволяет находить например какой компонент будет включен после текущего.
	 *
	 * $include_path=$_SERVER["DOCUMENT_ROOT"].$this->__file;
	 * Yp::before_component_include($include_path);
	 * include($include_path);
	 */
	public static function before_component_include($include_path)
	{
		Yp::noop();
	}

	public static function bx_get_section_by_id($id, $arSelect = null)
	{
		$cursor = CIBlockSection::GetList(
			null,
			['ID' => $id],
			false,
			$arSelect,
			false
		);

		$result = $cursor->Fetch();
		return $result;
	}

	public static function bx_element_preview_pic_url(&$element)
	{
		$preview_id = $element['PREVIEW_PICTURE'];
		if (!$preview_id)
		{
			$preview_id = $element['DETAIL_PICTURE'];
		}

		if (!$preview_id)
			return null;

		return Yp::bx_img_src_from_id($preview_id);
	}

	public static function bx_img_src_from_id($id)
	{
		$file_array = CFile::GetFileArray($id);
		return Bitrix\Iblock\Component\Tools::getImageSrc($file_array);
	}

	public static function array_random(&$array)
	{
		if (empty($array)) return null;

		$index = array_rand($array);
		$result = $array[$index];

		return $result;
	}

	public static function bx_is_guest()
	{
		global $USER;
		return !$USER->IsAuthorized();
	}

	public static function date_ymd_to_dmy($ymd, $separator = '/')
	{
		$y = substr($ymd, 0, 4);
		$m = substr($ymd, 5, 2);
		$d = substr($ymd, 8, 2);

		$result = $d . $separator . $m . $separator . $y;

		return $result;
	}

	public static function date_dmy_to_ymd($ymd, $separator = '/')
	{
		$d = substr($ymd, 0, 2);
		$m = substr($ymd, 3, 2);
		$y = substr($ymd, 6, 4);

		$result = $y . $separator . $m . $separator . $d;

		return $result;
	}

	/** https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
	 * todo upgrade: https://github.com/serbanghita/Mobile-Detect/
	 * @return bool
	 */
	public static function is_mobile()
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$is_mob = preg_match
			(
				'/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
				substr($useragent, 0, 4)
			);
		return $is_mob;
	}

	/**
	 * @param $content string|string[] '/vendor/vakata/jstree/dist/jstree.js' | 'css'
	 */
	public static function bx_head($content)
	{
		if (is_array($content))
		{
			$result=true;

			foreach ($content as $item)
			{
				$result &= Yp::bx_head($item);
			}

			return $result;
		}

		// todo: проверка что файл существует
		$ext = pathinfo($content, PATHINFO_EXTENSION);
		$is_css = $ext === 'css';

		$full_path = Yp::doc_root() . $content;
		if (!Yp::is_dev_server())
		{
			if (!Yp::string_ends($content, '.min.' . $ext))
			{
				if (file_exists($full_path))
				{
					$content = substr
						(
							$content, 0, strlen($content) - strlen($ext)
						) .
						'min.' . $ext;
				}
			}
		}

		$full_path = Yp::doc_root() . $content;
		if (!file_exists($full_path))
		{
			Yp::log('error: bx_head no file:'.$content);
			return false;
		}

		$asset = Asset::getInstance();
		if ($is_css)
		{
			$asset->addCss($content);
		}
		else if ($ext === 'js')
		{
			$asset->addJs($content);
		}
		else
		{
			Yp::log('error: unknown asset');
			return false;
		}

		return true;
	}

} // class Yp

Yp::init();

if (Yp::is_dev_server())
{
	if (function_exists('custom_mail'))
	{
		Yp::log('custom_mail already defined');
		custom_mail('', '', '', '', '');
	}
	else
	{
		function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters)
		{
			Yp::log('custom mail:' . $to . ' ' . $subject . PHP_EOL . $message . PHP_EOL
				. Yp::nice($additional_headers) . PHP_EOL . Yp::nice($additional_parameters) . PHP_EOL);

			return true;
		}
	}


}


$a = 1;

// site specific code
if (file_exists(__DIR__ . '/custom_yp_site.php'))
{
	include_once('custom_yp_site.php');
}


if (Yp::is_yp())
{
// developer only code
	if (file_exists(__DIR__ . '/custom_yp_dev.php'))
		include_once('custom_yp_dev.php');

	if (Yp::get_request('bx_execute_events'))
	{
		Yp::bx_execute_events();
		die('ok');
	}
}


if (Yp::get_request('sanitize_mail_list'))
{
	$count = Yp::sanitize_mail_list();

	echo('ok:sanitize_mail_list. count:' . $count);
	die();
}

if (Yp::get_request('yp_check'))
{
	echo('ok:yp_check');
	die();
}

