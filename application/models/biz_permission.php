<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 用户权限检测类
 */
class Biz_permission extends CI_Model {

    //访问，下载，等基本权限
    private $base_arr = array('report', 'visit', 'read', 'post', 'reply', 'upload', 'download');
    //帖子的所有可能的权限action
    private $manage_arr = array(
        'allow_top', // '置顶'
        'is_toppost', // '置顶回帖'
        'allow_digest', // '加精'
        'is_highlight', // '高亮'
        'is_bump', // '提升'
        'is_recommend', // '推荐'
        'is_edit', // '编辑'
        'is_check', // '审核'
        'is_copy', // '复制'
        'is_merge', // '合并'
        'is_split', // '切分'
        'is_move', // '移动'
        'is_editcategory', // '编辑分类'
        'is_ban', // '屏蔽'
        'is_close', // '关闭'
        'is_del', // '删除'
        'is_edituser', // '编辑用户'
        'is_banuser', // '禁止用户'
        'is_viewip', // '查看IP'
        'is_banip', // '禁止IP'
        'is_viewlog', // '查看管理日志',
    );
    //帖子的作者所拥有的权限action
    private $owner_arr = array(
        'is_edit', // '编辑'
        'is_del', // '删除'
        'is_move', // '移动'
        'is_editcategory', // '编辑分类'
        'is_close', // '关闭'
        'is_copy', // '复制'
        'is_merge', // '合并'
        'is_split', // '切分'
    );

    function __construct() {
        parent::__construct();
        $this->load->model(array('forums_model', 'topics_model', 'groups_admin_model', 'users_belong_model'));
    }

    /**
     * 返回某个用户对某个帖子的操作权限
     * @return type
     */
    public function check_manage($topic_id, $action) {
        $action = $this->get_full_action($action);
        $topic = $this->topics_model->get_by_id($topic_id);
        if ($topic['author_id'] == $this->user['id'] && in_array($action, $this->owner_arr)) {
            return true;
        }
        //获取管理权限
        $admin_permission = $this->groups_model->get_admin_permission($topic['forum_id']);
        return $admin_permission[$action];
    }

    /**
     * 传入版块id和动作,检测当前用户有没有相应的权限
     * @param int $forum_id 版块id
     * @param string $action 相应的动作比如（'report','visit','read','post','reply','upload','download'）
     * @return boolean
     */
    public function check_base($action, $forum_id = '') {
        if (in_array($action, $this->base_arr)) {
            $group_key = 'is_' . $action;
            //如果用户组里有设置，并且设置为1
            if (isset($this->user['group'][$group_key]) && $this->user['group'][$group_key] == 1) {
                if ('report' == $action) {//举报是任何版块都允许的。
                    return TRUE;
                }
                if (!empty($forum_id)) {
                    $forum = $this->forums_model->get_by_id($forum_id);
                    $forum_key = 'allow_' . $action;
                    $permission = $forum[$forum_key];
                    if (empty($permission)) {//版块里面没有设置
                        return TRUE;
                    } else {
                        $permission = explode(',', $permission);
                        $tmp = array_intersect($this->user['groups'], $permission);
                        if (!empty($tmp)) {
                            return TRUE;
                        }else{
                            //为空说明版块不允许操作。
                            return FALSE;
                        }
                    }
                } else {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * 用户组的审核机制和版块自己的审核机制，取较严厉者。
     * @param type $forum_id
     * @return int 返回此版块下的帖子是否需要审核。
     */
    public function get_check($forum_id) {
        $forum = $this->forums_model->get_by_id($forum_id);
        $forum_check = $forum['check'];
        $group_check = $this->user['group']['check'];
        return max($forum_check, $group_check);
    }

    /**
     * 传入版块id和编辑器关键字，返回此论坛版块是否拥有此权限。
     * @param type $type
     * @param type $forum_id
     * @return int
     */
    public function get_is($type,$forum_id='') {
        $types = array('bbcode', 'smilies', 'media', 'html','anonymous','hide','sign','permission');
        if (in_array($type, $types)) {
            $group_key = 'is_' . $type;
            if ($this->user['group'][$group_key] == 1) {
                if(!empty($forum_id)){
                    $forum = $this->forums_model->get_by_id($forum_id);
                    $forum_key = 'is_' . $type;
                    if (empty($forum[$forum_key])) {
                        return 1;
                    } else {
                        return $forum[$forum_key];
                    }
                }else{
                    return 1;
                }
            }
        }
        return 0;
    }
    
    /**
     * 传入一个action,返回在数据库中的字段值。比如：top=>allow_top
     * @param type $action
     * @return type
     */
    private function get_full_action($action) {
        foreach ($this->manage_arr as $value) {
            list($null, $tmp_action) = explode('_', $value);
            if ($tmp_action == $action) {
                return $value;
            }
        }
    }

    public function check_post_num() {
        $is_perm = TRUE;
        if ($this->user['group']['max_post_num'] > 0) {
            $is_perm = $this->user['group']['max_post_num'] > $this->user['today_posts'];
            if ($is_perm && $this->user['group']['min_pertime'] > 0) {
                //获取最后发帖时间
                $is_perm = $this->user['group']['min_pertime'] <= ($this->time - $this->user['last_post_time']);
            }
        }
        return $is_perm;
    }

    public function check_upload_num() {
        $is_perm = TRUE;
        if ($this->user['group']['max_upload_num'] > 0) {
            $is_perm = $this->user['group']['max_upload_num'] > $this->user['today_uploads'];
        }
        return $is_perm;
    }

    public function is_today($this_time) {
        return date('Ymd', $this_time) == date('Ymd', $this->time);
    }

}

?>
