BX.namespace('BX.Aspro.SelectOfferProp');

(() => {
  BX.Aspro.SelectOfferProp = function (e) {
    const _this = $(this);
    const obSelect = {};
    const objUrl = parseUrlQuery();
    const selectMode = !!_this.hasClass("list_values_wrapper");
    const container = _this.closest(".bx_catalog_item_scu");
    const img = _this.closest(".item-parent").find(".thumb img");
    const bDetail = _this.closest(".item_main_info").length;
    const depthCount = _this.closest(".item_wrapper").siblings().length;
    const item = bDetail ? container.closest(".item_main_info") : container.closest(".item");
    const offersTreeDocFragment = item.find(".offers-template-json:first").prop("content");

    const offersTree = $(offersTreeDocFragment).find("div").data("json");

    const artName = _this.closest(".item-parent").find(".article_block").data("name");
    const artVal = _this.closest(".item-parent").find(".article_block").data("value");
    const itemPicture = img.data("src") ? img.data("src") : img.attr("src");

    const obParams = {
      PARAMS: bDetail
        ? _this.closest(".item_main_info").find(".js-sku-config").first().data("params")
        : _this.closest(".catalog_block, .display_list").find(".js-sku-config").first().data("params"),
      ID: container.data("id"),
      PROPERTY_ID: container.data("propertyid"),
      SECTION_ID: container.data("section_id"),
      DEPTH: _this.closest(".item_wrapper").index(),
      VALUE: selectMode ? _this.find("option:selected").data("onevalue") : _this.data("onevalue"),
      PICTURE: itemPicture ? itemPicture : "",
      ARTICLE_NAME: artName ? artName : "",
      ARTICLE_VALUE: artVal ? artVal : "",
    };

    if (objUrl.clear_cache !== undefined && objUrl.clear_cache === "Y") {
      obParams["CLEAR_CACHE"] = "Y";
    }

    if (!obParams.PARAMS) {
      return;
    }

    // set active
    $(".bx_catalog_item_scu").removeClass("js-selected");
    container.addClass("js-selected");

    /* save selected values */
    for (let i = 0; i < depthCount + 1; i++) {
      const strName = "PROP_" + container.find(".item_wrapper:eq(" + i + ") > div").data("id");

      if (container.find(".item_wrapper:eq(" + i + ") select").length) {
        obSelect[strName] = container.find(".item_wrapper:eq(" + i + ") select option[selected]").data("onevalue");
      } else {
        obSelect[strName] = container.find(".item_wrapper:eq(" + i + ") li.active").data("onevalue");
      }
    }

    if (!selectMode) {
      _this.siblings().removeClass("active");
      _this.addClass("active");
    }

    BX.Aspro.Sku.init({
      selectedValues: obSelect,
      strPropValue: obParams["VALUE"],
      depth: obParams["DEPTH"],
      isDetail: !!bDetail,
    });

    /* get sku */
    if (!offersTree || typeofExt(offersTree) !== "array") return;

    const prevOfferIndex = BX.Aspro.Sku.GetCurrentOfferIndex(offersTree);
    const prevOfferId = offersTree[prevOfferIndex] ? offersTree[prevOfferIndex]["ID"] : null;

    BX.Aspro.Sku.UpdateSKUInfoByProps(offersTree, item);

    const currentOfferIndex = BX.Aspro.Sku.GetCurrentOfferIndex(offersTree);

    if (currentOfferIndex < 0) return;

    if (prevOfferIndex === currentOfferIndex || String(prevOfferId) === String(offersTree[currentOfferIndex]["ID"])) return;

    obParams["SELECTED_OFFER_ID"] = offersTree[currentOfferIndex]["ID"];

    if (
      BX.Aspro.Sku.obOffers[obParams["SELECTED_OFFER_ID"]] &&
      item.length &&
      BX.Aspro.Sku.obOffers[obParams["SELECTED_OFFER_ID"]]["item"] &&
      BX.Aspro.Sku.obOffers[obParams["SELECTED_OFFER_ID"]]["item"][0] === item[0]
    ) {
      applyOffer(obParams["SELECTED_OFFER_ID"], item);
    } else {
      if (BX.Aspro.Sku.xhr[obParams["ID"]]) {
        BX.Aspro.Sku.xhr[obParams["ID"]].abort();
      }

      BX.ajax
        .runAction("aspro:next.SelectOffer.select", {
          data: {
            params: {
              PARAMS: obParams.PARAMS,
              ID: obParams.ID,
              PROPERTY_ID: obParams.PROPERTY_ID,
              SECTION_ID: obParams.SECTION_ID,
              PICTURE: obParams.PICTURE,
              ARTICLE_NAME: obParams.ARTICLE_NAME,
              ARTICLE_VALUE: obParams.ARTICLE_VALUE,
              SELECTED_OFFER_ID: obParams.SELECTED_OFFER_ID,
              CLEAR_CACHE: obParams.CLEAR_CACHE,
            },
          },
          onrequeststart: function (xhr) {
            BX.Aspro.Sku.xhr[obParams["ID"]] = xhr;
          },
        })
        .then(
          (response) => {
            delete BX.Aspro.Sku.xhr[obParams["ID"]];

            if (!response || !response.data || !response.data.offer || !response.data.offer.ID) {
              return;
            }

            BX.Aspro.Sku.obOffers[obParams.SELECTED_OFFER_ID] = response.data.offer;
            BX.Aspro.Sku.mainItemForOffers = response.data.mainItemForOffers;
            BX.Aspro.Sku.obOffers[obParams.SELECTED_OFFER_ID]["item"] = item;
            applyOffer(obParams.SELECTED_OFFER_ID, item);
          },
          (error) => {
            delete BX.Aspro.Sku.xhr[obParams["ID"]];
            console.error(error);
          }
        );
    }

    function finalActionSKUInfo(currentOffer, wrapper) {
      if ($(".counter_wrapp.list").length) {
        $(".counter_wrapp.list .counter_block.big").removeClass("big");
      }

      const eventdata = {
        offer: currentOffer,
        wrapper: wrapper,
      };
      BX.onCustomEvent("onFinalActionSKUInfo", [eventdata]);
    }

    function applyOffer(offerId, item) {
      BX.Aspro.Sku.ChangeInfo(item, offerId);
      finalActionSKUInfo(BX.Aspro.Sku.obOffers[offerId], item);
    }
  };

  $(document).on("click", ".bx_catalog_item_scu li.item, .bx_catalog_item_scu li[data-onevalue]", BX.Aspro.SelectOfferProp);
  $(document).on("change", ".bx_catalog_item_scu select.list_values_wrapper", BX.Aspro.SelectOfferProp);
})();
