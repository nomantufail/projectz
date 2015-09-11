<?php
class Admin_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function update_army($position, $soldier)
    {
        $data = array(
            'position' => $position,
            'on_duty' => 1
        );

        $this->db->where('id', $soldier);
        $this->db->update('army', $data);
    }

    public function get_soldier_id($device_id)
    {
        $this->db->select('id, position, (target - position) as remaining');
        $this->db->limit(1);
        $this->db->where('on_duty',0);
        $result = $this->db->get('army')->result();
        if(sizeof($result) > 0)
        {
            if($result[0]->remaining > 0){
                $this->db->where('id',$result[0]->id);
                $this->db->update('army',array('device'=>$device_id, 'on_duty'=>1));
                return intval($result[0]->id);
            }
        }

        $this->db->select('MAX(target) as position');
        $result = $this->db->get('army')->result();
        $position = 0;
        if($result[0]->position != null){
            $position = $result[0]->position;
        }

        $target = $position + 10000;

        $this->db->insert('army', array(
            'position'=>$position,
            'target' => $target,
            'device' => $device_id,
            'on_duty' => 1,
        ));

        return $this->db->insert_id();

    }


    public function get_soldier($id)
    {
        $this->db->select('*');
        $this->db->where('id',$id);
        $result = $this->db->get('army')->result();
        return $result[0];
    }

    public function get_code()
    {
        return 7030000000;
    }
}