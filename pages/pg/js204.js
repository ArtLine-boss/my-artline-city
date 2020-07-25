function amCal(kol, summMat, orderProd, orderAcct, summMR1, prod_size_11, prod_size_22,PRODUCT_SH1,PRODUCT_SKOBA,clients_nadbavka1,kurs,nds,activ_flag,firm_nacenka){
         var clients_nadbavka = document.getElementById('clients_nadbavka').value;
			document.getElementById('flag').value = '1';
			var prod_skod, PRODUCT_SH ,arrayOper,arrayMat,arrayMat1,SizeMat,sOper,sOp,sel,txt,psize,psize1,prod_size_1,prod_size_2, arrayPage,opera,mater,eq, rflag,par11,par12,par13,par14,par15,par16,par17,vChe,arrOper;
			var ko1  = Number(document.getElementById('kol1').value);
			var ko2  = Number(document.getElementById('kol2').value);
			var ko3  = Number(document.getElementById('kol3').value);
			var ko4  = Number(document.getElementById('kol4').value);
			var ko5  = Number(document.getElementById('kol5').value);
			var ko6  = Number(document.getElementById('kol6').value);
			var ko7  = Number(document.getElementById('kol7').value);
			var qqeeeq;
			var sqtq;
			var summMR = 0;
			var shelk = true;
			var qflag = true;
			var sumOper = 0; 
			var sumMat = 0;
			var sumMat1_all = 0;
			var sumMat2_all = 0;
			var sumMat3_all = 0;
			var sumMat4_all = 0;
			var sumMat5_all = 0;
			var sumMat6_all = 0;
			var sumMat7_all = 0;
			var summOp1 = 0;
			var summOp2 = 0;
			var l_hidden = false;
			var strOper = 0;
			var strMat = 0;
			var strKolOper = 0;	
			var strKolMat = 0;	
			var str_value = '';
			var str_full = '';
			var sum1 = 0;
			var sum2 = 0;
			var sum3 = 0;
			var sum4 = 0;
			var sum5 = 0;
			var sum6 = 0;
			var sum7 = 0;
			var sum1_all = 0;
			var sum2_all = 0; 
			var sum3_all = 0;
			var sum4_all = 0;
			var sum5_all = 0; 
			var sum6_all = 0;
			var sum7_all = 0;
			var sumOp = 0;
			var sumM = 0;
			var kolstr = 0;
			var matsize;	
			var kolpage = 0;
			var wsize = 0;
			var hsize = 0;
			var arraysize = 0;
			var pagekol = 0;
			var pagekol1 = 0;
			var wsize1 = 0;
			var hsize1 = 0;
			var arraysize1 = 0;
			var wisize = 0;
			var hisize = 0;
			var flag;
			var size = 0;
			var par1 = 0;
			var par2 = 0;
			var par3 = 0;
			var par4 = 0;
			var par5 = 0;
			var par6 = 0;
			var par7 = 0;
			var offpr = 0;
			var par1_1 = 0;
			var par2_1 = 0;
			var par3_1 = 0;
			var par4_1 = 0;
			var par5_1 = 0;
			var par6_1 = 0;
			var par7_1 = 0;
			var kor ;
			var dlin;
			var sumSpam = 0;
			var kol_in_page;
			
			
			psize  =  document.getElementById('psize').value;
			psize1 = psize.split('*');
			prod_size_1 = psize1[0];
			prod_size_2 = psize1[1];

			if (document.getElementById('chek').checked){
				vChe = 4;
			}
			else {
				vChe = 0;
			}
			
			/*alert(" prod_size_1 " + prod_size_1 + " prod_size_2 " + prod_size_2)*/
			if (PRODUCT_SKOBA == '1'){
				prod_skoda = 4
				PRODUCT_SH  =  document.getElementById('selSh').value;
			}else 
			{
				PRODUCT_SH  = 0;
				prod_skoda = 2
			}
			/*alert(prod_size_1 + "*" + prod_size_2)*/


				console.log("-----------------------------------------------------START-----------------------------------------------------------")
			for (var i = 1 ; i <=kol; i++ ){
				

				var str1 = 'ch'+i + '1'; //операции
				var str2 = 'ch'+i + '2'; //кол-во операций
				var str3 = 'ch'+i + '3'; //материал
				var str4 = 'ch'+i + '4'; //кол-во материала
				var str5 = 'ch'+i + '5'; //кол-во страниц
				var str6 = 'ch'+i + '6'; //размер
				var str7 = 'ch'+i + '7'; //кол-во изделий на листе
				var str8 = 'ch'+i + '8'; //оборудование
				var str9 = 'ch'+i + '9'; //оборудование
				var str10 = 'ch'+i + '10'; //оборудование
				var str1_value = document.getElementById(str1).value;
				var str2_value = document.getElementById(str2).value;
				var str3_value = document.getElementById(str3).value;
				var str4_value = document.getElementById(str4).value;
				var str5_value = document.getElementById(str5).value;
				var str6_value = document.getElementById(str6).value;
				var str7_value = document.getElementById(str7).value;
				var str8_value = document.getElementById(str8).value;
				var str9_value = document.getElementById(str9).value;
				var str10_value = document.getElementById(str10).value;
				var date_rdy = document.getElementById('ch'+i + '20').value;
				var price_off = document.getElementById('ch'+i + '21').value ;
				var kol_off = document.getElementById('ch'+i + '22').value;
		      var kol_NEXT = document.getElementById('ch'+i + '23').value;
				var off_size = document.getElementById('ch'+i + '24').value;
				var nadbavka_off = document.getElementById('ch'+i + '25').value;
				var l_use = true;
				if (nadbavka_off == '') {
					nadbavka_off = 0
				}
				if(document.getElementById('os' + (i - 1)).className == 'closed'){
					l_use = false;
				}
						if (str2_value == ''){
					str2_value = '0'
				}
			if ((str3_value == '0@0!^0' || str3_value == '0@0!0*0^0') && (str2_value == '0' || str2_value == '') &&   str9_value == '0' &&  (str4_value == '0' || str4_value == '') && (str5_value == '0' || str5_value == '') && (str6_value == '0*0' || str6_value == '') && str7_value == '0?0^0*0/1`0' && str8_value != '28' ){
						str1_value = '';
					}
				
				
				var str_off = String(date_rdy) + "`" + String(price_off) + "`" + String(kol_off) + "`" + String(kol_NEXT) + "`" + String(off_size) +  "`" + String(nadbavka_off);
				
				if (str3_value != '0@0!^0' && str3_value != '0@0!0*0^0'){
						str3_sel = document.getElementById(str3); 
						str3_txt = str3_sel.options[str3_sel.selectedIndex].text;
				}
				var ht = 0;
				var wt = 0;
				if(document.getElementById('chek1').checked){
					kol33 = document.getElementById(str8).length;
					for ( y = 0; y < kol33; y ++){
						if (document.getElementById(str8).options[y].selected == true){
							srsss = (document.getElementById(str8).options[y].title).split('^')
							srsss1 = srsss[1].split("|");
							ht = srsss1[0];
							wt = srsss1[1];
						}
					}
				}
				/*Самоклейка*/
	
				/*if	(false === str3_txt.indexOf('Самоклейка') || false === str3_txt.indexOf('Пленка')){*/
					for (var u = 1; u <= kol; u++){
						if(document.getElementById('oper_name'+u).innerHTML.indexOf('Контурная резка по меткам') == '0'){
							if(document.getElementById('os' + (u - 1)).className != 'closed'){
								if(document.getElementById('ch' + u + '2').value != '0'){
									ht = 50;
									wt = 50;
								}
							}
						}		
					}
				
				flag = false;
				str_value =  str_value  + String(str8_value) + '(^)' + String(str1_value) + '(^)' + String(str2_value) + '(^)' +  String(str3_value) + '(^)' + String(str4_value)  + '(^)' + String(str5_value) + '(^)' + String(str6_value) + '(^)' + String(str7_value) + '(^)' +  String(str_off) + "{";
				console.log("-----------------------------------------------------" + i + "-----------------------------------------------------------")
			
			console.log("операции " + str1_value + " |кол-во операций " + str2_value + " |материал " + str3_value + " |кол-во материала " + str4_value + " |кол-во страниц " + str5_value + " |размер " + str6_value + " |кол-во изделий на листе" + str7_value + "| str9_value " + str9_value + " " +  str8_value)
			
			 rflag = false;
				mater = '';
				opera = '';
				
				if (str1_value!=''){
				arrOper = str1_value.split('^');
				if (arrOper[1]!=''){
					
					
					arrayOper = arrOper[0].split('#');
					if (arrayOper == "") {
						opera = ''
						strOper = 0;
					} else {
						opera = arrayOper[0];
						strOper = Number(arrayOper[1]);
						}
				}
				}else {
					opera = ''
						strOper = 0;
				}
				if (str1_value!='' && l_use){
				strKolOper = str2_value.replace(',','.');
			
				if (strKolOper == "") {
					strKolOper = 0;
				}
			
				addw = str3_value.split('^');

				if (str3_value != '0@0!^0' && str3_value != '0@0!0*0^0' && addw[1] != '-'){
					
					mattol = addw[1];
					mattol = mattol.replace(',','.');
				}
				
				arrayMat = addw[0].split('!');
				
				if (arrayMat != "") {
					SizeMat = arrayMat[1];
					arrayMat1 = arrayMat[0].split('@');
				
				if (arrayMat1 == "") {
					mater = '';
					strMat = 0;
				} else {
					mater = arrayMat1[0];
					strMat = Number(arrayMat1[1]);
					}
				}
					else {
						SizeMat = '0*0';
						strMat = 0;
					}
						} else {
						SizeMat = '0*0';
						strMat = 0;
					}
					
				strKolMat = str4_value;
				if (strKolMat == "") {
					strKolMat = 0;
				}
				kolstr = str5_value;
				if (kolstr == "") {
					alert("Введите кол-во страниц!")
					kolstr = 0;
				}
				matsize = str6_value;
				if (matsize == "") {
					alert("Введите размер!")
					matsize = "0*0";
				}

				adSpam = str7_value.split('/')
				adSpam1 = adSpam[1].split('`')
				if (adSpam1[0] == '0'){
					sumSpam = Number(sumSpam) + Number(adSpam1[1]);
				}
				arrayPage12 = adSpam[0].split('^')
				
				qqq_d = arrayPage12[1];
				
						arrayPage1 = arrayPage12[0];
				arrayPage = arrayPage1.split('?')
				id_stamp = arrayPage[0];
				kolpage1 = Number(arrayPage[1]);
				if (kolpage1 == "") {
					kolpage1 = 0;
				}
				eq = str8_value;
		/*	if(strOper > 0){*/
		/*Надо обработать как офсет*/
		l_off = false;

		for ( y = 0; y < document.getElementById(str8).length; y ++){
					if (document.getElementById(str8).options[y].selected == true){
			srsss = (document.getElementById(str8).options[y].title).split('^')
			if (srsss[0] != '0'){

						l_off = true; 	
						l_hidden = true; 
				
			}
		}
			}
			
	
		 // alert(strOper + ' ' + str8_value + ' ' + l_use)
			if((strOper > 0 || l_off) /*|| str9_value == '1' || str9_value == '11' || str9_value == '12' ||  str9_value == '13')*/  && (str8_value != '0') && l_use ){
					if (arrOper[1]!=''){
						summMR =  Number(summMR) + Number(arrOper[1]);
					}
				if (matsize != "0*0" && shelk) { 
				
					/*alert("matsize YES")*/
					arraysize  = matsize.split('*');
					wsize = Number(arraysize[0]) + Number(vChe);
					hsize = Number(arraysize[1]) + Number(vChe);
					arraysize1  = SizeMat.split('*');
					if(arraysize1.length > 1){
				
						wsize1 = Number(arraysize[0])- ht;;
						hsize1 = Number(arraysize[1])- wt;
						if(wsize1 != 0 && hsize1 != 0){
							size = Number(arraysize1[1]);
							var x1 = Number(wsize1)/Number(wsize);
							var x2 = Number(hsize1)/Number(hsize);
							var x3 = Number(hsize1)/Number(wsize);
							var x4 = Number(wsize1)/Number(hsize);
									
							var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
							var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
				/*			alert("y1 " + y1 + " y2 " + y2)*/
							if(y1.toFixed() >= y2.toFixed()){
								pagekol = y1.toFixed();
							}
							else {
								pagekol = y2.toFixed();
							}
							
							kol_in_page  = Math.ceil(pagekol);
							console.log("Кол-во изделий на листе " + kol_in_page)
						//	alert ("pagekol " + pagekol)
							/*alert(pagekol)*/
							pagekol = Math.ceil(pagekol);
							pagekol1 = 	Number(pagekol) * prod_skoda;
							/*alert("pagekol " + pagekol + " kolstr " + kolstr)*/
							if (kolstr == "" || kolstr == 0){
								/*alert("ko1 " + ko1 + " pagekol " + pagekol)*/
								par1 = Number(ko1)/Number(pagekol);
								par2 = Number(ko2)/Number(pagekol);
								par3 = Number(ko3)/Number(pagekol);
								par4 = Number(ko4)/Number(pagekol);
								par5 = Number(ko5)/Number(pagekol);
								par6 = Number(ko6)/Number(pagekol);
								par7 = Number(ko7)/Number(pagekol);
								/*alert("par1 " + par1 + " ko1 " + ko1 + " pagekol " + pagekol)*/
							}
							else {
								par1 = (Number(kolstr)/Number(pagekol1)) * Number(ko1);
								par2 = (Number(kolstr)/Number(pagekol1)) * Number(ko2);
								par3 = (Number(kolstr)/Number(pagekol1)) * Number(ko3);
								par4 = (Number(kolstr)/Number(pagekol1)) * Number(ko4);
								par5 = (Number(kolstr)/Number(pagekol1)) * Number(ko5);
								par6 = (Number(kolstr)/Number(pagekol1)) * Number(ko6);
								par7 = (Number(kolstr)/Number(pagekol1)) * Number(ko7);
							}
							par1 = Math.ceil(par1);
							par2 = Math.ceil(par2);
							par3 = Math.ceil(par3);
							par4 = Math.ceil(par4);
							par5 = Math.ceil(par5);
							par6 = Math.ceil(par6);
							par7 = Math.ceil(par7);
							
							console.log("Кол-во листво " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
						}else {
							pagekol = 0; 
							alert("Размер изделия превышает размер печатного листа!")
							par1 = "";
							par2 = "";
							par3 = "";
							par4 = "";
							par5 = "";
							par6 = "";
							par7 = "";
						} 
						
						
					}
					else {
						if(arraysize[0] != ""){
							
							if( Number(prod_size_1) >= Number(prod_size_2)){
								size = Number(prod_size_1) ;
							}else {
								size = Number(prod_size_2);
							}
							sel = document.getElementById(str1); 
							txt = sel.options[sel.selectedIndex].text;
							if (txt == '1+1'){
								pagekol = (Number(size)/1000) * 2 ;
							}
							else {
								pagekol = (Number(size)/1000);
							}
							rflag = true;
							
								
							par11 = par1_1;
							par12 = par2_1;
							par13 = par3_1;
							par14 = par4_1;
							par15 = par5_1;
							par16 = par6_1;
							par17 = par7_1;
							
							par1 = Number(pagekol) * Number(par1_1);
							par2 = Number(pagekol) * Number(par2_1);
							par3 = Number(pagekol) * Number(par3_1);
							par4 = Number(pagekol) * Number(par4_1);
							par5 = Number(pagekol) * Number(par5_1);
							par6 = Number(pagekol) * Number(par6_1);
							par7 = Number(pagekol) * Number(par7_1);
							console.log("Кол-во материала для ламинирования  "  + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
						}
						else{		
							par1 = Number(strKolMat) * Number(ko1);
							par2 = Number(strKolMat) * Number(ko2);
							par3 = Number(strKolMat) * Number(ko3);
							par4 = Number(strKolMat) * Number(ko4);
							par5 = Number(strKolMat) * Number(ko5);
							par6 = Number(strKolMat) * Number(ko6);
							par7 = Number(strKolMat) * Number(ko7);
							console.log("Кол-во листво " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
						}
					}
				
				}
				else {
					if (shelk){

					arraysize  = SizeMat.split('*');
				/*	alert("arraysize" + arraysize)*/
					if(arraysize.length > 1 ||  str9_value == '10'){

						wsize1 = Number(arraysize[0])- ht;
						hsize1 = Number(arraysize[1])- wt;
			
						if(prod_size_1 > prod_size_2){
							kor = prod_size_2;
							dlin = prod_size_1;
						}else 
						{
							kor = prod_size_1;
							dlin = prod_size_2;
						}
						switch(PRODUCT_SH){
						case '1':
							wisize  = (Number(dlin) * 2)  + Number(vChe);	
							hisize  = Number(kor)  + Number(vChe);
							flag = true;
							break;
						case '2':
						
							wisize  =  Number(dlin) + Number(vChe);
							hisize  = (Number(kor) * 2)  + Number(vChe);	
							flag = true;
							break;
						default:
							wisize  = Number(dlin)  + Number(vChe);	
							hisize  = Number(kor)  + Number(vChe);	
							break;
						}
						
					if (str9_value == '10') {
						/*широкоформатка*/
						console.log("широкоформатка")
						// if (wsize1 > hsize1) { 
							// var storona = hsize1;
							// var storona2 = wsize1;
						// } else {
							// var storona = wsize1;
							// var storona2 = hsize1;
						// }
						var x1 = Number(arraysize[0])/Number(prod_size_1);
						var x2 = Number(arraysize[0])/Number(prod_size_2);

						if(Math.floor(x1) >= Math.floor(x2)){
								pagekol = Math.floor(x1);
								var stron1 = prod_size_1;
							}
						else {
								pagekol = Math.floor(x2);
								var stron1 = prod_size_2;
							}
						console.log("Количестов в строку листа = " + pagekol)
						
						par1_m = ((Number(stron1)/1000) * (Number(ko1)/Number(pagekol)) + 0.04);
						par2_m = ((Number(stron1)/1000) * (Number(ko2)/Number(pagekol)) + 0.04);
						par3_m = ((Number(stron1)/1000) * (Number(ko3)/Number(pagekol)) + 0.04);
						par4_m = ((Number(stron1)/1000) * (Number(ko4)/Number(pagekol)) + 0.04);
						par5_m = ((Number(stron1)/1000) * (Number(ko5)/Number(pagekol)) + 0.04);
						par6_m = ((Number(stron1)/1000) * (Number(ko6)/Number(pagekol)) + 0.04);
						par7_m = ((Number(stron1)/1000) * (Number(ko7)/Number(pagekol)) + 0.04);
	
						sqr = (Number(prod_size_1) / 1000)  * (Number(prod_size_2)/1000);
						console.log("площадь одного изделия = " +  sqr)
						console.log("Кол-во метров материала " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
					
						
					}else { /*Не широкоформатка*/
							if((wsize1 >= wisize && hsize1 >= hisize ) || (wsize1 >=  hisize && hsize1 >= wisize)){
							
							
							var x1 = Number(wsize1)/Number(wisize);
							var x2 = Number(hsize1)/Number(hisize);
							var x3 = Number(hsize1)/Number(wisize);
							var x4 = Number(wsize1)/Number(hisize);
							var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
							var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
							//alert(y1 + " " + y2)
							if(y1.toFixed() >= y2.toFixed()){
								pagekol = y1.toFixed();
							}
							else {
								pagekol = y2.toFixed();
							}
								kol_in_page  = Math.ceil(pagekol);
							pagekol = Math.ceil(pagekol);
							pagekol1 = 	Number(pagekol) * prod_skoda;
						}else {
							if (isNaN(wsize1) && isNaN(wisize) && isNaN(hsize1) && isNaN(hisize) ){
							alert("Размер изделия превышает размер печатного листа!")
							pagekol = 0; 
							document.getElementById("price2").value = '0';
							document.getElementById("price3").value = '0';
							document.getElementById("price4").value = '0';
							document.getElementById("price5").value = '0';
							document.getElementById("price6").value = '0';
							document.getElementById("price7").value = '0';
							
							document.getElementById("price_2").value = '0';
							document.getElementById("price_3").value = '0';
							document.getElementById("price_4").value = '0';
							document.getElementById("price_5").value = '0';
							document.getElementById("price_6").value = '0';
							document.getElementById("price_7").value = '0';
						return;	
							}
						}
						console.log("Кол-во изделий на листе " + kol_in_page)
						
						if (kolstr == "" || kolstr == 0){
							
							if(strKolOper == 0 ){
								par1 = Number(ko1)/Number(pagekol);
								par2 = Number(ko2)/Number(pagekol);
								par3 = Number(ko3)/Number(pagekol);
								par4 = Number(ko4)/Number(pagekol);
								par5 = Number(ko5)/Number(pagekol);
								par6 = Number(ko6)/Number(pagekol);
								par7 = Number(ko7)/Number(pagekol);
							} else {
								par1 = Number(strKolMat);
								par2 = Number(strKolMat);
								par3 = Number(strKolMat);
								par4 = Number(strKolMat);
								par5 = Number(strKolMat);
								par6 = Number(strKolMat);
								par7 = Number(strKolMat);
							}
						}
						else {
							par1 = Number(kolstr)/Number(pagekol1) * Number(ko1);
							par2 = Number(kolstr)/Number(pagekol1) * Number(ko2);
							par3 = Number(kolstr)/Number(pagekol1) * Number(ko3);
							par4 = Number(kolstr)/Number(pagekol1) * Number(ko4);
							par5 = Number(kolstr)/Number(pagekol1) * Number(ko5);
							par6 = Number(kolstr)/Number(pagekol1) * Number(ko6);
							par7 = Number(kolstr)/Number(pagekol1) * Number(ko7);
						}
					}
						 
						par1 = Math.ceil(par1);
						par2 = Math.ceil(par2);
						par3 = Math.ceil(par3);
						par4 = Math.ceil(par4);
						par5 = Math.ceil(par5);
						par6 = Math.ceil(par6);
						par7 = Math.ceil(par7);
						console.log("Кол-во листво " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
					
					
					}
					else {
						if(arraysize[0] != ""){
							if( Number(wsize1) >= Number(hsize1)){
								size = Number(wsize1);
							}else {
								size = Number(hsize1);
							}
							sel = document.getElementById(str1); 
							txt = sel.options[sel.selectedIndex].text; 
							if (txt == '1+1'){
								pagekol = (Number(size)/1000) * 2 ;
							}
							else {
								pagekol = (Number(size)/1000);
							}
							rflag = true;
							
							par11 = par1_1;
							par12 = par2_1;
							par13 = par3_1;
							par14 = par4_1;
							par15 = par5_1;
							par16 = par6_1;
							par17 = par7_1;
							
							par1 = Number(pagekol) * Number(par1_1);
							par2 = Number(pagekol) * Number(par2_1);
							par3 = Number(pagekol) * Number(par3_1);
							par4 = Number(pagekol) * Number(par4_1);
							par5 = Number(pagekol) * Number(par5_1);
							par6 = Number(pagekol) * Number(par6_1);
							par7 = Number(pagekol) * Number(par7_1);
							console.log("Кол-во материала для ламинирования  " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
							
						}
						else{
									
							par1 = Number(strKolMat) * Number(ko1);
							par2 = Number(strKolMat) * Number(ko2);
							par3 = Number(strKolMat) * Number(ko3);
							par4 = Number(strKolMat) * Number(ko4);
							par5 = Number(strKolMat) * Number(ko5);
							par6 = Number(strKolMat) * Number(ko6);
							par7 = Number(strKolMat) * Number(ko7);
							
							console.log("Кол-во материала для операции  " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
						}
					}
				}
			}
				if(strKolOper != 0){
					if(par1 == 0){
					par1 = strKolOper;
					}
					if(par2 == 0){
					par2 = strKolOper;
					}
					if(par3 == 0){
					par3 = strKolOper;
					}
					if(par4 == 0){
					par4 = strKolOper;
					}
					if(par5 == 0){
					par5 = strKolOper;
					}
					if(par6 == 0){
					par6 = strKolOper;
					}
					if(par7 == 0){
					par7 = strKolOper;
					}
				}	
				
						 par1_1 = Math.ceil(par1);
						par2_1 = Math.ceil(par2);
						par3_1 = Math.ceil(par3);
						par4_1 = Math.ceil(par4);
						par5_1 = Math.ceil(par5);
						par6_1 = Math.ceil(par6);
						par7_1 = Math.ceil(par7);
				
				if(str9_value == '10' ){
					console.log( "стоимость операции " + strOper + " ! Площадь " + sqr + " !  стоимость материала"  + strMat + " ! длину на которой печаем " +  par2_m)

					console.log("(Площадь * кол-во * стоимость операции) + (стоимость материала *  длину на которой печаем )" )
					console.log("Стоимость  " + ko1 + " = " + ((Number(strOper) * Number(sqr) * Number(ko1)) + (Number(strMat) * Number(par1_m))) + ' | ' 
								+ ko2 + " = " + ((Number(strOper) * Number(sqr) * Number(ko2)) + (Number(strMat) * Number(par2_m))) + ' | ' 
								+ ko3 + " = " + ((Number(strOper) * Number(sqr) * Number(ko3)) + (Number(strMat) * Number(par3_m))) + ' | ' 
								+ ko4 + " = " + ((Number(strOper) * Number(sqr) * Number(ko4)) + (Number(strMat) * Number(par4_m))) + ' | ' 
								+ ko5 + " = " + ((Number(strOper) * Number(sqr) * Number(ko5)) + (Number(strMat) * Number(par5_m))) + ' | ' 
								+ ko6 + " = " + ((Number(strOper) * Number(sqr) * Number(ko6)) + (Number(strMat) * Number(par6_m))) + ' | ' 
								+ ko7 + " = " + ((Number(strOper) * Number(sqr) * Number(ko7)) + (Number(strMat) * Number(par7_m))))
					sum1_all = Number(sum1_all) + ((Number(strOper) * Number(sqr) * Number(ko1)) + (Number(strMat) * Number(par1_m)));
					sum2_all = Number(sum2_all) + ((Number(strOper) * Number(sqr) * Number(ko2)) + (Number(strMat) * Number(par2_m)));
					sum3_all = Number(sum3_all) + ((Number(strOper) * Number(sqr) * Number(ko3)) + (Number(strMat) * Number(par3_m)));
					sum4_all = Number(sum4_all) + ((Number(strOper) * Number(sqr) * Number(ko4)) + (Number(strMat) * Number(par4_m)));
					sum5_all = Number(sum5_all) + ((Number(strOper) * Number(sqr) * Number(ko5)) + (Number(strMat) * Number(par5_m)));
					sum6_all = Number(sum6_all) + ((Number(strOper) * Number(sqr) * Number(ko6)) + (Number(strMat) * Number(par6_m)));
					sum7_all = Number(sum7_all) + ((Number(strOper) * Number(sqr) * Number(ko7)) + (Number(strMat) * Number(par7_m)));
					qflag = false;
				}

			if ( strKolOper != 0 && str9_value == '0' ){

				//alert(" par2 strKolOper != 0 && strKolMat != 0 "  +  ((Number(strMat) * Number(strKolMat) * Number(ko2)) + (Number(strOper) * Number(strKolOper) * Number(ko2))))
				//alert(" par3 strKolOper != 0 && strKolMat != 0 "  +  ((Number(strMat) * Number(strKolMat) * Number(ko3)) + (Number(strOper) * Number(strKolOper) * Number(ko3))))
				str_full =  str_full  +  String(opera) + '|0,0;' + String(Number(strKolOper) * Number(ko2)) + ','  + ko2 + ';' + String(Number(strKolOper) * Number(ko3)) + ','  + ko3 + ';' + String(Number(strKolOper) * Number(ko4)) + ','  + ko4 + ';' + String(Number(strKolOper) * Number(ko5)) + ','  + ko5 + ';' + String(Number(strKolOper) * Number(ko6)) + ','  + ko6 + ';' + String(Number(strKolOper) * Number(ko7)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
				/*
				opera,mater
				*/
				console.log("Кол-во операции и кол-во материала!" )
				console.log("(Стоимость операции * Кол-во операций * тираж ) + (Стоимость материала * кол-во материала * тираж)")
				console.log("Стоимость операции: " + strOper + " кол-во операций для одного: " + strKolOper + "! Стоимость материала: " + strMat + " кол-во материала для одного: " + strKolMat)
				console.log("Стоимость  " + ko1 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko1)) + (Number(strOper) * Number(strKolOper) * Number(ko1))) + ' | ' 
								+ ko2 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko2)) + (Number(strOper) * Number(strKolOper) * Number(ko2))) + ' | ' 
								+ ko3 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko3)) + (Number(strOper) * Number(strKolOper) * Number(ko3))) + ' | ' 
								+ ko4 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko4)) + (Number(strOper) * Number(strKolOper) * Number(ko4))) + ' | ' 
								+ ko5 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko5)) + (Number(strOper) * Number(strKolOper) * Number(ko5))) + ' | ' 
								+ ko6 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko6)) + (Number(strOper) * Number(strKolOper) * Number(ko6))) + ' | ' 
								+ ko7 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko7)) + (Number(strOper) * Number(strKolOper) * Number(ko7))))
						qflag = false;
				sum1_all = Number(sum1_all) + ((Number(strMat) * Number(strKolMat) * Number(ko1)) + (Number(strOper) * Number(strKolOper) * Number(ko1)));
				sum2_all = Number(sum2_all) + ((Number(strMat) * Number(strKolMat) * Number(ko2)) + (Number(strOper) * Number(strKolOper) * Number(ko2)));
				sum3_all = Number(sum3_all) + ((Number(strMat) * Number(strKolMat) * Number(ko3)) + (Number(strOper) * Number(strKolOper) * Number(ko3)));
				sum4_all = Number(sum4_all) + ((Number(strMat) * Number(strKolMat) * Number(ko4)) + (Number(strOper) * Number(strKolOper) * Number(ko4)));
				sum5_all = Number(sum5_all) + ((Number(strMat) * Number(strKolMat) * Number(ko5)) + (Number(strOper) * Number(strKolOper) * Number(ko5)));
				sum6_all = Number(sum6_all) + ((Number(strMat) * Number(strKolMat) * Number(ko6)) + (Number(strOper) * Number(strKolOper) * Number(ko6)));
				sum7_all = Number(sum7_all) + ((Number(strMat) * Number(strKolMat) * Number(ko7)) + (Number(strOper) * Number(strKolOper) * Number(ko7)));

			}
			if ( str9_value == '14' ){

				
				
				
				
				str_full =  str_full  +  String(opera) + '|0,0;' + String(Number(ko2)) + ','  + ko2 + ';' + String( Number(ko3)) + ','  + ko3 + ';' + String(Number(ko4)) + ','  + ko4 + ';' + String(Number(ko5)) + ','  + ko5 + ';' + String(Number(ko6)) + ','  + ko6 + ';' + String(Number(ko7)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
				/*
				opera,mater
				*/
				
					if(prod_size_1 > prod_size_2){
							kor = prod_size_2;
							dlin = prod_size_1;
						}else 
						{
							kor = prod_size_1;
							dlin = prod_size_2;
						}
							
							sel3 = document.getElementById(str3); 
							txt3 = sel3.options[sel3.selectedIndex].text;
							kol_mat = 0;
						if (document.getElementById('selSh').value == '1'){
							//>по короткой стороне
							if (txt3.indexOf('2:1') > 0){
								kol_mat = ((Number(kor)/12) - 2);
							}else{
								kol_mat = ((Number(kor)/9) + 1) ;
							}
						} else {
							//>по длинной стороне
							
								if (txt3.indexOf('2:1') > 0){
								kol_mat = ((Number(dlin)/12) - 2);
							}else{
								kol_mat = ((Number(dlin)/9) + 1) ;
							}
							
							
						}
						strKolMat = kol_mat.toFixed(0);
						
				
				console.log("Кол-во операции и кол-во материала!" )
				console.log("((Стоимость операции  + (Стоимость материала * кол-во материала) )* тираж)")
				console.log("Стоимость операции: " + strOper  + "! Стоимость материала: " + strMat + " кол-во материала для одного: " + strKolMat)
				console.log("Стоимость  " + ko1 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko1)) + (Number(strOper) * Number(ko1))) + ' | ' 
								+ ko2 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko2)) + (Number(strOper) * Number(ko2))) + ' | ' 
								+ ko3 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko3)) + (Number(strOper) * Number(ko3))) + ' | ' 
								+ ko4 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko4)) + (Number(strOper) * Number(ko4))) + ' | ' 
								+ ko5 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko5)) + (Number(strOper) * Number(ko5))) + ' | ' 
								+ ko6 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko6)) + (Number(strOper) * Number(ko6))) + ' | ' 
								+ ko7 + " = " + ((Number(strMat) * Number(strKolMat) * Number(ko7)) + (Number(strOper) * Number(ko7))))
						qflag = false;
				sum1_all = Number(sum1_all) + ((Number(strMat) * Number(strKolMat) * Number(ko1)) + (Number(strOper)  * Number(ko1)));
				sum2_all = Number(sum2_all) + ((Number(strMat) * Number(strKolMat) * Number(ko2)) + (Number(strOper)  * Number(ko2)));
				sum3_all = Number(sum3_all) + ((Number(strMat) * Number(strKolMat) * Number(ko3)) + (Number(strOper)  * Number(ko3)));
				sum4_all = Number(sum4_all) + ((Number(strMat) * Number(strKolMat) * Number(ko4)) + (Number(strOper)  * Number(ko4)));
				sum5_all = Number(sum5_all) + ((Number(strMat) * Number(strKolMat) * Number(ko5)) + (Number(strOper)  * Number(ko5)));
				sum6_all = Number(sum6_all) + ((Number(strMat) * Number(strKolMat) * Number(ko6)) + (Number(strOper)  * Number(ko6)));
				sum7_all = Number(sum7_all) + ((Number(strMat) * Number(strKolMat) * Number(ko7)) + (Number(strOper)  * Number(ko7)));

			}

			//alert("kolpage " + kolpage)
			
			
				//		alert(" par2 strKolOper != 0 && strKolMat == 0 && kolpage == 0 "  +   (Number(strOper) * Number(strKolOper) * Number(ko2)))
				//alert(" par3 strKolOper != 0 && strKolMat == 0 && kolpage == 0 "  +   (Number(strOper) * Number(strKolOper) * Number(ko3)))
				

						/*if(strKolOper != 0 && strKolMat == 0 && kolpage == 0 ){*/
							if(str9_value == '1'){
					console.log("Расчет стопы для резки" )
					
				
					//kol_in_page //кол-во на странице
						if (kolstr == "" || kolstr == 0){
							
						par111 = Number(ko1)/Number(kol_in_page);
						par222 = Number(ko2)/Number(kol_in_page);
						par333 = Number(ko3)/Number(kol_in_page);
						par444 = Number(ko4)/Number(kol_in_page);
						par555 = Number(ko5)/Number(kol_in_page);
						par666 = Number(ko6)/Number(kol_in_page);
						par777 = Number(ko7)/Number(kol_in_page);
						
						}
						else {
							par111 = Number(kolstr)/Number(kol_in_page) * Number(ko1);
							par222 = Number(kolstr)/Number(kol_in_page) * Number(ko2);
							par333 = Number(kolstr)/Number(kol_in_page) * Number(ko3);
							par444 = Number(kolstr)/Number(kol_in_page) * Number(ko4);
							par555 = Number(kolstr)/Number(kol_in_page) * Number(ko5);
							par666 = Number(kolstr)/Number(kol_in_page) * Number(ko6);
							par777 = Number(kolstr)/Number(kol_in_page) * Number(ko7);
						}
						if (str2_value != '0') {

							par111 = Number(str2_value) * Number(str4_value) ;
							par222 = Number(str2_value) * Number(str4_value) ;
							par333 = Number(str2_value) * Number(str4_value) ;
							par444 = Number(str2_value) * Number(str4_value) ;
							par555 = Number(str2_value) * Number(str4_value) ;
							par666 = Number(str2_value) * Number(str4_value) ;
							par777 = Number(str2_value) * Number(str4_value) ;
							
							par1 = Math.ceil(par111);
							par2 = Math.ceil(par222);
							par3 = Math.ceil(par333);
							par4 = Math.ceil(par444);
							par5 = Math.ceil(par555);
							par6 = Math.ceil(par666);
							par7 = Math.ceil(par777);
							
							par111 = Number(str4_value) ;
							par222 = Number(str4_value) ;
							par333 = Number(str4_value) ;
							par444 = Number(str4_value) ;
							par555 = Number(str4_value) ;
							par666 = Number(str4_value) ;
							par777 = Number(str4_value) ;
							
						}
						par111 = Math.ceil(par111);
						par222 = Math.ceil(par222);
						par333 = Math.ceil(par333);
						par444 = Math.ceil(par444);
						par555 = Math.ceil(par555);
						par666 = Math.ceil(par666);
						par777 = Math.ceil(par777);
						
			
						par111_kol = (Number(mattol) * Number(par111))/50;
						par222_kol = (Number(mattol) * Number(par222))/50;
						par333_kol = (Number(mattol) * Number(par333))/50;
						par444_kol = (Number(mattol) * Number(par444))/50;
						par555_kol = (Number(mattol) * Number(par555))/50;
						par666_kol = (Number(mattol) * Number(par666))/50;
						par777_kol = (Number(mattol) * Number(par777))/50;
						
						
						par111_kol = Math.ceil(par111_kol);
						par222_kol = Math.ceil(par222_kol);
						par333_kol = Math.ceil(par333_kol);
						par444_kol = Math.ceil(par444_kol);
						par555_kol = Math.ceil(par555_kol);
						par666_kol = Math.ceil(par666_kol);
						par777_kol = Math.ceil(par777_kol);
						
							console.log("Кол-во стоп для резки  " + ko1 + " = " + par111_kol + ' | ' + ko2 + " = " + String(par222_kol) + ' | ' + ko3 + " = " + par333_kol + ' | ' + ko4 + " = " + par444_kol + ' | ' + ko5 + " = " + par555_kol + ' | ' + ko6 + " = " + par666_kol + ' | ' + ko7 + " = " + par777_kol)
					if (str2_value != '0') {
						var kol_rez = Number(str2_value) / 2;
						shelk = false;
					}
					else {
						var kol_rez  = Math.ceil(Number(kol_in_page) / 2) + 4 ;
					}
						
						
						console.log("Кол-во резов листа  " + kol_rez )
					if (str2_value != '0') {
					str_full =  str_full  +   String(opera) + '|0,0;' + String(Number(par222)) + ','  + ko2 + ';' + String(Number(par333)) + ','  + ko3 + ';' + String(Number(par444)) + ','  + ko4 + ';' + String(Number(par555) ) + ','  + ko5 + ';' + String(Number(par666))+ ','  + ko6 + ';' + String(Number(par777)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
						shelk = false;
					}
					else {
						str_full =  str_full  +   String(opera) + '|0,0;' + String(Number(par222)) + ','  + ko2 + ';' + String(Number(par333)) + ','  + ko3 + ';' + String(Number(par444)) + ','  + ko4 + ';' + String(Number(par555)) + ','  + ko5 + ';' + String(Number(par666))+ ','  + ko6 + ';' + String(Number(par777)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
					}
						
						qflag = false;
						if (str2_value == '0') {
								console.log("Стоимость операции: " + strOper)
								console.log("Стоимость операции * Кол-во резов листа * кол-во стоп ")
									console.log("Стоимость  " + ko1 + " = " + (Number(strOper) * Number(par111_kol) * Number(kol_rez)) + ' | ' 
										+ ko2 + " = " + (Number(strOper) * Number(par222_kol) * Number(kol_rez)) + ' | ' 
										+ ko3 + " = " + (Number(strOper) * Number(par333_kol) * Number(kol_rez)) + ' | ' 
										+ ko4 + " = " + (Number(strOper) * Number(par444_kol) * Number(kol_rez)) + ' | ' 
										+ ko5 + " = " + (Number(strOper) * Number(par555_kol) * Number(kol_rez)) + ' | ' 
										+ ko6 + " = " + (Number(strOper) * Number(par666_kol) * Number(kol_rez)) + ' | ' 
										+ ko7 + " = " + (Number(strOper) * Number(par777_kol) * Number(kol_rez)))
							sum1_all = Number(sum1_all) + (Number(strOper) * Number(par111_kol) * Number(kol_rez));
							sum2_all = Number(sum2_all) + (Number(strOper) * Number(par222_kol) * Number(kol_rez));
							sum3_all = Number(sum3_all) + (Number(strOper) * Number(par333_kol) * Number(kol_rez));
							sum4_all = Number(sum4_all) + (Number(strOper) * Number(par444_kol) * Number(kol_rez));
							sum5_all = Number(sum5_all) + (Number(strOper) * Number(par555_kol) * Number(kol_rez));
							sum6_all = Number(sum6_all) + (Number(strOper) * Number(par666_kol) * Number(kol_rez));
							sum7_all = Number(sum7_all) + (Number(strOper) * Number(par777_kol) * Number(kol_rez));
					}
					else {
						console.log("Стоимость операции: " + strOper + " Стоимость материала: " + strMat)
						console.log("(Стоимость операции * Кол-во резов листа * кол-во стоп) + (Кол-во мат * стоимость материала)" )
						console.log("Стоимость  " + ko1 + " = " + ((Number(strOper) * Number(par111_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko2 + " = " + ((Number(strOper) * Number(par222_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko3 + " = " + ((Number(strOper) * Number(par333_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko4 + " = " + ((Number(strOper) * Number(par444_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko5 + " = " + ((Number(strOper) * Number(par555_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko6 + " = " + ((Number(strOper) * Number(par666_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)) + ' | ' 
								+ ko7 + " = " + ((Number(strOper) * Number(par777_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value)))
						sum1_all = Number(sum1_all) + ((Number(strOper) * Number(par111_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum2_all = Number(sum2_all) + ((Number(strOper) * Number(par222_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum3_all = Number(sum3_all) + ((Number(strOper) * Number(par333_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum4_all = Number(sum4_all) + ((Number(strOper) * Number(par444_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum5_all = Number(sum5_all) + ((Number(strOper) * Number(par555_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum6_all = Number(sum6_all) + ((Number(strOper) * Number(par666_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
						sum7_all = Number(sum7_all) + ((Number(strOper) * Number(par777_kol) * Number(kol_rez)) + Number(strMat) * Number(str4_value));
					}	
				}
					
					
				if(str9_value == '11' || str9_value == '12'){
					console.log("Расчет стопы" )
			
					
					//kol_in_page //кол-во на странице
						if (kolstr == "" || kolstr == 0){
							
						par111 = Number(ko1)/Number(kol_in_page);
						par222 = Number(ko2)/Number(kol_in_page);
						par333 = Number(ko3)/Number(kol_in_page);
						par444 = Number(ko4)/Number(kol_in_page);
						par555 = Number(ko5)/Number(kol_in_page);
						par666 = Number(ko6)/Number(kol_in_page);
						par777 = Number(ko7)/Number(kol_in_page);
						
						}
						else {
							par111 = Number(kolstr)/Number(kol_in_page) * Number(ko1);
							par222 = Number(kolstr)/Number(kol_in_page) * Number(ko2);
							par333 = Number(kolstr)/Number(kol_in_page) * Number(ko3);
							par444 = Number(kolstr)/Number(kol_in_page) * Number(ko4);
							par555 = Number(kolstr)/Number(kol_in_page) * Number(ko5);
							par666 = Number(kolstr)/Number(kol_in_page) * Number(ko6);
							par777 = Number(kolstr)/Number(kol_in_page) * Number(ko7);
						}
			
						par111 = Math.ceil(par111);
						par222 = Math.ceil(par222);
						par333 = Math.ceil(par333);
						par444 = Math.ceil(par444);
						par555 = Math.ceil(par555);
						par666 = Math.ceil(par666);
						par777 = Math.ceil(par777);
						
						tolsh = 0
						if (str9_value == '11'){
							tolsh = 0.15;
						}
						if (str9_value == '12'){
							tolsh = 0.5;
						}
			
						
						
						par111_kol = (Number(mattol) * Number(par111))/Number(tolsh);
						par222_kol = (Number(mattol) * Number(par222))/Number(tolsh);
						par333_kol = (Number(mattol) * Number(par333))/Number(tolsh);
						par444_kol = (Number(mattol) * Number(par444))/Number(tolsh);
						par555_kol = (Number(mattol) * Number(par555))/Number(tolsh);
						par666_kol = (Number(mattol) * Number(par666))/Number(tolsh);
						par777_kol = (Number(mattol) * Number(par777))/Number(tolsh);
						
						
						par111_kol = Math.ceil(par111_kol);
						par222_kol = Math.ceil(par222_kol);
						par333_kol = Math.ceil(par333_kol);
						par444_kol = Math.ceil(par444_kol);
						par555_kol = Math.ceil(par555_kol);
						par666_kol = Math.ceil(par666_kol);
						par777_kol = Math.ceil(par777_kol);
						
							console.log("Кол-во стоп " + ko1 + " = " + par111_kol + ' | ' + ko2 + " = " + String(par222_kol) + ' | ' + ko3 + " = " + par333_kol + ' | ' + ko4 + " = " + par444_kol + ' | ' + ko5 + " = " + par555_kol + ' | ' + ko6 + " = " + par666_kol + ' | ' + ko7 + " = " + par777_kol)
					
						var kol_rez  = Math.ceil(Number(kol_in_page) / 2) + 4 ;
						
					
					str_full =  str_full  +   String(opera) + '|0,0;' + String(Number(par222_kol)) + ','  + ko2 + ';' + String(Number(par333_kol)) + ','  + ko3 + ';' + String(Number(par444_kol)) + ','  + ko4 + ';' + String(Number(par555_kol)) + ','  + ko5 + ';' + String(Number(par666_kol))+ ','  + ko6 + ';' + String(Number(par777_kol)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
						
						qflag = false;
						
						console.log("Стоимость операции: " + strOper)
						console.log("Стоимость операции * кол-во стоп + ")
							console.log("Стоимость  " + ko1 + " = " + ((Number(strOper) * Number(par111_kol)) +  (Number(ko1) * Number(strMat))) + ' | ' 
								+ ko2 + " = " + ((Number(strOper) * Number(par222_kol)) +  (Number(ko2) * Number(strMat))) + ' | ' 
								+ ko3 + " = " + ((Number(strOper) * Number(par333_kol)) +  (Number(ko3)* Number(strMat))) + ' | ' 
								+ ko4 + " = " + ((Number(strOper) * Number(par444_kol)) +  (Number(ko4) * Number(strMat))) + ' | ' 
								+ ko5 + " = " + ((Number(strOper) * Number(par555_kol)) +  (Number(ko5) * Number(strMat))) + ' | ' 
								+ ko6 + " = " + ((Number(strOper) * Number(par666_kol)) +  (Number(ko6) * Number(strMat))) + ' | ' 
								+ ko7 + " = " + ((Number(strOper) * Number(par777_kol)) +  (Number(ko7)* Number(strMat))))
					sum1_all = Number(sum1_all) + ((Number(strOper) * Number(par111_kol)) +  (Number(ko1) * Number(strMat)));
					sum2_all = Number(sum2_all) + ((Number(strOper) * Number(par222_kol)) +  (Number(ko2) * Number(strMat)));
					sum3_all = Number(sum3_all) + ((Number(strOper) * Number(par333_kol)) +  (Number(ko3) * Number(strMat)));
					sum4_all = Number(sum4_all) + ((Number(strOper) * Number(par444_kol)) +  (Number(ko4) * Number(strMat)));
					sum5_all = Number(sum5_all) + ((Number(strOper) * Number(par555_kol)) +  (Number(ko5) * Number(strMat)));
					sum6_all = Number(sum6_all) + ((Number(strOper) * Number(par666_kol)) +  (Number(ko6) * Number(strMat)));
					sum7_all = Number(sum7_all) + ((Number(strOper) * Number(par777_kol)) +  (Number(ko7) * Number(strMat)));
						
				}					
	/*}*/
		if ( str7_value != '0?0^0*0/1`0'){
					sqtq = 0;
					if (qqq_d != '0*0'){
			
							qqeeeq = qqq_d.split('*');
							sqtq = (Number(qqeeeq[0]) / 1000 ) * (Number(qqeeeq[1]) / 1000) ;
										
					}
			
				if(shelk){
				 
					str_full =  str_full  +   String(opera) + '|0,0;' + String( Number(ko2) / Number(kolpage1)) + ','  + ko2 + ';' + String( Number(ko3) / Number(kolpage1)) + ','  + ko3 + ';' + String(Number(ko4) / Number(kolpage1) ) + ','  + ko4 + ';' + String(Number(ko5) / Number(kolpage1) ) + ','  + ko5 + ';' + String(Number(ko6) / Number(kolpage1))+ ','  + ko6 + ';' + String(Number(ko7) / Number(kolpage1)) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
					qflag = false;
					
						console.log("Стоимость операции: " + strOper + " кол-во за один раз " + kolpage1 + " Стоимость материала: " + strMat)
						console.log("(Стоимость операции *  тираж / Кол-во операций) * (Стоимость материала * тираж * площадь одного) ")
							console.log("Стоимость  " + ko1 + " = " + ((Number(strOper) * Number(ko1) / Number(kolpage1))+ (Number(sqtq) * Number(strMat) * Number(ko1))) + ' | ' 
								+ ko2 + " = " + ((Number(strOper) * Number(ko2) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko2))) + ' | ' 
								+ ko3 + " = " + ((Number(strOper) * Number(ko3) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko3))) + ' | ' 
								+ ko4 + " = " + ((Number(strOper) * Number(ko4) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko4))) + ' | ' 
								+ ko5 + " = " + ((Number(strOper) * Number(ko5) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko5))) + ' | ' 
								+ ko6 + " = " + ((Number(strOper) * Number(ko6) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko6))) + ' | ' 
								+ ko7 + " = " + ((Number(strOper) * Number(ko7) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko7))))
			
				
					
					sum1_all = Number(sum1_all) + ((Number(strOper) *  Number(ko1) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko1)));
					sum2_all = Number(sum2_all) + ((Number(strOper) *  Number(ko2) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko2)));
					sum3_all = Number(sum3_all) + ((Number(strOper) *  Number(ko3) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko3)));
					sum4_all = Number(sum4_all) + ((Number(strOper) *  Number(ko4) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko4)));
					sum5_all = Number(sum5_all) + ((Number(strOper) *  Number(ko5) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko5)));
					sum6_all = Number(sum6_all) + ((Number(strOper) *  Number(ko6) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko6)));
					sum7_all = Number(sum7_all) + ((Number(strOper) *  Number(ko7) / Number(kolpage1)) + (Number(sqtq) * Number(strMat) * Number(ko7)));
				} else {
					str_full =  str_full  +   String(opera) + '|0,0;' + String(Number(par2)) + ','  + ko2 + ';' + String( Number(par3)) + ','  + ko3 + ';' + String(Number(par4)  ) + ','  + ko4 + ';' + String(Number(par5)) + ','  + ko5 + ';' + String(Number(par6) )+ ','  + ko6 + ';' + String(Number(par7) ) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
					qflag = false;
					
						console.log("Стоимость операции: " + strOper + " кол-во за один раз " + kolpage1 + " Стоимость материала: " + strMat)
						console.log("(Стоимость операции *  Кол-во операций) * (Стоимость материала * тираж * площадь одного) ")
							console.log("Стоимость  " + ko1 + " = " + ((Number(strOper) * Number(par1) )+ (Number(sqtq) * Number(strMat) * Number(ko1))) + ' | ' 
								+ ko2 + " = " + ((Number(strOper) * Number(par2) ) + (Number(sqtq) * Number(strMat) * Number(ko2))) + ' | ' 
								+ ko3 + " = " + ((Number(strOper) * Number(par3) ) + (Number(sqtq) * Number(strMat) * Number(ko3))) + ' | ' 
								+ ko4 + " = " + ((Number(strOper) * Number(par4) ) + (Number(sqtq) * Number(strMat) * Number(ko4))) + ' | ' 
								+ ko5 + " = " + ((Number(strOper) * Number(par5) ) + (Number(sqtq) * Number(strMat) * Number(ko5))) + ' | ' 
								+ ko6 + " = " + ((Number(strOper) * Number(par6) ) + (Number(sqtq) * Number(strMat) * Number(ko6))) + ' | ' 
								+ ko7 + " = " + ((Number(strOper) * Number(par7) ) + (Number(sqtq) * Number(strMat) * Number(ko7))));
			
				
					
					sum1_all = Number(sum1_all) + ((Number(strOper) *  Number(par1)) + (Number(sqtq) * Number(strMat) * Number(ko1)));
					sum2_all = Number(sum2_all) + ((Number(strOper) *  Number(par2)) + (Number(sqtq) * Number(strMat) * Number(ko2)));
					sum3_all = Number(sum3_all) + ((Number(strOper) *  Number(par3)) + (Number(sqtq) * Number(strMat) * Number(ko3)));
					sum4_all = Number(sum4_all) + ((Number(strOper) *  Number(par4)) + (Number(sqtq) * Number(strMat) * Number(ko4)));
					sum5_all = Number(sum5_all) + ((Number(strOper) *  Number(par5)) + (Number(sqtq) * Number(strMat) * Number(ko5)));
					sum6_all = Number(sum6_all) + ((Number(strOper) *  Number(par6)) + (Number(sqtq) * Number(strMat) * Number(ko6)));
					sum7_all = Number(sum7_all) + ((Number(strOper) *  Number(par7)) + (Number(sqtq) * Number(strMat) * Number(ko7)));
				}
				
				
				
				}
				
					
	


					if (l_off){
								
								if (off_size != "0*0"){
									
									arraysize  = SizeMat.split('*');
									wsize1 = Number(arraysize[0])- ht;
									hsize1 = Number(arraysize[1])- wt;
									off_size1 = off_size.split('*');
									
									var x1 = Number(wsize1)/Number(off_size1[0]);
									var x2 = Number(wsize1)/Number(off_size1[1]);
									var x3 = Number(hsize1)/Number(off_size1[0]);
									var x4 = Number(hsize1)/Number(off_size1[1]);
									var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
									var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
										//alert(y1 + " " + y2)
										if(y1.toFixed() >= y2.toFixed()){
											kol_in_list = y1.toFixed();
										}
										else {
											kol_in_list = y2.toFixed();
										}
									
										var x1 = Number(off_size1[0])/Number(prod_size_1);
										var x2 = Number(off_size1[1])/Number(prod_size_2);
										var x3 = Number(off_size1[1])/Number(prod_size_1);
										var x4 = Number(off_size1[0])/Number(prod_size_2);
										var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
										var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
										//alert(y1 + " " + y2)
										if(y1.toFixed() >= y2.toFixed()){
											kol_in_page = y1.toFixed();
										}
										else {
											kol_in_page = y2.toFixed();
										}
								}
								else {
									kol_in_page = 1;
									kol_in_list = 1;
								}
							console.log("Офсет: ТИРАЖ * кол-ва листе * кол-во заготовк на лист")
							console.log("Офсет: ТИРАЖ " + String(kol_off) + " кол-ва листе " + String(kol_in_list) + " кол-во заготовк на лист " + String(kol_in_page))
						
									ko1 = Number(kol_off)  * Number(kol_in_list) * Number(kol_in_page)
									if (kolstr != 0 ) {
										ko1 =  Number(ko1) /  Number(kolstr);
									}
									ko1 = ko1.toFixed()
								document.getElementById('kol1').value = ko1
									document.getElementById('par1').value = ko1
						
								str_full =  str_full  +   String(opera) + '|0,0;' + "0" + ','  + "0" + ';' + "0" + ','  + "0" + ';' + "0" + ','  + "0" + ';' + "0" + ','  + "0" + ';' + "0" + ','  + "0" + ';' + "0" + ','  + "0" + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
								qflag = false;
								ko2  = 0;
								ko3  = 0;
								ko4  = 0;
								ko5  = 0;
								ko6  = 0;
								ko7  = 0;
		
								offpr = Number(offpr) +  Number(price_off) + ( Number(price_off) * Number(nadbavka_off) / 100 );
								
								par1_1 = Number(kol_off);
								par2_1 = Number(kol_off);
								par3_1 = Number(kol_off);
								par4_1 = Number(kol_off);
								par5_1 = Number(kol_off);
								par6_1 = Number(kol_off);
								par7_1 = Number(kol_off);
				
						}
			
			
				
				
				
			if (qflag)	{
					if (par2 == 0){
						par2 = ko2;
					}
					if (par3 == 0){
						par3 = ko3;
					}
					if (par4 == 0){
						par4 = ko4;
					}
					if (par5 == 0){
						par5 = ko5;
					}
					if (par6 == 0){
						par6 = ko6;
					}
					if (par7 == 0){
						par7 = ko7;
					}
				console.log("Кол-во листво " + ko1 + " = " + par1 + ' | ' + ko2 + " = " + par2 + ' | ' + ko3 + " = " + par3 + ' | ' + ko4 + " = " + par4 + ' | ' + ko5 + " = " + par5 + ' | ' + ko6 + " = " + par6 + ' | ' + ko7 + " = " + par7)
				//console.log(" par2 " + par2 + " strMat " + strMat + " strOper " + strOper + "sum " + ((Number(strMat) + Number(strOper)) * Number(par2)))
		//alert(" par3 " + par3 + " strMat " + strMat + " strOper " + strOper + "sum " + ((Number(strMat) + Number(strOper)) * Number(par3)))
				if (rflag){	
					str_full =  str_full  +  String(opera) + '|0,0;' + String(par12) + ','  + ko2 + ';' + String(par13) + ','  + ko3 + ';' + String(par14) + ','  + ko4 + ';' + String(par15) + ','  + ko5 + ';' + String(par16) + ','  + ko6 + ';' + String(par17) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off + "|" + id_stamp + "|" + String(str10_value) + "!"; 
				}
				else {
				str_full =  str_full  +  String(opera) + '|0,0;' + String(par2) + ','  + ko2 + ';' + String(par3) + ','  + ko3 + ';' + String(par4) + ','  + ko4 + ';' + String(par5) + ','  + ko5 + ';' + String(par6) + ','  + ko6 + ';' + String(par7) + ','  + ko7 + '|' + mater + "|" + eq + "|" + str_off +  "|" + id_stamp + "|" + String(str10_value) + "!"; 
				}
				console.log("Стоимость операции + Стоимость материала * тираж!" )
				console.log("Стоимость операции: " + strOper + "! Стоимость материала: " + strMat)
			
				console.log("Стоимость  " + ko1 + " = " + ((Number(strMat) + Number(strOper)) * Number(par1)) + ' | ' 
								+ ko2 + " = " + ((Number(strMat) + Number(strOper)) * Number(par2)) + ' | ' 
								+ ko3 + " = " + ((Number(strMat) + Number(strOper)) * Number(par3)) + ' | ' 
								+ ko4 + " = " + ((Number(strMat) + Number(strOper)) * Number(par4)) + ' | ' 
								+ ko5 + " = " + ((Number(strMat) + Number(strOper)) * Number(par5)) + ' | ' 
								+ ko6 + " = " + ((Number(strMat) + Number(strOper)) * Number(par6)) + ' | ' 
								+ ko7 + " = " + ((Number(strMat) + Number(strOper)) * Number(par7)))
				sum1_all = Number(sum1_all) + ((Number(strMat) + Number(strOper)) * Number(par1));
				sum2_all = Number(sum2_all) + ((Number(strMat) + Number(strOper)) * Number(par2));
				sum3_all = Number(sum3_all) + ((Number(strMat) + Number(strOper)) * Number(par3));
				sum4_all = Number(sum4_all) + ((Number(strMat) + Number(strOper)) * Number(par4));
				sum5_all = Number(sum5_all) + ((Number(strMat) + Number(strOper)) * Number(par5));
				sum6_all = Number(sum6_all) + ((Number(strMat) + Number(strOper)) * Number(par6));
				sum7_all = Number(sum7_all) + ((Number(strMat) + Number(strOper)) * Number(par7));
				}

			qflag = true;
			rflag = false;	
			}
			qflag = true;
			rflag = false;

			
			}	
			

		/*	alert("summMR " + summMR)
			alert("clients_nadbavka " + clients_nadbavka)
				alert("kurs " + kurs)
				alert("sum2_all " + sum2_all)
				alert("sum_nadbavka " + (((Number(sum2_all))/ 100) *  Number(clients_nadbavka)))
				alert("sum_ " + (Number(summMR) + Number(sum2_all) + (((Number(sum2_all))/ 100) *  Number(clients_nadbavka))))
				*/
				
				
			console.log("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<END>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>")
			console.log("Стоимость приладки " + summMR  + " Надбавка клиента " +  clients_nadbavka + " КУРС " + kurs + " НДС " + nds + " НАЦЕНКА ФИРМЫ " + firm_nacenka)
			console.log("sum1_all " + sum1_all  + " sum2_all " + sum2_all  + " sum3_all " + sum3_all  + " sum4_all " + sum4_all  + " sum5_all " + sum5_all  + " sum6_all " + sum6_all  + " sum7_all " + sum7_all)
			sum1_all = Number(summMR) + Number(sum1_all) 
			sum2_all = Number(summMR) + Number(sum2_all) 
			sum3_all = Number(summMR) + Number(sum3_all)
			sum4_all = Number(summMR) + Number(sum4_all) 
			sum5_all = Number(summMR) + Number(sum5_all)
			sum6_all = Number(summMR) + Number(sum6_all)
			sum7_all = Number(summMR) + Number(sum7_all)
			
			
			sum1_all = (Number(sum1_all) + (((Number(sum1_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum1_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum2_all = (Number(sum2_all) + (((Number(sum2_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum2_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum3_all = (Number(sum3_all) + (((Number(sum3_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum3_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum4_all = (Number(sum4_all) + (((Number(sum4_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum4_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum5_all = (Number(sum5_all) + (((Number(sum5_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum5_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum6_all = (Number(sum6_all) + (((Number(sum6_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum6_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			sum7_all = (Number(sum7_all) + (((Number(sum7_all))/ 100) *  Number(clients_nadbavka)) + (((Number(sum7_all))/ 100) *  Number(firm_nacenka))) * Number(kurs);
			
			sum1_all = Number(sum1_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100) + Number(sumSpam) ;
			sum2_all = Number(sum2_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			sum3_all = Number(sum3_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			sum4_all = Number(sum4_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			sum5_all = Number(sum5_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			sum6_all = Number(sum6_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			sum7_all = Number(sum7_all) + ((Number(offpr)/ (Number(nds) + 100)) * 100)  + Number(sumSpam);
			
			
			sum1_all = Number(sum1_all) + ((Number(sum1_all) /100) * Number(nds));
			sum2_all = Number(sum2_all) + ((Number(sum2_all) /100) * Number(nds));
			sum3_all = Number(sum3_all) + ((Number(sum3_all) /100) * Number(nds));
			sum4_all = Number(sum4_all) + ((Number(sum4_all) /100) * Number(nds));
			sum5_all = Number(sum5_all) + ((Number(sum5_all) /100) * Number(nds));
			sum6_all = Number(sum6_all) + ((Number(sum6_all) /100) * Number(nds));
			sum7_all = Number(sum7_all) + ((Number(sum7_all) /100) * Number(nds)); 
			
			sum1 = (Number(sum1_all)/Number(ko1));
			sum2 = (Number(sum2_all)/Number(ko2));
			sum3 = (Number(sum3_all)/Number(ko3));
			sum4 = (Number(sum4_all)/Number(ko4));
			sum5 = (Number(sum5_all)/Number(ko5));
			sum6 = (Number(sum6_all)/Number(ko6));
			sum7 = (Number(sum7_all)/Number(ko7));
			
			sum1 = sum1.toFixed(2);
			sum2 = sum2.toFixed(2);
			sum3 = sum3.toFixed(2);
			sum4 = sum4.toFixed(2);
			sum5 = sum5.toFixed(2);
			sum6 = sum6.toFixed(2);
			sum7 = sum7.toFixed(2);
				
			sum1_all = Number(sum1) * Number(ko1) ;
			sum2_all = Number(sum2) * Number(ko2) ;
			sum3_all = Number(sum3) * Number(ko3) ;
			sum4_all = Number(sum4) * Number(ko4) ;
			sum5_all = Number(sum5) * Number(ko5) ;
			sum6_all = Number(sum6) * Number(ko6) ;
			sum7_all = Number(sum7) * Number(ko7) ;
			
			sum1_all = sum1_all.toFixed(2);
			sum2_all = sum2_all.toFixed(2);
			sum3_all = sum3_all.toFixed(2);
			sum4_all = sum4_all.toFixed(2);
			sum5_all = sum5_all.toFixed(2);
			sum6_all = sum6_all.toFixed(2);
			sum7_all = sum7_all.toFixed(2);
			console.log("Стоимость за ед. " + ko1 + " = " + sum1 + ' | ' + ko2 + " = " + sum2 + ' | ' + ko3 + " = " + sum3 + ' | ' + ko4 + " = " + sum4 + ' | ' + ko5 + " = " + sum5 + ' | ' + ko6 + " = " + sum6 + ' | ' + ko7 + " = " + sum7)
			console.log("Стоимость за тираж " + ko1 + " = " + sum1_all + ' | ' + ko2 + " = " + sum2_all + ' | ' + ko3 + " = " + sum3_all + ' | ' + ko4 + " = " + sum4_all + ' | ' + ko5 + " = " + sum5_all + ' | ' + ko6 + " = " + sum6_all + ' | ' + ko7 + " = " + sum7_all)	
			
			if(ko1 == 0 ){
				sum1 = 0;
				sum1_all = 0;
			}
			
			if(ko2 == 0 ){
				sum2 = 0;
				sum2_all = 0;
			}
			
			
			if(ko3 == 0 ){
				sum3 = 0;
				sum3_all = 0;
			}
			if(ko4 == 0 ){
				sum4 = 0;
				sum4_all = 0;
			}
			
			if(ko5 == 0 ){
				sum5 = 0;
				sum5_all = 0;
			}
			
			
			if(ko6 == 0 ){
				sum6 = 0;
				sum6_all = 0;
			}
			
			if(ko7 == 0 ){
				sum7 = 0;
				sum7_all = 0;
			}
			
			/*	str_value = str_value.substring(0, str_value.length-3);*/
			str_value = str_value.substring(0, str_value.length-1);
				str_full = str_full.substring(0, str_full.length-1);
			var theElement = document.getElementById("raschet");
			var srt1 ="<div class='row'><div class='col-md-2'><div class='block1'><label>Цена за ед. : </label></div></div><div class='col-md-1'><div class='block1'>	<input  type = 'hidden'  id = 'par2' name = 'price1' size='10' value = '" + String(sum1) + "'><input type='text' size='5' id='price1' name='price' value = '" + String(sum1) + "' disabled ></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price2' value = '" + String(sum2) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price3' value = '" + String(sum3) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price4' value = '" + String(sum4) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price5' value = '" + String(sum5) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price6' value = '" + String(sum6) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price7' value = '" + String(sum7) + "'></div></div><div class='col-md-2'><div class='block1'>руб. с НДС </div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Цена за тираж : </label></div></div><div class='col-md-1'><div class='block1'><input  type = 'hidden'  id = 'par3' name = 'price_1' size='10' value = '" + String(sum1_all) + "'><input type='text' id='price_1' name='sum' size='5'  value = '" + String(sum1_all) + "' disabled ></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price_2' value = '" + String(sum2_all) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' id='price_3' size='5' value = '" + String(sum3_all) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price_4' value = '" + String(sum4_all) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price_5' value = '" + String(sum5_all) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price_6' value = '" + String(sum6_all) + "'></div></div><div class='col-md-1'><div class='block1'><input type='text' size='5' id='price_7' value = '" + String(sum7_all) + "'></div></div><div class='col-md-2'><div class='block1'>руб. с НДС </div></div><div class='col-md-3'><div class='block1'><input type='hidden' value='" + String(str_value) + "' name='Template' id = 'Template'><input type='hidden' value='" + String(str_full) + "' name='Template1' id ='Template1' ><input type='hidden'  size='5' value='" + String(ko1) + "' name='kol'><input type='hidden' value='"+ String(orderProd) +"' name='orderProd' id='orderProd'><input type='hidden' value='"+ String(orderAcct) +"' name='orderAcct' id ='orderAcct' ></div></div></div>";
			if(activ_flag == "1" ) { srt1 = srt1  + "<div class='row'><div class='col-md-2'><div class='block1'><input type='button' onclick='validate_form(1)' value='Создать' /></div></div></div>"}; 
			theElement.innerHTML = srt1;
			
			
			if(l_hidden){
				document.getElementById("kol2").style.display = 'none';
				document.getElementById("kol3").style.display = 'none';
				document.getElementById("kol4").style.display = 'none';
				document.getElementById("kol5").style.display = 'none';
				document.getElementById("kol6").style.display = 'none';
				document.getElementById("kol7").style.display = 'none';
				
				document.getElementById("price2").style.display = 'none';
				document.getElementById("price3").style.display = 'none';
				document.getElementById("price4").style.display = 'none';
				document.getElementById("price5").style.display = 'none';
				document.getElementById("price6").style.display = 'none';
				document.getElementById("price7").style.display = 'none';
				
				document.getElementById("price_2").style.display = 'none';
				document.getElementById("price_3").style.display = 'none';
				document.getElementById("price_4").style.display = 'none';
				document.getElementById("price_5").style.display = 'none';
				document.getElementById("price_6").style.display = 'none';
				document.getElementById("price_7").style.display = 'none';
			} else{
				document.getElementById("kol2").style.display = 'block';
				document.getElementById("kol3").style.display = 'block';
				document.getElementById("kol4").style.display = 'block';
				document.getElementById("kol5").style.display = 'block';
				document.getElementById("kol6").style.display = 'block';
				document.getElementById("kol7").style.display = 'block';
				
				document.getElementById("price2").style.display = 'block';
				document.getElementById("price3").style.display = 'block';
				document.getElementById("price4").style.display = 'block';
				document.getElementById("price5").style.display = 'block';
				document.getElementById("price6").style.display = 'block';
				document.getElementById("price7").style.display = 'block';
				
				document.getElementById("price_2").style.display = 'block';
				document.getElementById("price_3").style.display = 'block';
				document.getElementById("price_4").style.display = 'block';
				document.getElementById("price_5").style.display = 'block';
				document.getElementById("price_6").style.display = 'block';
				document.getElementById("price_7").style.display = 'block';
			}
			
			
			/*var theElement = document.getElementById("raschet2");
			var srt2 = "<a href='#' onclick='downloadCSV({ filename: 'stock-data.csv' });'>Download CSV</a>";
				theElement.innerHTML = srt2;*/

}

