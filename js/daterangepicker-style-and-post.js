$(function() {
	$('input[name="dealsReportDateFrom"]').daterangepicker({
		autoUpdateInput: true,
    		showISOWeekNumbers: true,
		singleDatePicker: true,
		showDropdowns: true,
		startDate: moment().add(1, 'days'),
		locale: {
			cancelLabel: 'Очистить',
			applyLabel: 'OK',
			format: 'DD.MM.YYYY',
			monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			monthNamesShort: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			daysOfWeek: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
			firstDay: 1,
			weekLabel: '№'

		}
	});
	$('input[name="dealsReportDateTo"]').daterangepicker({
		autoUpdateInput: true,
    		showISOWeekNumbers: true,
		singleDatePicker: true,
		showDropdowns: true,
		startDate: moment().add(2, 'days'),
		locale: {
			cancelLabel: 'Очистить',
			applyLabel: 'OK',
			format: 'DD.MM.YYYY',
			monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			monthNamesShort: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
			daysOfWeek: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
			firstDay: 1,
			weekLabel: '№'

		}
	});
});

