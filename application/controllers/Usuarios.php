<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'email', 'form_validation']);
    }

    public function login() {
        if ($this->input->post()) {
            // Validação do formulário
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]');

            if ($this->form_validation->run() === FALSE) {
                $data['erro'] = validation_errors();
                $data['tela'] = 'login';
                $this->load->view('usuarios/login', $data);
                return;
            }

            $email = $this->input->post('email');
            $senha = $this->input->post('senha');

            // Verificar existência do domínio
            $dominio = explode('@', $email)[1];
            if (!checkdnsrr($dominio, 'MX')) {
                $data['erro'] = 'O domínio do email não existe.';
                $data['tela'] = 'login';
                $this->load->view('usuarios/login', $data);
                return;
            }

            $usuario = $this->Usuario_model->login($email, $senha);
            
            if ($usuario) {
                if (!$usuario->verificado) {
                    $data['erro'] = 'Por favor, verifique seu email antes de fazer login.';
                    $data['tela'] = 'login';
                    $this->load->view('usuarios/login', $data);
                    return;
                }
                // Armazena todos os dados do usuário na sessão
                $this->session->set_userdata('usuario_id', $usuario->id);
                $this->session->set_userdata('usuario_nome', $usuario->nome);
                $this->session->set_userdata('usuario_email', $usuario->email);
                
                $this->session->set_flashdata('mensagem', 'Login realizado com sucesso!');
                redirect('tarefas');
            } else {
                $data['erro'] = 'Email ou senha incorretos.';
                $data['tela'] = 'login';
                $this->load->view('usuarios/login', $data);
            }
        } else {
            $data['tela'] = 'login';
            $this->load->view('usuarios/login', isset($data) ? $data : []);
        }
    }

    public function register() {
        if ($this->input->post()) {
            // Validação do formulário
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
            $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[8]');
            $this->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[2]');

            if ($this->form_validation->run() === FALSE) {
                $data['erro'] = validation_errors();
                $data['tela'] = 'register';
                $this->load->view('usuarios/login', $data);
                return;
            }

            $email = $this->input->post('email');
            $senha = $this->input->post('senha');
            $nome = $this->input->post('nome');

            // Verificar existência do domínio
            $dominio = explode('@', $email)[1];
            if (!checkdnsrr($dominio, 'MX')) {
                $data['erro'] = 'O domínio do email não existe.';
                $data['tela'] = 'register';
                $this->load->view('usuarios/login', $data);
                return;
            }
            
            // Verificar se o email já existe
            if ($this->Usuario_model->email_existe($email)) {
                $data['erro'] = 'Este e-mail já está cadastrado. <a href="' . site_url('usuarios/login') . '">Faça login aqui</a>';
                $data['tela'] = 'register';
                $this->load->view('usuarios/login', $data);
                return;
            }

            // Gerar token de verificação
            $token = bin2hex(openssl_random_pseudo_bytes(32));

            // Registrar o usuário
            $usuario_id = $this->Usuario_model->register($email, $senha, $nome, $token);
            
            if ($usuario_id) {
                // Configurar email
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => 'smtp.gmail.com',
                    'smtp_port' => 587,
                    'smtp_user' => 'seu-email@gmail.com', // Substitua pelo seu email
                    'smtp_pass' => 'sua-senha-de-app', // Substitua pela senha de aplicativo
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'newline' => "\r\n",
                    'wordwrap' => TRUE
                ];
                $this->email->initialize($config);

                $this->email->from('seu-email@gmail.com', 'Sistema de Tarefas');
                $this->email->to($email);
                $this->email->subject('Verifique seu email - Sistema de Tarefas');
                $verification_link = site_url('usuarios/verificar/' . $token);
                $this->email->message("
                    <h2>Bem-vindo ao Sistema de Tarefas!</h2>
                    <p>Por favor, clique no link abaixo para verificar seu email:</p>
                    <p><a href='$verification_link' style='color: #4361ee; text-decoration: none;'>Verificar Email</a></p>
                    <p>Se você não se registrou, ignore este email.</p>
                ");

                if ($this->email->send()) {
                    $data['mensagem'] = 'Um email de verificação foi enviado para ' . $email . '. Por favor, verifique sua caixa de entrada.';
                    $data['tela'] = 'login';
                    $this->load->view('usuarios/login', $data);
                } else {
                    $data['erro'] = 'Erro ao enviar o email de verificação. Tente novamente.';
                    $data['tela'] = 'register';
                    $this->load->view('usuarios/login', $data);
                }
            } else {
                $data['erro'] = 'Erro ao criar usuário. Tente novamente.';
                $data['tela'] = 'register';
                $this->load->view('usuarios/login', $data);
            }
        } else {
            $data['tela'] = 'register';
            $this->load->view('usuarios/login', isset($data) ? $data : []);
        }
    }

    public function verificar($token) {
        if ($this->Usuario_model->verificar_email($token)) {
            $this->session->set_flashdata('mensagem', 'Email verificado com sucesso! Agora você pode fazer login.');
            $data['tela'] = 'login';
        } else {
            $this->session->set_flashdata('erro', 'Token inválido ou expirado.');
            $data['tela'] = 'login';
        }
        $this->load->view('usuarios/login', $data);
    }

    public function logout() {
        // Remove todos os dados do usuário da sessão
        $this->session->unset_userdata(['usuario_id', 'usuario_nome', 'usuario_email']);
        $this->session->set_flashdata('mensagem', 'Logout realizado com sucesso!');
        redirect('usuarios/login');
    }
}