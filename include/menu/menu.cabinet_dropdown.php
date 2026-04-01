<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "cabinet_dropdown",
    Array(
        "COMPONENT_TEMPLATE" => "cabinet_dropdown",
        "MENU_CACHE_TIME" => "3600000",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => array(
        ),
        "DELAY" => "N",
        "MAX_LEVEL" => "4",
        "ALLOW_MULTI_SELECT" => "Y",
        "ROOT_MENU_TYPE" => "cabinet",
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
    )
);?>