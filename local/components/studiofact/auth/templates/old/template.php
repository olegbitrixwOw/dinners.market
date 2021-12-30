<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin("");  ?>
<ul><? global $USER;
if ($USER->IsAuthorized()) { 
	$arUser = getUserData($USER->GetID());
	// pr($arUser['TYPE']);
	?>
	<?if($arUser['TYPE'] == 'manager'):?>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/organization/" class="auth" title="<?=GetMessage("STUDIOFACT_BALANCE");?>">
				Баланс компании: <?if(!empty($arUser['BALANCE'])):?><?=$arUser['BALANCE']?><?else:?>0<?endif;?></a>
		</li>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>">
				<?=$USER->GetLogin();?> (менеджер)</a>
		</li>
	<?elseif($arUser['TYPE'] == 'admin'):?>
		<li class="inline">
			<a href="<?=SITE_DIR;?>firm/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>">
				<?=$USER->GetLogin();?> (администратор)</a>
		</li>
	<?else:?>
		<li class="inline personal"><a href="<?=SITE_DIR;?>personal/" class="auth" title="<?=GetMessage("STUDIOFACT_PERSONAL");?>"><?=$USER->GetLogin();?></a></li>
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