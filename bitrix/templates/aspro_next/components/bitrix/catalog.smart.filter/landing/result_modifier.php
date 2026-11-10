<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// register the real control names and the values they accept, so that neither
// ?<FILTER_NAME>_xxxxx=1 nor <control>=<anything> reaches the cache key through
// CURRENT_BASE_PAGE
\Aspro\Next\CacheableUrl::setAllowedNamesFromFilterResult($arResult, (string)($arParams['FILTER_NAME'] ?? ''));

// clamp out-of-range values of the range controls in the iblock filter itself:
// they reach the cache key of bitrix:catalog.section through the global filter
\Aspro\Next\SmartFilter::normalizeGlobalFilter($arResult, (string)($arParams['FILTER_NAME'] ?? ''));
