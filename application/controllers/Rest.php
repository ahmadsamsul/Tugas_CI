<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Rest extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
		parent::__construct();		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
 
	}

	public function index()
	{
		$data = $this->db->get('ayam')->result();
		if($data){
			echo json_encode(['status' => 1 ,'data' => $data]); 
			exit();
		}
		echo json_encode(['status' => 0]);
		exit();
	}

    function insert() {

		if(!$this->input->post('jenis') || !$this->input->post('jumlah') || !$this->input->post('harga')){
			echo json_encode(['status' => 0, 'msg' => 'isi dulu.']);
			exit();
		}

		$this->form_validation->set_rules('jenis', $this->input->post('jenis'), 'trim|required|is_unique[ayam.jenis]'); 

		if($this->form_validation->run() == false){
			echo json_encode(['status' => 0, 'msg' => 'keyword ada yang sama.']); 
			exit();
		}

        $data = array(
                    'jenis'		=> $this->input->post('jenis'),
                    'jumlah'		=> $this->input->post('jumlah'),
					'harga'			=> $this->input->post('harga')
				); 

        $insert = $this->db->insert('ayam', $data); 
        if ($insert) {
			echo json_encode(['status' => 1, 'msg' => 'sukses.']);
			exit();
        } else {
            echo json_encode(['status' => 0, 'msg' => 'gagal masukkan data.']);
			exit();
		}
		
	}
	function edit() {

		if(!$this->input->post('jenis') || !$this->input->post('jumlah') || !$this->input->post('harga')){
			echo json_encode(['status' => 0, 'msg' => 'isi dulu.']);
			exit();
		}

		$query = $this->db->get_where('ayam', array('jenis' => $this->input->post('jenis')));
    	if($query->num_rows() > 1){
    	    echo json_encode(['status' => 0, 'msg' => 'keyword ada yang sama/id tidak terkirim.']);
			exit();
    	}

		$data = array(
			'id'			=> $this->input->post('id'),
			'jenis'		=> $this->input->post('jenis'),
			'jumlah'		=> $this->input->post('jumlah'),
			'harga'			=> $this->input->post('harga')
		);

		$edit = $this->db->replace('ayam', $data);

		if ($edit) {
			echo json_encode(['status' => 1, 'msg' => 'sukses edit - '.$this->input->post('jenis').'.']);
			exit();
        } else {
            echo json_encode(['status' => 0, 'msg' => 'gagal edit data.']);
			exit();
		}
	}

	function delete(){
		
		if(!$this->input->post('id')){
			echo json_encode(['status' => 0, 'msg' => 'id tidak terdeteksi.']);
			exit();
		}

		$query = $this->db->get_where('ayam', array('id' => $this->input->post('id')));
		if(!$query->num_rows()){
			echo json_encode(['status' => 0, 'msg' => 'Tidak ada data untuk dihapus.']);
			exit();
		}

		foreach($query->result() as $ntod){
			$kw = $ntod->jenis;
		}

		$delete = $this->db->delete('ayam', array('id' => $this->input->post('id')));

		if ($delete) {
			echo json_encode(['status' => 1, 'msg' => 'sukses hapus - '.$kw.'.']);
			exit();
        } else {
            echo json_encode(['status' => 0, 'msg' => 'gagal delete data.']);
			exit();
		}
	}

}
