<?php

class Forums_model extends CI_Model {

    public $forums;
    private $table = 'forums';
    
    function __construct() {
        parent::__construct();
    }

    function initialize() {
//        $query = $this->db->query("select u.* from users u left join users_extra ex on ex.user_id=u.id where u.id=$user_id limit 0,1");
////        $this->db->select('id,email');
////        $query = $this->db->get('users',2,0);
////        $this->db->from('id');
////        var_dump($this->db->call_function('get_client_info'));
//        $user = $query->row_array();
//        if (!empty($user['group_id'])) {
//            $query = $this->db->get_where('groups', array('id'=>$user['group_id']), 1, 0);
//            $user_group = $query->row_array();
//            $user['group'] = $user_group;
//        }
////        var_dump($user);die;
        return TRUE;
    }

    function get_forums($cache = TRUE) {
        if ($cache && !empty($this->forums)) {
            return $this->forums;
        }
        $this->db->order_by("display_order");
        $query = $this->db->get($this->table);
        $forums = $query->result_array();
        if (!empty($forums)) {
            $forums = $this->format($forums);
        }
        $this->forums = $forums;
        return $forums;
    }

    private function format($forums) {
        if (empty($forums)) {
            return array();
        }
        $new_forums = $tmp = array();
        foreach ($forums as $key => $value) {
            $tmp[$value['parent_id']][] = $value;
        }
        //最多三级分类
        $new_forums = $tmp[0];
        unset($tmp[0]);
        foreach ($new_forums as $key => $value) {//最多三级分类
            if(isset($tmp[$value['id']])){
                $new_forums[$key]['sub'] = $tmp[$value['id']];
                unset($tmp[$value['id']]);
                foreach ($new_forums[$key]['sub'] as $k => $v) {
                    if(isset($tmp[$v['id']])){
                        $new_forums[$key]['sub'][$k]['sub'] = $tmp[$v['id']];
                    }
                }
            }
        }
        return $new_forums;
    }
    public function update_old($data){
        foreach ($data as $key=>$val){
            if($val['type']==$type){
                $name = trim($val['name']);
                if(!empty($name)){
                    $insert_data['display_order'] = intval($val['order']);
                    $insert_data['name'] = $name;
                    $insert_data['parent_id'] = is_numeric($val['pid'])?$val['pid']:$tmp_pids[$val['pid']];
                    $insert_data['type'] = $type_arr[$val['type']];
                    $insert_data['manager'] = trim(preg_replace('/[,;\s]+/', ',', $val['manager']),',');
                    if($this->db->insert($this->table, $insert_data)){
                        $id = $this->db->insert_id();
                        $tmp_pids[$key] = $id;
                    }else{
                        return FALSE;
                    }
                }
                unset($data[$key]);
            }
        }
        return TRUE;
    }
    
    public function insert_new($data){
        $type_arr = array(1=>'group', 2=> 'forum', 3=> 'sub');
        $tmp_pids = array();
        for($type=1;$type<=3;$type++){
            foreach ($data as $key=>$val){
                if($val['type']==$type){
                    $name = trim($val['name']);
                    if(!empty($name)){
                        $insert_data['display_order'] = intval($val['order']);
                        $insert_data['name'] = $name;
                        $insert_data['parent_id'] = is_numeric($val['pid'])?$val['pid']:$tmp_pids[$val['pid']];
                        $insert_data['type'] = $type_arr[$val['type']];
                        $insert_data['manager'] = trim(preg_replace('/[,;\s]+/', ',', $val['manager']),',');
                        if($this->db->insert($this->table, $insert_data)){
                            $id = $this->db->insert_id();
                            $tmp_pids[$key] = $id;
                        }else{
                            return FALSE;
                        }
                    }
                    unset($data[$key]);
                }
            }
        }
        return TRUE;
    }
}

?>
