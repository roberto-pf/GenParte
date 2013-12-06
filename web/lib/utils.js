	var calendar;
	
	
	(function (){
	  window.$GET = [];
	  if(location.search){
		var params = decodeURIComponent(location.search).match(/[a-z_]\w*(?:=[^&]*)?/gi);
		if(params){
		  var pm, i = 0;
		  for(; i < params.length; i++){
			pm = params[i].split('=');
			$GET[pm[0]] = pm[1] || '';
		  }
		}
	  }
	
	})();
	
	
	function generateCalendar(days){
		var daysOfWeek = [ 'Domingo', 'Lunes', 'Martes', 'Mi\u00e9rcoles', 'Jueves', 'Viernes', 'S\u00e1bado'];
		
		calendar = $('#calendar').fullCalendar({
			
			firstDay:1,	//first day of week
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ], 
		    monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: daysOfWeek,
		    dayNamesShort: ['Dom','Lun','Mar','Mi\u00e9','Jue','Vie','S\u00e1b'],
		    buttonText: {
				today: 'hoy',
				month: 'mes',
				week: 'semana',
				day: 'dia'
		    },
			
			dayRender: function (date, cell) {
				if (date.getDay() == 0 || date.getDay() == 6 ) {
					cell.css("background-color", "#FAEBD7");
				}
				for(var i=0; i<days.length; i++)
					if($.fullCalendar.formatDate(date, 'yyyy-MM-dd') === days[i])
						cell.css('background-color', '#F08080');
			},
			
			editable: true,
			
			//event to click of day
			selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var dateString = daysOfWeek[start.getDay()] + " " + $.datepicker.formatDate('dd-mm-yy', start);
				$("#startDate").val(  $.datepicker.formatDate('yy/mm/dd', start)  );
				$("#endDate").val( $.datepicker.formatDate('yy/mm/dd', end) );
				$("#allDay").val(allDay);
				$( "#dialog" ).dialog({ title: dateString });
				$("select#activity").val("1");
				$(":input#quantity").val("Cantidad");
				//$(":input#quantity").attr("place", "Cantidad");
				$( "#dialog" ).dialog('open');
				calendar.fullCalendar('unselect');
			},
			
			//lets drop event
			droppable: true,
			drop: function(date, allDay) { 
				var originalEventObject = $(this).data('eventObject');
				var copiedEventObject = $.extend({}, originalEventObject);

				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				
				$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
				
				if ($('#drop-remove').is(':checked')) {
					$(this).remove();
				}	
			},
			
			//event click to events
			eventClick: function(event) {
				$('#calendar').fullCalendar('removeEvents',event._id);
			},
			
			
			//amarillo #FFFFE0
			//rosa #FFE4E1
			
			loading: function(bool) {
				if (bool) {
					$('#loading').show();
				}else{
					$('#loading').hide();
				}
			}
			
		});
	}
	
	
	
	function festiveDaysConsult(){	
		$.support.cors = true;
				
		var ok = false;
		   $("select#calendarFestive option").each(function(){
			if($(this).attr('value') == $GET['festive']){
				$('select#calendarFestive').val($GET['festive']);
				ok = true;
			}
		});
		if(ok){
			
			var soapMessage =
				'<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'+
					'<soap:Body>'+
						'<getCalendarFestive xmlns="http://genparte.disastercode.com.es">'+
							'<strName>'+$GET['festive']+'</strName>'+
						'</getCalendarFestive>'+
					'</soap:Body>'+
				'</soap:Envelope>';

			$.ajax({
				url: 'http://genparte.disastercode.com.es/ws/servicioWsdl.php?wsdl', 
				type: "POST",
				dataType: "xml", 
				contentType: "text/xml",
				data: soapMessage, 
				success: function(data, status, req, xml, xmlHttpRequest, responseXML) {
					$(req.responseXML).find('return').each(function(){
					
						if( $(this).find('type')){
							var total = $(this).find('type').length;
							var days = new Array();
							for(var i=0; i<total; i++){
								var strType = $(this).find('type')[i].childNodes[0].nodeValue;
								var strDate = $(this).find('date')[i].childNodes[0].nodeValue;
								days[i] = strDate;
							}
							generateCalendar(days);
						}
					});
				}, 
				error: function processError(data, status, req) {
					console.log(data);
					console.log(status);
					console.log(req);
				}
			});
		}
	}
	
	
	
	
	
	$(document).ready(function() {
		$( "#dialog" ).dialog({
			height: 190,
			width: 470,
			modal: true,
			autoOpen: false
		});

		$('#principalsDats').formly({'theme':'Dark'}, function(e) { 
			$('.callback').html(e); 
		});
		
		
		if($GET['festive']) 
			festiveDaysConsult();
		else
			generateCalendar(new Array());

	});

	
	
	function genBackColor(act){
		switch(act){
			case '1':
				return '#6495ED';//Cornflowerblue
			break;
			case '2':
				return '#D2691E';	//Chocolate
			break;
			case '3':
				return '#8B008B';	//Darkmagenta
			break;
			case '4':
				return '#2F4F4F';	 	//Darkslategray
			break;
			case '5':
				return '#006400';	//Darkgreen
			break;
			case '6':
				return '#DC143C';	//Crimson
			break;
			case '7':
				return '#FF1493';	//Deeppink
			break;
			case '8':
				return '#00FF00';	//lime
			break;
			default:
				return '#1E90FF';	//Dodgerblue
		}
	}
	
	function genBrdColor(act){
			switch(act){
			case '1':
				return '#FFFFFF';
			break;
			case '2':
				return '#FFFFFF';
			break;
			case '3':
				return '#FFFFFF';
			break;
			case '4':
				return '#FFFFFF';
			break;
			case '5':
				return '#FFFFFF';
			break;
			case '6':
				return '#FFFFFF';
			break;
			case '7':
				return '#FFFFFF';
			break;
			case '8':
				return '#FFFFFF';
			break;
			default:
				return '#FFFFFF';
		}
	}
	
	function addEvent(){
		var quantity = $(":input#quantity").val();
		var activity = $("select#activity").val();
		var backColor = genBackColor(activity);
		var brdColor = backColor;
		var txtColor = genBrdColor(activity);
		
		calendar.fullCalendar('renderEvent',
			{
				activity: activity,
				title: quantity,
				start: new Date($(":hidden#startDate").val()),
				end: new Date($(":hidden#endDate").val()),
				allDay: $(":hidden#allDay").val(),
				backgroundColor: backColor,
				borderColor: brdColor,
				textColor: txtColor
			},
			true // make the event "stick"
			
		);
		$( "#dialog" ).dialog('close');
	}
	
	function ejectFestive(){
		window.location.href = 'index.html?festive='+$("select#calendarFestive").val();
	}
	



	
	
	
	