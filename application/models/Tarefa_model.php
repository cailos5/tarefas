<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarefa_model extends CI_Model {

    protected $table = 'tarefas';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($usuario_id, $status = null, $prioridade = null) {
        $this->db->where('usuario_id', $usuario_id);
        
        if (!empty($status) && $status != 'all') {
            $this->db->where('status', $status);
        }
        
        if (!empty($prioridade) && $prioridade != 'all') {
            // Converter texto para valor numérico se necessário
            $prioridade_valores = [
                'baixa' => 0,
                'media' => 1, 
                'alta' => 2
            ];
            
            if (array_key_exists($prioridade, $prioridade_valores)) {
                $this->db->where('prioridade', $prioridade_valores[$prioridade]);
            } else {
                $this->db->where('prioridade', $prioridade);
            }
        }
        
        $this->db->order_by('prioridade', 'DESC');
        $this->db->order_by('data_vencimento', 'ASC');
        $this->db->order_by('data_criacao', 'DESC'); // Added ordering by creation date
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    } 
    
    public function insert($data) {
        $data['data_criacao'] = date('Y-m-d H:i:s'); // Set creation date
        return $this->db->insert($this->table, $data);
    }    
    
    public function update($id, $data) {
        unset($data['data_criacao']); // Prevent updating creation date
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
    
    /**
     * Método adicional para contar tarefas por status
     */
    public function count_by_status($usuario_id) {
        $this->db->select('status, COUNT(*) as total');
        $this->db->where('usuario_id', $usuario_id);
        $this->db->group_by('status');
        $query = $this->db->get($this->table);
        return $query->result();
    }
}