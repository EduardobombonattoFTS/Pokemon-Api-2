<?php

namespace App\Services;

use App\Models\Truck;

class TruckService {
    protected Truck $model;
    private bool $viewResponse = true;

    public function __construct(Truck $model = null) {
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
        $data = $this->getAll()->getData();
        return view('truck_index', [
            'data' => $data,
        ]);
    }

    //função para checar se placa do caminhão é válida, desnecessária pois pode usar o site do governo direto, mas aqui fica por isso mesmo.
    public function isValidPLate($plate) {
        if (strlen($plate) != 7) {
            return false;
        }
        // Verifica o formato LLLDDDD, sendo L letra e D digito
        if (ctype_alpha(substr($plate, 0, 3)) && ctype_digit(substr($plate, 3, 4))) {
            return true;
        }
        // Verifica o formato LLLDLDD, sendo L letra e D dgito
        if (ctype_alpha(substr($plate, 0, 3)) && ctype_digit($plate[3]) && ctype_alpha($plate[4]) && ctype_digit(substr($plate, 5, 2))) {
            return true;
        }
        return false;
    }

    public function getAll($viewResponse = null) {
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

    public function create(array $data, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        //checa se CPF está vazio
        if (empty($data['plate'])) {
            return $this->fail("Placa é obrigatório, por favor preencher.", [], false);
        }
        //checa se CPF já esta cadastrado
        if ($this->model->where('plate', $data['plate'])->first()) {
            return $this->fail("Placa já cadastrado.", [], false);
        }
        //checa se placa é valida
        if (!$this->isValidPLate($data['plate'])) {
            return $this->fail("Placa inválida, favor verificar", [], false);
        }

        try {

            $create = $this->model->create($data);

            if (!$create)
                return $this->notFound("Não foi possível inserir os dados.", [], false);

            return $this->success("Dados inserido no Banco de dados.", $create, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Falha ao inserir dados.", $e);
        }
    }

    public function update($data, $uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);
        try {

            $update = $this->model->where('uuid', $uuid);

            if ($update->doesntExist())

                return $this->notFound("Registro não encontrado.", [], false);

            $update = $update->first();

            foreach ($data as $key => $value) {
                if ($value !== null) $update->$key = $value;
            }
            if (!$update->save())
                return $this->notFound("Não foi possivel salvar as alterações do registro.", [], false);

            return $this->success("Alterações salva com sucesso.", $data, 200, false);
        } catch (\Exception $e) {
            return $this->fail("Houve uma falha ao salvar as alterações do registro", $e);
        }
    }

    public function destroy($uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        try {

            $destroy = $this->model->where('uuid', $uuid);
            if ($destroy->doesntExist())
                return $this->notFound("Registro não encontrado.", [], false);
            $destroy = $destroy->delete();
            if (!$destroy)
                return $this->notFound("Não foi possivel deletar o registro.", [], false);

            return $this->success("Registro deletado com sucesso.", $destroy, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Houve uma falha ao deletar o registro", $e);
        }
    }
}
