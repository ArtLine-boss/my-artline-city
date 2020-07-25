<!doctype html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>Расчеты для клиента</title>
		<script src="js/main.min.js"></script>
		<script src="//api.bitrix24.com/api/v1/"></script>
    </head>
    <body class="o-page o-page--center">	
		<script>
			$(document).ready(function() {
				BX24.callMethod(
					'placement.bind', 
					{ 
						PLACEMENT: 'CRM_CONTACT_LIST_MENU', 
						HANDLER: "https://artline.city/bitrix24/", 
						TITLE: 'Расчеты для клиента', 
						DESCRIPTION: 'Расчеты для клиента' 
					},
					function(result) {
						if(result.status == 400)
							alert(result.answer.error_description);
						else {
							alert("Приложение установлено");
							BX24.installFinish();
						}
					}
				);
				BX24.callMethod(
					'placement.bind', 
					{ 
						PLACEMENT: 'CRM_COMPANY_LIST_MENU', 
						HANDLER: "https://artline.city/bitrix24/", 
						TITLE: 'Расчеты для клиента', 
						DESCRIPTION: 'Расчеты для клиента' 
					},
					function(result) {
						if(result.status == 400)
							alert(result.answer.error_description);
						else {
							alert("Приложение установлено");
							BX24.installFinish();
						}
					}
				);
				BX24.callMethod(
					'placement.bind', 
					{ 
						PLACEMENT: 'CRM_CONTACT_DETAIL_TAB', 
						HANDLER: "https://artline.city/bitrix24/", 
						TITLE: 'Расчеты для клиента', 
						DESCRIPTION: 'Расчеты для клиента' 
					},
					function(result) {
						if(result.status == 400)
							alert(result.answer.error_description);
						else {
							alert("Приложение установлено");
							BX24.installFinish();
						}
					}
				);
				BX24.callMethod(
					'placement.bind', 
					{ 
						PLACEMENT: 'CRM_COMPANY_DETAIL_TAB', 
						HANDLER: "https://artline.city/bitrix24/", 
						TITLE: 'Расчеты для клиента', 
						DESCRIPTION: 'Расчеты для клиента' 
					},
					function(result) {
						if(result.status == 400)
							alert(result.answer.error_description);
						else {
							alert("Приложение установлено");
							BX24.installFinish();
						}
					}
				);
			});
		</script>
	
	<body>
</html>