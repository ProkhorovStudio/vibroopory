<?

use CNext as Solution;

define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $arTheme, $APPLICATION;

$APPLICATION->ShowAjaxHead();

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$get = $request->getQueryList();

$arTheme = Solution::GetFrontParametrsValues(SITE_ID);
$urlback = $get['url'];

$template = strtolower($arTheme["REGIONALITY_VIEW"]);
if ($arTheme["REGIONALITY_SEARCH_ROW"] == "Y" && $template == "select") {
	$template = "popup_regions";
}
?>
<a href="#" class="close jqmClose"><i></i></a>
<div class="form">
	<div class="form_head">
		<h2><?=\Bitrix\Main\Localization\Loc::getMessage('CITY_CHOISE');?></h2>
	</div>

	<?$arUrl = $APPLICATION->IncludeComponent(
		"aspro:regionality.list.next",
		$template,
		array(
			"URL" => $urlback,
			"POPUP" => "Y",
		)
	);?>

	<script type="text/javascript">
	(function () {
		BX.loadScript("<?=SITE_TEMPLATE_PATH;?>/js/jquery-ui.js",autocompleteHandler);

		function autocompleteHandler() {
			if (arNextOptions["THEME"]["REGIONALITY_SEARCH_ROW"] != "Y") {
				$("#search")
					.autocomplete({
						minLength: 2,
						source: arRegions,
						appendTo: $(".autocomplete").parent(),
						select: function (event, ui) {
							$.removeCookie("current_region");
							$.cookie("current_region", ui.item.ID, {
								path: "/",
								domain: arNextOptions["SITE_ADDRESS"],
							});
							$("#search").val(ui.item.label);
							return false;
						},
					})
					.data("ui-autocomplete")._renderItem = function (ul, item) {
						const region = item.REGION ? " (" + item.REGION + ")" : "";
						return $("<li>")
							.append(
								"<a href='" +
									item.HREF +
									"' class='cityLink'>" +
									item.label +
									region +
									"</a>"
							)
							.appendTo(ul);
					};
			} 
			else {
				$("#search")
					.autocomplete({
						minLength: 2,
						source: function (request, response) {
							$.getJSON(
								arNextOptions["SITE_DIR"] + "ajax/city_select.php",
								{
									term: request.term,
									url: "<?=$urlback;?>",
								},
								response
							);
						},
						appendTo: $(".autocomplete").parent(),
						select: function (event, ui) {
							$.removeCookie("current_region");
							$.cookie("current_region", ui.item.ID, {
								path: "/",
								domain: arNextOptions["SITE_ADDRESS"],
							});
							$("#search").val(ui.item.label);
							return false;
						},
					})
					.data("ui-autocomplete")._renderItem = function (ul, item) {
						const region = item.REGION ? " (" + item.REGION + ")" : "";
						return $("<li>")
								.append(
									"<a href='" +
										item.HREF +
										"' class='cityLink'>" +
										item.label +
										region +
										"</a>"
								)
								.appendTo(ul);
					};
			}

			$(".h-search .wrapper .search_btn").on("click", function () {
				const block = $(this).closest(".wrapper").find("#search");
				if (block.length) {
					block.trigger("focus");
					block.data("ui-autocomplete").search(block.val());
				}
			});
		}

		const current_region_item = $(".cities .items_block .item.current");
		let current_region_obl = "";
		$(".cities .item:not(.current)").each(function () {
			if ($(this).data("id") == current_region_item.data("id")) {
				$(this).addClass("shown");
			}
		});
		if ($(".popup_regions .parent_block").length) {
			$(".popup_regions .parent_block").each(function () {
				const _this = $(this);
				let item = "";

				item = _this.find(
					".item[data-id=" + current_region_item.data("id") + "]"
				);
				if (item.length) {
					item.addClass("current");
					current_region_obl = item.parent();
					current_region_obl.addClass("current shown");
				}
			});
		}
		if ($(".popup_regions .block.regions").length) {
			$(".popup_regions .block.regions").each(function () {
				const _this = $(this);
				const obl_block = _this.find(".parent_block");
				let item = "";

				if (!obl_block.length) {
					if (current_region_obl) {
						_this
							.find(".item[data-id=" + current_region_obl.data("id") + "]")
							.addClass("current");
					} 
					else {
						item = _this.find(
							".item[data-id=" + current_region_item.data("id") + "]"
						);
						if (item.length) {
							item.addClass("current");
							current_region_obl = item.parent();
							current_region_obl.addClass("current shown");
						}
					}
				}
			});
			$(".popup_regions .block.regions .item").on("click", function () {
				const _this = $(this);
				const obl_block = _this.parent(".parent_block");

				_this.siblings().removeClass("current");
				_this.addClass("current");
				if (obl_block.length) {
					$(".cities .item").siblings().removeClass("current shown");
					$(".cities .item[data-id=" + _this.data("id") + "]").addClass(
						"current shown"
					);
				} 
				else {
					if ($(".popup_regions .parent_block").length) {
						var parent_block = $(
							".popup_regions .parent_block[data-id=" + _this.data("id") + "]"
						);
						$(".popup_regions .parent_block")
							.siblings()
							.removeClass("current shown");
						parent_block.addClass("current shown");

						if (parent_block.find(".item.current").length) {
							parent_block.find(".item.current").trigger("click");
						} 
						else {
							parent_block.find(".item:first-child").trigger("click");
						}
					} 
					else {
						$(".cities .item").siblings().removeClass("current shown");
						$(".cities .item[data-id=" + _this.data("id") + "]").addClass(
							"current shown"
						);
					}
				}
			});
		}
		$(".cities .item a").on("click", function (e) {
			e.preventDefault();

			const _this = $(this);

			$.removeCookie("current_region");
			$.cookie("current_region", _this.data("id"), {
				path: "/",
				domain: arNextOptions["SITE_ADDRESS"],
			});
			location.href = _this.attr("href");
		});
	})();
	</script>
</div>