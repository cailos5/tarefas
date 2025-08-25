<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarefas extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tarefa_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'upload', 'form_validation']);
    }

    public function index() {
        $usuario_id = $this->session->userdata('usuario_id');
        $status = $this->input->get('status');
        $prioridade = $this->input->get('priority');
        
        $data['tarefas'] = $this->Tarefa_model->get_all($usuario_id, $status, $prioridade);
        $this->load->view('tarefas/listar', $data);
    }

    public function add() {
        $usuario_id = $this->session->userdata('usuario_id');
        
        if ($this->input->method() === 'post') {
            // Validação do formulário
            $this->form_validation->set_rules('descrição', 'Descrição', 'required|trim');
            $this->form_validation->set_rules('data_vencimento', 'Data de Vencimento', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[pendente,andamento,concluido]');
            $this->form_validation->set_rules('prioridade', 'Prioridade', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]');

            if ($this->form_validation->run() === FALSE) {
                $data['erro'] = validation_errors();
                $this->load->view('tarefas/adicionar', $data);
                return;
            }

            // Configuração do upload de imagem
            $imagem = null;
            if (!empty($_FILES['imagem']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('imagem')) {
                    $upload_data = $this->upload->data();
                    $imagem = $upload_data['file_name'];
                } else {
                    $data['erro'] = $this->upload->display_errors();
                    $this->load->view('tarefas/adicionar', $data);
                    return;
                }
            }
            
            $data = [
                'descricao' => $this->input->post('descrição'),
                'data_vencimento' => $this->input->post('data_vencimento'),
                'status' => $this->input->post('status'), 
                'prioridade' => (int) $this->input->post('prioridade'),
                'usuario_id' => $usuario_id,
                'imagem' => $imagem
            ];
            
            if ($this->Tarefa_model->insert($data)) {
                $this->session->set_flashdata('mensagem', 'Tarefa adicionada com sucesso!');
            } else {
                $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa. Tente novamente.');
            }
            
            redirect('tarefas');
        } else {
            $this->load->view('tarefas/adicionar');
        }
    }

    public function edit($id) {
        $usuario_id = $this->session->userdata('usuario_id');
        
        // Verificar se a tarefa pertence ao usuário
        $tarefa = $this->Tarefa_model->get_by_id($id);
        if (!$tarefa || $tarefa->usuario_id != $usuario_id) {
            show_404();
        }
        
        if ($this->input->method() === 'post') {
            // Validação do formulário
            $this->form_validation->set_rules('descrição', 'Descrição', 'required|trim');
            $this->form_validation->set_rules('data_vencimento', 'Data de Vencimento', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[pendente,andamento,concluido]');
            $this->form_validation->set_rules('prioridade', 'Prioridade', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]');

            if ($this->form_validation->run() === FALSE) {
                $data['erro'] = validation_errors();
                $data['tarefa'] = $tarefa;
                $this->load->view('tarefas/editar', $data);
                return;
            }

            // Configuração do upload de imagem
            $imagem = $tarefa->imagem; // Mantém a imagem atual por padrão
            
            if (!empty($_FILES['imagem']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('imagem')) {
                    // Remove a imagem anterior se existir
                    if ($tarefa->imagem && file_exists('./uploads/' . $tarefa->imagem)) {
                        unlink('./Uploads/' . $tarefa->imagem);
                    }
                    
                    $upload_data = $this->upload->data();
                    $imagem = $upload_data['file_name'];
                } else {
                    $data['erro'] = $this->upload->display_errors();
                    $data['tarefa'] = $tarefa;
                    $this->load->view('tarefas/editar', $data);
                    return;
                }
            }
            
            $data = [
                'descricao' => $this->input->post('descrição'),
                'data_vencimento' => $this->input->post('data_vencimento'),
                'status' => $this->input->post('status'),
                'prioridade' => (int) $this->input->post('prioridade'),
                'imagem' => $imagem
            ];
            
            if ($this->Tarefa_model->update($id, $data)) {
                $this->session->set_flashdata('mensagem', 'Tarefa atualizada com sucesso!');
            } else {
                $this->session->set_flashdata('erro', 'Erro ao atualizar tarefa. Tente novamente.');
            }
            
            redirect('tarefas');
        } else {   
            $data['tarefa'] = $tarefa;
            $this->load->view('tarefas/editar', $data);
        }
    }

    public function delete($id) {
        $usuario_id = $this->session->userdata('usuario_id');
        
        // Verificar se a tarefa pertence ao usuário
        $tarefa = $this->Tarefa_model->get_by_id($id);
        if (!$tarefa || $tarefa->usuario_id != $usuario_id) {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => false, 'message' => 'Tarefa não encontrada']));
                return;
            }
            show_404();
        }
        
        // Remove a imagem se existir
        if ($tarefa->imagem && file_exists('./Uploads/' . $tarefa->imagem)) {
            unlink('./Uploads/' . $tarefa->imagem);
        }
        
        if ($this->Tarefa_model->delete($id)) {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => true, 'message' => 'Tarefa excluída com sucesso!']));
                return;
            }
            $this->session->set_flashdata('mensagem', 'Tarefa excluída com sucesso!');
        } else {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['success' => false, 'message' => 'Erro ao excluir tarefa. Tente novamente.']));
                return;
            }
            $this->session->set_flashdata('erro', 'Erro ao excluir tarefa. Tente novamente.');
        }
        
        // Se não for AJAX, redireciona normalmente
        if (!$this->input->is_ajax_request()) {
            redirect('tarefas');
        }
    }
    
    public function change_status($id) {
        $usuario_id = $this->session->userdata('usuario_id');
        
        // Verificar se a tarefa pertence ao usuário
        $tarefa = $this->Tarefa_model->get_by_id($id);
        if (!$tarefa || $tarefa->usuario_id != $usuario_id) {
            echo json_encode(['success' => false, 'message' => 'Tarefa não encontrada']);
            return;
        }
        
        $novo_status = $this->input->post('status');
        $status_permitidos = ['pendente', 'andamento', 'concluido'];
        
        if (in_array($novo_status, $status_permitidos)) {
            if ($this->Tarefa_model->update($id, ['status' => $novo_status])) {
                echo json_encode(['success' => true, 'message' => 'Status atualizado']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Status inválido']);
        }
    }
}