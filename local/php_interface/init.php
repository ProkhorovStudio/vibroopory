<?php
AddEventHandler('form', 'onBeforeResultAdd', 'my_onBeforeResultAdd');
function my_onBeforeResultAdd($WEB_FORM_ID, &$arFields, &$arrVALUES)
{
    global $APPLICATION;
    $spam = false;
    
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();

    if(!$request->isAjaxRequest()){
        //$APPLICATION->ThrowException('Ошибка!');
        //return false;
    }
}

AddEventHandler('ipol.sdek', 'onCalculate', 'changeSDEKTerms');

function changeSDEKTerms(&$arResult, $profile, $arConfig, $arOrder){
    $arResult['TRANSIT'] = '';
	//$arConfig['DELIVERY_PRICE']['TITLE'] = "Стоимость доставки, от";
	$arConfig['DELIVERY_PRICE']['TITLE']['VALUE'] = "Стоимость доставки, от";
	return $arResult;
}

function getSiteUrl() {
    $protocol = $_SERVER['HTTPS'] != '' ? 'https://' : 'http://';
    return $protocol . $_SERVER['SERVER_NAME'];
}

//Подключаем модуль инфоблоков
if (\Bitrix\Main\Loader::includeModule('iblock'))
{
    //регистрируем обработчик события
    \Bitrix\Main\EventManager::getInstance()->addEventHandler(
        "iblock",
        "OnTemplateGetFunctionClass",
        array("FunctionMyLeft", "eventHandler")
    );
    //подключаем файл с определением класса FunctionBase
    //это пока требуется т.к. класс не описан в правилах автозагрузки
    include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/lib/template/functions/fabric.php");
    class FunctionMyLeft extends \Bitrix\Iblock\Template\Functions\FunctionBase
    {
        //Обработчик события на вход получает имя требуемой функции
        //парсер её нашел в строке SEO
        public static function eventHandler($event)
        {
            $parameters = $event->getParameters();
            $functionName = $parameters[0];
            if ($functionName === "my_price")
            {
                //обработчик должен вернуть SUCCESS и имя класса
                //который будет отвечать за вычисления
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS,
                    "\\FunctionMyLeft"
                );
            }
        }
        //собственно функция выполняющая "магию"
        public function calculate($parameters)
        {
            $result = $this->parametersToArray($parameters);

            $el = CIBlockElement::GetList(array(), array('CODE' => $parameters[0]), false, array('nTopCount' => 1), array('IBLOCK_ID','ELEMENT_ID','ID'))->Fetch();

           $dbPrice = CPrice::GetList(array(), array("PRODUCT_ID"=>$el["ID"]),false,false, array());

            while ($arPrice = $dbPrice->Fetch())
            {
                $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
                    $arPrice["ID"],
                    $GLOBALS['USER']->GetUserGroupArray(),
                    "N",
                    SITE_ID
                );
                $discountPrice = CCatalogProduct::CountPriceWithDiscount(
                    $arPrice["PRICE"],
                    $arPrice["CURRENCY"],
                    $arDiscounts
                );
                $arPrice["DISCOUNT_PRICE"] = $discountPrice;
            }

            //последний параметр - длина строки
            $str = str_replace("&nbsp;", " ", $parameters[0]);
            //а вот собственно left
            return number_format($discountPrice, 0, ',', ' ');
        }
    }
}

