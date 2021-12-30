<?php
namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Request;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PriceMaths;

use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Main\UserTable; 

use Bitrix\Sale\Internals\UserBudgetPool;
use Bitrix\Main\Loader;



Loc::loadMessages(__FILE__);


class CompanyBudgetPool
{
    protected static $userBudgetPool = array();

    protected $items = array();

    const BUDGET_TYPE_ORDER_CANCEL_PART = 'ORDER_CANCEL_PART'; //
    const BUDGET_TYPE_ORDER_UNPAY = 'ORDER_UNPAY'; //
    const BUDGET_TYPE_ORDER_PART_RETURN = 'ORDER_PART_RETURN'; //
    const BUDGET_TYPE_OUT_CHARGE_OFF = 'OUT_CHARGE_OFF'; //
    const BUDGET_TYPE_EXCESS_SUM_PAID = 'EXCESS_SUM_PAID'; //
    const BUDGET_TYPE_MANUAL = 'MANUAL'; //
    const BUDGET_TYPE_ORDER_PAY = 'ORDER_PAY'; //
    const BUDGET_TYPE_ORDER_PAY_PART = 'ORDER_PAY_PART'; //

    public function __construct()
    {


    }

    /**
     * @param $sum
     * @param $type
     * @param Sale\Order $order
     * @param Sale\Payment $payment
     */
    public function add($sum, $type, Sale\Order $order, Sale\Payment $payment = null)
    {
        $fields = array(
            "SUM" => $sum,
            "CURRENCY" => $order->getCurrency(),
            "TYPE" => $type,
            "ORDER" => $order,
        );

        if ($payment !== null) 
            $fields['PAYMENT'] = $payment;

        $this->items[] = $fields;
        $arFields = [0=>$sum];
        // setLog($arFields);
    }

    /**
     * @return array
     */
    public function get()
    {
        if (isset($this->items))
            return $this->items;

        return false;
    }

