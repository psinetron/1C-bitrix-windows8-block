<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//CJSCore::Init(array("jquery"));

//$this->IncludeComponentTemplate();
CModule::IncludeModule('iblock');
$arSelect = Array("*");
$arFilter = Array("IBLOCK_ID"=>4);


$res = CIBlockElement::GetList(Array("sort"=>"asc"), $arFilter, false, false, $arSelect);


if(1){ $this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));}
print '<div id="'.$this->GetEditAreaId($arFields['ID']).'" style="text-align:center">';
$i=0;
$summS=0;
$dopClass='';
while($ob = $res->GetNextElement()){
    $i++;
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();

    $arButtons = CIBlock::GetPanelButtons(
        $arFields["IBLOCK_ID"],
        $arFields["ID"],
        0,
        array("SECTION_BUTTONS"=>false, "SESSID"=>false)
    );
    $arFields["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
    $arFields["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

    $arButtons = CIBlock::GetPanelButtons(
        $arFields["IBLOCK_ID"],
        0,
        $arParams["PARENT_SECTION"],
        array("SECTION_BUTTONS"=>false)
    );



        $this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
    $this->AddEditAction($arFields['ID'], $arFields['EDIT_LINK'], CIBlock::GetArrayByID($arFields["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arFields['ID'], $arFields['DELETE_LINK'], CIBlock::GetArrayByID($arFields["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => "¬ы действительно хотите удалить блок?"));
    $pic1=CFile::GetFileArray($arProps['Backimage']['VALUE']);

    //print $arProps['Width']['VALUE'].' - ' . $arProps['Link']['VALUE']. '<br/>';
    $dopClass='';
    if ($summS==0){
        $dopClass='imageBlocksNonLeft';
    }

    $summS+=intval(substr($arProps['Width']['VALUE'],1));




    if ($summS==4){
        $dopClass='imageBlocksNonRight';
        $summS=0;
    } else if ($summS>4){
        $dopClass='imageBlocksNonLeft';
        $summS=0;
    }

    if ($arProps['Width']['VALUE']=='x4'){
        $dopClass='imageBlocksNonLeft imageBlocksNonRight';
    }



$dopFunc='';

if ($arProps['Link']['VALUE']!=''){
    $dopClass.= " imageBlocksCursor ";
    $dopFunc=' onclick="document.location.href = \''.$arProps['Link']['VALUE'].'\'" ';
}

    print '<div class="imageBlocks'.$arProps['Width']['VALUE']. ' ' . $dopClass .'" id="'.$this->GetEditAreaId($arFields['ID']).'" '.$dopFunc.'>';
    if ($arProps['Video']['VALUE']==''){
        print '<div class="imageBlocksInner" style="background:url(\''.$pic1['SRC'].'\') center"><div class="'. $arProps['TextAlign']['VALUE_XML_ID'].'">'. html_entity_decode($arProps['Text']['VALUE']['TEXT']). '</div></div>';
    } else {

      print '<div class="imageBlocksVideo">';
        $viWidth=0;
      switch ($arProps['Width']['VALUE']){
          case 'x1':$viWidth=221; break;
          case 'x2':$viWidth=468; break;
      }

$APPLICATION->IncludeComponent(
    "bitrix:player",
    "",
    Array(
        "PLAYER_TYPE" => "auto",
        "USE_PLAYLIST" => "N",
        "PATH" => $arProps['Video']['VALUE'],
        "WIDTH" => $viWidth,
        "HEIGHT" => "320",
        "FULLSCREEN" => "Y",
        "SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
        "SKIN" => "bitrix.swf",
        "CONTROLBAR" => "none",
        "WMODE" => "transparent",
        "HIDE_MENU" => "Y",
        "SHOW_CONTROLS" => "N",
        "SHOW_STOP" => "N",
        "SHOW_DIGITS" => "Y",
        "CONTROLS_BGCOLOR" => "FFFFFF",
        "CONTROLS_COLOR" => "000000",
        "CONTROLS_OVER_COLOR" => "000000",
        "SCREEN_COLOR" => "000000",
        "AUTOSTART" => "N",
        "REPEAT" => "N",
        "VOLUME" => "90",
        "DISPLAY_CLICK" => "play",
        "MUTE" => "N",
        "HIGH_QUALITY" => "Y",
        "ADVANCED_MODE_SETTINGS" => "N",
        "BUFFER_LENGTH" => "1",
        "DOWNLOAD_LINK_TARGET" => "_self"

    )
);
        print '</div>';
    }

    print '
    </div>';



    $innerI++;
}
print '</div>
<div class="ClearBoth"></div>

';







?>
