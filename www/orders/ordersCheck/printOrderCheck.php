<?php
	$AppUI->isAccess(ACCESSES::PRINT_ORDERS_CHECK);
	
	$msg = null;

	do {
		$idAcct = isset($_GET['idAcct']) ? intval($_GET['idAcct']) : null;
		//проверка существования чека
		$chck = new classes_ordersCheck();
		$where = array(
			'sql' => 'number_check=:order',
			'param' => array(
				'order' => $idAcct,
			),
		);
		if(count($chck->loadAll($where))) {
			$msg = 'Товарный чек для данного заказа уже создан';
			break;
		}
		//заказ
		$order = new classes_orders();
		if(null !== ($msg = $order->loadById($idAcct))) {
			break;
		}
		//менеджер
		$user = new classes_users();
		if(null !== ($msg = $user->loadByLogin($order->USER_ID))) {
			break;
		}
		//кассир
		$user_kassa = new classes_users();
		if(null !== ($msg = $user_kassa->loadByLogin($AppUI->login))) {
			break;
		}
		
		//получаем информацию о продуктах
		$orderProduct = new classes_orderProduct();
		$where = array(
			'sql' => 'ORDER_ID=:order',
			'param' => array(
				'order' => $idAcct,
			),
		);
		$products = $orderProduct->loadAll($where);
		
		$setting = new classes_settings();
		if(null !== ($msg = $setting->loadById(4))) {
			break;
		}
		
		$summaAll = 0;
		$productsArr = array();
		foreach ($products as $k => $v) {
			$productsArr[] = $v->ID;
			$summaAll += floatval($v->SUMM);
		}
		
		$check = new classes_ordersCheck();
		$check->user_id = $AppUI->login;
		$check->number_check = $idAcct;
		$check->products = implode(',', $productsArr);
		$check->summaAll = $summaAll;
		if(null !== ($msg = $check->store())) {
			break;
		}
		
?>

<style>
	body {
		font-size: 10px;
	}
	
	.tbl {
		border: 1px solid black;
		border-collapse: collapse;
	}
	
	table {
		width: 100%;
	}
</style>

<table>
	<tbody>
		<tr>
			<td style="border-bottom: 1px solid" align="center">ОДО "АртЛайнСити", УНП: 290479470</td>
		</tr>
		<tr>
			<td style="font-size: 8px;" align="center">(наименование организации, УНП)</td>
		</tr>
	</tbody>
</table>
<table>
	<tbody>
		<tr>
			<td align="center"><b>Товарный чек № <?php echo $idAcct; ?> от <?php echo API::FormatDate($order->DATE_OR); ?>г.</b></td>
		</tr>
	</tbody>
</table>

<table class="tbl">
	<thead>
		<tr>
			<th class="tbl">Наименование</th>
			<th class="tbl">Ед. изм.</th>
			<th class="tbl">Кол-во</th>
			<th class="tbl">Сумма, руб. коп.</th>
			<th class="tbl">Ставка НДС, %</th>
			<th class="tbl">Сумма НДС, руб. коп.</th>
			<th class="tbl">Всего с НДС, руб. коп.</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($products as $k => $v) {
				$summa = floatval($v->SUMM) / (1 + floatval($setting->VAL)/100);
				$nds = floatval($v->SUMM) - $summa;
			?>
				<tr>
					<td class="tbl"><?php echo $v->p_names; ?></td>
					<td class="tbl" align="center"><?php echo $v->units; ?></td>
					<td class="tbl" align="center"><?php echo $v->TOTAL; ?></td>
					<td class="tbl" align="center"><?php echo API::FormatMoney($summa); ?></td>
					<td class="tbl" align="center"><?php echo $setting->VAL; ?></td>
					<td class="tbl" align="center"><?php echo API::FormatMoney($nds); ?></td>
					<td class="tbl" align="center"><?php echo API::FormatMoney($v->SUMM); ?></td>
				</tr>
			<?php
			}
			?>
				<tr>
					<td class="tbl" align="right" colspan="6"><b>Всего</b></td>
					<td class="tbl" align="center"><b><?php echo API::FormatMoney($summaAll); ?></b></td>
				</tr>
			<?php
		?>
	</tbody>
</table>

<table>
	<tr><td>Всего отпущено на сумму: <?php echo API::NumberToStringMoney($summaAll); ?>.</td></tr>
</table>

<table style="width: auto;">
	<tr>
		<td>Продавец</td><td style="border-bottom: 1px solid; width: 40%;"></td><td>/</td><td style="border-bottom: 1px solid"><?php echo classes_accordUsers::getFIO($user->USER_LOGIN, ($order->parent_company - 1)); ?></td>
	</tr>
	<tr>
		<td>Кассир</td><td style="border-bottom: 1px solid; width: 40%;"></td><td>/</td><td style="border-bottom: 1px solid"><?php echo classes_accordUsers::getFIO($user_kassa->USER_LOGIN, ($order->parent_company - 1)); ?></td>
	</tr>
</table>

<script>
	window.print();
</script>

<?php
		
	} while(false);
	
	echo $msg;
?>