AddEventHandler("search", "BeforeIndex", "onBeforeIndexHandler");
function onBeforeIndexHandler($arFields) {
	if (!CModule::IncludeModule("iblock")) // подключаем модуль
		return $arFields;

	if ($arFields["MODULE_ID"] == "iblock") {

$db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
                                    $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
                                    $arFields["ITEM_ID"],          // ID индексируемого свойства
                                    array("sort" => "asc"),       // Сортировка (можно упустить)
                                    Array("CODE"=>"CML2_ARTICLE")); // CODE свойства (в данном случае артикул)
      if($ar_props = $db_props->Fetch())
         $arFields["TITLE"] .= " ".$ar_props["VALUE"];   // Добавим свойство в конец заголовка индексируемого элемента
         
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arFields["PARAM2"],$arFields["ITEM_ID"]);
		$IPROPERTY = $ipropValues->getValues();

		$arWords = explode(" ", $IPROPERTY["ELEMENT_PAGE_TITLE"]);
		$arWords += explode(" ", $arFields["BODY"]);

		$arWords2 = preg_split("/\s+|\(|\)|-|_/", $IPROPERTY["ELEMENT_PAGE_TITLE"]);
		$arWords2 += preg_split("/\s+|\(|\)|-|_/", $arFields["BODY"]);

		$arWords = array_merge($arWords, $arWords2);

		$arFields["BODY"] = '';

		$nameWithoutSpace = str_replace(" ", "", $arFields["TITLE"]);
		$arFields["BODY"] .= " " . $nameWithoutSpace;
		foreach ($arWords as $str) {
			if ($str) {
				$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_WORDS");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
				$arFilter = Array("IBLOCK_ID"=>IntVal(34), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_WORDS" => $str);
				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
				while($ob = $res->GetNextElement()){
					$arProps = $ob->GetProperties();
					$arFields["BODY"] .= " " . implode(' ', $arProps['WORDS']["VALUE"]);
				}

				// значения между цифрами
				$arWordsSize = preg_split("/(\d+)(\.+)?(\d+)(\.+)?/", $str);

				$wordX = $arWordsSize[1];
				if ($wordX) {
					$arFilter = Array("IBLOCK_ID"=>IntVal(34), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_WORDS" => $wordX);
					$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
					while($ob = $res->GetNextElement()){
						$arProps = $ob->GetProperties();

						foreach ($arProps['WORDS']["VALUE"] as $val) {
							$arFields["BODY"] .= " " . str_replace($wordX, $val, $str);
						}
					}
				}
			}
		}

//		if ($arFields["ITEM_ID"] == 621) {
//			echo "<pre>";
//			var_dump($arFields["ITEM_ID"], $arFields, $arWords);
//			echo "</pre>"; die();
//		}


	}
	return $arFields; // вернём изменения
}


//выводим пользовательское HTML поле в свойствах разделов
AddEventHandler('main', 'OnUserTypeBuildList', array('CUserTypeSectionsHtmlField', 'GetUserTypeDescription'), 5000);
class CUserTypeSectionsHtmlField {

    public static function GetUserTypeDescription() {
        return array(
            // уникальный идентификатор
            'USER_TYPE_ID' => 'sections_html_field',
            // имя класса, методы которого формируют поведение типа
            'CLASS_NAME' => 'CUserTypeSectionsHtmlField',
            // название для показа в списке типов пользовательских свойств
            'DESCRIPTION' => 'HTML/text',
            // базовый тип на котором будут основаны операции фильтра
            'BASE_TYPE' => 'string',
        );
    }

    public static function GetDBColumnType($arUserField) {
        switch (strtolower($GLOBALS['DB']->type)) {
            case 'mysql':
                return 'text';
                break;
        }
    }

    public static function GetSettingsHTML($arUserField = false, $arHtmlControl, $bVarsFromForm) {
        $result = '';

        return $result;
    }

    public static function CheckFields($arUserField, $value) {
        $aMsg = array();
        return $aMsg;
    }

