<?php
	class classes_AccessRole extends core_DBObject {
		var $level_id = null;
		var $user_id = null;
		var $date_start = null;
		var $date_end = null;
		
		public function __construct()
        {
            parent::__construct('accessrole', 'ID', 'classes_AccessRole');
        }
	}
?>