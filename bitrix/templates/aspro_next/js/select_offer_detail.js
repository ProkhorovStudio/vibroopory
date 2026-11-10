(function (window) {
  const detailRoot = ".item_main_info";

  function getOfferQuantities() {
    return BX.Aspro.Sku.getOfferQuantities();
  }

  function setNeutralLikeState($icons) {
    $icons.removeClass("added");
    $icons.find(".value:not(.added)").show();
    $icons.find(".value.added").hide();
  }

  function rememberQuantity($counter) {
    const id = $counter.attr("data-item");
    const value = $counter.find("input.text").val();

    const quantity = parseInt(value, 10);

    if (id && !isNaN(quantity) && quantity > 0) {
      getOfferQuantities()[id] = quantity;
    }
  }

  $(document).on("input", ".offer_buy_block .counter_block input.text", function () {
    rememberQuantity($(this).closest(".counter_block"));
  });

  $(document).on("click", ".offer_buy_block .counter_block .plus, .offer_buy_block .counter_block .minus", function () {
    const $counter = $(this).closest(".counter_block");

    setTimeout(function () {
      rememberQuantity($counter);
    }, 0);
  });

  function getMainItem() {
    var sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;

    return (sku && sku.mainItemForOffers) || {};
  }

  function isEmptyGallery(obj) {
    return !obj.GALLERY || !obj.GALLERY.length;
  }

  function noPhoto() {
    return getMainItem().NO_PHOTO || "";
  }

  function applyPicture($offersImg, picture) {
    const srcBig = picture.big;

    if (!srcBig) return;

    const srcSmall = picture.small || srcBig;

    const $link = $offersImg.find("a").first();
    const $img = $offersImg.find("img").first();

    $offersImg.find("link[itemprop=image]").attr("href", srcBig);

    $img.attr("src", srcSmall);
    $img.attr("title", picture.title || "");
    $img.attr("alt", picture.alt || "");
    $img.attr("data-src", srcSmall);

    switch (arNextOptions["THEME"]["DETAIL_PICTURE_MODE"]) {
      case "MAGNIFIER":
        $img.attr("data-large", srcBig).attr("xoriginal", srcBig).attr("xpreview", srcSmall);
        $img.addClass("zoom_picture");

        if (typeof InitZoomPict === "function") {
          InitZoomPict($img);
        }
        break;

      case "POPUP":
        $img.removeClass("zoom_picture");
        $link.attr("href", srcBig).addClass("popup_link").removeClass("line_link");

        if (!picture.hasGallery) {
          $link.removeClass("popup_link");
        }
        break;

      default:
        $img.removeClass("zoom_picture");
        $link.attr("href", "javascript:void(0)").addClass("line_link").removeClass("popup_link");
        break;
    }

    $link.attr("title", picture.title || "");
    $link.attr("alt", picture.alt || "");

  }

  function SetMainPictureSKU(wrapper, obj) {
    const $offersImg = wrapper.find(".offers_img");

    if (!$offersImg.length) return;

    const picture = {
      big: noPhoto(),
      small: "",
      title: obj.NAME || "",
      alt: obj.NAME || "",
      hasGallery: !isEmptyGallery(obj),
    };

    if (picture.hasGallery) {
      const first = obj.GALLERY[0];
      picture.big = first.BIG && first.BIG.src ? first.BIG.src : picture.big;
      picture.small = first.SMALL && first.SMALL.src ? first.SMALL.src : first.SRC || picture.big;
      picture.title = first.TITLE || picture.title;
      picture.alt = first.ALT || picture.alt;
    }

    applyPicture($offersImg, picture);

    $offersImg.stop(true, true).css("opacity", 1);
  }

  function SetCounterSKU(wrapper, obj) {
    const $counter = wrapper.find(".offer_buy_block .counter_wrapp .counter_block").first();

    if (!$counter.length) return;

    const config = obj.CONFIG || {};
    const inBasket = window.arBasketAspro && window.arBasketAspro["BASKET"] && window.arBasketAspro["BASKET"][obj.ID] !== undefined;
    const showCounter =
      config.ACTION === "ADD" &&
      obj.CAN_BUY &&
      config.OPTIONS &&
      config.OPTIONS.USE_PRODUCT_QUANTITY_DETAIL &&
      !inBasket;

    if (showCounter) {
      const sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;
      const counterHtml = sku.buildCounterHtml(obj, null, {
        minus: $counter.find(".minus").attr("id") || "",
        input: $counter.find("input.text").attr("id") || "",
        plus: $counter.find(".plus").attr("id") || "",
      });
      const inputValue = sku.getOfferQuantityValue(obj);

      $counter
        .attr("data-item", obj.ID)
        .html(counterHtml)
        .show();

      $counter.find("input.text").data("product", "offersTree");

      sku.getOfferQuantities()[obj.ID] = inputValue;

      wrapper.find(".offer_buy_block .to-cart, .offer_buy_block .one_click").attr("data-quantity", inputValue);

      BX.onCustomEvent("onCounterProductAction", [
        { type: "change", params: { id: $counter.find("input.text"), value: inputValue } },
      ]);
    } else {
      $counter.hide();
      wrapper.find(".total_summ").slideUp(50);
    }
  }

  function SetBasketBtnSKU(wrapper, obj) {
    const $buttonBlock = wrapper.find(".offer_buy_block .button_block").first();

    if (!$buttonBlock.length || !obj.HTML) return;

    const config = obj.CONFIG || {};
    const isWide =
      config.ACTION === "ORDER" ||
      !obj.CAN_BUY ||
      (config.ACTION === "SUBSCRIBE" && obj.CATALOG_SUBSCRIBE === "Y");
    const inBasket =
      window.arBasketAspro && window.arBasketAspro["BASKET"] && window.arBasketAspro["BASKET"][obj.ID] !== undefined;

    if (inBasket) {
      $buttonBlock.addClass("wide").html(obj.HTML);

      if (typeof markProductAddBasket === "function") {
        markProductAddBasket(obj.ID);
      }
    } else if (isWide) {
      $buttonBlock.addClass("wide").html(obj.HTML);
    } else {
      $buttonBlock.removeClass("wide").html(obj.HTML);
    }
  }

  function SetFixedHeaderSKU(wrapper) {
    if (typeof window.setNewHeader !== "function") return;
    if (wrapper && $(wrapper).closest(".fast_view_frame").length) return;

    window.setNewHeader(wrapper);
  }

  function SetThumbsSKU(wrapper, obj) {
    const $container = wrapper.find(".wrapp_thumbs");

    if (!$container.length) return;

    const $thumbs = $container.find(".thumbs");
    const $video = $container.find(".popup_video").not(".mobile");
    const slider = isEmptyGallery(obj) ? [] : obj.GALLERY;
    const hasMore = slider.length > 1;

    const $slider = wrapper.find(".item_slider").not(".flex");

    $slider.filter(".has_more, .has_one").toggleClass("has_more", hasMore).toggleClass("has_one", !hasMore);
    $video.toggleClass("fromtop", slider.length > 5);

    const $oldList = $thumbs.find(".slides_block").first();

    if ($oldList.length && $oldList.data("bxSlider")) {
      $oldList.data("bxSlider").destroySlider();
    }

    if ($thumbs.length && $.hasData($thumbs[0])) {
      $.removeData($thumbs[0]);
    }

    let slideHtml = '<ul class="slides_block" id="thumbs">';

    for (let index = 0; index < slider.length; index++) {
      const slide = slider[index];

      if (!slide || typeof slide !== "object") continue;

      const bigImg = slide.BIG ? slide.BIG.src : "";
      const smallImg = slide.SMALL ? slide.SMALL.src : "";
      const thumbImg = slide.THUMB ? slide.THUMB.src : "";

      slideHtml +=
        '<li ' +
        (index === 0 ? 'class="current"' : "") +
        ' data-slide_key="' +
        index +
        '" data-big_img="' +
        BX.Aspro.Utils.escapeHtml(bigImg) +
        '" data-small_img="' +
        BX.Aspro.Utils.escapeHtml(smallImg) +
        '">' +
        '<span><img class="xzoom-gallery" width="50" data-xpreview="' +
        BX.Aspro.Utils.escapeHtml(thumbImg) +
        '" src="' +
        BX.Aspro.Utils.escapeHtml(thumbImg) +
        '" alt="' +
        BX.Aspro.Utils.escapeHtml(slide.ALT || obj.NAME) +
        '" title="' +
        BX.Aspro.Utils.escapeHtml(slide.TITLE || obj.NAME) +
        '" /></span>' +
        "</li>";
    }

    slideHtml += "</ul>";

    if (!$thumbs.length || !hasMore) {
      $thumbs.html(slideHtml).hide();
      $slider.find(".slides .flex-direction-nav").remove();

      if (!$video.length) {
        $container.hide();
      }

      return;
    }

    $container.show();
    $thumbs.show();

    const bBxSlider = $thumbs.hasClass("bxSlider");

    const count = slider.length;

    if (bBxSlider) {
      const navId = "thumbs_nav_" + Date.now();

      $thumbs.addClass("bxSlider");
      $thumbs.removeClass("flexslider flexslider-init flexslider-control-nav flexslider-direction-nav");
      $thumbs.css("max-width", "");
      $thumbs.html(
        '<div class="inner_slider">' +
          slideHtml +
          "</div>" +
          '<span class="thumbs_navigation bx-controls-direction" id="' +
          navId +
          '"><span class="slide-prev"></span><span class="slide-next"></span></span>'
      );

      $thumbs.find(".slides_block").bxSlider({
        mode: "vertical",
        infiniteLoop: count > 5,
        minSlides: 5,
        maxSlides: 5,
        slideMargin: 10,
        pager: false,
        adaptiveHeight: false,
        touchEnabled: false,
        responsive: false,
        nextSelector: "#" + navId + " .slide-next",
        prevSelector: "#" + navId + " .slide-prev",
        oneToOneTouch: false,
        moveSlides: count > 5 ? 1 : 0,
        preventDefaultSwipeY: true,
        onSliderLoad: function () {
          if (count > 5) {
            $(this).closest(".bx-viewport").addClass("long");
            $(this).closest(".bxSlider").find(".bx-controls-direction a").addClass("opacityv");
          }

          $thumbs.find('li[data-slide_key="0"]').addClass("flex-active-slide");
        },
      });

      const $slides = wrapper.find(".item_slider.has_more .slides");

      $slides.find(".flex-direction-nav.ext").remove();
      $slides.append(
        '<ul class="flex-direction-nav ext"><li class="flex-nav-prev"><span class="flex-prev">Previous</span></li><li class="flex-nav-next"><span class="flex-next">Next</span></li></ul>'
      );

      return;
    }

    const options = $thumbs.attr("data-plugin-options") || $container.attr("data-plugin-options");

    $thumbs.addClass("flexslider");

    if (options) {
      $thumbs.attr("data-plugin-options", options);
    }

    $thumbs.removeClass("flexslider-init flexslider-control-nav flexslider-direction-nav");
    $thumbs.find(".flex-control-nav, .flex-direction-nav, .flex-viewport").remove();
    $thumbs.html(slideHtml + '<span class="thumbs_navigation custom_flex"></span>');

    const visibleThumbs = wrapper.closest(".fast_view_frame").length ? 3 : 4;
    $thumbs.css("max-width", Math.ceil((count <= visibleThumbs ? count : visibleThumbs) * 64 - 10) + "px");

    if (typeof InitFlexSlider === "function") {
      InitFlexSlider();
    }
  }

  function SetAdditionalGallerySKU(wrapper, obj) {
    if (wrapper.closest(".fast_view_frame").length) {
      return;
    }

    const $gallery = $(".galerys-block");

    if (!$gallery.length) return;

    const galleryItems = (obj.ADDITIONAL_GALLERY || []).concat(getMainItem().ADDITIONAL_GALLERY || []);
    const bTypeBig = !!$gallery.find(".big_slider").length;
    const $slider = bTypeBig ? $gallery.find(".big_slider") : $gallery.find(".small_slider");

    if (!$slider.length) return;

    if ($.hasData($slider[0])) {
      $.removeData($slider[0]);
      $slider.find(".flex-control-nav").remove();
      $slider.find(".flex-direction-nav").remove();
    }

    let slides = "";
    let ethumbs = "";

    for (let i = 0; i < galleryItems.length; i++) {
      const arPhoto = galleryItems[i];

      if (!arPhoto || !arPhoto.DETAIL || !arPhoto.PREVIEW || !arPhoto.THUMB) continue;

      if (bTypeBig) {
        slides +=
          '<li class="col-md-12 item"><a href="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.DETAIL.SRC) +
          '" class="fancy" rel="gallery" target="_blank" title="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.TITLE) +
          '"><img src="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.PREVIEW.src) +
          '" class="img-responsive inline" title="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.TITLE) +
          '" alt="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.ALT) +
          '" /><span class="zoom"></span></li>';
        ethumbs +=
          '<li class="item"><img class="img-responsive inline" border="0" src="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.THUMB.src) +
          '" title="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.TITLE) +
          '" alt="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.ALT) +
          '" /></li>';
      } else {
        slides +=
          '<li class="col-md-3 item visible"><div><img src="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.PREVIEW.src) +
          '" class="img-responsive inline" title="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.TITLE) +
          '" alt="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.ALT) +
          '" /></div><a href="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.DETAIL.SRC) +
          '" class="fancy dark_block_animate" rel="gallery" target="_blank" title="' +
          BX.Aspro.Utils.escapeHtml(arPhoto.TITLE) +
          '"></a></li>';
      }
    }

    $slider.find(".slides").html(slides);
    $slider.removeClass("flexslider-init");

    if (bTypeBig) {
      const $ethumbs = $gallery.find(".ethumbs");

      if ($ethumbs.length) {
        if ($.hasData($ethumbs[0])) {
          $.removeData($ethumbs[0]);
          $ethumbs.find(".flex-control-nav").remove();
          $ethumbs.find(".flex-direction-nav").remove();
        }
        $ethumbs.find(".slides").html(ethumbs);
        $ethumbs.removeClass("flexslider-init");
      }
    }

    if (galleryItems.length) {
      $gallery.removeClass("hidden");

      if (typeof InitFlexSlider === "function") {
        InitFlexSlider();
      }
    } else {
      $gallery.addClass("hidden");
    }
  }

  function SetFlexGallerySKU(wrapper, obj) {
    const $wrapper = wrapper.closest(detailRoot).find(".item_slider.flex.color-controls");

    if (!$wrapper.length) return;

    const pictures = obj.GALLERY || [];
    let slides = "";

    if (!pictures.length) {
      slides =
        '<li id="mphoto-0" class="current" data-value="">' +
        '<span data-fancybox-group="item_slider" class="fancy" title="' +
        BX.Aspro.Utils.escapeHtml(obj.NAME) +
        '">' +
        '<img border="0" src="' +
        BX.Aspro.Utils.escapeHtml(noPhoto()) +
        '" alt="' +
        BX.Aspro.Utils.escapeHtml(obj.NAME) +
        '" title="' +
        BX.Aspro.Utils.escapeHtml(obj.NAME) +
        '" />' +
        "</span>" +
        "</li>";
    } else {
      for (let i = 0; i < pictures.length; i++) {
        const picture = pictures[i];
        if (!picture || !picture.BIG || !picture.SMALL) continue;

        slides +=
          '<li id="mphoto-' + i + '" ' + (i === 0 ? 'class="current" ' : "") + 'data-value="' + BX.Aspro.Utils.escapeHtml(obj.ID) + "_" + BX.Aspro.Utils.escapeHtml(picture.ID) + '">' +
          '<a href="' +
          BX.Aspro.Utils.escapeHtml(picture.BIG.src) +
          '" data-fancybox-group="item_slider" class="fancy popup_link" title="' +
          BX.Aspro.Utils.escapeHtml(picture.TITLE) +
          '">' +
          '<img border="0" src="' +
          BX.Aspro.Utils.escapeHtml(picture.SMALL.src) +
          '" alt="' +
          BX.Aspro.Utils.escapeHtml(picture.ALT) +
          '" title="' +
          BX.Aspro.Utils.escapeHtml(picture.TITLE) +
          '" />' +
          '<div class="zoom"></div></a>' +
          "</li>";
      }
    }

    $wrapper.removeData("flexslider");
    $wrapper.removeClass("flexslider-init flexslider-control-nav flexslider-direction-nav");
    $wrapper.find(".flex-viewport, .flex-control-nav, .flex-direction-nav").remove();
    $wrapper.html('<ul class="slides">' + slides + "</ul>");

    if (typeof InitFlexSlider === "function") {
      InitFlexSlider();
    }
  }

  $(document).on("click", ".item_main_info .item_slider .thumbs .slides_block li", function () {
    const $itemSlider = $(this).closest(".item_slider");
    const $offersImg = $itemSlider.find(".offers_img");

    if (!$offersImg.length) return;

    const $li = $(this);
    const $img = $li.find("img");
    const srcBig = $li.attr("data-big_img") || "";
    const srcSmall = $li.attr("data-small_img") || $img.attr("src");

    SetCurrentThumb($li);

    applyPicture($offersImg, {
      big: srcBig,
      small: srcSmall,
      title: $img.attr("title") || "",
      alt: $img.attr("alt") || "",
      hasGallery: true,
    });
  });

  function SetArticleSKU(wrapper, obj) {
    const $block = wrapper.find(".item_block--article");
    const $inner = $block.find(".article");
    const $value = $inner.find(".value");

    if (!$block.length || !$inner.length || !$value.length) return;

    const articleText = obj.ARTICLE || "";

    if (articleText) {
      $inner.find(".block_title").text((obj.ARTICLE_NAME || "") + ":");
      $value.text(articleText);
      $inner.show();
    } else {
      $value.text("");
      $inner.hide();
    }
  }

  function SetDescriptionSKU(wrapper, obj) {
    const mainItem = getMainItem();

    if (!mainItem.SHOW_SKU_DESCRIPTION) return;

    const $detailText = wrapper.closest(".catalog_detail").find(".detail_text");
    const $previewText = wrapper.closest(".catalog_detail").find(".preview_text");

    if ($detailText.length) {
      if (obj.DETAIL_TEXT && obj.DETAIL_TEXT.length) {
        $detailText.html(obj.DETAIL_TEXT);
      } else if (mainItem.DETAIL_TEXT && mainItem.DETAIL_TEXT.length) {
        $detailText.html(mainItem.DETAIL_TEXT);
      }
    }

    if ($previewText.length) {
      if (obj.PREVIEW_TEXT && obj.PREVIEW_TEXT.length) {
        $previewText.html(obj.PREVIEW_TEXT);
      } else if (mainItem.PREVIEW_TEXT && mainItem.PREVIEW_TEXT.length) {
        $previewText.html(mainItem.PREVIEW_TEXT);
      }
    }
  }

  function SetDetailPropsSKU(wrapper, obj) {
    if (typeof obj.DETAIL_PROPS_HTML !== "string" || !obj.DETAIL_PROPS_HTML.length) {
      return;
    }

    const $detailProps = wrapper.closest(".catalog_detail").find(".js-detail-props").first();

    if ($detailProps.length) {
      $detailProps.replaceWith(obj.DETAIL_PROPS_HTML);
    }
  }

  function SetCheaperFormSKU(wrapper, obj) {
    const $cheaperForm = wrapper.find(".cheaper_form span");

    if (!$cheaperForm.length) return;

    $cheaperForm.data("autoload-product_name", obj.NAME);
    $cheaperForm.data("autoload-product_id", obj.ID);
  }

  function SetPropsTitleSKU(wrapper) {
    wrapper.find(".bx_item_detail_scu .item.active > i[title]").each(function () {
      const title = $(this).attr("title");
      const separatorIndex = title.indexOf(":");
      const value = title.substring(separatorIndex + 1).trim();

      if (separatorIndex === -1 || !value) {
        return;
      }

      $(this)
        .closest(".bx_item_detail_scu")
        .find(".bx_item_section_name > span")
        .first()
        .text(title.substring(0, separatorIndex) + ": ")
        .append($('<span class="val"></span>').text(value));
    });
  }

  window.SetPropsTitleSKU = SetPropsTitleSKU;

  function SetViewedSKU(obj) {
    if (typeof setViewedProduct !== "function") return;

    const pictureId = obj.PREVIEW_PICTURE_FIELD && obj.PREVIEW_PICTURE_FIELD.ID
      ? obj.PREVIEW_PICTURE_FIELD.ID
      : obj.GALLERY && obj.GALLERY.length
      ? obj.GALLERY[0].ID
      : false;

    setViewedProduct(obj.ID, {
      PRODUCT_ID: getMainItem().PRODUCT_ID,
      IBLOCK_ID: obj.IBLOCK_ID,
      NAME: obj.NAME,
      DETAIL_PAGE_URL: obj.URL,
      PICTURE_ID: pictureId,
      CATALOG_MEASURE_NAME: obj.MEASURE,
      MIN_PRICE: obj.MIN_PRICE,
      CAN_BUY: obj.CONFIG && obj.CONFIG.CAN_BUY ? "Y" : "N",
      IS_OFFER: "Y",
      WITH_OFFERS: "N",
    });
  }

  function SetLocationSKU(wrapper, obj) {
    const mainItem = getMainItem();

    if (!mainItem.OID || typeof setLocationSKU !== "function") return;
    if (wrapper.closest(".fast_view_frame").length) return;

    setLocationSKU(obj.ID, mainItem.OID);
  }

  function SetDetailLinkSKU(wrapper, obj) {
    if (!obj.URL || !wrapper.closest(".fast_view_frame").length) return;

    const detailPath = obj.URL.split("?")[0];

    wrapper.find("a[href]").each(function () {
      const href = $(this).attr("href");

      if (typeof href === "string" && href.indexOf(detailPath) === 0) {
        $(this).attr("href", obj.URL);
      }
    });
  }

  function SetPopupTitleSKU(wrapper, obj) {
    if (!obj.URL || !wrapper.closest(".fast_view_frame").length) return;

    const $titleLink = $(".fast_view_frame .form_head h2 a");

    if (!$titleLink.length) return;

    $titleLink.attr("href", obj.URL);

    if (arNextOptions["THEME"]["CHANGE_TITLE_ITEM_DETAIL"] !== "Y") return;

    const skuName = obj.IPROPERTY_VALUES && obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE ? obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE : obj.NAME;

    $titleLink.html(BX.Aspro.Utils.escapeHtml(skuName));
  }

  function SetPageTitleSKU(wrapper, obj) {
    if (arNextOptions["THEME"]["CHANGE_TITLE_ITEM_DETAIL"] !== "Y") return;
    if (wrapper.closest(".fast_view_frame").length) return;

    const mainItem = getMainItem();
    const postfix = mainItem.POSTFIX || "";
    const skuName = obj.IPROPERTY_VALUES && obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE ? obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE : obj.NAME;
    const skuWindowTitle = obj.IPROPERTY_VALUES && obj.IPROPERTY_VALUES.ELEMENT_META_TITLE ? obj.IPROPERTY_VALUES.ELEMENT_META_TITLE : obj.NAME;

    $("h1").html(BX.Aspro.Utils.escapeHtml(skuName));
    document.title = skuWindowTitle + postfix;

    window.ItemObj = window.ItemObj || {};
    ItemObj.TITLE = skuName;
    ItemObj.WINDOW_TITLE = skuWindowTitle + postfix;

    $(".catalog_detail input[data-sid=PRODUCT_NAME]").val(BX.Aspro.Utils.escapeHtml(skuName));
  }

  function SetLikeSKU(wrapper, obj) {
    const $likeIcons = wrapper.find(".like_icons");

    if (!$likeIcons.length) return;

    $likeIcons.find(".wish_item[data-item], .wish_item_button[data-item]").attr("data-item", obj.ID);
    $likeIcons.find(".compare_item[data-item]").attr("data-item", obj.ID);

    $likeIcons.find(".wish_item[data-item]").css("display", obj.CAN_BUY ? "" : "none");

    setNeutralLikeState($likeIcons.find(".wish_item[data-item], .compare_item[data-item]"));

    if (typeof setStatusButton === "function") {
      setStatusButton();
    }
  }

  function SetPriceSKU(wrapper, obj) {
    const $cost = wrapper.find(".cost.prices");

    if (!$cost.length) return;

    const action = obj.CONFIG ? obj.CONFIG.ACTION : "";

    if (action === "NOTHING") {
      ClearPriceSKU($cost);
      return;
    }

    $cost.children(".price, .js_price_wrapper").show();

    if (obj.PRICE_MATRIX && obj.PRICE_MATRIX_HTML) {
      const $stub = $cost.find(".with_matrix").first();
      const $wrapper = $cost.find(".js_price_wrapper").first();

      if ($stub.length || $wrapper.length) {
        const $target = $wrapper.length ? $wrapper : $cost.append('<div class="js_price_wrapper"></div>').find(".js_price_wrapper");

        $target.html((obj.PRICE_RANGE_HTML || '') + obj.PRICE_MATRIX_HTML);

        const $keep = $target.add($stub);
        $cost.children().not($keep).remove();

        $stub.hide();
        $cost.find(".price_matrix_wrapper").show();
        SetRangeTopPrice($target, obj);

        return;
      }
    }

    if (obj.PRICES_HTML) {
      const $withMatrix = $cost.find(".with_matrix").first();

      $cost.html(obj.PRICES_HTML);

      if ($withMatrix.length && !$cost.find(".with_matrix").length) {
        $cost.prepend($withMatrix);
      }

      const $mainInfo = wrapper.closest(".item_main_info");
      const strMainId = $mainInfo.length ? $mainInfo.attr("id") : "";
      if (strMainId) $cost.children(".price").first().attr("id", strMainId + "_price");

      return;
    }

    const price = obj.MIN_PRICE;

    if (!price || price.DISCOUNT_VALUE === undefined) {
      ClearPriceSKU($cost);
      return;
    }

    const $wrap = $cost.find(".price_matrix_wrapper").first();
    const $price = $wrap.find(".price").first();

    if (!$price.length) return;

    $price.attr("data-currency", price.CURRENCY).attr("data-value", price.DISCOUNT_VALUE);
    $price.find(".values_wrapper").first().html(getPriceHTML(price.DISCOUNT_VALUE, price.CURRENCY, price.PRINT_DISCOUNT_VALUE));

    if (obj.SHOW_MEASURE === "Y") {
      const measure = obj.MEASURE || "";

      if (measure) {
        $price.find(".price_measure").remove();
        $price.find(".values_wrapper").first().after('<span class="price_measure">/' + measure + "</span>");
      } else {
        $price.find(".price_measure").remove();
      }
    }

    const $discount = $wrap.find(".discount").first();
    const $sale = $wrap.find(".sale_block").first();

    if ($discount.length) {
      if (obj.SHOW_OLD_PRICE && parseFloat(price.PRICE) > parseFloat(price.DISCOUNT_VALUE)) {
        $discount.html(getPriceHTML(price.PRICE, price.CURRENCY, price.PRINT_PRICE)).show();
      } else {
        $discount.html("").hide();
      }
    }

    if ($sale.length) {
      const percent =
        parseFloat(price.DISCOUNT_VALUE) > 0
          ? Math.round(((parseFloat(price.PRICE) - parseFloat(price.DISCOUNT_VALUE)) * 100) / parseFloat(price.PRICE))
          : 0;

      if (obj.SHOW_DISCOUNT_PERCENT && percent > 0) {
        const saleText = obj.SHOW_DISCOUNT_PERCENT_NUMBER === "Y" ? percent + "%" : getPriceHTML(price.DISCOUNT_DIFF, price.CURRENCY, price.PRINT_DISCOUNT_DIFF);

        $sale.find(".values_wrapper").last().html(saleText);
        $sale.show();
      } else {
        $sale.hide();
      }
    }

    $cost.find(".with_matrix").hide();
    $wrap.show();
  }

  function ClearPriceSKU($cost) {
    const $stub = $cost.children(".with_matrix");

    $cost.children().not($stub).remove();
    $stub.hide();
  }

  function SetRangeTopPrice($wrapper, obj) {
    const sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;

    if (!sku || typeof sku.setRangeTopPrice !== "function") return;

    const quantity = getOfferQuantities()[obj.ID] ?? ((obj.CONFIG && obj.CONFIG.MIN_QUANTITY_BUY) || 1);
    const selected =
      typeof sku.checkPriceRange === "function"
        ? sku.checkPriceRange(quantity, obj)
        : "";

    sku.setRangeTopPrice($wrapper, obj, selected);
  }

  function getPriceHTML(price, currency, printPrice) {
    return typeof getCurrentPrice === "function" ? getCurrentPrice(price, currency, printPrice || "") : printPrice || "";
  }

  $(document).on("click", ".item_main_info .offers_img a.popup_link", function (e) {
    e.preventDefault();

    const $link = $(this);
    const $root = $link.closest(".item_main_info");
    let arPict = [];
    let index = 0;

    $root.find(".wrapp_thumbs .thumbs .slides_block > li:not(.bx-clone)").each(function () {
      const $li = $(this);
      const big = $li.attr("data-big_img");
      const $img = $li.find("img");

      if (!big) return;

      arPict.push({ title: $img.attr("title") || "", href: big });

      if ($li.hasClass("current")) index = arPict.length - 1;
    });

    if (!arPict.length) {
      arPict.push({ title: $link.attr("title") || "", href: $link.attr("href") });
    }

    if (typeof $.fancybox !== "function") return;

    $.fancybox(arPict, {
      index: index,
      openEffect: "fade",
      closeEffect: "fade",
      nextEffect: "fade",
      prevEffect: "fade",
      type: "image",
    });
  });

  if (typeof window.revealOffersImg !== "function") {
    window.revealOffersImg = function (root) {
      $(root || document).find(".item_main_info .offers_img").stop(true, true).css("opacity", 1);
    };
  }

  $(function () {
    window.revealOffersImg();
    SetPropsTitleSKU($(detailRoot));
  });

  BX.addCustomEvent("onCounterProductAction", function (eventdata) {
    if (!eventdata || !eventdata.params || !eventdata.params.id || !eventdata.params.id.length) return;

    const $wrapper = eventdata.params.id.closest(detailRoot);

    if (!$wrapper.length) return;

    if (!window.BX || !BX.Aspro || !BX.Aspro.Sku) return;

    setTimeout(function () {
      BX.Aspro.Sku.handleCounterAction(eventdata, $wrapper);
    }, 0);
  });

  BX.addCustomEvent("onFinalActionSKUInfo", function (eventdata) {
    if (!eventdata) return;

    const wrapper = eventdata.wrapper;
    const objOffer = eventdata.offer;

    if (!wrapper || !objOffer || !wrapper.closest(detailRoot).length) return;

    SetLocationSKU(wrapper, objOffer);
    SetDetailLinkSKU(wrapper, objOffer);
    SetPopupTitleSKU(wrapper, objOffer);
    SetPageTitleSKU(wrapper, objOffer);
    SetPriceSKU(wrapper, objOffer);
    SetLikeSKU(wrapper, objOffer);
    SetBasketBtnSKU(wrapper, objOffer);
    SetCounterSKU(wrapper, objOffer);
    SetMainPictureSKU(wrapper, objOffer);
    SetThumbsSKU(wrapper, objOffer);
    SetAdditionalGallerySKU(wrapper, objOffer);
    SetFlexGallerySKU(wrapper, objOffer);
    SetArticleSKU(wrapper, objOffer);
    SetDescriptionSKU(wrapper, objOffer);
    SetDetailPropsSKU(wrapper, objOffer);
    SetCheaperFormSKU(wrapper, objOffer);
    SetPropsTitleSKU(wrapper);
    SetViewedSKU(objOffer);
    SetFixedHeaderSKU(wrapper);

    const sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;
    const priceAction = objOffer.CONFIG ? objOffer.CONFIG.ACTION : "";

    if (priceAction !== "ORDER" && priceAction !== "NOTHING" && objOffer.PRICE_MATRIX && arNextOptions["THEME"]["SHOW_TOTAL_SUMM"] === "Y" && sku && typeof sku.setPriceAction === "function") {
      sku.setPriceAction(objOffer, "Y", "", wrapper);
    }

    if (typeof obNextPredictions === "object") {
      obNextPredictions.showAll();
    }
  });

  $(() => {
    const nodeMainInfo = document.querySelector(".item_main_info");
    if (!nodeMainInfo) {
      return;
    }

    const fillFixedHeader = function () {
      SetFixedHeaderSKU(nodeMainInfo);
    };

    if (typeof window.setNewHeader === "function") {
      fillFixedHeader();
    } else {
      BX.addCustomEvent("onHeaderFixedSkuReady", fillFixedHeader);
    }
  });
})(window);
