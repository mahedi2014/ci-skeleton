<?php
	Class User_data extends CI_Model {

		function __construct()
		{
			// Call the Model constructor
			parent::__construct();
		}
		
		//check login, for admin user
		function check_panel_login()
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			
			$this->db->select('*');
			$this->db->from('user_panel');
			
			$this->db->where('email', $email);
			$this->db->where('password', $password); 
			$this->db->where('status', 0); 

			$query = $this->db->get();
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
		
		//get user accessible menu list
		function menu_access_list()
		{
			$mid = $this->session->userdata('paccess');
			 
			$sql = "SELECT mnl.id, mi.name as class,fn.name as func, mnl.name, mnl.parent, mnl.order FROM menu_list mnl 
					LEFT JOIN function_info fn ON mnl.f_id = fn.id
					LEFT JOIN module_info mi ON fn.m_id = mi.id
					WHERE mnl.id IN ($mid) AND menu_type = 1 order by mnl.order";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//get accessible menu list
		function admin_menu_access_list()
		{
			
			$mid = $this->session->userdata('access');
				
			$where_cond = "mnl.id IN ($mid) AND";
				
			//bypass permission for super admin
			if($this->session->userdata('type') == 1)
			{
				$where_cond = "";				
			}
			
			
			$sql = "SELECT mnl.id, mi.name as class,fn.name as func, mnl.name, mnl.parent, 
					mnl.order FROM menu_list mnl 
					LEFT JOIN function_info fn ON mnl.f_id = fn.id
					LEFT JOIN module_info mi ON fn.m_id = mi.id
					WHERE $where_cond menu_type = 1 order by mnl.order";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();
		}
		
		//get user accessible page
		function check_accessibility($module,$func)
		{
			$mid = $this->session->userdata('access');
			
			$where_cond = "mnl.id IN ($mid) AND";
				
			//bypass permission for super admin
			if($this->session->userdata('type') == 1)
			{
				$where_cond = "";				
			}
			
			
			$sql = "SELECT mnl.id, mnl.parent FROM menu_list mnl 
						LEFT JOIN function_info fn ON mnl.f_id = fn.id
						LEFT JOIN module_info mi ON fn.m_id = mi.id
						WHERE $where_cond fn.name = '$func' AND mi.name = '$module'";
		
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			
			return false;
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
			$this->db->select('*');
			$this->db->from('user_panel');
			
			$query = $this->db->get();
			return $query->result();
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
		
		//get user profile
		function user_profile($email)
		{
			$this->db->select('*');
			
			$this->db->from('user_panel');
			
			$this->db->where('email',$email);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		
		function get_last_ten_entries()
		{
			$query = $this->db->get('entries', 10);
			return $query->result();
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
		
		//insert classes function
		function insert_function($module_id, $function)
		{
			$data['name'] = $function; // function name
			$data['m_id'] = $module_id; // module name
			$data['status'] = 0;
			$data['created'] = time();

			$this->db->insert('function_info', $data);
		}
		
		//get subscription info
		function subscription_data()
		{
			$sql = "SELECT * FROM subscriber_info";
			
			$query = $this->db->query($sql);
			
			return $query->result_array();			
		}
		
		//insert new user 
		function isnert_user_data($data)
		{
			$this->db->insert('user_panel', $data);
			
			return $this->db->insert_id();
		}

		function update_entry()
		{
			$this->title   = $_POST['title'];
			$this->content = $_POST['content'];
			$this->date    = time();

			$this->db->update('entries', $this, array('id' => $_POST['id']));
		}

	}