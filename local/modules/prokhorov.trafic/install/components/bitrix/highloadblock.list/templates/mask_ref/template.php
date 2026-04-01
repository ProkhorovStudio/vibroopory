<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
$this->addExternalCss("/style.css");
/** @global CMain $APPLICATION */
/** @var array $arParams */
/** @var array $arResult */


if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

?>

<style>
    .modalAdd,.wrp,.modalEdit{
        display: none;
    }
    .wrp.show{
        z-index: 2;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgb(215 215 215 / 46%);
        display: block;
    }
    .modalAdd.show,.modalEdit.show{
        display: block;
        background: #fff;
        border-radius:6px;
        position:fixed;
        width: 300px;
        left: 0;
        right: 0;
        margin: 0 auto;
        top:20%;
        padding: 35px;
        z-index:3;
        padding-bottom: 20px;
    }
    td.UF_MASC_NUM{
    	width: 50px;
	    text-align: center;
	    padding-top: 15px;
	    padding-bottom: 15px;
    }
    .modalAdd .close,.modalEdit .close{
        display: block;
        position: absolute;
        right: 4px;
        top: 6px;
        opacity:0.5;
        cursor: pointer;
    }

    .modalAdd input,.modalEdit input{
        display: block;
        width: 100%;
        padding: 8px;
        border: 1px solid #d7d7d7;
        border-radius: 6px;
        outline: none !important;
        margin-bottom: 10px;
    }
    .modalAdd button,.modalEdit button{
        margin: 0 auto;
        display: block;
        width: 100%;
        font-size: 16px;
        text-transform: uppercase;
        margin-top: 18px;
        padding: 10px;
    }
    #addIp{
        padding: 10px;
        margin-bottom: 16px;
    }
    th > .button-list{
        width: 250px;
        background: #d7d7d7;
        height: 47px;
        margin-bottom: 20px;
        padding: 0;
    }
    td.button-list{
        display: flex;
        margin-top: 8px;
    }
    .edit-button{
        border: 1px solid #d7d7d7;
        padding: 5px;
        border-radius: 6px;
        margin-right: 18px;
        margin-left: 15px;
        cursor: pointer;
    }
    .delete-btn{
        border: 1px solid #d7d7d7;
        padding: 5px;
        border-radius: 6px;
        cursor: pointer;
    }
</style>


<div class="reports-result-list-wrap">
<div class="report-table-wrap">
<div class="reports-list-left-corner"></div>
<div class="reports-list-right-corner"></div>
<table cellspacing="0" class="reports-list-table" id="report-result-table">
	<!-- head -->
	<tr>
		<?php

		$fieldNames = array_keys($arResult['tableColumns']);
		unset($fieldNames[0]);
		$fieldNamesCount = count($fieldNames);
		$i = 0;
		foreach($fieldNames as $col):

			$i++;

			if ($i === 1)
			{
				
			}
			else if ($i === $fieldNamesCount)
			{
				$th_class = 'reports-last-column';
			}
			else
			{
				$th_class = 'reports-head-cell';
			}

			// title
			$arUserField = $arResult['fields'][$col];
			$title = trim((string)($arUserField["LIST_COLUMN_LABEL"] ?? ''));
			if ($title === '')
			{
				$title = $col;
			}

			// sorting
			$defaultSort = 'DESC';
			//$defaultSort = $col['defaultSort'];

			if ($col === $arResult['sort_id'])
			{
				$th_class .= ' reports-selected-column';

				if($arResult['sort_type'] == 'ASC')
				{
					$th_class .= ' reports-head-cell-top';
				}
			}
			else
			{
				if ($defaultSort == 'ASC')
				{
					$th_class .= ' reports-head-cell-top';
				}
			}

			?>
			<th class="<?=$th_class?>" colId="<?=htmlspecialcharsbx($col)?>" defaultSort="<?=$defaultSort?>">
				<div class="reports-head-cell"><?php
					if($defaultSort):
						?><span class="reports-table-arrow"></span><?php
					endif;
				?><span class="reports-head-cell-title"><?=htmlspecialcharsex($title)?></span></div>
			</th>

			<?php
		endforeach;
		?>
	</tr>

	<!-- data -->
	<?php
	foreach ($arResult['rows'] as $row):
		?>
	<tr class="reports-list-item">
		<?php
		$i = 0;
		foreach ($fieldNames as $col):
			if($col == 'ID')
			{
				unset($col);
			}

			$i++;
			if ($i === 1)
			{
				$td_class = 'reports-first-column';
			}
			else if ($i === $fieldNamesCount)
			{
				$td_class = 'reports-last-column';
			}
			else
			{
				$td_class = '';
			}



	
			if (false) // numeric rows
			{
				$td_class .= ' reports-numeric-column';
			}

			$finalValue = $row[$col];

            if($col == 'UF_MASC_START'){
                $start = $finalValue;
            }
            if($col == 'UF_MASC_END'){
                $end = $finalValue;
            }


			?>

			<td title="<?=$finalValue?>" class=" <?=$class?> <?=$td_class?> <?=$col;?>"><?=$finalValue?></td>

			<?php
		endforeach;
		?>
       <!--  <td class="button-list">
            <div class="edit-button" start="<?=$start?>" end="<?=$end;?>" idElement="<?=$idElement;?>">Изменить</div>
            <div class="delete-btn" idElement="<?=$idElement;?>">Удалить</div>
        </td> -->
	</tr>
	<?php
    unset($nameIp);
	endforeach;
	?>

</table>

<?php
if ($arParams['ROWS_PER_PAGE'] > 0):
	$APPLICATION->IncludeComponent(
		'bitrix:main.pagenavigation',
		'',
		array(
			'NAV_OBJECT' => $arResult['nav_object'],
			'SEF_MODE' => 'N',
		),
		false
	);
endif;
?>


<form id="hlblock-table-form" action="" method="get">
	<input type="hidden" name="BLOCK_ID" value="<?=htmlspecialcharsbx($arParams['BLOCK_ID'])?>">
	<input type="hidden" name="sort_id" value="">
	<input type="hidden" name="sort_type" value="">
</form>

<script type="text/javascript">
	BX.ready(function(){
		var rows = BX.findChildren(BX('report-result-table'), {tag:'th'}, true);
		for (i in rows)
		{
			var ds = rows[i].getAttribute('defaultSort');
			if (ds == '')
			{
				BX.addClass(rows[i], 'report-column-disabled-sort')
				continue;
			}

			BX.bind(rows[i], 'click', function(){
				var colId = this.getAttribute('colId');
				var sortType = '';

				var isCurrent = BX.hasClass(this, 'reports-selected-column');

				if (isCurrent)
				{
					var currentSortType = BX.hasClass(this, 'reports-head-cell-top') ? 'ASC' : 'DESC';
					sortType = currentSortType == 'ASC' ? 'DESC' : 'ASC';
				}
				else
				{
					sortType = this.getAttribute('defaultSort');
				}

				var idInp = BX.findChild(BX('hlblock-table-form'), {attr:{name:'sort_id'}});
				var typeInp = BX.findChild(BX('hlblock-table-form'), {attr:{name:'sort_type'}});

				idInp.value = colId;
				typeInp.value = sortType;

				BX.submit(BX('hlblock-table-form'));
			});
		}
	});
</script>

</div>
</div>