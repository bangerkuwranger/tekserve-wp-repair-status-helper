var $j = jQuery;
$j('button.positive').click(function () {
    var img_base_path = tekRepairStatusUris.themeUri + '/images/step';
    var repair_status = new Array('Created', 'In Progress', 'Testing', 'On Hold', 'Done', 'Ready for Pickup');
    var sro1 = $j('#sro1').val();
    var sro2 = $j('#sro2').val();
    var sro3 = $j('#sro3').val();
    var zip = $j('#zip').val();
    var sro_zip = 'Invoice #: ' + sro1 + '-' + sro2 + '-' + sro3 + '<br />' + 'Billing Zip Code: ' + zip;
   	var $loader = '<div id="loader" ><img src="' + tekRepairStatusUris.themeUri + '/images/ajax-loader.gif" /></div>';
   	if ( !$j('#sro1').val() || !$j('#sro2').val() || !$j('#sro3').val() || !$j('#zip').val() ) {
   		$j('form.status-front').addClass('errorForm');
   	}
   	else {
   		$j('form.status-front').removeClass('errorForm');
		$j.ajax({
			type: 'GET',
			dataType: 'jsonp',
			url: 'http://www.tekserve.com/wp-content/plugins/tekserve-repair-status-helper/ajaxp.php/?sro=' + sro1 + sro2 + sro3 + '&zip=' + zip,
			beforeSend: function() {
				$j('#status-content').children('p').add('form.status-front').hide();
			
				$j('#status-content').append($loader);
			},
			success: function (msg) {
				if (msg == false || msg == null || msg.typeOf == 'string' ) {
					$j('#loader').hide();
					$j('#status-title').find('strong').html('Login Failed');
					$j('#fail-msg').show();
					return;
				}
				var product_name = 'Product: ' + msg.product;
				$j('#status-title').find('strong').html('Repair Status');
				$j('form.status-front').hide();
				$j('#loader').hide();
				$j('#customer-info').show();
				$j('.customer-info').show();
				$j('#customer-info div').html('Name: ' + msg.name + '<br/>' + sro_zip);
				console.log(sro_zip);
				var product_name = '<h3>Product</h3><div>' + msg.product + '</div>';
				console.log(product_name);
				$j('#product-info').html(product_name);
				var result = msg.status;
				switch(result)
				{
				case 'Created':
					break;
				case 'In Progress':
					break;
				case 'INTAKE':
					result = 'In Progress';
					break;
				case 'REPAIR':
					result = 'In Progress';
					break;
				case 'hold\/internal':
					result = 'In Progress';
					break;
				case 'hold\/complete payment':
					result = 'On Hold';
					break;
				case 'hold\/info':
					result = 'On Hold';
					break;
				case 'hold\/payment':
					result = 'On Hold';
					break;
				case 'hold\/no Email':
					result = 'On Hold';
					break;
				case 'hold\/recovery':
					result = 'On Hold';
					break;
				case 'hold\/discuss':
					result = 'On Hold';
					break;
				case 'hold\/spill':
					result = 'On Hold';
					break;
				case 'hold\/pw':
					result = 'On Hold';
					break;
				case 'WAIT':
					result = 'On Hold';
					break;
				case 'Testing':
					break;
				case 'Service Complete TOAC':
					result = 'Ready for Pickup';
					break;
				case 'Service Complete':
					result = 'Ready for Pickup';
					break;
				case 'Done':
					result = 'Done';
					break;
				default:
					result = 'On Hold';
				}
				var result_index = $j.inArray(result, repair_status);
				$j('#status-content').children('img').attr('src', img_base_path + result_index + '.svg');
				$j('#status-content').children('img').attr('alt', result);
				$j('#status-content').children('img').show();
				var detail_cmt = $j('ul.repair-details').find('li');
				$j(detail_cmt[result_index]).show();
			}
		});
	}
});

function checkLen(x, y) {
    if (y.length == x.maxLength) {
        var next = x.tabIndex;
        if (next < document.getElementById('status-front').length) {
            document.getElementById('status-front').elements[next].focus();
        }
    }
}

function showExampleSRO() {
	$j('#whats-sro').toggle();
}