<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    private $table = 'usuarios';
    
    public function __construct() {
        parent::__construct();
        $this->load->database(); // garante que o banco foi carregado
    }

    public function get_usuario($email, $senha) {
        return $this->db
            ->get_where('usuarios', ['email' => $email, 'senha' => $senha])
            ->row();
    }

    public function criar($data) {
        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        return $this->db->insert($this->table, $data);
    }

    public function login($email, $senha) {
        $usuario = $this->db->get_where($this->table, ['email' => $email])->row();
        if ($usuario && password_verify($senha, $usuario->senha)) {
            return $usuario;
        }
        return false;
    }

    public function register($email, $senha, $nome, $token = null) {
        $data = [
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'nome' => $nome,
            'verificado' => FALSE,
            'token_verificacao' => $token,
            'token_expiracao' => date('Y-m-d H:i:s', strtotime('+24 hours'))
        ];
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    public function email_existe($email) {
        $this->db->where('email', $email);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function verificar_email($token) {
        $this->db->where('token_verificacao', $token);
        $this->db->where('verificado', FALSE);
        $this->db->where('token_expiracao >=', date('Y-m-d H:i:s'));
        $usuario = $this->db->get($this->table)->row();
        if ($usuario) {
            $this->db->update($this->table, [
                'verificado' => TRUE,
                'token_verificacao' => NULL,
                'token_expiracao' => NULL
            ], ['id' => $usuario->id]);
            return TRUE;
        }
        return FALSE;
    }
}