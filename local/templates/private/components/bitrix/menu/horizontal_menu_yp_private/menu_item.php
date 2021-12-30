<?php
/** @noinspection PhpUndefinedVariableInspection */

/*
 * array (
  'TEXT' => 'Пицца',
  'LINK' => '/catalog/pizza/',
  'SELECTED' => false,
  'PERMISSION' => 'X',
  'ADDITIONAL_LINKS' =>
  array (
    0 => '/catalog/pizza/',
  ),
  'ITEM_TYPE' => 'D',
  'ITEM_INDEX' => 0,
  'PARAMS' =>
  array (
    'FROM_IBLOCK' => true,
    'IS_PARENT' => true,
    'DEPTH_LEVEL' => '1',
  ),
  'CHAIN' =>
  array (
    0 => 'Пицца',
  ),
  'DEPTH_LEVEL' => 1,
  'IS_PARENT' => true,
)

дочерний

array (
  'TEXT' => 'Американская пицца',
  'LINK' => '/catalog/american_pizza/',
  'SELECTED' => false,
  'PERMISSION' => 'X',
  'ADDITIONAL_LINKS' =>
  array (
    0 => '/catalog/american_pizza/',
  ),
  'ITEM_TYPE' => 'D',
  'ITEM_INDEX' => 1,
  'PARAMS' =>
  array (
    'FROM_IBLOCK' => true,
    'IS_PARENT' => false,
    'DEPTH_LEVEL' => '2',
  ),
  'CHAIN' =>
  array (
    0 => 'Американская пицца',
  ),
  'DEPTH_LEVEL' => 2,
  'IS_PARENT' => false,
)



 * */

$resolve_icon=function($arItem)
{
	$link=$arItem['LINK'];

	if ($link=='/catalog/pizza/')	return 'icon-pizza';
	if ($link=='/catalog/rolls/')	return 'icon-sushi';
	if ($link=='/catalog/deserty/')	return 'icon-dessert';
	if ($link=='/catalog/drinks/')	return 'icon-drink';
	if ($link=='/catalog/soups/')	return 'icon-soup';
	if ($link=='/catalog/hot_dishes/')	return 'icon-pasta';
	if ($link=='/catalog/salads/')	return 'icon-salad';

	return 'icon-sushi';
};

/** @noinspection PhpUndefinedVariableInspection */
$depth_level=$arItem['DEPTH_LEVEL'];

$link=$arItem['LINK'];

// передаём день недели
$get=http_build_query($_GET);
if ($get)
{
	$link .= '?' .$get;
}

if ($depth_level==1)
{
	if (!empty($prev_depth))
	{
		if ($prev_depth==2)
		{
			?>
		</ul>
	</li>
			<?php

		}
	}

	$li_class='';
	$a_class='';
	$extra='';
	$extra2='';
	if ($arItem['IS_PARENT'])
    {
		$li_class='dropdown';
		$a_class='dropdown-toggle';
		$extra='data-toggle="dropdown"';
		$extra2='aria-haspopup="true" aria-expanded="false"';
    }

	if ($arItem['SELECTED'])
    {
		$li_class.=' active';
    }
	?>
	<li class="<?=$li_class?> ">
    <a href="<?=$link?>" class="<?=$a_class?> a_no_underline" <?=$extra?> role="button"
							 <?=$extra2?>
    >
        <span
				class="icon <?=$resolve_icon($arItem)?>"></span> <span
				class="item"><span class="item-name"><?=$arItem['TEXT']?></span></span><span
				class="caret-icon"></span>
    </a>
	<?
}
else if ($depth_level==2)
{
	if ($prev_depth == 1)
	{
		?>
			<ul class="dropdown-menu">
		<?
	}
	?>
				<li><a class="a_no_underline" href="<?=$link?>"><?=$arItem['TEXT']?></a></li>
	<?
}

$prev_depth=$depth_level;