    /**
     * @param $index
     * @return bool
     */
    public function delete($index)
    {
        if (isset($this->items) && isset($this->items[$index]))
        {
            unset($this->items[$index]);
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return UserBudgetPool
     */
    public static function getUserBudgetPool($key)
    {
        if (!isset(static::$userBudgetPool[$key]))
            static::$userBudgetPool[$key] = new static();

        return static::$userBudgetPool[$key];
    }

    /**
     * @param Sale\Order $order
     * @param $value
     * @param $type
     * @param Sale\Payment $payment
     */
    public static function addPoolItem(Sale\Order $order, $value, $type, Sale\Payment $payment = null)
    {
        if (floatval($value) == 0)
            return;

        $key = $order->getUserId();
        $pool = static::getUserBudgetPool($key);
        $pool->add($value, $type, $order, $payment);
    }

    /**
     * @param $userId
     * @return Sale\Result
     */
    public static function onUserBudgetSave($userId)
    {   
        $result = new Sale\Result();

        $pool = static::getUserBudgetPool($userId);
        foreach ($pool->get() as $key => $budgetDat)
        {

            $orderId = null;
            $paymentId = null;

            if (isset($budgetDat['ORDER'])
                && ($budgetDat['ORDER'] instanceof Sale\OrderBase))
            {
                $orderId = $budgetDat['ORDER']->getId();
            }

            if (isset($budgetDat['PAYMENT'])
                && ($budgetDat['PAYMENT'] instanceof Sale\Payment))
            {
                $paymentId = $budgetDat['PAYMENT']->getId();
            }

//          if ($budgetDat['TYPE'] == Internals\UserBudgetPool::BUDGET_TYPE_ORDER_PAY_PART
//              || $budgetDat['TYPE'] == Internals\UserBudgetPool::BUDGET_TYPE_ORDER_PAY)
//          {
//              if (!\CSaleUserAccount::Pay($userId, ($budgetDat['SUM'] * -1), $budgetDat['CURRENCY'], $orderId, false, $paymentId))
//              {
//                  $result->addError( new ResultError(Loc::getMessage("SALE_PROVIDER_USER_BUDGET_".$budgetDat['TYPE']."_ERROR"), "SALE_PROVIDER_USER_BUDGET_".$budgetDat['TYPE']."_ERROR") );
//              }
//          }
//          else
//          {
            if (!\CSaleUserAccount::UpdateAccount($userId, $budgetDat['SUM'], $budgetDat['CURRENCY'], $budgetDat['TYPE'], $orderId, '', $paymentId))
            {
                $result->addError( new Sale\ResultError(Loc::getMessage("SALE_PROVIDER_USER_BUDGET_".$budgetDat['TYPE']."_ERROR"), "SALE_PROVIDER_USER_BUDGET_".$budgetDat['TYPE']."_ERROR") );
            }
//          }

            $pool->delete($key);
        }

        return $result;
    }

    

    /**
     * @param Sale\Order $order
     * @return int
     */
    public static function getUserBudgetTransForOrder(Sale\Order $order)
    {
        $ignoreTypes = array(
            static::BUDGET_TYPE_ORDER_PAY
        );
        $sumTrans = 0;

        if ($order->getId() > 0)
        {
            $resTrans = \CSaleUserTransact::GetList(
                array("TRANSACT_DATE" => "DESC"),
                array(
                    "ORDER_ID" => $order->getId(),
                ),
                false,
                false,
                array("AMOUNT", "CURRENCY", "DEBIT")
            );
            while ($transactDat = $resTrans->Fetch())
            {
                if ($transactDat['DEBIT'] == "Y")
                {
                    $sumTrans += $transactDat['AMOUNT'];
                }
                else
                {
                    $sumTrans -= $transactDat['AMOUNT'];
                }
            }
        }

        if ($userBudgetPool = static::getUserBudgetPool($order->getUserId()))
        {
            foreach ($userBudgetPool->get() as $userBudgetDat)
            {
                if (in_array($userBudgetDat['TYPE'], $ignoreTypes))
                    continue;

                $sumTrans += $userBudgetDat['SUM'];
            }
        }

        return $sumTrans;
    }

    /**
     * @param Sale\Order $order
     * @return int
     */
    public static function getUserBudgetByOrder(Sale\Order $order)
    {
        $budget = static::getUserBudget($order->getUserId(), $order->getCurrency());
        if ($userBudgetPool = static::getUserBudgetPool($order->getUserId()))
        {
            foreach ($userBudgetPool->get() as $userBudgetDat)
            {
                $budget += $userBudgetDat['SUM'];
            }
        }

        return $budget;
    }

    /**
     * @param $userId
     * @param $currency
     * @return float|null
     */
    public static function getUserBudget($userId, $currency)
    {
        $budget = null;
        if ($userAccount = \CSaleUserAccount::GetByUserId($userId, $currency))
        {
            if ($userAccount['LOCKED'] != 'Y')
                $budget = floatval($userAccount['CURRENT_BUDGET']);
        }

        return $budget;
    }

    public static function updateAccount($sum, $companyBudget){

        // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 

        $balance =  $companyBudget['UF_SUM_RUB'] - $sum;
        $data = array("UF_SUM_RUB"=>$balance);
        $result = $entity_data_class::update($companyBudget['ID'], $data);

        //setLog('updateAccount');
    }

    public static function getCompanyBudget($userBillingAccount){

        // получаем аккаунт через userId
        $hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass(); 

        $rsData = $entity_data_class::getList(array(
               "select" => array("*"),
               "order" => array("ID" => "ASC"),
               // "filter" => array("ID"=>$arUser['UF_BILLING_ACCOUNT'])  // Задаем параметры фильтра выборки
               "filter" => array("ID" => $userBillingAccount)
        ));
        $account = false;
        while($arData = $rsData->Fetch()){
            $account = $arData;
        }
       return $account;
    }
}





class customHandler extends PaySystem\BaseServiceHandler implements PaySystem\IRefund
{
    /**
     * @param Payment $payment
     * @param Request $request
     * @return PaySystem\ServiceResult
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function initiatePay(Payment $payment, Request $request = null)
    {
        $result = new PaySystem\ServiceResult();

        /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
        $paymentCollection = $payment->getCollection();

        if ($paymentCollection)
        {
            /** @var \Bitrix\Sale\Order $order */
            $order = $paymentCollection->getOrder();
            if ($order)
            {
                $res = $payment->setPaid('Y');
                if ($res->isSuccess())
                {
                    $res = $order->save();
                    if (!$res->isSuccess())
                    {
                        $result->addErrors($res->getErrors());
                    }
                }
                else
                {
                    $result->addErrors($res->getErrors());
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCurrencyList()
    {
        return array();
    }

    /** 
     * @param Payment $payment
     * @param int $refundableSum
     * @return PaySystem\ServiceResult
     */
    public function refund(Payment $payment, $refundableSum)
    {
        $result = new PaySystem\ServiceResult();

        /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
        $paymentCollection = $payment->getCollection();

        /** @var \Bitrix\Sale\Order $order */
        $order = $paymentCollection->getOrder();

        if ($this->isUserBudgetLock($order))
        {
            $result->addError(new EntityError(Loc::getMessage('ORDER_PSH_INNER_ERROR_USER_BUDGET_LOCK')));
            return $result;
        }
        // проверка
        /* !!! */
        $arUser = $this->getUserData($order->getUserId());
        if($arUser['TYPE'] != 'user'){ 
            CompanyBudgetPool::addPoolItem($order, $refundableSum, CompanyBudgetPool::BUDGET_TYPE_ORDER_UNPAY, $payment);
        }else{
            UserBudgetPool::addPoolItem($order, $refundableSum, UserBudgetPool::BUDGET_TYPE_ORDER_UNPAY, $payment);
            // setLog($refundableSum);
        }
        return $result;
    }
 
    /**
     * @param Payment $payment
     * @return PaySystem\ServiceResult
     */
    public function creditNoDemand(Payment $payment) 
    {

        $result = new PaySystem\ServiceResult();

        /** @var \Bitrix\Sale\PaymentCollection $collection */
        $collection = $payment->getCollection();

        /** @var \Bitrix\Sale\Order $order */
        $order = $collection->getOrder();

        if ($this->isUserBudgetLock($order))
        {
            $result->addError(new EntityError(Loc::getMessage('ORDER_PSH_INNER_ERROR_USER_BUDGET_LOCK')));
            return $result;
        }

        /* проверка */  
        /* !!! */
        $arUser = $this->getUserData($order->getUserId());
        
        // setLog($order->getUserId());
        // setLog($arUser['TYPE']);

        $budget = 0;
        $paymentSum = PriceMaths::roundPrecision($payment->getSum());
        if($arUser['TYPE'] != 'user'){  
            $companyBudget = CompanyBudgetPool::getCompanyBudget($arUser['UF_BILLING_ACCOUNT']);
            // setLog($companyBudget);

            $budget = $companyBudget['UF_SUM_RUB'];
            if($budget >= $paymentSum){
                CompanyBudgetPool::addPoolItem($order, ( $paymentSum * -1 ), CompanyBudgetPool::BUDGET_TYPE_ORDER_PAY, $payment);
                CompanyBudgetPool::updateAccount($paymentSum, $companyBudget);
            }
        }else{
            $budget = PriceMaths::roundPrecision(UserBudgetPool::getUserBudgetByOrder($order));
            if($budget >= $paymentSum){
                UserBudgetPool::addPoolItem($order, ( $paymentSum * -1 ), UserBudgetPool::BUDGET_TYPE_ORDER_PAY, $payment);
            }
        }
        if($budget < $paymentSum){
            $error = $result->addError(new EntityError(Loc::getMessage('ORDER_PSH_INNER_ERROR_INSUFFICIENT_MONEY')));
        }
        return $result;
    }

    /**
     * @param Payment $payment
     * @return PaySystem\ServiceResult
     */
    public function debitNoDemand(Payment $payment)
    {
        return $this->refund($payment, $payment->getSum());
    }

    /**
     * @param Order $order
     * @return bool
     */
    private function isUserBudgetLock(Order $order)
    {
        if ($userAccount = \CSaleUserAccount::GetByUserId($order->getUserId(), $order->getCurrency()))
             return $userAccount['LOCKED'] == 'Y';

        return false;
    }

    public function getUserData($userId){
        $result = \Bitrix\Main\UserTable::getList(array(
            'select' => array('ID',
              'LOGIN', 
              'EMAIL', 
              'UF_BILLING_ACCOUNT', 
              'UF_USER_TYPE', 
              'UF_GILD',
              'UF_LIMIT_FR', 
              'UF_LIMIT_TH', 
              'UF_LIMIT_WE', 
              'UF_LIMIT_TU', 
              'UF_LIMIT_MO',
              'UF_BALANCE_FR',
              'UF_BALANCE_TH',
              'UF_BALANCE_WE',
              'UF_BALANCE_TU',
              'UF_BALANCE_MO'), 
            'filter' => array('ID' => $userId)
        ));
        if($arUser = $result->fetch()){
            // setLog($arUser['UF_USER_TYPE']);
            switch ($arUser['UF_USER_TYPE']) {
                case '1':
                    $arUser['TYPE'] = 'user';
                    break;
                case '2':
                    $arUser['TYPE'] = 'employee';
                    break;
                case '3':
                    $arUser['TYPE'] = 'manager';
                    break;
                 case '4':
                    $arUser['TYPE'] = 'admin';
                    break;
                default:
                    $arUser['TYPE'] = 'user';
                    break;
            }
            return $arUser;
        }
        return false;
    }
}