<?php
class ControllerShippingBoxnow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/boxnow');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] 	= $this->language->get('heading_title');
		$data['text_edit'] 		= $this->language->get('text_edit');
		
		$data['heading_title_report'] 		= $this->language->get('heading_title_report');
		$data['heading_help'] 		= $this->language->get('heading_help');
		$data['text_extension'] 		= $this->language->get('text_extension');
		$data['text_success'] 		= $this->language->get('text_success');
		$data['entry_cost'] 		= $this->language->get('entry_cost');
		$data['entry_tax_class'] 		= $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] 		= $this->language->get('entry_geo_zone');
		$data['entry_status'] 		= $this->language->get('entry_status');
		$data['entry_sort_order'] 		= $this->language->get('entry_sort_order');
		$data['entry_payment_modules'] 		= $this->language->get('entry_payment_modules');
		$data['entry_partner_id'] = $this->language->get('entry_partner_id');
		$data['entry_free_shipping'] = $this->language->get('entry_free_shipping');
		$data['help_payment_modules'] 		= $this->language->get('help_payment_modules');
		$data['entry_api_url'] 		= $this->language->get('entry_api_url');
		$data['entry_client_id'] 		= $this->language->get('entry_client_id');
		$data['entry_client_secret'] 		= $this->language->get('entry_client_secret');
		$data['entry_warehouse_number'] 		= $this->language->get('entry_warehouse_number');
		$data['column_box_now_status'] 		= $this->language->get('column_box_now_status');
		$data['column_order_status'] 		= $this->language->get('column_order_status');
		$data['column_vouchers'] 		= $this->language->get('column_vouchers');
		$data['column_info'] 		= $this->language->get('column_info');
		$data['column_locker_id'] 		= $this->language->get('column_locker_id');
		$data['column_total_products'] 		= $this->language->get('column_total_products');
		$data['text_voucher_send_instructioms'] 		= $this->language->get('text_voucher_send_instructioms');
		$data['text_voucher_success'] 		= $this->language->get('text_voucher_success');
		$data['text_voucher_notfound'] 		= $this->language->get('text_voucher_notfound');
		$data['text_voucher_pending'] 		= $this->language->get('text_voucher_pending');
		$data['text_voucher_send'] 		= $this->language->get('text_voucher_send');
		$data['text_voucher_status_success'] 		= $this->language->get('text_voucher_status_success');
		$data['status_message_error'] 		= $this->language->get('status_message_error');
		$data['error_permission'] 		= $this->language->get('error_permission');
		$data['text_enabled'] 		= $this->language->get('text_enabled');
		$data['text_disabled'] 		= $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boxnow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/boxnow', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('shipping/boxnow', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		if (isset($this->request->post['boxnow_api_url'])) {
			$data['boxnow_api_url'] = $this->request->post['boxnow_api_url'];
		} else {
			$data['boxnow_api_url'] = $this->config->get('boxnow_api_url');
		}
		
		if (isset($this->request->post['boxnow_client_id'])) {
			$data['boxnow_client_id'] = $this->request->post['boxnow_client_id'];
		} else {
			$data['boxnow_client_id'] = $this->config->get('boxnow_client_id');
		}		
		
		if (isset($this->request->post['boxnow_client_secret'])) {
			$data['boxnow_client_secret'] = $this->request->post['boxnow_client_secret'];
		} else {
			$data['boxnow_client_secret'] = $this->config->get('boxnow_client_secret');
		}
		
		if (isset($this->request->post['boxnow_warehouse_number'])) {
			$data['boxnow_warehouse_number'] = $this->request->post['boxnow_warehouse_number'];
		} else {
			$data['boxnow_warehouse_number'] = $this->config->get('boxnow_warehouse_number');
		}
		
		if (isset($this->request->post['boxnow_partner_id'])) {
			$data['boxnow_partner_id'] = $this->request->post['boxnow_partner_id'];
		} else {
			$data['boxnow_partner_id'] = $this->config->get('boxnow_partner_id');
		}

		if (isset($this->request->post['boxnow_cost'])) {
			$data['boxnow_cost'] = $this->request->post['boxnow_cost'];
		} else {
			$data['boxnow_cost'] = $this->config->get('boxnow_cost');
		}
		
		if (isset($this->request->post['boxnow_free_shipping'])) {
			$data['boxnow_free_shipping'] = $this->request->post['boxnow_free_shipping'];
		} else {
			$data['boxnow_free_shipping'] = $this->config->get('boxnow_free_shipping');
		}

		if (isset($this->request->post['boxnow_tax_class_id'])) {
			$data['boxnow_tax_class_id'] = $this->request->post['boxnow_tax_class_id'];
		} else {
			$data['boxnow_tax_class_id'] = $this->config->get('boxnow_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['boxnow_geo_zone_id'])) {
			$data['boxnow_geo_zone_id'] = $this->request->post['boxnow_geo_zone_id'];
		} else {
			$data['boxnow_geo_zone_id'] = $this->config->get('boxnow_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['boxnow_status'])) {
			$data['boxnow_status'] = $this->request->post['boxnow_status'];
		} else {
			$data['boxnow_status'] = $this->config->get('boxnow_status');
		}

		if (isset($this->request->post['boxnow_sort_order'])) {
			$data['boxnow_sort_order'] = $this->request->post['boxnow_sort_order'];
		} else {
			$data['boxnow_sort_order'] = $this->config->get('boxnow_sort_order');
		}
		
		$data['boxnow_payment_modules'] = array();
		if (isset($this->request->post['boxnow_payment_modules'])) {
			$data['boxnow_payment_modules'] = $this->request->post['boxnow_payment_modules'];
		} else {
			$data['boxnow_payment_modules'] = $this->config->get('boxnow_payment_modules');
		}
		
		
		$this->load->model('extension/extension');
		
		// Payment
		$files = glob(DIR_APPLICATION . 'controller/{extension/payment,payment}/*.php', GLOB_BRACE);
		
		$data['payment_modules'] = array();
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('payment/' . $extension);

				$text_link = $this->language->get('text_' . $extension);

				if ($text_link != 'text_' . $extension) {
					$link = $this->language->get('text_' . $extension);
				} else {
					$link = '';
				}
				
				if ($this->config->get($extension . '_status')) {
					$data['payment_modules'][] = array(
						'name'		=> $this->language->get('heading_title'),
						'code'		=> $extension
					);
				};
			}
		}		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/boxnow.tpl', $data));
	}
	
	public function report() {
		
		$this->load->language('shipping/boxnow');
		$this->load->language('sale/order');
		
		$data['heading_title_report'] 		= $this->language->get('heading_title_report');
		$data['heading_help'] 		= $this->language->get('heading_help');
		$data['text_extension'] 		= $this->language->get('text_extension');
		$data['text_success'] 		= $this->language->get('text_success');
		$data['entry_cost'] 		= $this->language->get('entry_cost');
		$data['entry_tax_class'] 		= $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] 		= $this->language->get('entry_geo_zone');
		$data['entry_status'] 		= $this->language->get('entry_status');
		$data['entry_sort_order'] 		= $this->language->get('entry_sort_order');
		$data['entry_payment_modules'] 		= $this->language->get('entry_payment_modules');
		$data['help_payment_modules'] 		= $this->language->get('help_payment_modules');
		$data['entry_partner_id'] = $this->language->get('entry_partner_id');
		$data['entry_free_shipping'] = $this->language->get('entry_free_shipping');
		$data['entry_api_url'] 		= $this->language->get('entry_api_url');
		$data['entry_client_id'] 		= $this->language->get('entry_client_id');
		$data['entry_client_secret'] 		= $this->language->get('entry_client_secret');
		$data['entry_warehouse_number'] 		= $this->language->get('entry_warehouse_number');
		$data['column_box_now_status'] 		= $this->language->get('column_box_now_status');
		$data['column_order_status'] 		= $this->language->get('column_order_status');
		$data['column_vouchers'] 		= $this->language->get('column_vouchers');
		$data['column_info'] 		= $this->language->get('column_info');
		$data['column_locker_id'] 		= $this->language->get('column_locker_id');
		$data['column_order_id'] 		= $this->language->get('column_order_id');
		$data['column_customer'] 		= $this->language->get('column_customer');
		$data['column_date_added'] 		= $this->language->get('column_date_added');
		$data['column_total'] 		= $this->language->get('column_total');
		$data['column_total_products'] 		= $this->language->get('column_total_products');
		$data['text_voucher_send_instructioms'] 		= $this->language->get('text_voucher_send_instructioms');
		$data['text_voucher_success'] 		= $this->language->get('text_voucher_success');
		$data['text_voucher_notfound'] 		= $this->language->get('text_voucher_notfound');
		$data['text_voucher_pending'] 		= $this->language->get('text_voucher_pending');
		$data['text_voucher_send'] 		= $this->language->get('text_voucher_send');
		$data['text_voucher_status_success'] 		= $this->language->get('text_voucher_status_success');
		$data['status_message_error'] 		= $this->language->get('status_message_error');
		$data['error_permission'] 		= $this->language->get('error_permission');
		$data['text_enabled'] 		= $this->language->get('text_enabled');
		$data['text_disabled'] 		= $this->language->get('text_disabled');
		$data['text_no_results'] 		= $this->language->get('text_no_results');

		
		$this->load->model('shipping/boxnow');
		$this->load->model('sale/order');

		$this->document->setTitle($this->language->get('heading_title_report'));

		$this->load->model('setting/setting');
		
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}		
		
		$data['orders'] = array();

		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_report'),
			'href' => $this->url->link('shipping/boxnow/report', 'token=' . $this->session->data['token'] . $url, true)
		);

		$order_total = $this->model_shipping_boxnow->getBoxNowTotalOrders($filter_data);

		$results = $this->model_shipping_boxnow->getBoxNowOrders($filter_data);

		foreach ($results as $result) {
			
			$boxnow_info = $this->model_shipping_boxnow->getBoxNowStatus($result['order_id']);
			
			if($boxnow_info) {
				$boxnow_request_id 		= $boxnow_info['request_id'];
				$boxnow_parcels 		= json_decode($boxnow_info['parcels'],TRUE);
				$boxnow_status_message	= $boxnow_info['status_message'];
				$boxnow_locker_id		= $boxnow_info['locker_id'];
				$boxnow_status			= $boxnow_info['status'];
			} else {
				$boxnow_request_id 		= '';
				$boxnow_parcels 		= '';
				$boxnow_status_message	= '';
				$boxnow_locker_id		= '';
				$boxnow_status			= '';
			}
			
			$order_total_products 	= 0;
			$order_products 		= $this->model_sale_order->getOrderProducts($result['order_id']);
			
			foreach($order_products as $order_product) {
				$order_total_products += $order_product['quantity'];
			}
			
			$data['orders'][] = array(
				'order_id'      		=> $result['order_id'],
				'customer'      		=> $result['customer'],
				'order_status'  		=> $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         		=> $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' 		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' 		=> $result['shipping_code'],
				'products' 				=> $order_total_products,
				'boxnow_request_id' 	=> $boxnow_request_id,
				'boxnow_parcels' 		=> $boxnow_parcels,

				'boxnow_locker_id' 		=> $boxnow_locker_id,
				'boxnow_status' 		=> $boxnow_status,
				'boxnow_status_message'	=> $boxnow_status_message,
				'boxnow_submit' 		=>  $this->url->link('shipping/boxnow/deliveryRequests', 'token=' . $this->session->data['token'].'&order_id='.$result['order_id'], true),
				'view'          		=> $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].'&quantity=1'. $url, true)
			);
		}
		
		$warehouse_number = $this->config->get('boxnow_warehouse_number');
		$warehouse_number = array_filter(array_map('trim', explode(PHP_EOL, $warehouse_number)));
		$warehouse_number_array = [];
		foreach($warehouse_number as $row) {
			$parts = array_map('trim', explode(':', $row));
			$warehouse_number_array[$parts[0]] = isset($parts[1]) ? $parts[1] : 'Warehouse #'.$parts[0];
		}
		$data['warehouse_number'] = $warehouse_number_array;

		$data['partner_id'] = $this->config->get('boxnow_partner_id');
		
		$url = '';
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('shipping/boxnow/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('shipping/boxnow_list.tpl', $data));
	}
	
	public function deliveryRequests() {
		if (isset($this->request->get['order_id']) && $this->request->get['order_id']) {
			
			$this->load->language('shipping/boxnow');
			$this->load->model('sale/order');
			$this->load->model('shipping/boxnow');
			
			$order 			= $this->model_sale_order->getOrder( $this->request->get['order_id'] );
			$boxnow_data 	= $this->model_shipping_boxnow->getBoxNowStatus( $this->request->get['order_id'] );
			
			$quantity = 1;
			
			if( isset($this->request->get['quantity']) && $this->request->get['quantity'] > 1 ) {
				$quantity = $this->request->get['quantity'];
			};
			
			$locker_id =$this->request->get['locker_id'];
			if (!$locker_id) $locker_id = $boxnow_data['locker_id'];
			$warehouse_number = $this->request->get['warehouse_number'];
			if (!$warehouse_number) {
				$warehouse_number = $this->config->get('boxnow_warehouse_number');
				$warehouse_number = array_filter(array_map('trim', explode(PHP_EOL, $warehouse_number)));
				$warehouse_number_array = [];
				foreach($warehouse_number as $row) {
					$parts = array_map('trim', explode(':', $row));
					$warehouse_number_array[$parts[0]] = isset($parts[1]) ? $parts[1] : 'Warehouse #'.$parts[0];
				}
				$warehouse_number = reset(array_keys($warehouse_number_array));
			}
			
			$curl = curl_init();		
					
			curl_setopt_array($curl, array(
				CURLOPT_URL => $this->config->get('boxnow_api_url').'/api/v1/auth-sessions',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
					"grant_type": "client_credentials",
					"client_id": "'.$this->config->get('boxnow_client_id').'",
					"client_secret": "'.$this->config->get('boxnow_client_secret').'"
				}',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
			));
			
			// Get API response
			$response = curl_exec($curl);

			curl_close($curl);

			// Decode API response
			$json = json_decode($response, true);

			// Initiate Delivery Request CURL
			$post = curl_init();

			// Pass bearer token
			$authorization = "Authorization: Bearer " . $json['access_token'];
			
			// Set the Number of Vouchers relative to the quantity selected by user
			$items = array();
			$x = 1;
			while($x <= $quantity) {
			  $items[] = array(
				"value"				=> number_format(0, 2, '.', ''),
				"compartmentSize" 	=> 3
			  );
			  $x++;
			}	
			
            $phone = $order['telephone'];
            
            $re = '/^(?:\+?359|0)?/m';
            $str = $phone;
            $subst = '+359';
        
            $phone_box = preg_replace($re, $subst, $str);
			
			$cod = false;
			if ($order['payment_code'] == 'cod') $cod = true;

			// Create a JSON with all necessary fields
			$data = array(
				"orderNumber" 			=> $order['order_id'],
				"invoiceValue" 			=> number_format($order['total'], 2, '.', ''),
				"paymentMode" 			=> $cod ? "cod" : "prepaid",
				"amountToBeCollected" 	=> number_format($order['total'], 2, '.', ''),
				"allowReturn" 			=> true,
				"origin" 				=> array (
					"contactEmail" 	=> $this->config->get('config_email'),
					"locationId" 	=> $warehouse_number,
				),
				"destination" 	=> array (
					"contactNumber" => $phone_box,
					"contactEmail" 	=> $order['email'],
					"contactName" 	=> $order['shipping_firstname'].' '.$order['shipping_lastname'],
					"locationId" 	=> $locker_id,
				),
				"items" => $items
			);

			// Create JSON
		
			$data_json = json_encode($data);
			 
			// Prepare CURL for delivery request
			curl_setopt_array($post, array(
				CURLOPT_URL => $this->config->get('boxnow_api_url').'/api/v1/delivery-requests',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					$authorization,
					"Content-Type: application/json",
					"Content-Length:" . strlen($data_json)
				),
				CURLOPT_POSTFIELDS => $data_json
			));

			//
			$response = curl_exec($post);

			curl_close($post);
			
			if(isset($response) && $response) {
				$response_data = JSON_DECODE($response,TRUE);
				if( !isset($response_data['status']) && isset($response_data['id']) ) {
					$response_data['status_id'] 			= 1;
					$response_data['status_message'] 		= NULL;
					$response_delivery = $this->model_shipping_boxnow->updateRequest($order, $response_data);
					$this->session->data['success'] = $this->language->get('text_voucher_status_success');
				} else {
				
					$error_codes = [
						'P400' => 'Заявка с грешни данни. Информацията изпратена от Вас не може да бъде валидирана.',
						'P401' => 'Заявка с грешна начална точка на пратката. Уверете се, че ползвате валиден ID на локацията, от която BOX NOW ще вземе пратката.',
						'P402' => 'Невалидна крайна дестинация! Уверете се, че използвате правилното ID на локацията (Номер на BOX NOW автомат) и че подадените данни са коректни.',
						'P403' => 'Не Ви е позволено да ползвате доставки от типа (Всеки Автомат към същия автомат) AnyAPM - SameAPM. Обърнете се към техническата поддръжка, ако считате, че това е невярно',
						'P404' => 'Невалиден CSV импорт. Вижте съдържанието на грешката за повече информация.',
						'P405' => 'Невалиден телефонен номер. Проверете дали изпращате телефон в подходящ интернационален формат, пример: +359 xx xxx xxxx.',
						'P406' => 'Невалиден размер. Уверете се, че в заявката си изпращате някой от необходимите размери 1, 2 или 3 (Малък, Среден или Голям). Размерът е задължителна опция, особено когато изпращате от дадена машина директно.',
						'P407' => 'Невалиден код за държавата. Уверете се, че изпращате коректен код за държава във формат по ISO 3166-1alpha-2. Пример: BG',
						'P408' => 'Невалидна стойност на поръчката – сума, която да бъде събрана по наложения платеж. Уверете се, че Вашата поръчка е в допустимите граници, между 0 и 5000',
						'P409' => 'Невалидна референция на партньора. Уверете се, че реферирате валидно ID на партньор.',
						'P410' => 'Конфликт в номера на поръчката. Опитвате се да направите заявка за доставка за ID на поръчката, което е било използвано и/или сте генерирали товарителница за тази поръчка вече. Моля използвайте друго ID за поръчката.',
						'P411' => 'Вие не можете да използвате „наложен платеж“ като тип за плащане. Използвайте друг тип плащане или се свържете с нашият екип по поддръжка.',
						'P412' => 'Вие не можете да създадете заявка за връщане на пратка. Свържете се с нашата поддръжка, ако считате това за невярно.',
						'P420' => 'Не е възможно отказването на пратката. Типа пратки, които можете да откажете са от тип: „new“, „undelivered“. Пратки, които не можете да откажете са в състояние „returned“ или „lost“. Уверете се, че пратката е в процес по доставка и опитайте отново. ',
						'P430' => 'Тази пратка не е в готовност за AnyAPM потвърждение. Най-вероятно пратката е потвърдена за доставка. Обърнете се към поддръжката, ако считате това за невярно.',
					];
				
					$response_data['id'] 				= 0;
					$response_data['status_id'] 		= 2;
					$response_data['parcels'] 			= array();
					$response_data['status_message'] 	= sprintf('Voucher was not created (Error Code: %s). '.(!empty($error_codes[$response_data['code']]) ? $error_codes[$response_data['code']] : 'You can refer to the relevant link for <a href="https://boxnow.gr/docs/api/partner-api/troubleshooting/" target="_blank">help</a> or contact us at <a href="mailto:info@boxnow.gr">info@boxnow.gr</a>'), $response_data['code']);
					$response_delivery = $this->model_shipping_boxnow->updateRequest($order, $response_data);
					$this->session->data['error'] 	= $response_data['status_message'];
				};
			};
	
			$this->response->redirect($this->url->link('shipping/boxnow/report', 'token=' . $this->session->data['token'], true));
		}	
	}
	
	public function getParcel() {
		
		$curl = curl_init();		
				
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->config->get('boxnow_api_url').'/api/v1/auth-sessions',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"grant_type": "client_credentials",
				"client_id": "'.$this->config->get('boxnow_client_id').'",
				"client_secret": "'.$this->config->get('boxnow_client_secret').'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		// Get API response
		$response = curl_exec($curl);
		
		curl_close($curl);

		// Decode API response
		$json = json_decode($response, true);

		// Initiate Delivery Request CURL
		$post = curl_init();

		// Pass bearer token
		$authorization = "Authorization: Bearer " . $json['access_token'];
		
		$parcel_id = '';
		if(isset($this->request->get['parcel_id']) && $this->request->get['parcel_id'] ) {
			$parcel_id = $this->request->get['parcel_id'];
		};
		
		header("Content-type:application/pdf");
		header("Content-Disposition:attachment;filename=".$parcel_id.".pdf");			

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->config->get('boxnow_api_url').'/api/v1/parcels/'.$parcel_id.'/label.pdf',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				$authorization,
				"Content-Type: application/pdf"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}	
	
	public function install() {
		$this->load->model('shipping/boxnow');

		$this->model_shipping_boxnow->install();
	}	
	
	public function uninstall() {
		$this->load->model('shipping/boxnow');

		$this->model_shipping_boxnow->uninstall();
	}	

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/boxnow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}