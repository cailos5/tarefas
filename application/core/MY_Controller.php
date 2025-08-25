<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Usuario_model');

        // Verificar se o usuário está logado
        if (!$this->session->userdata('usuario_id')) {
            $this->session->set_flashdata('erro', 'Por favor, faça login para acessar esta página.');
            redirect('usuarios/login');
        }

        // Verificar se o email está verificado
        $usuario = $this->Usuario_model->get_by_id($this->session->userdata('usuario_id'));
        if (!$usuario || !$usuario->verificado) {
            $this->session->set_flashdata('erro', 'Por favor, verifique seu email antes de acessar esta página.');
            $this->session->unset_userdata(['usuario_id', 'usuario_nome', 'usuario_email']);
            redirect('usuarios/login');
        }
    }
}