    public static function GetEditFormHTML($arUserField, $arHtmlControl) {
        if ($arUserField["ENTITY_VALUE_ID"] < 1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"]) > 0)
            $arHtmlControl["VALUE"] = htmlspecialchars($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
        ob_start();
        CFileMan::AddHTMLEditorFrame($arHtmlControl["NAME"], $arHtmlControl["VALUE"], "html", "html", 200, "N", 0, "", "", "s1");
        $b = ob_get_clean();
        return $b;
    }

    public static function GetEditFormHTMLMulty($arUserField, $arHtmlControl) {
        $html = 'Поле не может быть множественным!';
        return $html;
    }

    public static function GetFilterHTML($arUserField, $arHtmlControl) {
        $sVal = intval($arHtmlControl['VALUE']);
        $sVal = $sVal > 0 ? $sVal : '';

        return CUserTypeSectionsHtmlField::GetEditFormHTML($arUserField, $arHtmlControl);
    }

    public static function GetAdminListViewHTML($arUserField, $arHtmlControl) {
        return '';
    }

    public static function GetAdminListViewHTMLMulty($arUserField, $arHtmlControl) {
        return '';
    }

    public static function GetAdminListEditHTML($arUserField, $arHtmlControl) {
        return '';
    }

    public static function GetAdminListEditHTMLMulty($arUserField, $arHtmlControl) {
        return '';
    }

    public static function onsearchIndex($arUserField) {
        return '';
    }

    public static function OnBeforeSave($arUserField, $value) {
        return $value;
    }
}


/**
 * Регистрация пользовательской функции для работы с шаблонами инфоблоков
 *
 * Функция добавляет возможность использования {=minpricesection} в шаблонах инфоблоков
 * для получения минимальной цены товаров в разделе и его подразделах
 */

// Проверяем наличие модуля инфоблоков
if (\Bitrix\Main\Loader::includeModule('iblock')) {

    /**
     * Регистрируем обработчик события для добавления пользовательской функции в шаблоны
     *
     * Событие OnTemplateGetFunctionClass вызывается при разборе шаблонных функций
     * Позволяет добавить собственные функции вида {=functionName}
     */
    \Bitrix\Main\EventManager::getInstance()->addEventHandler(
        "iblock",                    // Модуль инфоблоков
        "OnTemplateGetFunctionClass", // Событие получения класса функции
        ["FunctionMinPriceSection", "eventHandler"] // Класс и метод обработчика
    );

    // Подключаем файл с базовым классом для функций шаблонов
    include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/lib/template/functions/fabric.php");

    /**
     * Класс FunctionMinPriceSection
     *
     * Реализует пользовательскую функцию для получения минимальной цены товаров в разделе
     * Наследуется от базового класса функций шаблонов инфоблоков
     */
    class FunctionMinPriceSection extends \Bitrix\Iblock\Template\Functions\FunctionBase
    {
        /**
         * Обработчик события OnTemplateGetFunctionClass
         *
         * Определяет, какую функцию будет обрабатывать данный класс
         * Вызывается системой при разборе шаблонных функций
         *
         * @param object $event Объект события с параметрами
         * @return \Bitrix\Main\EventResult|null Результат обработки события
         */
        public static function eventHandler($event)
        {
            // Получаем параметры события
            $parameters = $event->getParameters();
            // Имя запрашиваемой функции (первый параметр)
            $functionName = $parameters[0];

            // Если запрашивается функция minpricesection
            if ($functionName === "minpricesection") {
                // Возвращаем успешный результат с именем класса для обработки
                return new \Bitrix\Main\EventResult(
                    \Bitrix\Main\EventResult::SUCCESS, // Код успеха
                    "\\FunctionMinPriceSection"         // Имя класса-обработчика
                );
            }
        }

        /**
         * Подготовка параметров функции
         *
         * Вызывается перед выполнением calculate() для обработки параметров функции
         *
         * @param \Bitrix\Iblock\Template\Entity\Base $entity Объект сущности (раздел/элемент)
         * @param array $parameters Массив параметров функции
         * @return array Обработанные аргументы для calculate()
         */
        public function onPrepareParameters(\Bitrix\Iblock\Template\Entity\Base $entity, array $parameters)
        {
            $arguments = [];

            // Сохраняем ID текущей сущности для использования в calculate()
            // Если параметр не передан, используется ID текущего элемента/раздела
            $this->data['id'] = $entity->getId();

            // Обрабатываем каждый параметр
            foreach ($parameters as $parameter) {
                $arguments[] = $parameter->process($entity);
            }

            return $arguments;
        }

        /**
         * Основная функция расчета минимальной цены раздела
         *
         * Выполняет поиск минимальной цены среди всех товаров раздела и его подразделов
         *
         * @param array $parameters Параметры функции:
         *                         - $parameters[0] (опционально) ID раздела
         *                         - если не указан, используется ID из data['id']
         * @return string|null Отформатированная минимальная цена или null
         */
        public function calculate($parameters)
        {
            // ID группы цен (1 - базовая группа)
            $priceGroup = '1';

            // Подключаем необходимые модули
            \Bitrix\Main\Loader::includeModule("catalog");
            \Bitrix\Main\Loader::includeModule('currency');

            // Определяем ID раздела: из параметра или из данных сущности
            $sectionID = (!empty(reset($parameters)) ? reset($parameters) : $this->data['id']);

            /**
             * Шаг 1: Получение информации о основном разделе
             * Получаем границы раздела для поиска всех подразделов
             */
            $section = \Bitrix\Iblock\SectionTable::getList([
                'filter' => ['ID' => $sectionID],
                'select' => ['LEFT_MARGIN', 'RIGHT_MARGIN', 'IBLOCK_ID', 'ID']
            ])->fetchRaw();

            /**
             * Шаг 2: Сбор всех подразделов
             * Используем Nested Sets (вложенные множества) для получения всех дочерних разделов
             */
            $subSections = \Bitrix\Iblock\SectionTable::getList([
                'filter' => [
                    '>=LEFT_MARGIN' => $section['LEFT_MARGIN'],  // Все разделы с левой границей >=
                    '<=RIGHT_MARGIN' => $section['RIGHT_MARGIN'], // и правой границей <=
                    '=IBLOCK_ID' => $section['IBLOCK_ID'],      // Того же инфоблока
                ],
                'select' => ['ID']
            ]);

            $arSectionsID = [];
            while ($section = $subSections->fetch()) {
                $arSectionsID[] = $section['ID'];
            }

            /**
             * Шаг 3: Получение всех элементов во всех подразделах
             * Используем запрос через Bitrix ORM
             */
            $elementSection = new \Bitrix\Main\Entity\Query('\Bitrix\Iblock\SectionElementTable');
            $elementSection->addSelect('IBLOCK_ELEMENT_ID')
                ->setFilter(['=IBLOCK_SECTION_ID' => $arSectionsID])
                ->registerRuntimeField(
                    'SECTION',
                    [
                        'data_type' => '\Bitrix\Iblock\SectionTable',
                        'reference' => [
                            '=this.IBLOCK_SECTION_ID' => 'ref.ID',
                        ],
                        'join_type' => 'inner'
                    ]
                );

            // Выполняем запрос к базе данных
            $resElementsID = \Bitrix\Main\Application::getConnection()->query($elementSection->getQuery());

            $arElementsID = [];
            while ($elementsID = $resElementsID->fetch()) {
                $arElementsID[] = $elementsID['IBLOCK_ELEMENT_ID'];
            }

            /**
             * Шаг 4: Поиск минимальной цены среди найденных элементов
             * Используем JOIN с таблицей цен и сортировку по возрастанию цены
             */
            $arItem = \Bitrix\Iblock\ElementTable::getList(
                [
                    'filter' => ['=ID' => $arElementsID],
                    'order' => ['PriceTable.PRICE_SCALE' => 'asc'], // Сортировка по цене (по возрастанию)
                    'select' => [
                        'PriceTable.PRICE_SCALE', // Цена, конвертированная в базовую валюту
                    ],
                    'limit' => 1, // Берем только первый (самый дешевый)
                    'runtime' => [
                        // Добавляем связь с таблицей цен
                        new \Bitrix\Main\Entity\ReferenceField(
                            'PriceTable',
                            \Bitrix\Catalog\PriceTable::class,
                            [
                                '=this.ID' => 'ref.PRODUCT_ID',           // Связь по ID товара
                                $priceGroup => 'ref.CATALOG_GROUP_ID'    // Фильтр по группе цен
                            ],
                            ['join_type' => 'RIGHT'] // RIGHT JOIN чтобы получить все элементы с ценами
                        )
                    ]
                ]
            )->fetchRaw();

            /**
             * Шаг 5: Форматирование результата
             * Если товар с ценой найден, форматируем цену в базовой валюте
             */
            $minPriceSection = null;
            if (!empty($arItem)) {
                /**
                 * Шаг 5: Форматирование цены с разделителем тысяч
                 *
                 * Преобразует 25202.00 в "25 202"
                 */

                // Извлекаем значение цены
                $priceKey = key($arItem);
                $minPriceValue = $arItem[$priceKey]; // 25202.00

                // Убеждаемся, что это число
                $numericValue = floatval($minPriceValue);

                // Округляем до целого (если нужно отбросить копейки)
                $integerValue = round($numericValue);

                // Форматируем с разделителем тысяч
                // number_format(число, знаков_после_запятой, разделитель_дробной, разделитель_тысяч)
                $formattedPrice = number_format($integerValue, 0, '', ' ');
                // Результат: "25 202"

                return $formattedPrice;
            }

            return $minPriceSection;
        }
    }
}
