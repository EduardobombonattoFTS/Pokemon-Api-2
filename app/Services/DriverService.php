<?php

namespace App\Services;

use App\Models\Driver;

class DriverService {

    protected Driver $model;
    private bool $viewResponse = true;

    public function __construct(Driver $model = null) {
        $this->model = $model;
    }

    public function viewResponse(bool $status = null) {
        $this->viewResponse = ($status ?? ($this->viewResponse ?? true));

        return $this;
    }

    public function createResponse($message = null, $data = null, $errors = null, int $statusCode = null, bool $status = false, bool $breakCode = false) {
        $response = [];

        $response["message"] = $message;

        if (is_string($data)) $data = [$data];
        $response["data"] = $data;

        if (is_string($errors)) $errors = [$errors];
        $response["errors"] = $errors;

        $response["statusCode"] = $statusCode;

        if (!is_null($status)) $response["status"] = $status;

        if ($breakCode) {
            header("Content-Type: application/json");
            http_response_code($statusCode);
            echo json_encode($response);
            exit;
        }

        return response()->json($response, $statusCode);
    }

    public function success($message, $data = [], $statusCode = 200, $breakCode = true) {
        return $this->createResponse($message, $data, null, $statusCode, true, $breakCode);
    }
    public function notFound($messageError, $data = [], $breakCode = true) {
        return $this->createResponse("Nenhuma informação existente!", $data, $messageError, 404, false, $breakCode);
    }
    public function fail($messageError, $data = [], $statusCode = 400, $breakCode = true) {
        return $this->createResponse($messageError, $data, $messageError, $statusCode, false, $breakCode);
    }

    public function index() {
        $data = $this->getAllDataFromDatabase()->getData();
        return view('driver_index', [
            'data' => $data,
        ]);
    }

    public function getAllDataFromDatabase($viewResponse = null) {
        $this->viewResponse($viewResponse);

        try {
            $all = $this->model->orderBy('id')->get();
            if ($all->count() > 0)
                return $this->success("Registros retornados.", $all, 200, false);

            return $this->notFound("Nenhum registro retornado.", [], false);
        } catch (\Exception $e) {

            return $this->fail("Houve uma falha ao retornar os registros", $e);
        }
    }

    protected function validateCpf($cpf) {
        if (empty($cpf)) {
            return "O campo CPF obrigatório, por favor preencher.";
        }
        if (strlen($cpf) != 11) {
            return "O CPF inválido. Deve ter 11 dígitos.";
        }
        if (!ctype_digit($cpf)) {
            return "O CPF inválido. Deve conter apenas números.";
        }
        if ($this->model->where('cpf', $cpf)->first()) {
            return "CPF já cadastrado em nosso sistema .";
        }
        return true;
    }

    public function createDriverOnDatabase(array $data, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        // Valida o CPF
        $cpfValidation = $this->validateCpf($data['cpf']);
        if ($cpfValidation !== true) {
            return $this->fail($cpfValidation, [], false);
        }

        try {
            $create = $this->model->create($data);
            if (!$create)
                return $this->notFound("Não foi possível cadastrar o motorista, favor verificar os dados.", [], false);

            return $this->success("Motorista cadastrado com sucesso.", $create, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Falha ao inserir o cadastro do motorista.", $e);
        }
    }

    public function updateDriverOnDatabase($data, $uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        if (!$this->model->where('uuid', $uuid)) {
            return $this->fail("uuid errado", [], false);
        }
        try {

            $update = $this->model->where('uuid', $uuid);
            if ($update->doesntExist())
                return $this->notFound("Motorista não encontrado, favor verificar as informações", [], false);

            $update = $update->first();
            foreach ($data as $key => $value) {
                if ($value !== null) $update->$key = $value;
            }
            if (!$update->save())
                return $this->notFound("Não foi possivel salvar as alterações do registro.", [], false);

            return $this->success("Dados do motorista alterados com sucesso.", $data, 200, false);
        } catch (\Exception $e) {
            return $this->fail("Falha ao atualizar dados do motorista", $e);
        }
    }

    public function destroyDriverOnDatabase($uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        try {
            $destroy = $this->model->where('uuid', $uuid);
            if ($destroy->doesntExist())
                return $this->notFound("Motorista não encontrado, favor verificar as informações.", [], false);
            $destroy = $destroy->delete();
            if (!$destroy)
                return $this->notFound("Não foi possível excluir o motorista, favor verficiar as informações.", [], false);

            return $this->success("Motorista excluído com sucesso.", $destroy, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Houve uma falha ao excluir o motorista.", $e);
        }
    }
}
