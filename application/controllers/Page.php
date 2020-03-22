<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    function index(){

        $this->load->helper('url');

        if(isset($_POST['cek']) || isset($_GET['del']) ){

            $data = "";
            if(isset($_POST['cek'])){
                if($_POST['cek'] == 'insert'){
                    $urll = site_url('/rest/insert');
                    $data = ['jenis' => $this->input->post('jenis'), 'harga' => $this->input->post('harga'), 'jumlah' => $this->input->post('jumlah')];
                }else if($_POST['cek'] == 'edit'){
                    $urll = site_url('/rest/edit');
                    $data = ['id' => $this->input->post('id'), 'jenis' => $this->input->post('jenis'), 'harga' => $this->input->post('harga'), 'jumlah' => $this->input->post('jumlah')];
                }
            }

            if(isset($_GET['del'])){
                $data = ['id' => $this->input->get('del')];
                $urll = site_url('/rest/delete');
            }
            
            $ch = curl_init($urll);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respon = curl_exec($ch);
            curl_close($ch);
            $okeh = json_decode($respon, false);
            $this->session->set_flashdata('msg', $okeh->msg);
            $this->session->set_flashdata('status', $okeh->status);

            redirect(site_url(),'refresh');
        }

        $this->load->view('home');
    }
}