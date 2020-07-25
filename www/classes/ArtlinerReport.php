<?php
class classes_ArtlinerReport extends core_DBObject {
    public $id_calc = null;
    public $id_order = null;
    public $id_order_artliner = null;
    public $date_artliner = null;
    public $id_client = null;
    public $product_name = null;
    public $product_count = null;
    public $product_size = null;
    public $product_pages = null;
    public $product_laminat = null;
    public $product_summa = null;
    public $payment_type = null;
    public $payment = null;
    public $payment_date = null;
    public $carriers_type = null;
    public $carriers_code = null;
    public $carriers_date = null;
    public $post_index = null;
    public $post_address = null;
    public $post_username = null;
    public $post_email = null;
    public $post_phone = null;

    public function __construct()
    {
        parent::__construct('artliner_report', 'id', 'classes_ArtlinerReport');
    }
}
?>