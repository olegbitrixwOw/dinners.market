<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin("");  
// pr($arParams['USER']);?>
<ul>
	<li class="inline menu_logo_mobile">
		<a href="/">
		<img src="<?=SITE_DIR;?>include/logo/header_logo.png" title="Интернет-магазин доставки" alt="Интернет-магазин доставки" class="" /></a>
	</li>
<? // global $USER;
if ($arParams['USER']) { ?>
	<?if($arParams['USER']['TYPE'] == 'manager'):?>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/organization/" class="auth" title="<?=GetMessage("STUDIOFACT_BALANCE");?>">
				Баланс компании: <?if(!empty($arParams['USER']['BALANCE'])):?><?=$arParams['USER']['BALANCE']?><?else:?>0<?endif;?></a>
		</li>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>">
				<?=$arParams['USER']['LOGIN'];?> (менеджер)</a>
		</li>
	<?elseif($arParams['USER']['TYPE'] == 'admin'):?>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>">
				<?=$arParams['USER']['LOGIN'];?> (администратор)</a>
		</li>
	<?else:?>
		<li class="inline personal"><a href="<?=SITE_DIR;?>personal/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>">
			<?=$arParams['USER']['LOGIN'];?></a></li>
	<?endif;?>
	<li class="inline"><a href="<?=SITE_DIR;?>?logout=yes" title="<?=GetMessage("STUDIOFACT_EXIT");?>"><?=GetMessage("STUDIOFACT_EXIT");?></a></li>
<?
} else {
	?><li class="inline"><a href="<?=SITE_DIR;?>auth/" class="open_auth auth" title="<?=GetMessage("STUDIOFACT_AUTH");?>">
        <svg class="i-icon">
            <use xlink:href="#svg-icon-user"></use>
        </svg>
        <?=GetMessage("STUDIOFACT_AUTH");?>
    </a></li>
	<li class="inline"><a href="<?=SITE_DIR;?>auth/register.php" title="<?=GetMessage("STUDIOFACT_REGISTER");?>"><?=GetMessage("STUDIOFACT_REGISTER");?></a></li><?
} ?></ul> 