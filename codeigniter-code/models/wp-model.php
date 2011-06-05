<?php

/**
 * The model that handles the wordpress database
 */
class Wp extends CI_Model {
    /**
     * The post id
     * @var int
     */
    public $id;
    
    /**
     * The category_id
     * @var int
     */
    public $category_id;
    
    /**
     * Database
     * @var resource
     */
    public $db = null;

	public $user_id = 0;
    
    function __construct() {
		parent::__construct();
		$this->load->config('wordpress');
		//Database is autoloaded
		$this->db = $this->load->database('wordpress', true);
    }
    
    //Set the id
    public function set_id($id = 0){
		$this->id = $id;
    }
    
    public function set_category_id($id = 0) {
		$this->category_id = $id;
    }

	public function set_user_id($id = 0){
		$this->user_id = $id;
	}
    
    public function post($comments = true){
		if ((int)$this->id <= 0) return;
		$post = $this->db->select()->from('posts')->where('ID', $this->id)->where('post_type', 'post')->get()->first_row();
		if ($comments == true){
	    	$post->comments = $this->comments();
		}
		return $post;
    }
    
    public function posts() {
		return $this->db->select()->from('posts')->where('post_type', 'post')->get()->first_row();
    }
    
    public function links() {
		return $this->db->select()->from('links')->where('link_visible', 'Y')->get()->result();
    }
    
    public function categories() {
		$sql = "SELECT *
				FROM `wp_term_taxonomy`, `wp_terms`
				WHERE `wp_terms`.`term_id`=1 and `taxonomy` = 'category' AND `wp_terms`.`term_id`=`wp_term_taxonomy`.`term_id`
				ORDER BY `wp_term_taxonomy`.`taxonomy` ";
		return $this->db->query($sql)->result();
    }
    
    public function category() {
		if ((int)$this->id <= 0) return;
		$sql = "SELECT * FROM `wp_posts`, wp_term_relationships, wp_term_taxonomy 
				WHERE `wp_term_relationships`.`object_id` = `wp_posts`.`id` AND `post_status` = 'publish' 
				AND `post_type` = 'post' AND `wp_term_taxonomy`.`term_taxonomy_id` = `wp_term_relationships`.`term_taxonomy_id` 
				AND `wp_term_taxonomy`.`taxonomy` = 'category' AND `wp_term_taxonomy`.`term_id` = {$this->category_id}";
		return $this->db->query($sql)->result();
    }
    
    public function comments() {
		if ((int)$this->id <= 0) return;
		return $this->db->select()->from('comments')->where('comment_post_ID', $this->id)->get()->result();
    }
    
    public function user(){
		return $this->db->select()->from('users')->where('ID', $this->user_id)->get()->first_row();
    }
}