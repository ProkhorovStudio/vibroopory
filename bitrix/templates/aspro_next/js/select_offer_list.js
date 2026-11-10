BX.namespace('BX.Aspro.SelectOfferList');

(() => {
  BX.addCustomEvent("onCounterProductAction", function (eventdata) {
    const $input = eventdata && eventdata.params && eventdata.params.id;
    if (!$input || !$input.length) return;

    if ($input.closest(".item_main_info").length) {
      return;
    }

    const $wrapper = $input.closest(".catalog_item_wrapp, .list_item_wrapp");
    if (!$wrapper.length || !$wrapper.find(".bx_catalog_item_scu").length) {
      return;
    }

    const sku = window.BX && window.BX.Aspro && window.BX.Aspro.Sku;
    if (!sku || typeof sku.handleCounterAction !== "function") return;

    sku.handleCounterAction(eventdata, $wrapper);
  });
})();
