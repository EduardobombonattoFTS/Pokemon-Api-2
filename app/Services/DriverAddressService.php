<?php

namespace App\Services;

use App\Models\DriverAddress;
use Illuminate\Support\Facades\Http;

class DriverAddressService {

    protected DriverAddress $model;
    private bool $viewResponse = true;

    public function __construct(DriverAddress $model = null) {
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
        return view('driver_address_index', [
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
    public function viacepRequest($cep) {
        $response = json_decode(Http::get("https://viacep.com.br/ws/{$cep}/json/"));
        $formattedResponse = [
            'street' => $response->logradouro,
            'district' => $response->bairro,
            'city' => $response->localidade,
            'state' => $response->uf,
        ];
        return $formattedResponse;
    }

    public function createDriverAddressOnDatabase(array $data, $viewResponse = null) {
        $this->viewResponse($viewResponse);
        if (key_exists("cep", $data)) {
            $data = array_merge($data, $this->viacepRequest($data["cep"]));
        }

        $existingAddress = $this->model->where('motorista_id', $data['motorista_id'])->first();
        if ($existingAddress) {
            return $this->fail("O motorista já possui um endereço registrado.", [], 400, false);
        }

        try {
            $create = $this->model->create($data);

            if (!$create)
                return $this->notFound("Não foi adicionar endereço ao motorista, favor verificar os dados.", [], false);

            return $this->success("Endereço cadastrado com sucesso.", $create, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Falha ao cadastrar endereço, favor tente novamente.", $e);
        }
    }

    public function updateDriverAddressOnDatabase($data, $uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);
        try {

            $update = $this->model->where('uuid', $uuid);

            if ($update->doesntExist())

                return $this->notFound("Endereço do motorista não encontrado, tente novamente.", [], false);

            $update = $update->first();

            foreach ($data as $key => $value) {
                if ($value !== null) $update->$key = $value;
            }
            if (!$update->save())
                return $this->notFound("Não foi possivel salvar as alterações do endereço do motorista, tente novamente.", [], false);

            return $this->success("Alterações no endereço salvas com sucesso.", $data, 200, false);
        } catch (\Exception $e) {
            return $this->fail("Houve uma falha ao salvar as alterações do endereço, favor tente novamente", $e);
        }
    }

    public function destroyDriverAddressOnDatabase($uuid, $viewResponse = null) {
        $this->viewResponse($viewResponse);

        try {

            $destroy = $this->model->where('uuid', $uuid);
            if ($destroy->doesntExist())
                return $this->notFound("Endereço não encontrado, verifique as informações e tente novamente", [], false);
            $destroy = $destroy->delete();
            if (!$destroy)
                return $this->notFound("Não foi possivel excluir o endereço, favor tente novamente.", [], false);

            return $this->success("Endereço excluído com sucesso.", $destroy, 200, false);
        } catch (\Exception $e) {

            return $this->fail("Houve uma falha ao excluir o endereço, favor tentar novamente", $e);
        }
    }
}
