						<?CNext::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isBlog):?>
									</div> <?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
									<div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?CNext::get_banners_position('SIDE', 'Y');?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "sect", "AREA_FILE_SUFFIX" => "sidebar", "AREA_FILE_RECURSIVE" => "Y"), false);?>
										</div>
									</div>
								</div><?endif;?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CNext::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CNext::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(!$isHideLeftBlock && !$isBlog):?>
					<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
						</div> <?// .right_block?>				
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<div class="left_block">
								<?CNext::ShowPageType('left_block');?>
							</div>
						<?endif;?>
					<?endif;?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>				
				<?endif;?>
			</div> <?// #content?>
			<?CNext::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>
		<footer id="footer">

			<?if($APPLICATION->GetProperty("viewed_show") == "Y" || $is404):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include", 
					"basket", 
					array(
						"COMPONENT_TEMPLATE" => "basket",
						"PATH" => SITE_DIR."include/footer/comp_viewed.php",
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "",
						"AREA_FILE_RECURSIVE" => "Y",
						"EDIT_TEMPLATE" => "standard.php",
						"PRICE_CODE" => array(
							0 => "BASE",
						),
						"STORES" => array(
							0 => "",
							1 => "",
						),
						"BIG_DATA_RCM_TYPE" => "bestsell"
					),
					false
				);?>					
			<?endif;?>
			<?CNext::ShowPageType('footer');?>
		</footer>
		<div class="bx_areas">
			<?CNext::ShowPageType('bottom_counter');?>
		</div>
		<?CNext::ShowPageType('search_title_component');?>
		<?CNext::setFooterTitle();
		CNext::showFooterBasket();?>

<div class="cookie-modal">
    <span class="close"></span>
    <div>
        <p>Мы используем файлы cookie для повышения удобства пользования Сайтом. Если вы не согласны с этим, отключите использование cookie в настройках браузера. Продолжая пользоваться Сайтом, вы соглашаетесь <a href="/politika-cookie/" target="_blank">с использованием cookie</a></p>
        <button>Согласен</button>
    </div>
</div>

<!-- виджет Б24 -->
    <script>
        (function(w,d,u){
            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b13048440/crm/site_button/loader_2_1ww5ek.js');
    </script>
<!-- виджет Б24 -->
<?php
//поддержка тега robots
$page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
    . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(stripos($page,"/filter/")===false){
    if(stripos($page,"PAGEN_")===false){
        global $META_ROBOTS;
        if(isset($META_ROBOTS) && trim($META_ROBOTS) != "") {//Установлено свойство элемента/раздела инфоблока
            $APPLICATION->AddHeadString('<meta name="robots" content="' . $META_ROBOTS . '"/>');
            header("X-Robots-Tag: " . $META_ROBOTS);
        }
        else {
            $props = $APPLICATION->GetPageProperty("META_ROBOTS");
            if (isset($props) && trim($props) != "") {//Установлено свойство страницы
                $APPLICATION->AddHeadString('<meta name="robots" content="' . $props . '"/>');
                header("X-Robots-Tag: " . $props);
            } else {
                $props = $APPLICATION->GetDirPropertyList();
                if (isset($props["META_ROBOTS"]) && trim($props["META_ROBOTS"]) != "") {//Установлено свойство раздела
                    $APPLICATION->AddHeadString('<meta name="robots" content="' . $props["META_ROBOTS"] . '"/>');
                    header("X-Robots-Tag: " . $props["META_ROBOTS"]);
                } else {
                    $APPLICATION->AddHeadString('<meta name="robots" content="index, follow"/>');
                    header("X-Robots-Tag: index, follow");
                }
            }
        }
    }
    else {
        $APPLICATION->AddHeadString('<meta name="robots" content="noindex, follow"/>');
        header("X-Robots-Tag: noindex, follow");
    }
}
else {
    $APPLICATION->AddHeadString('<meta name="robots" content="noindex, nofollow"/>');
    header("X-Robots-Tag: noindex, nofollow");
}
?>


	</body>
</html>