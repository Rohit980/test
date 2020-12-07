<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->load->helper('form');
        $this->load->library('form_validation');
	    $this->load->model(array('Admin_model'=>'admin'));
	    // Your own constructor code
	    if($this->session->userdata('usertype')!=='Admin')
	    {
	    	redirect('logout','refresh');
	    }
	}
				
	function index($page = 'admin/dashboard',$data = null)
	{
		$data['page_title'] = "Admin";
		$data['form_name'] = "Dashboard";
		$data['table_name'] = 'Dashboard';
		$data['view_customer']=$this->db->get_where('users')->result();
		$data['vendor_list']=$this->db->get('vendor')->result();
		$this->load->view('common/header');
		$this->load->view($page,$data);
		$this->load->view('common/footer');	
	}
	public function view_customer()
	{	
		$data['page_title'] = "Admin";
		$data['form_name'] = "Add Customer";
		$data['table_name'] = 'View Customer';
		$data['view_customer']=$this->db->get_where('users')->result();
		$this->load->view('common/header');
		$this->load->view('admin/view_customer',$data);
		$this->load->view('common/footer');
	}
	public function edit_customer()
	{ 
        $id = $this->uri->segment(3);
        $data['vendor_list']= $this->db->get_where('vendor',array('id'=>$id))->row();
		$page = 'admin/edit_vendor'; 
		$this->index($page,$data);
	}
	public function update_customer()
	{ 
	   $this->admin->update_vendor();
	   $this->session->set_flashdata('info_success', "Vendor has been updated successfully.");
        redirect('Admin/view_vendor', 'refresh');
	}
	public function del_customer(){
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('doctors');
        $this->session->set_flashdata('warning', "Doctor has been deleted successfully.");
        redirect('Admin/doctors', 'refresh');
    } 
	public function view_vendor()
	{	
		$data['page_title'] = "Admin";
		$data['form_name'] = "Add Vendor";
		$data['table_name'] = 'View Vendor';
		$data['vendor_list']=$this->db->get('vendor')->result();
		$this->load->view('common/header');
		$this->load->view('admin/view_vendor',$data);
		$this->load->view('common/footer');
	}
	public function edit_vendor()
	{ 
        $id = $this->uri->segment(3);
        $data['vendor_list']= $this->db->get_where('vendor',array('id'=>$id))->row();
		$page = 'admin/edit_vendor'; 
		$this->index($page,$data);
	}
	public function update_vendor()
	{ 
	   $this->admin->update_vendor();
	   $this->session->set_flashdata('info_success', "Vendor has been updated successfully.");
        redirect('Admin/view_vendor', 'refresh');
	}
	public function del_vendor(){
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('doctors');
        $this->session->set_flashdata('warning', "Doctor has been deleted successfully.");
        redirect('Admin/doctors', 'refresh');
    } 
	public function view_vendor_list()
	{	
		$data['page_title'] = "All Vendor";
		$data['form_name'] = "Add Vendor";
		$data['table_name'] = 'View Vendor Listing';
		$data['vendor_list']=$this->db->get('vendor_list')->result();
		$this->load->view('common/header');
		$this->load->view('admin/view_vendor_list',$data);
		$this->load->view('common/footer');
	} 
	public function view_blog()
	{	
		$blog['blog']=$this->db->get('blog');
		$this->load->view('admin/header');
		$this->load->view('admin/view_blog',$blog);
		$this->load->view('admin/footer');
	}
	public function add_blog()
	{
		$this->load->library('form_validation');
		$this->load->helper('file');
	
		$this->form_validation->set_rules('blog_name', 'blog name', 'required');
		$this->form_validation->set_rules('blog_image', '', 'callback_check');
		$this->form_validation->set_rules('blog_description',' blog description',' required');
		if($this->form_validation->run()== true)
		{
				$this->session->set_flashdata('Warning', "Required all Fields");
						redirect('Admin/view_blog', 'refresh');
		}
		else
		{
			$data = $this->admin->add_blog();
			$output = array ('result' =>true);
	 
			$this->session->set_flashdata('info_success', "Blog has been added successfully.");
			redirect('Admin/view_blog', 'refresh');
		}
	}
	
	
	public function del_doctor(){
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('doctors');
        $this->session->set_flashdata('warning', "Doctor has been deleted successfully.");
        redirect('Admin/doctors', 'refresh');
    }
	public function edit_doctor()
	{
	    $page = 'admin/edit_doctor';
        $id = $this->uri->segment(3);
	    $res['specialities'] = $this->db->get('specialities')->result();
        $res['result']= $this->db->get_where('doctors',array('id'=>$id))->row();
        //echo $this->db->last_query();die;
		$this->index($page,$res);
	}
	public function update_doctor()
	{ 
	   $this->admin->update_doctor();
	   $this->session->set_flashdata('info_success', "Doctor has been updated successfully.");
        redirect('Admin/doctors', 'refresh');
	}
	
	public function view_area()
	{
	    $data['area'] = $this->db->get('area')->result();
		$data['cities'] = $this->db->get_where('cities',array('state_id =' => 38)); 
        //echo $this->db->last_query();die;
		$page = 'admin/view_area';   
		$this->index($page,$data);
	}
	public function add_area()
	{
		$data = array(
            'name' => $this->input->post('area_name'),
            'city_id' => $this->input->post('city')
        );
        $this->db->insert('area',$data);
		$this->session->set_flashdata('info_success', "Area has been added successfully.");
        redirect('Admin/view_area', 'refresh');
	}
	
	
	
	
	public function view_banner()
	{
	    $data['banner'] = $this->db->get('banner')->result();
        //echo $this->db->last_query();die;
		$page = 'admin/view_banner';  
		$this->index($page,$data);
	}
	
	
	public function add_banner()
	{
		$this->form_validation->set_rules('userfile', 'File', 'trim|required');
		$data = $this->admin->add_banner();
		$output = array ('result' =>true);
		$this->session->set_flashdata('info_success', "Banner has been added successfully.");
		echo json_encode($data);
	}
	public function del_benner(){
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('banner');
        $this->session->set_flashdata('warning', "Banner has been deleted successfully.");
        redirect('Admin/view_banner', 'refresh');
    }
	
	public function view_package()
	{
	    $data['package'] = $this->db->get('package')->result();
        //echo $this->db->last_query();die;
		$page = 'admin/view_package';  
		$this->index($page,$data);
	}
	public function add_package()
	{
		$data = $this->admin->add_package();
		$output = array ('result' =>true);
		$this->session->set_flashdata('info_success', "Banner has been added successfully.");
		redirect('Admin/view_package', 'refresh');
	}
	public function members()
	{
	   // $data['branch'] = $this->admin->get_branch();
	   $data['specialities'] = $this->db->get('specialities')->result();
        //echo $this->db->last_query();die;
        $data['memers'] = $this->admin->get_member();
		$page = 'admin/members';
		$this->index($page,$data);
	}
    public function view_member(){
        $id  = $this->uri->segment(3);
        $data['details'] = $query = $this->db->get_where('members', array('id' => $id))->row();
        // echo $this->db->last_query();die;
        $this->session->set_flashdata('warning', "Member has been deleted successfully.");
        $page = 'admin/view_member';
        $this->load->view('admin/header');
        $this->load->view($page,$data);
        $this->load->view('admin/footer');
    }
    public function del_member(){
		
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('members');
        $this->session->set_flashdata('warning', "Member has been deleted successfully.");
        redirect('Admin/members', 'refresh');
    }
	
	public function services()
	{
		$page = 'admin/services';
		$this->index($page);
	}
	
	public function save_services()
	{
	    $data = $this->admin->save_services2();
	    echo json_encode($data);
	}
	public function get_all_services()
	{
	    $data = $this->admin->get_all_services();
	    echo json_encode($data);
	}
	
    public function category(){
        $page = 'admin/category';
        $data['category'] = $this->db->get('category')->result();
		$this->index($page,$data); 
    }
    public function add_category(){
        $data = array(
            'name'=>$this->input->post('name')
            );
        $insert = $this->db->insert('category', $data);
        $this->session->set_flashdata('warning', "Scientific has been updated successfully.");
        redirect('Admin/category', 'refresh');
    }
    public function del_category(){
        $id  = $this->uri->segment(3);
        $this -> db -> where('id', $id);
        $this -> db -> delete('category');
        $this->session->set_flashdata('warning', "Scientific has been deleted successfully.");
        redirect('Admin/category', 'refresh');
    }
    function subcat()
	{
		$page = 'admin/sub_category';
        $data['cat'] = $this->db->get('category')->result();
        $this->db->select('category.name,sub_category.id,sub_category.image,sub_category.link');
        $this->db->from('sub_category');
        $this->db->join('category', 'category.id = sub_category.cat_id','left');
        $data['sub_category'] = $this->db->get()->result();
		$this->index($page,$data);
	}
    function add_subcategor(){
        $config = array(
        'upload_path' => "./assets/scientific/",
        'allowed_types' => "gif|jpg|png|jpeg|pdf|csv",
        'encrypt_name' => TRUE
        );
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('file_name'))
        { 
            $data['imageError'] =  $this->upload->display_errors();
            print_r($data['imageError']);
        }
        else
        {
            $imageDetailArray = $this->upload->data();
            $image =  $imageDetailArray['file_name'];
        }
        $data = array(
            'cat_id'=>$this->input->post('cat'),
            'link'=>$this->input->post('link'),
            'image'=>$image,
            );
        $insert = $this->db->insert('sub_category', $data);
        $this->session->set_flashdata('warning', "Scientific has been updated successfully.");
        redirect('Admin/subcat', 'refresh');
    }
    function del_subcategory(){
        $id  = $this->uri->segment(3);
       
        $this -> db -> where('id', $id);
        $this -> db -> delete('sub_category');
        
        $this->session->set_flashdata('warning', "Scientific has been deleted successfully.");
        redirect('Admin/subcat', 'refresh');
    }
    
    
   	function contact_list()
	{
		$data['contact']=$this->db->get('contact')->result();
		$this->load->view('admin/header.php');
		$this->load->view('admin/contact_list',$data);
		$this->load->view('admin/footer');
	}

	function Edit_blog($id)
	{
	$data['edit_blog']=$this->admin->edit_blog($id);
	$this->load->view('admin/header');
	$this->load->view('admin/edit_blog',$data);	
	$this->load->view('admin/footer');
	}
	function Edit_blogg(){
		$this->admin->Update($this->input->post('hide'));
		redirect('Admin/view_blog','refresh');
	}
	
	function delete_blog()
	{
		if(isset($_POST['submit']))
		{
		$id=$this->input->post('del');
		$this->admin->delete_blog($id);
		$this->view_blog();
		}
		
	}
	function delete_package()
	{
		if(isset($_POST['submit']))
		{
		$id=$this->input->post('del');
		$this->admin->delete_package($id);
		$this->view_package();
		}
		
	}
	
	
	
}