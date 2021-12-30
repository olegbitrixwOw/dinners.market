<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
$config = new \Citfact\Getfood\Configurator();

$action = $request->getPost("action");
$value = $request->getPost("value");
$option = $request->getPost("option");
$result = array(
    "error" => true,
    "result" => false
);
if($action && $value)
{
    switch ($action)
    {
        case 'setColor':
            $style = $config->getColorCss($value);
            $config->makeColorCss($value);
            $config->setOption($option, $value);
            $result["result"] = $style;
            $result["error"] = false;
            break;
        case 'saveOption':
            if ($option!=="default")
            {
                $config->setOption($option, $value);
                $result["result"] = 1;
                $result["error"] = false;
            }
            else
            {
                $config->setDefault();
                $result["result"] = 1;
                $result["error"] = false;
            }

    }
}
echo json_encode($result, JSON_PRESERVE_ZERO_FRACTION);