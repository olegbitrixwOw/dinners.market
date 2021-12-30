<?php
// горизонтальное меню для food house
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1)
{
	return;
} ?>
<nav class="navbar navbar-default navbar-ovsinka affix-top" role="navigation">
	<div class="container">
		<!-- todo: main navigation on mobile -->
        <!-- main navigation on mobile -->
        <div class="navbar-header">
            <!-- button for open navigation -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
		<!-- main navigation -->
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav text-uppercase" id="nav_overflow">
<?php
$arItem=[
	'IS_PARENT' => false,
	'SELECTED' => false, // wip
	'LINK' => '/',
	'DEPTH_LEVEL' => 1,
	'TEXT' => 'Все блюда',

];
include('menu_item.php');
foreach ($arResult as $arItem)
{
	include('menu_item.php');
}
?>
			</ul>
		</div>
		<!-- /main navigation -->
	</div>
</nav>
