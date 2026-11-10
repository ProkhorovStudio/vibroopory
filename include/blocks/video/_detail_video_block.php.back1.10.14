<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?//options from \Aspro\Functions\CAsproNext::showBlockHtml?>
<?
$arOptions = (array)$arConfig['PARAMS'];
if (!isset($arOptions['VIDEO']) || !$arOptions['VIDEO']) return;

$bOneVideo = count((array)$arOptions['VIDEO']) == 1;
?>
<div class="video_block">
    <div class="grid-list grid-list--gap-20 <?=$bOneVideo ? 'one-video-block' : 'grid-list--items-2-601';?>">
        <?foreach ($arOptions['VIDEO'] as $v => $value):?>
            <div class="grid-list__item item rounded-4">
                <?if (is_array($value)):?>
                    <?$videoMimeType = \Aspro\Functions\CAsproNext::getMimeContentType($_SERVER['DOCUMENT_ROOT'].$value['path']);?>
                    <div class="video_body video_from_file">
                        <video id="js-video_<?=$v;?>" 
                            width="<?=$bOneVideo ? $value['width'] : '540';?>" 
                            height="<?=$bOneVideo ? $value['height'] : '357';?>"  
                            class="video-js" 
                            controls="controls" 
                            preload="metadata" 
                            data-setup="{}"
                        >
                            <source src="<?=$value['path'];?>" type="<?=$videoMimeType;?>" />
                        </video>
                    </div>
                    <div class="title"><?=strlen($value["title"]) ? $value["title"] : '';?></div>
                <?elseif (strpos($value, 'iframe')):?>
                    <?if ($bOneVideo):?>
                        <?=$value?>
                    <?else:?>
                        <div class="video_body">
                            <?=str_replace(
                                'src=', 
                                'width="660" height="457" src=', 
                                str_replace(
                                    ['width', 'height'], 
                                    ['data-width', 'data-height'], 
                                    $value
                                )
                            );?>
                        </div>	
                    <?endif;?>
                <?else:?>
                    <?=$value;?>
                <?endif;?>
            </div>
        <?endforeach;?>
    </div>
</div>