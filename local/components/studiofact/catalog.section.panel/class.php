<?php

class CatalogSectionPanel extends CBitrixComponent
{
	/**
	 * Основная функция компонента
	 */
	protected function run()
	{
		global $sort_by, $order_by;

		$this->arResult['order_by'] = "desc";
		$this->arResult['order_by_new'] = "asc";
		if ($_REQUEST["order_by"] == "asc") {
			$this->arResult['order_by'] = "asc";
			$this->arResult['order_by_new'] = "desc";
		}
		if ($_REQUEST["sort_by"] == "CATALOG_PRICE_1") {
			$this->arResult['sort_by'] = "property_MINIMUM_PRICE";
			$this->arResult['sort_by_price'] = true;
		} else if ($_REQUEST["sort_by"] == "show_counter") {
			$this->arResult['sort_by'] = "show_counter";
			$this->arResult['sort_by_counter'] = true;
		} else {
			$this->arResult['sort_by'] = "created";
			$this->arResult['sort_by_created'] = true;
		}

		$sort_by = $this->arResult['sort_by'];
		$order_by = $this->arResult['order_by'];

		if ($this->StartResultCache()) {
			$this->includeComponentTemplate();
		}
	}


	/**
	 * Запуск компонента
	 */
	public function executeComponent()
	{
		$this->run();
	}


	/**
	 * Подключение языкового файла компонента
	 */
	public function onIncludeComponentLang()
	{
		$this->includeComponentLang('class.php');
	}
}