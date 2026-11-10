(function (window) {
  function getCardScope() {
    return $(".item_main_info").not(".fast_view_frame .item_main_info");
  }

  function getLikeIconsHtml() {
    const $wrapper = getCardScope().find(".like_wrapper");
    if (!$wrapper.length) return "";

    const $copy = $wrapper.clone();
    $copy.find(".wish_item, .compare_item").each(function () {
      const $item = $(this);
      const added = $item.hasClass("added");
      $item.find("span.value").each(function () {
        const $value = $(this);
        let display = "";
        if (added) {
          display = $value.hasClass("added") ? "inline-block" : "none";
        }
        $value.css("display", display);
      });
    });

    return (
      '<div class="like_icons full"><div class="wrapp_stockers">' +
      $copy.html() +
      "</div></div>"
    );
  }

  window.setNewHeader = function (obOffer) {
    let ratingHtml = "", priceHtml = "", imgHtml = "", skuHtml = "", buttonHtml = "";

    if (arNextOptions["THEME"]["SHOW_HEADER_GOODS"] != "Y") return;

    if (typeof obOffer !== "undefined" && BX.message("SKU_HEADER_WOFFERS") === "Y") $("#headerfixed .logo-row").addClass("woffers");

    const $scope = getCardScope();

    if ($scope.find(".rating").length) {
      ratingHtml = '<div class="rating"><div class="iblock-vote small"><table class="table-no-border"><tbody><tr>';
      if ($scope.find(".rating table td").length) {
        $scope.find(".rating table td").each(function (index) {
          let index_title = index + 1;
          ratingHtml +=
            '<td><div class="star-active ' +
            ($(this).find(".star-active").hasClass("star-voted") ? "star-voted" : "star-empty") +
            '" title="' +
            index_title +
            '"></div></td>';
        });
      } else if ($scope.find(".rating .item-rating").length) {
        $scope.find(".rating .item-rating").each(function (index) {
          let index_title = index + 1;
          ratingHtml +=
            '<td><div class="star-active ' +
            ($(this).hasClass("filled") ? "star-voted" : "star-empty") +
            '" title="' +
            index_title +
            '"></div></td>';
        });
      }

      ratingHtml += "</tr></tbody></table></div></div>";
    }
    if ($scope.find('[itemprop="offers"]').length) {
      const $counter = $scope.find(".counter_wrapp .counter_block input").first();
      const quantity = parseInt($counter.val(), 10) || 1;
      const sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;

      const $toCart = $scope.find(".to-cart").first();
      const currency = $toCart.attr("data-currency") || "RUB";
      const measure = $scope.find(".price_measure").first().text().replace(/\s/g, " ").trim();

      let unitPrice = "";
      let rawPrice = sku && typeof sku.getPriceForQuantity === "function" ? sku.getPriceForQuantity($scope, quantity) : undefined;

      if (!rawPrice) {
        rawPrice = parseFloat($toCart.attr("data-value"));
      }

      if (rawPrice > 0 && window.BX.Currency) {
        unitPrice = (window.BX.Currency.currencyFormat(rawPrice, currency, true) || String(rawPrice)) + measure;
      } else if (rawPrice > 0) {
        unitPrice = String(rawPrice) + measure;
      }

      if (!unitPrice) {
        const $offerData = $scope.find(".js_price_wrapper[data-current-offer]").first();

        if ($offerData.length) {
          try {
            const offerData = JSON.parse($offerData.attr("data-current-offer"));
            const minPrice = (offerData.ITEM_PRICES || [])[0];

            if (minPrice && minPrice.PRINT_DISCOUNT_PRICE) {
              unitPrice = minPrice.PRINT_DISCOUNT_PRICE + (offerData.MEASURE && offerData.SHOW_MEASURE === "Y" ? " /" + offerData.MEASURE : "");
            }
          } catch (e) {}
        }
      }

      if (unitPrice) {
        priceHtml = '<div class="price"><span>' + unitPrice + "</span></div>";
      } else {
        if ($scope.find(".offers_img.wof").length || $scope.find(".prices_tab").length) {
          if ($scope.find(".prices_block .offers_price.min").length) {
            priceHtml = '<div class="price">' + $scope.find(".prices_block .offers_price.min").html() + "</div>";
          } else {
            const $valueBlock = $scope
              .find(".prices_block .with_matrix .price_value_block")
              .filter(function () {
                return $(this).find(".offers_price_wrapper").length === 0 && $(this).text().replace(/\s/g, "") !== "";
              })
              .first();

            if ($valueBlock.length) {
              priceHtml = '<div class="price">' + $valueBlock.html() + "</div>";
            } else {
              const $offersPrice = $scope
                .find(".prices_block .cost.prices .offers_price")
                .filter(function () {
                  return $(this).text().replace(/\s/g, "") !== "";
                })
                .first();

              if ($offersPrice.length) {
                priceHtml = '<div class="price">' + $offersPrice.html() + "</div>";
              } else {
                const $matrixWrapper = $scope.find(".prices_block .price_matrix_wrapper").first();

                if ($matrixWrapper.length) priceHtml = '<div class="price">' + $matrixWrapper.html() + "</div>";
              }
            }
          }
        } else {
          if ($scope.find(".prices_block .with_matrix").length)
            priceHtml = '<div class="with_matrix">' + $scope.find(".prices_block .with_matrix").html() + "</div>";
          else if ($scope.find(".prices_block .price_group.min").length) priceHtml = $scope.find(".prices_block .price_group.min").html();
          else if ($scope.find(".prices_block .price_matrix_wrapper").length)
            priceHtml = $scope.find(".prices_block .price_matrix_wrapper").html();
        }
      }
    }
    if ($scope.find(".offers_img.wof").length) {
      if ($scope.find(".item_slider .slides .popup_link").length) {
        imgHtml = $scope.find(".item_slider .slides .popup_link").html().replace("id", "data-id");
      } else {
        imgHtml = $scope.find(".item_slider .slides > .offers_img a:first").html().replace("id", "data-id");
      }
    } else if ($scope.find(".item_slider .slides > ul > li:first link").length) {
      imgHtml = '<img src="' + $scope.find(".item_slider .slides > ul > li:first link").attr("href") + '" />';
    } else {
      imgHtml = $scope.find(".item_slider .slides > ul > li:first a").html();
    }
    if ($scope.find(".prices_tab").length) {
      buttonHtml = '<span class="more btn btn-default btn-lg">' + BX.message("MORE_INFO_SKU") + "</span>";
    } else if ($scope.find(".middle_info .buy_block .button_block").length) {
      buttonHtml = $scope.find(".middle_info .buy_block .button_block").html();
    }
    if ($scope.find(".sku_props .bx_catalog_item_scu > div").length) {
      let skuHtmlTmp = "";
      $scope.find(".sku_props .bx_catalog_item_scu > div").each(function () {
        let _this = $(this),
          rowClass = _this.children("div").first().attr("class") || _this.attr("class") || "",
          li_block = _this.find("li.active"),
          select_block = _this.find("select");
        if (li_block.length) {
          skuHtmlTmp +=
            '<div class="bx_catalog_item_scu"><div class="bx_scu"><div class="' +
            rowClass +
            '"><ul><li class="active" title="' +
            (li_block.attr("title") || li_block.find("i").attr("title") || "") +
            '">' +
            li_block.html() +
            "</li></ul></div></div></div>";
        } else if (select_block.length) {
          if (select_block.find("option:selected").data("img_src") !== undefined) {
            skuHtmlTmp +=
              '<div class="bx_catalog_item_scu"><div class="bx_scu"><div class="bx_item_detail_scu"><ul><li class="active" title="' +
              select_block.val() +
              '"><span class="cnt1"><span class="cnt_item" style="background-image:url(' +
              select_block.find("option:selected").data("img_src") +
              ')"></span></span></li></ul></div></div></div>';
          } else {
            skuHtmlTmp +=
              '<div class="bx_catalog_item_scu"><div class="bx_scu"><div class="' +
              rowClass +
              '"><ul><li class="active"><span class="cnt">' +
              select_block.val() +
              "</span></li></ul></div></div></div>";
          }
        }
      });
      skuHtml = skuHtmlTmp.replace(/mCustomScrollbar/g, "");
    }

    $("#headerfixed .logo-row").html(
      '<div class="inner-table-block product_block table">' +
        '<div class="module_products_list">' +
        '<div class="item main_item_wrapper flexbox flexbox--row">' +
        '<div class="foto-cell"><div class="image_wrapper_block">' +
        imgHtml +
        "</div></div>" +
        '<div class="item-name-cell"><div class="title"><span>' +
        $("#pagetitle").text() +
        "</span></div>" +
        ratingHtml +
        ($scope.find(".middle_info .item-stock").length
          ? '<div class="item-stock">' + $scope.find(".middle_info .item-stock").html() + "</div>"
          : "") +
        "</div>" +
        '<div class="flexbox flexbox--row">' +
        '<div class="price-cell"><div class="cost prices">' +
        priceHtml +
        "</div></div>" +
        '<div class="item-sku">' +
        '<div class="but-cell flexbox flexbox--row sku_props">' +
        skuHtml +
        "</div>" +
        "</div>" +
        '<div class="but-cell"><div class="button_block">' +
        buttonHtml +
        "</div></div>" +
        getLikeIconsHtml() +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );
  };

  BX.onCustomEvent("onHeaderFixedSkuReady", []);

  $(document).ready(() => {
    if (arNextOptions["THEME"]["SHOW_HEADER_GOODS"] == "Y") {
      $("#headerfixed .logo-row").addClass("wproduct");
    }

    if (BX.message("TYPE_SKU") != "TYPE_1" || BX.message("HAS_SKU_PROPS") != "Y") {
      setNewHeader();
    }

    $(".logo-row .module_products_list .but-cell .more").click(function () {
      const offsetSelector = getCardScope().find(".middle_info .sku_props").length
        ? ".middle_info"
        : ".tabs_section";

      const offset = $(offsetSelector).offset().top;
      $("body, html").animate({ scrollTop: offset }, 500);
    });
  });

  $(document).ajaxSuccess(function (event, xhr, settings) {
    if (!settings || !settings.url) return;

    const url = settings.url;
    if (url.indexOf("getAjaxBasket.php") === -1 && url.indexOf("show_basket_fly.php") === -1) {
      return;
    }

    setNewHeader();
  });

  let counterRefreshTimer = null;

  BX.addCustomEvent("onCounterProductActionResize", function () {
    if (counterRefreshTimer) clearTimeout(counterRefreshTimer);

    counterRefreshTimer = setTimeout(function () {
      counterRefreshTimer = null;
      setNewHeader();
    }, 150);
  });

  function queueHeaderRefresh() {
    if (counterRefreshTimer) clearTimeout(counterRefreshTimer);

    counterRefreshTimer = setTimeout(function () {
      counterRefreshTimer = null;
      setNewHeader();
    }, 300);
  }

  document.addEventListener(
    "click",
    function (e) {
      if (!e.target.closest || !e.target.closest(".counter_block .plus, .counter_block .minus")) return;
      if (!e.target.closest(".item_main_info")) return;

      queueHeaderRefresh();
    },
    true
  );

  document.addEventListener(
    "input",
    function (e) {
      if (!e.target.closest || !e.target.closest(".counter_block input")) return;
      if (!e.target.closest(".item_main_info")) return;

      queueHeaderRefresh();
    },
    true
  );
})(window);
