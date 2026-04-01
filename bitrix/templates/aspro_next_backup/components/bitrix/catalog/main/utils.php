<?php
function setMetaRobotsFromSection(int $iblockId, ?int $sectionId)
{
    global $META_ROBOTS;
    if($iblockId>0 && $sectionId!=null && $sectionId>0) {
        $sectionInfo = CIBlockSection::GetList(
            [],
            [
                "ID" => $sectionId,
                "IBLOCK_ID" => $iblockId
            ],
            false,
            ["ID", "IBLOCK_ID", "UF_META_ROBOTS", "IBLOCK_SECTION_ID"],
            false
        )->fetch();
        $META_ROBOTS = $sectionInfo["UF_META_ROBOTS"];
        if (!isset($META_ROBOTS) || trim($META_ROBOTS) == "")
            setMetaRobotsFromSection($iblockId, $sectionInfo["IBLOCK_SECTION_ID"]);
    }
}

function setMetaRobotsFromElement(int $iblockId, string $elementCode)
{
    global $META_ROBOTS;
    if($iblockId>0 && trim($elementCode)!="") {
        $elementInfo = CIBlockElement::GetList(
            [],
            [
                "=CODE" => $elementCode,
                "=IBLOCK_ID" => $iblockId
            ],
            false,
            false,
            ["ID", "IBLOCK_ID", "PROPERTY_META_ROBOTS","IBLOCK_SECTION_ID"]
        )->fetch();
       //echo "<pre>"; print_r($elementInfo); echo "</pre>";
        $META_ROBOTS = $elementInfo["PROPERTY_META_ROBOTS_VALUE"];
        if (!isset($META_ROBOTS) || trim($META_ROBOTS) == "")
            setMetaRobotsFromSection($iblockId, $elementInfo["IBLOCK_SECTION_ID"]);
    }
}