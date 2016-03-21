<?php
	Class Admin_data extends CI_Model {
		
		function __construct()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		//check login, for admin user
		function check_login()
		{
			$user_id = $this->input->post('userid');
			$password = $this->input->post('password');
					
			$this->db->select('adm.*,gr.menu_access,gr.short_code,gr.keyword');
			$this->db->from('admin_profile as adm');
			$this->db->join('user_group as gr', 'adm.group_id = gr.id');
			$this->db->where('adm.user_id',$user_id);
			$this->db->where('adm.password',$password);
			$this->db->where('adm.status',1);

			$query = $this->db->get();

			//$query = $this->db->query($sql);
			
			return $query->result();
		}
		
		//get module data
		function module_data($id="")
		{
			$this->db->select('*');
			$this->db->from('module_info');
			
			if($id !=""){
				$this->db->where('id',$id);
			}
			
			$query = $this->db->get();
			return $query->result();
		}
		
		//list of menus
		function menu_list($all=0)
		{
			$this->db->select('*');
			$this->db->from('menu_list');
			
			if($all == 0){	//SHOWING PARENT TYPE MENU
				$this->db->where('parent',0);
			}
			
			$query = $this->db->get();
			return $query->result_array();
		}
		
		//list of group
		function user_group()
		{
			$this->db->select('*');
			
			$this->db->from('user_group');
			
			$query = $this->db->get();
			
			return $query->result_array();
		}
		
		//list of menus
		function build_link($id)
		{
			$this->db->select('module_info.name as class,function_info.name as function');
			$this->db->from('function_info');
			$this->db->join('module_info', 'function_info.m_id = module_info.id');
			$this->db->where('function_info.id',$id);
			
			$query = $this->db->get();
			return $query->result_array();
		}
		
		//list of keyword
		function get_keyword_list($sh_id, $stat=0)
		{
			$this->db->select('*');
			$this->db->from('service_info');
			$this->db->where('sh_id', $sh_id);
			
			if($stat==1){
				$this->db->where('status', 0);
			}
			
			//keyword label access checking, for assigned user
			$keyword_assigned = $this->session->userdata('keyword');
			
			if($keyword_assigned!=""){
				$key_arr=explode(",",$keyword_assigned);
				$this->db->where_in('keyword', $key_arr);
			}
			
			$query = $this->db->get();
			return $query->result();
		}
		
		//list of modules
		function get_function_list($m_id)
		{
			$this->db->select('*');
			$this->db->from('function_info');
			$this->db->where('m_id', $m_id);
			
			$query = $this->db->get();
			return $query->result();
		}
		
		//list of modules
		function module_list()
		{
			$this->db->select('*');
			$this->db->from('module_info');
			
			$query = $this->db->get();
			return $query->result();
		}
		
		//get list of user
		function user_list()
		{
			$sql = "SELECT ap.*, gr.name FROM admin_profile ap
					LEFT JOIN user_group gr ON ap.group_id = gr.id";
			
			$query = $this->db->query($sql);
			
			return $query->result();
		}
		
		//get list of short_code
		function short_code()
		{
			$sql = "SELECT sh.*, adm.user_id FROM short_code sh 
					LEFT JOIN admin_profile adm ON sh.updated_by = adm.id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		//get list of gateway data
		function get_gateway_data($id=0)
		{
			$where =($id>0)?"WHERE id=$id":"";
			
			$sql = "SELECT * FROM operator_gw $where";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//check unique group name
		function check_unique_group($name)
		{
			$this->db->select('*');
			
			$this->db->from('user_group');
			
			$this->db->where('name',$name);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//check unique request replace
		function unique_replace($short_code,$keyword)
		{
			$this->db->select('*');
			
			$this->db->from('request_replace');
			
			$this->db->where('short_code',$short_code);
			
			$this->db->where('keyword',$keyword);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//check unique request replace for update
		function unique_replace_upd($short_code, $keyword, $id)
		{
			$this->db->select('*');
			
			$this->db->from('request_replace');
			
			$this->db->where('short_code',$short_code);
			
			$this->db->where('keyword',$keyword);
			
			$this->db->where('id !=',$id);
						
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//unique service, request for service
		function unique_service($sh_id,$keyword)
		{
			$this->db->select('*');
			$this->db->from('service_info');
			
			$this->db->where('sh_id',$sh_id);
			$this->db->where('keyword',$keyword);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//check unique service, for sub service
		function check_unique_service($short_code,$keyword,$table_name)
		{
			$this->db->select('*');
			$this->db->from($table_name);
			
			$this->db->where('short_code',$short_code);
			$this->db->where('keyword',$keyword);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//check unique service, for sub service
		function check_unique_sub_key_service($short_code,$keyword,$sub_keyword,$table_name)
		{
			$this->db->select('*');
			$this->db->from($table_name);
			
			$this->db->where('short_code',$short_code);
			$this->db->where('keyword',$keyword);
			$this->db->where('sub_keyword',$sub_keyword);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//check uniquie static service
		function check_unique_static_service($sh_id,$keyword)
		{
			$this->db->select('*');
			$this->db->from('static_service');
			
			$this->db->where('sh_id',$sh_id);
			$this->db->where('keyword',$keyword);
			
			$query = $this->db->get();
			
			return $query->num_rows();		
		}
		
		//get list of services
		function service_info()
		{
			$sql = "SELECT sv.*, adm.user_id, sh.short_code FROM service_info sv 
					LEFT JOIN admin_profile adm ON sv.updated_by = adm.id
					LEFT JOIN short_code sh ON sv.sh_id = sh.id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get quiz services
		function quiz_service_info()
		{
			$sql = "SELECT * FROM quiz_reply_content";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get quiz services
		function get_data_by_table_name($table_name,$field=array())
		{
			$sql = "SELECT * FROM $table_name";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//insert gw data
		function add_gw_data($data)
		{
			//check duplicacy
			$sql = "select * from operator_gw where short_code='".$data['short_code']."' and operator='".$data['operator']."'";
			
			$query = $this->db->query($sql);
	
			if($query->num_rows()<1){			
				$this->db->insert('operator_gw', $data);
				return $this->db->insert_id();		
			}
			else{ return 0;}
		}
		
		//update gw data
		function edit_gw_data($data,$id)
		{
			//check duplicacy
			$sql = "select * from operator_gw where short_code='".$data['short_code']."' and operator='".$data['operator']."' and id!=".$id;
			
			$query = $this->db->query($sql);
	
			if($query->num_rows()<1){			
				$this->db->where('id', $id);
				$this->db->update('operator_gw', $data);
				return 1;
			}
			else{ return 0;}
		}
		
		//insert data by table name
		function insert_by_table_name($table_name, $data)
		{
			$this->db->insert($table_name, $data);
			return $this->db->insert_id();		
		}
		
		//insert batch of data by table
		function insert_batch_by_table_name($table_name, $data)
		{
			$this->db->insert_batch($table_name, $data);
			return $this->db->insert_id();		
		}
		
		//get priority range for operator
		public function get_priority_range($short_code){
			//get priority range of pushpull service
			$qry = "SELECT push,operator FROM operator_gw where short_code=".$short_code;
			
			$query = $this->db->query($qry);

			return $query->result_array();
			
			//set default priority range
			/* $min_pri = 1; 
			$max_pri = 1; 
			
			if(count($pr_arr)>0){
				//get push pull priority range
				$priority_range = $pr_arr[0]['push'];
				explode("-",$priority_range);
				
				$min_pri = $priority_range[0];
				$max_pri = (isset($priority_range[1]))?$priority_range[1]:$min_pri;
				
				//check min priority is greater than max
				if($max_pri<$min_pri)
					$max_pri = $min_pri;
			}
			
			return $min_pri.'-'.$max_pri; */
		}
		
		//get list of services charge
		function service_charge()
		{
			$sql = "SELECT * FROM charge_format";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		
		//get services by id
		function get_service_by_id($id)
		{
			$sql = "SELECT * FROM service_info WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get request replace by id
		function request_replace_by_id($id)
		{
			$sql = "SELECT * FROM request_replace WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get services by id
		function get_service_charge_by_id($id)
		{
			$sql = "SELECT * FROM charge_format WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get web services by id
		function get_web_service_by_id($id)
		{
			$sql = "SELECT * FROM web_service WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get web short code by id
		function short_code_by_id($id)
		{
			$sql = "SELECT * FROM short_code WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get web services by id
		function get_static_service_by_id($id)
		{
			$sql = "SELECT * FROM static_service WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get sub hotkey service by id
		function get_sub_hotkey_service_by_id($id)
		{
			$sql = "SELECT * FROM sub_hotkey_service WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		
		//get subscriber by id
		function get_subscriber_by_id($id)
		{
			$sql = "SELECT * FROM subscriber_info WHERE id=$id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		
		//get list of services
		function static_service_info()
		{
			$extra_cond = "";
			
			//process extra condition
			$sh_code = $this->session->userdata('short_code');
			$key = $this->session->userdata('keyword');
			
			if($sh_code!=""){
				$key_arr = explode(",",$key);
			
				$extra_cond = ($key !="")?
					"WHERE short_code IN($sh_code) AND keyword IN('".implode("','",$key_arr)."')":"";
			}
			//end of extra condtion process
			
			$sql = "SELECT sv.*, adm.first_name, adm.last_name FROM static_service sv 
					LEFT JOIN admin_profile adm ON sv.updated_by = adm.id $extra_cond";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get list of Sub hotkey services
		function sub_hotkey_service_info()
		{
			$extra_cond = "";
			
			//process extra condition
			$sh_code = $this->session->userdata('short_code');
			$key = $this->session->userdata('keyword');
			
			if($sh_code!=""){
				$key_arr = explode(",",$key);
			
				$extra_cond = ($key !="")?
					"WHERE short_code IN($sh_code) AND keyword IN('".implode("','",$key_arr)."')":"";
			}
			//end of extra condtion process
			
			$sql = "SELECT sv.*, adm.first_name, adm.last_name FROM sub_hotkey_service sv 
					LEFT JOIN admin_profile adm ON sv.updated_by = adm.id $extra_cond";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get list of web services
		function web_service_info()
		{
			$sql = "SELECT wv.*, adm.first_name, adm.last_name FROM web_service wv 
					LEFT JOIN admin_profile adm ON wv.updated_by = adm.id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		
		//check service registration duplicacy
		function check_reg_duplicacy($mobile, $keyword, $short_code)
		{
			$sql = "SELECT id,status FROM subscriber_info WHERE keyword = '$keyword' AND short_code = $short_code 
					AND mobile = $mobile LIMIT 1";
			
			$query = $this->db->query($sql);
			
			return $query->num_rows();
		}
		
		//get subscription info
		function subscription_data($search_key)
		{
			$sql = "SELECT subs.*, inf.name FROM subscriber_info subs 
					LEFT JOIN service_info inf ON subs.service_id = inf.id
					WHERE subs.mobile LIKE '%$search_key%' LIMIT 100";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//post subscriber info
		function get_subscriber_info_by_short_key($short_code, $keyword)
		{
			
			$sql = "SELECT subs.*, inf.name FROM subscriber_info subs 
					LEFT JOIN service_info inf ON subs.service_id = inf.id
					LEFT JOIN short_code sc ON inf.sh_id = sc.id
					WHERE sc.short_code = $short_code AND inf.keyword = '$keyword'";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//subscription function
		function get_subscription_function($short_code, $keyword)
		{
			
			$sql = "SELECT info.post_function,info.id,info.charge,info.subscr_days FROM service_info info 
					LEFT JOIN short_code sc ON info.sh_id = sc.id
					WHERE sc.short_code = $short_code AND info.keyword = '$keyword'";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get subscription info with total no of subscriber
		function subscriber_info_with_total()
		{
			// $sql = "SELECT subs.short_code,subs.keyword, count(subs.id) total, srv.name FROM subscriber_info subs 
					// LEFT JOIN service_info srv ON subs.service_id = srv.id
					// WHERE subs.status=0 GROUP BY subs.short_code,subs.keyword";
			
			$extra_cond = "";
			//process extra condition
			$sh_code = $this->session->userdata('short_code');
			$key = $this->session->userdata('keyword');
			
			if($sh_code!=""){
				$key_arr = explode(",",$key);
			
				$extra_cond = ($key !="")?
					"AND short_code IN($sh_code) AND keyword IN('".implode("','",$key_arr)."')":"";
			}
			//end of extra condtion process
			
			$sql = "SELECT code.short_code,srv.keyword,srv.name ,IF((select plog.id from subscription_post_log plog where plog.short_code = code.short_code and plog.keyword=srv.keyword and plog.log_date > '".date('Y-m-d')." 00:00' limit 1),1,0) posted FROM service_info srv
					LEFT JOIN short_code code ON srv.sh_id = code.id
					WHERE srv.status=0 AND (srv.reg_mode='YES' OR srv.force_reg_mode='YES') $extra_cond";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get subscription info with short_code and keyword
		function subscriber_info_with_skey($short_code, $keyword)
		{
			$tl_ct_cond = "AND operator NOT IN('citycell','teletalk')";
			
			$sql = "SELECT subs.short_code, subs.operator, subs.keyword, count(subs.id) total, srv.name, srv.charge FROM 
							subscriber_info subs 
					LEFT JOIN service_info srv ON subs.service_id = srv.id
					WHERE subs.keyword = '$keyword' AND subs.short_code = $short_code AND subs.status=0 $tl_ct_cond 
					GROUP BY subs.short_code, subs.keyword, subs.operator";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//get subscription reply
		function subscription_reply($short_code,$keyword){
			$sql = "SELECT service_reply FROM static_service
					WHERE keyword = '$keyword' AND short_code = $short_code";
			
			$query = $this->db->query($sql);
			
			$res = $query->result_array();	
	 
			if(count($res)>0) return $res[0]['service_reply'];
			
			return;
		}
		
		
		//get subscription info with short_code and keyword
		function subscriber_info($short_code, $keyword)
		{
			$sql = "SELECT subs.*, srv.id, srv.name, srv.charge FROM subscriber_info subs 
					LEFT JOIN service_info srv ON subs.service_id = srv.id
					WHERE subs.keyword = '$keyword' AND subs.short_code = $short_code AND subs.status=0";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		
		//get user details
		function user_details($id)
		{
			$sql = "SELECT ap.* FROM admin_profile ap
					LEFT JOIN user_group ug ON ap.group_id = ug.id
					WHERE ap.id = $id";
			
			$query = $this->db->query($sql);
			
			return $query->result();
		}
		
		//get receive post function by id
		function receive_post_func_by_id($id)
		{
			$sql = "SELECT * FROM service_receive_post_function
					WHERE id = $id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//get all keyword
		function get_all_keyword()
		{
			$sql = "SELECT distinct keyword FROM service_info";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//Update receive post function by id
		function update_receive_post($data, $id)
		{
			$this->db->where('id', $id);
			return $this->db->update('service_receive_post_function', $data);
		}
		
		//Update subscribe status
		function update_subscriber_by_id($data, $id)
		{			
			$this->db->where('id', $id);
		
			$this->db->update('subscriber_info', $data);
			
			return $this->db->affected_rows();
		}
		
		//get all types of link data
		function user_menu_link()
		{
			$sql = "SELECT me.id,me.name name, me.parent, me.menu_type type, me.order, mdl.name class, func.name function FROM menu_list me 
					LEFT JOIN function_info func ON me.f_id = func.id
					LEFT JOIN module_info mdl ON func.m_id = mdl.id
					WHERE me.id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//get user details, $flag value for user assigned menu/ user not assigned menu
		function user_all_menu()
		{
			$sql = "SELECT me.id,me.name name, me.parent, me.menu_type type, me.order, mdl.name class, func.name function FROM menu_list me 
					LEFT JOIN function_info func ON me.f_id = func.id
					LEFT JOIN module_info mdl ON func.m_id = mdl.id
					WHERE me.id";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//details of modules
		function module_details($m_id)
		{
			$this->db->select('*');
			$this->db->from('function_info');
			
			$this->db->where('m_id',$m_id);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		
		//list of group
		function group_data()
		{
			$this->db->select('*');
			
			$this->db->from('user_group');
			
			$query = $this->db->get();
			
			return $query->result_array();
		}
		
		//list of request replace
		function request_replace()
		{
			$this->db->select('*');
			
			$this->db->from('request_replace');
			
			$query = $this->db->get();
			
			return $query->result_array();
		}
		
		//get group details
		function group_details($id)
		{
			$this->db->select('*');
			$this->db->from('user_group');
			$this->db->where('id',$id);
			
			$query = $this->db->get();
			return $query->result();
		}
		
		//get all menubar 
		function get_all_menubar()
		{
			$this->db->select('*');
			$this->db->from('menu_list');
			$this->db->where('menu_type','1');
			
			$query = $this->db->get();
			return $query->result_array();
		}
		
		//get service charge
		function get_service_charge()
		{
			$this->db->select('*');
			$this->db->from('charge_format');
			
			$query = $this->db->get();
			return $query->result_array();
		}
		
		//get subscription charge
		function get_subscription_charge($keyword,$short_code)
		{
			$charge = 0.0;
			
			$sql = "SELECT inf.charge FROM service_info inf
					INNER JOIN short_code sc ON inf.sh_id = sc.id 
					WHERE inf.keyword = '$keyword' AND sc.short_code = $short_code";
			
			$query = $this->db->query($sql);
			
			$res = $query->result_array();
			
			if(count($res[0])>0){
				$charge = $res[0]['charge'];
			}
			
			return $charge;
		}
		
		//get service function list
		function get_service_functions()
		{
			$sql = "SELECT * FROM service_receive_post_function
					ORDER BY id ASC";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//process receive post function
		function receive_post_data()
		{
			$this->db->select('*');
			$this->db->from('service_receive_post_function');
			$this->db->order_by("id", "asc"); 
			
			$query = $this->db->get();
			return $query->result_array();
		}
			
		//get dashboard short description
		function load_dashboard_data()
		{
			//get operator wise sms with status 'READY'
			$gp = $this->load_sms_info_by_opr('gp_outbox',1);	
			$gp_sms = ($gp[0]['ready']=="")?0:$gp[0]['ready'];
			
			$bl = $this->load_sms_info_by_opr('bl_outbox');	
			$bl_sms = ($bl[0]['ready']=="")?0:$bl[0]['ready'];
			
			$robi = $this->load_sms_info_by_opr('robi_outbox',1);	
			$robi_sms = ($robi[0]['ready']=="")?0:$robi[0]['ready'];
			
			$airtel = $this->load_sms_info_by_opr('airtel_outbox');	
			$airtel_sms = ($airtel[0]['ready']=="")?0:$airtel[0]['ready'];
			
			$tele = $this->load_sms_info_by_opr('tele_outbox');
			$tele_sms = ($tele[0]['ready']=="")?0:$tele[0]['ready'];
			
			$citycell = $this->load_sms_info_by_opr('citycell_outbox');
			$citycell_sms = ($citycell[0]['ready']=="")?0:$citycell[0]['ready'];
			
			$dashboard['sms_ready'] = array( 'gp'=>$gp_sms,
							   	'bl'=>$bl_sms,
								'robi'=>$robi_sms,
								'airtel'=>$airtel_sms,
								'ct'=>$citycell_sms,
								'tele'=>$tele_sms
							);
			//end of getting sms no
			
			//get user subscription info
			$sub = $this->get_subscriber_by_operator();					
			
			$dashboard['subscriber'] = array('gp'=>$sub[0]['gp'],
											'bl'=>$sub[0]['bl'],
											'robi'=>$sub[0]['robi'],
											'airtel'=>$sub[0]['ar'],
											'ct'=>$sub[0]['ct'],
											'tl'=>$sub[0]['tl']
										);
										
			//get sending sms no by operator
			$tot_sms = $this->total_sms_send_today();					
			
			$dashboard['sms_send'] = array('gp'=>$tot_sms[0]['gp'],
											'bl'=>$tot_sms[0]['bl'],
											'robi'=>$tot_sms[0]['robi'],
											'airtel'=>$tot_sms[0]['ar'],
											'ct'=>$tot_sms[0]['ct'],
											'tl'=>$tot_sms[0]['tl']
										);
			
			return $dashboard;
		}
				
		protected function load_sms_info_by_opr($table_name,$stat=0){
			
			$status = 'READY';
			
			if($stat==1)	//for gp sms status all time QUEUE
				$status = 'QUEUE';
			
			$sql = "SELECT SUM(IF(status='$status',1,0)) as ready FROM $table_name  WHERE FROM_UNIXTIME(date,'%Y-%m-%d')='".date('Y-m-d')."'";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();	
		}
		
		//return total subscriber
		protected function get_subscriber_by_operator(){
			
			//$sql = "SELECT COUNT(id) as subs FROM subscriber_info WHERE status=0 AND req_date='".date("Y-m-d")."'";
			$sql = "SELECT SUM(IF(operator='grameenphone',1,0)) gp,
					SUM(IF(operator='banglalink',1,0)) bl,
					SUM(IF(operator='robi',1,0)) robi,
					SUM(IF(operator='teletalk',1,0)) tl,
					SUM(IF(operator='airtel',1,0)) ar,
					SUM(IF(operator='citycell',1,0)) ct

					FROM subscriber_info WHERE STATUS=0 AND req_date='".date("Y-m-d")."'";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();	
		}
		
		function get_subscr_post_log_by_date($date){
			$sql = "SELECT * FROM subscription_post_log WHERE DATE_FORMAT(log_date, '%Y-%m-%d')='$date' order by id desc";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//log post subscription info
		function log_subscription_post($data){
			$operators = $data['operators'];
			$operator_name = explode(",",$operators);

			$grameenphone_tot = 0;
			$banglalink_tot = 0;
			$robi_tot = 0;
			$teletalk_tot = 0;
			$airtel_tot = 0;
			$citycell_tot = 0;
			$unknown_tot = 0;
			 
			foreach($operator_name as $op_tot){
				$op = explode(":",$op_tot); //get operator and total from operator:total
				
				$name = $op[0]."_tot";
				
				$$name = $op[1]; //assigned total sms on operator variable
			}
			
			$ins_arr = array(
							'short_code'=>$data['short_code'],
							'keyword'=>$data['keyword'],
							'service'=>$data['service_name'],
							'reply'=>$data['reply'],
							'grameenphone'=>$grameenphone_tot,
							'banglalink'=>$banglalink_tot,
							'robi'=>$robi_tot,
							'airtel'=>$airtel_tot,
							'teletalk'=>$teletalk_tot,
							'citycell'=>$citycell_tot,
							'log_date'=>date("Y-m-d H:i")
						);
			
			$this->db->insert('subscription_post_log', $ins_arr);
		}
		
		//return total subscriber
		protected function total_sms_send_today(){
			
			//$sql = "SELECT COUNT(id) as subs FROM subscriber_info WHERE status=0 AND req_date='".date("Y-m-d")."'";
			$sql = "SELECT SUM(IF(operator='grameenphone',1,0)) gp,
					SUM(IF(operator='banglalink',1,0)) bl,
					SUM(IF(operator='robi',1,0)) robi,
					SUM(IF(operator='teletalk',1,0)) tl,
					SUM(IF(operator='airtel',1,0)) ar,
					SUM(IF(operator='citycell',1,0)) ct FROM sms_log WHERE status LIKE '%successfully' AND FROM_UNIXTIME(create_date,'%Y-%m-%d')='".date("Y-m-d")."'";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
						
		//insert post receive function
		function insert_receive_post($data)
		{
			$this->db->insert('service_receive_post_function', $data);
			
			return $this->db->insert_id();
		}
		
		//insert classes name
		function insert_module($module)
		{
			$data['name'] = $module; // module name
			$data['created'] = time();

			$this->db->insert('module_info', $data);
			
			return $this->db->insert_id();
		}
		
		//insert classes name
		function insert_menu($data)
		{
			$this->db->insert('menu_list', $data);
			return $this->db->insert_id();
		
		}
		
		//insert short code
		function insert_short_code($data)
		{
			$this->db->insert('short_code', $data);
			return $this->db->insert_id();		
		}
		
			
		//update service
		function subscription_update($ids,$data)
		{
			$this->db->where_in('id', $ids);
			
			return $this->db->update('subscriber_info', $data);
		}
		
		//Inactive bulk subscriber
		function bulk_subscr_inactive($table_name,$data)
		{
			$this->db->update_batch($table_name, $data, 'mobile');
			
			return $this->db->affected_rows();
		}
		
		//insert service
		function insert_service($data)
		{
			$this->db->insert('service_info', $data);
			return $this->db->insert_id();
		}
		
		//insert quiz reply
		function insert_quiz_reply($data)
		{
			$this->db->insert('quiz_reply_content', $data);
			return $this->db->insert_id();		
		}
		
		//insert service charge
		function insert_service_charge($data)
		{
			$this->db->insert('charge_format', $data);
			return $this->db->insert_id();		
		}
		
		//insert new group
		function insert_group($data)
		{
			$this->db->insert('user_group', $data);
			return $this->db->insert_id();		
		}
		
		//update service
		function update_service($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('service_info', $data);			
		}
		
		//update request replace
		function update_request_replace($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('request_replace', $data);			
		}
		
		//update short code
		function update_short_code($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('short_code', $data);			
		}
		
		//update service charge
		function update_service_charge($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('charge_format', $data);			
		}
		
		//update web service
		function update_web_service($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('web_service', $data);			
		}
		
		//update static service
		function update_static_service($data,$id)
		{
			$this->db->where('id', $id);
			
			//process extra condition
			$sh_code = $this->session->userdata('short_code');
			$key = $this->session->userdata('keyword');
			
			if($sh_code !="" && $key !=""){
				$short_arr = explode(",",$sh_code);
				$key_arr = explode(",",$key);
			
				$this->db->where_in('short_code', $short_arr);
				$this->db->where_in('keyword', $key_arr);	
			}
			//end of extra condtion process
			
			$this->db->update('static_service', $data);
			
			return $this->db->affected_rows();
		}
		
		//update sub hotkey service
		function update_sub_hotkey_service($data,$id)
		{
			$this->db->where('id', $id);
			
			//process extra condition
			$sh_code = $this->session->userdata('short_code');
			$key = $this->session->userdata('keyword');
			$sub_keyword = $this->session->userdata('sub_keyword');
			
			if($sh_code !="" && $key !=""){
				$short_arr = explode(",",$sh_code);
				$key_arr = explode(",",$key);
			
				$this->db->where_in('short_code', $short_arr);
				$this->db->where_in('keyword', $key_arr);	
				$this->db->where_in('sub_keyword', $sub_keyword);
			}
			//end of extra condtion process
			
			$this->db->update('sub_hotkey_service', $data);
			
			return $this->db->affected_rows();
		}
		
		//insert static service
		function insert_static_service($data)
		{
			$this->db->insert('static_service', $data);
			return $this->db->insert_id();		
		}
		
		//insert sub hotkey service
		function insert_sub_hotkey_service($data)
		{
			$this->db->insert('sub_hotkey_service', $data);
			return $this->db->insert_id();		
		}
		
		//insert web service
		function insert_web_service($data)
		{
			$this->db->insert('web_service', $data);
			return $this->db->insert_id();		
		}
		
		//insert classes function
		function insert_function($module_id, $function)
		{
			$data['name'] = $function; // function name
			$data['m_id'] = $module_id; // module name
			$data['status'] = 0;
			$data['created'] = time();

			$this->db->insert('function_info', $data);
		}
		
		//insert new user 
		function isnert_user_data($data)
		{
			$this->db->insert('admin_profile', $data);
			return $this->db->insert_id();
		}
		
		//insert service registration data
		function insert_reg_service($data)
		{
			$this->db->insert('subscriber_info', $data);
			return $this->db->insert_id();
		}
		
		//update group access/activation
		function update_group($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('user_group', $data);
		}
		
		//update user access/activation
		function update_user($data,$id)
		{
			$this->db->where('id', $id);
			return $this->db->update('admin_profile', $data);
		}
		
		//delete static service
		function del_web_service($id)
		{
			$this->db->where('id', $id);
			return $this->db->delete('web_service');
		}
		
		//delete request replace
		function delete_request_replace($id)
		{
			$this->db->where('id', $id);
			$this->db->delete('request_replace');
			return $this->db->affected_rows() > 0;
		}
		
		//delete delete receive post function
		function delete_post_func_by_id($id)
		{
			$this->db->where('id', $id);
			return $this->db->delete('service_receive_post_function');
		}

		//delete static service
		function del_static_service($id)
		{
			$this->db->where('id', $id);
			return $this->db->delete('static_service');
		}
		
		//delete sub hotkey service
		function del_sub_hotkey_service($id)
		{
			$this->db->where('id', $id);
			return $this->db->delete('sub_hotkey_service');
		}
		
		//get operator name
		function opereator_name($num){
			$num_ext = substr($num,0,3);
			
			switch($num_ext){
				case '017':
					$operator = 'grameenphone';
					break;
				
				case '019':
					$operator = 'banglalink';
					break;
					
				case '015':
					$operator = 'teletalk';
					break;
				
				case '018':
					$operator = 'robi';
					break;
					
				case '016':
					$operator = 'airtel';
					break;
					
				case '011':
					$operator = 'citycell';
					break;
				
				default:
					$operator = 'unknown';
					break;
			}
			
			return $operator;
		}



		/************************* INVALID keyword replace  by-force service start 20.10.2015*****************/
		public function content_replace($q_arr){
			$date_arr = explode("-",$q_arr['from_to_date']);
			$keyword = $q_arr['keyword'];
			$short_code = $q_arr['short_code'];
			$from       = trim($date_arr[0]);
			$to        = trim($date_arr[1]);

			$sql = "SELECT id,mobile,short_code,keyword,message_in,message_out,create_date
						FROM sms_log
						where short_code = '$short_code'
							and keyword = '$keyword'
							and (FROM_UNIXTIME(create_date,'%m/%d/%Y') BETWEEN '".$from."' AND '".$to."')
							order by create_date desc";

			$query = $this->db->query($sql);

			return $query->result_array();

		}

		/************************* INVALID keyword replace  by-force service start 20.10.2015*****************/
		function replace_content_by_keyword($q_rr){
			$date_arr   = explode("-",$q_rr['from_to_date']);
			$from       = trim($date_arr[0]);
			$to         = trim($date_arr[1]);
			$id         = $q_rr['id'];
			$keyword    = $q_rr['keyword'];
			$content    = $q_rr['content'];
			$mobile     = $q_rr['mobile'];
			$short_code = $q_rr['short_code'];

			// curl //
			$urlEncode = urlencode($content);
			$url="http://192.168.50.103/smsproject/sms_webreq/services/request_process/?msg=$urlEncode&from=$mobile&to=$short_code";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,'');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch);
			curl_close ($ch);
			$serverMsgJson = (array)json_decode($server_output);
			$firstMsg = substr($serverMsgJson['msg'],0,30);

			if(($firstMsg=='To confirm, please send YES to') & ( (substr($mobile,0,3) == '017') | (substr($mobile,0,5) == '88017'))){
				$secondUrl="http://192.168.50.103/smsproject/sms_webreq/services/request_process/?msg=yes&from=$mobile&to=$short_code";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$secondUrl);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,'');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$server_output_string = curl_exec ($ch);
				curl_close ($ch);
			}
			if($server_output){
				$sql = "update sms_log set keyword = '$content'
                    where id = '$id'";
				$query = $this->db->query($sql);
			}
		}

	}