<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

    //public variables...

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('admin_model');
    }

    public function index()
    {
        $this->load->view('admin/welcome');
    }

    public function war_zone($device_id)
    {
        $soldier_id = $this->admin_model->get_soldier_id($device_id);
        $soldier = $this->admin_model->get_soldier($soldier_id);
        $code = $this->admin_model->get_code();
        $this->load->view('admin/war_zone',[
            'soldier_id'=>$soldier_id,
            'start'=> $soldier->position,
            'limit'=>$soldier->target,
            'code' => $code,
        ]);
    }

    public function save($number, $status, $soldier)
    {
        $this->db->select("*");
        $this->db->where(array(
            'number'=>$number,
            'status'=>$status,
        ));
        $result = $this->db->get('numbers')->num_rows();
        if($result > 0)
            return 0;

        $arr1 = array(
            'number'=>$number,
            'status'=>$status,
        );

        $this->db->trans_start();

        if($status != 2)
            $this->db->insert('numbers', $arr1);

        $this->admin_model->update_army($number, $soldier);

        return $this->db->trans_complete();

    }

    public function refresh($device_id)
    {
        $this->db->where('device',$device_id);
        echo $this->db->update('army', array('on_duty'=>0));
    }

}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */