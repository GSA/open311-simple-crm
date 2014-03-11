<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller { 

	function __construct() {

		parent::__construct();

        $this->config->load('fms_endpoint', FALSE, TRUE);
		/* Standard Libraries */
		$this->load->database();
		
		$this->load->helper('url');

		$this->load->helper('fms_endpoint');
		$this->load->library('Ion_auth');
		$this->load->library('grocery_CRUD');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
	}


	function index() {
		try{
			$crud = $this->_set_common_report_crud(array('report_id','status', 'requested_datetime','priority','category_id','external_id','media_url','description','address'));
			$output = $crud->render();
			$this->_admin_output($output);
		} catch(Exception $e) {
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function reports() {
		// explicitly list all fields (was missing out report-id)
		$crud = $this->_set_common_report_crud(array());
		$output = $crud->render();
		$this->_admin_output($output);
	}

	// show a single report (anticipate this is for printing)
	function report($id) {
		$this->db->select('reports.*,
			statuses.is_closed, statuses.status_name,
			priorities.prio_name,
			categories.category_name,
			open311_clients.name AS open311_clients_name
		');
		$this->db->from('reports');
		$this->db->join('priorities', 'reports.priority = priorities.prio_value');
		$this->db->join('categories', 'reports.category_id = categories.category_id');
		$this->db->join('statuses', 'reports.status = statuses.status_id');
		$this->db->join('open311_clients', 'reports.source_client = open311_clients.id', 'left outer');
		$this->db->where('report_id', $id);

		$query = $this->db->get();
		if ($query->num_rows()==1) {
			$image_url = $query->row()->media_url;
			if (! preg_match('/\.(gif|jpe?g|png)$/', $image_url)) {
				$image_url = false;
			}
			$this->load->vars(array(
				'report' => $query->row(), 
				'external_link' => $this->_get_external_url(null, $query->row()),
				'image_url' => $image_url));
			$output = array('output' => $this->load->view('report', '', true));
			$this->_admin_output($output);
		} else {
			show_error("Record $id not found", 404);
		}
	}

	function reports_csv() {
		$this->load->helper('csv');
		$query = $this->db->get('reports');
		$filename = date('Y-m-d_Hi') . '_reports.csv';
		query_to_csv($query, TRUE, $filename);
	}

	function categories() {
		$crud = new grocery_CRUD();

		$crud->set_theme('flexigrid');
		// $crud->set_theme('twitter-bootstrap');
		
		$crud->set_table('categories');
		$crud->unset_texteditor('description'); # maybe don't unset this one
		$crud->unset_texteditor('status_notes','keywords');
		$crud->required_fields('category_id','category_name');
		$crud->set_rules('category_id', 'Category Identifier', 'trim|alpha_numeric');

		if (!($this->ion_auth->is_admin() || is_config_true($this->config->item('can_edit_categories')))) {
			$crud->unset_delete();
			$crud->unset_add();
			$crud->unset_edit();
		} else {
			$crud->callback_edit_field('keywords', array($this,'_text_keywords_field'));
		}

		$crud->set_subject('Open311 category');
		$output = $crud->render();

		$this->_admin_output($output);
	}

	function category_attributes() {
		$crud = new grocery_CRUD();

		$crud->set_theme('flexigrid');
		$crud->set_table('category_attributes');
		//$crud->set_model('attributes_join');
		$crud->set_relation('category_id','categories','category_name',null,'category_name ASC');
		$crud->display_as ( 'category_id' , "Category");

		$crud->unset_texteditor('datatype_description','description', 'values');	

		$crud->field_type('order','integer');	
		$crud->field_type('description','string');	
		$crud->field_type('datatype_description','string');			
		
		$crud->field_type('variable','dropdown',
            array('true' => 'True', 'false' => 'False'));		
		$crud->field_type('required','dropdown',
            array('true' => 'True', 'false' => 'False'));


		$crud->field_type('datatype','dropdown',
            array(	'string' => 'Text',
            		'text' => 'Textbox', 	
            		'number' => 'Number', 
            		'datetime' => 'Date Picker',             		
            		'singlevaluelist' => 'Dropdown Menu', 
            		'multivaluelist' => 'Multi-select List'));
		

		$crud->required_fields('category_id', 'attribute_id','variable', 'datatype', 'required', 'description', 'order', 'description');


		if (!($this->ion_auth->is_admin() || is_config_true($this->config->item('can_edit_categories')))) {
			$crud->unset_delete();
			$crud->unset_add();
			$crud->unset_edit();
		} else {
			$crud->callback_edit_field('keywords', array($this,'_text_keywords_field'));
		}

		$crud->set_subject('Open311 Service Definition');
		$output = $crud->render();

		$this->_admin_output($output);
	}


	
	function request_updates() {
		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('request_updates');
		$crud->unset_texteditor('update_desc');
		if (!($this->ion_auth->is_admin())) {
			$crud->unset_delete();
			$crud->unset_edit();
		} 
		$crud->unset_add(); // disabled: should only be created by editing a report
		$crud->columns('id', 'report_id', 'is_outbound', 'status_id', 'updated_at','update_desc', 'old_status_id', 'external_update_id');
		$crud->set_subject('Service request updates');
		$crud->set_relation('status_id','statuses',
			'<span class="fmse-status-{is_closed}">{status_name}</span>',null,'status_name ASC');
		$crud->callback_column('report_id', array($this, '_report_id_link_field'));
		$crud->callback_column('is_outbound', array($this, '_yes_no_field'));
		$crud->display_as('id', 'Update ID');
		$crud->display_as('report_id', 'Report');
		$crud->display_as('old_status_id', 'Old status');
		$crud->display_as('status_id', 'New status');
		$crud->display_as('update_desc', 'Description of update');
		$crud->display_as('is_outbound', 'Outbound?');
		$crud->display_as('remote_update_id', 'Remote<br/>update id');
	    
		$output = $crud->render();

		$this->_admin_output($output);
	}

	function settings() {
		if (!$this->ion_auth->is_admin()) {
			redirect('admin/');
		} else {
			$crud = new grocery_CRUD();
			$crud->set_theme('datatables'); /* text wraps for the long descriptions in databables, not flexigrid */
			$crud->set_table('config_settings');
			$crud->display_as('desc', 'Explanation');
			$crud->callback_column('desc', array($this, '_full_description'));
			$crud->unset_texteditor('name','value');
			$crud->edit_fields('name', 'desc', 'value');
			$crud->callback_edit_field('value', array($this,'_text_value_field'));  // the default (textarea) is too big for any current setttings 
			$crud->callback_edit_field('name', array($this,'_read_only_name_field'));  // read-only during edit
			$crud->callback_edit_field('desc', array($this,'_read_only_desc_field'));  // read-only during edit
			$crud->set_subject("configuration setting");
			$output = $crud->render();
			$this->_admin_output($output);
		}
	}

	function statuses() {
		if (!$this->ion_auth->is_admin()) {
			redirect('admin/');
		} else {
			$crud = new grocery_CRUD();
			$crud->set_theme('flexigrid'); 
			$crud->set_table('statuses');
			$crud->set_subject("problem status");
			$crud->unset_texteditor('description');
			$output = $crud->render();
			$this->_admin_output($output);
		}
	}
	
	function api_keys() {
		if (!$this->ion_auth->is_admin()) {
			redirect('admin/');
		} else {
			$crud = new grocery_CRUD();
			$crud->set_theme('flexigrid'); 
			$crud->set_table('api_keys');
			$crud->set_subject("API key");
			$crud->set_relation('client_id','open311_clients', 
				'<a href="/admin/open311_clients/{id}">{name}</a>', null,'name ASC');
			$crud->display_as('client_id', 'Client');
			$crud->unset_texteditor('notes');
			$output = $crud->render();
			$this->_admin_output($output);
		}
	}

	function open311_clients() {
		if (!$this->ion_auth->is_admin()) {
			redirect('admin/');
		} else {
			$crud = new grocery_CRUD();
			$crud->set_theme('flexigrid'); 
			$crud->set_table('open311_clients');
			$crud->set_subject("Open311 client");
			$crud->unset_texteditor('notes','client_url');
			$crud->callback_edit_field('client_url', array($this,'_text_client_url_field'));  
			$output = $crud->render();
			$this->_admin_output($output);
		}
	}

	function about() {
		$output = array('output' => $this->load->view('about', '', true));
		$this->load->view('admin_view.php', $output);
	}
	
	function help() {
		$output = array('output' => $this->load->view('help', '', true));
		$this->load->view('admin_view.php', $output);
	}

	function open311() {
		$output = array('output' => $this->load->view('open311', '', true));
		$this->load->view('admin_view.php', $output);
	}

	// There is some magic here: using xxx_report_id because running callback_column directly
	// on report_id breaks other field renders (such as actions) that contain report_id.
	function _set_common_report_crud($columns) {
		$crud = new grocery_CRUD();
		// default columns excludes: token address_id simply because FMS/FMS-endpoint doesn't use them
		$default_columns = array('report_id', 'status', 'requested_datetime', 'priority',  'category_id',
			'external_id', 'media_url', 'status_notes', 'description', 'agency_responsible', 'service_notice',
			'updated_datetime', 'expected_datetime', 'address', 'postal_code', 'lat', 'long',
			'email', 'device_id', 'source_client', 'account_id', 'first_name', 'last_name', 'phone','engineer');
		$columns = $columns? $columns : $default_columns;
		foreach ($columns as &$colname) {
			if ($colname == 'report_id') {
				$colname = 'xxx_report_id';
			}
		}
		$crud->columns($columns);
		$crud->edit_fields($default_columns);
		$crud->set_theme('flexigrid');
		$crud->set_table('reports');
		$crud->set_subject('Problem report');
		$crud->set_relation('category_id','categories','category_name',null,'category_name ASC');
		$crud->set_relation('priority','priorities',
			'<span class="fmse-prio fmse-prio{prio_value}">{prio_name}</span>',null,'prio_value ASC');
		$crud->set_relation('status','statuses',
			'<span class="fmse-status-{is_closed}">{status_name}</span>',null,'status_name ASC');
		$crud->set_relation('source_client','open311_clients', 
			'<a href="/admin/open311_clients/{id}">{name}</a>', null,'name ASC');
		$crud->display_as('client_id', 'Client');
		$crud->callback_column('media_url',array($this,'_linkify'));
		$crud->callback_column('external_id', array($this, '_get_external_url'));
		$crud->callback_edit_field('media_url', array($this,'_text_media_url_field'));  
		$crud->display_as('requested_datetime', 'Received')
			->display_as('updated_datetime', 'Updated')
			->display_as('expected_datetime', 'Expected')		
			->display_as('category_id', 'Category')
			->display_as('media_url', 'Media URL');
		$external_id_col_name = config_item('external_id_col_name');
		$crud->display_as('external_id', empty($external_id_col_name)?'External ID':$external_id_col_name);
		$crud->unset_texteditor('address', 'status_notes', 'service_notice');
		$crud->add_action('View', '/assets/fms-endpoint/images/report.png', 'admin/report');
		
		$crud->callback_column('xxx_report_id', array($this, '_report_id_link_field'));
		$crud->display_as('xxx_report_id', 'ID');
		$crud->callback_edit_field('xxx_report_id', array($this, '_read_only_report_id_field'));
		
		$crud->callback_before_update(array($this,'_fix_zero_prio_callback'));
		$crud->callback_after_update(array($this, '_check_for_status_update_after'));
		
		return $crud;
	}
	
	// force the default priority (0) since the groceryCRUD drop-down for priority doesn't
	// seem to auto-select an option if it's zero... hence it's returned from the form as
	// NULL
	function _fix_zero_prio_callback($post_array, $primary_key) {
		if ($post_array['priority'] == null) {
			$post_array['priority'] = FMSE_DEFAULT_REPORT_PRIORITY;
		}
		
		// get the current status (from the db) and store it in $post_array, cos we'll check it
		// immediately after the save to see if it's changed
		$this_record = $this->db->get_where('reports', array('report_id' => $primary_key))->row();
		$post_array['old_status']=$this_record->status; // for detecting status changes
		return $post_array;
	}
		
	function _admin_output($output = null) {
		$this->load->view('admin_view.php', $output);
	}

	// make the ID a link to the 
	function _report_id_link_field($value, $row) {
		$rid = $row->report_id;
		return "<a href='" . config_item('base_url') . "/admin/report/$rid' class='report-id-link'>$rid</a>";
	}
	
	function _read_only_report_id_field($value, $primary_key) { 
		return "<input type='hidden' value='$value' name='$primary_key'/>$value";
	}
	function _read_only_name_field($value, $primary_key) { return $this->_read_only_field('name', $value); }
	function _read_only_desc_field($value, $primary_key) { return $this->_read_only_field('desc', $value); }
	function _read_only_field($name, $value) {
		return '<input type="hidden" value="' . $value . '" name="' . $name . '"/>' . $value;
	}

	function _text_value_field($value, $primary_key) { return $this->_text_field('value', $value); }
	function _text_media_url_field($value, $primary_key) { return $this->_text_field('media_url', $value); }
	function _text_client_url_field($value, $primary_key) { return $this->_text_field('client_url', $value); }
	function _text_keywords_field($value, $primary_key) { return $this->_text_field('keywords', $value); }
	function _text_field($name, $value) {
		return '<input type="text" value="' . $value . '" name="' . $name . '"/>';
	}
	function _yes_no_field($value, $primary_key) { return ($value? "yes" : "no"); }

	// turn the value (assumed to be a good URL) into a link
	// class of link varies if it looks like this is a fixmystreet-like link, heheh
	function _linkify($url, $row, $link_text='link') {
		$retval = '';
		if ($url) {
			$css_class = (preg_match('/https?:\/\/(\\w*\\.)*fixmy/', $url))? 'fmse-web-link-fms':'fmse-web-link';
			$retval = '<a href="' . $url . '" class="' . $css_class . '" target="_blank">' . $link_text . '</a>';
		}
		return $retval;
	}

	function _full_description($value, $row) {
		return $value = wordwrap($row->desc, strlen($row->desc), "<br>", true);
	}

    function _get_external_url($value, $row) {
		$client_lookup = $this->db->get_where("open311_clients", array('id' => $row->source_client));
		$url = '';
		if ($client_lookup->num_rows()==0) {
			return "";
		} else if (!empty($row->external_id)) {
			$url = $client_lookup->row()->client_url;
			$url = preg_replace('/%id%/', $row->external_id, $url);
			$url = $this->_linkify($url, $row, $row->external_id);
		}
		return $url;
	}
	
	function _check_for_status_update_after($post_array,$primary_key) {
		if ($post_array['old_status'] != $post_array['status']) {
			// create a status change record
			$desc = "Status changed";
			$status_lookup = $this->db->get_where("statuses", array('status_id' => $post_array['status']));
			if ($status_lookup->num_rows()==1) {
				$desc = "Marked as \"" . $status_lookup->row()->status_name . '"';
			} // else fail silently? 
			$org = $this->config->item('organisation_name');
			if  (! empty($org) ) {
				$desc = "$desc by $org";
			}
			$request_update = array(
				'report_id' => $primary_key,
				'status_id' => $post_array['status'],
				'old_status_id' => $post_array['old_status'],
				'changed_by' => 0, // TODO this should be user ID
				// 'changed_by_name' => ? // TODO this should be user email, etc
				'is_outbound' => 1, // this is an *outbound* update, because we're sending it out to the client
				'update_desc' => $desc //for now, don't send FMS empty descriptions, because it doesn't digest them nicely
			);
			$this->db->insert('request_updates', $request_update);
		}
		return true;
	}
}

?>
